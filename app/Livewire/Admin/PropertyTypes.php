<?php

namespace App\Livewire\Admin;

use App\Models\PropertyType;
use Livewire\Component;
use Livewire\WithPagination;

class PropertyTypes extends Component
{
    use WithPagination;

    public string $search = '';
    public int $perPage = 10;
    public string $sortBy = 'created_at';
    public string $sortDir = 'desc';

    // CRUD state
    public bool $showModal = false;
    public bool $showDeleteModal = false;
    public ?int $editingId = null;
    public ?int $deletingId = null;

    // Form fields
    public string $name = '';

    protected function rules(): array
    {
        return [
            'name' => 'required|string|max:255|unique:property_types,name' . ($this->editingId ? ',' . $this->editingId : ''),
        ];
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatedPerPage(): void
    {
        $this->resetPage();
    }

    public function sortBy(string $column): void
    {
        if ($this->sortBy === $column) {
            $this->sortDir = $this->sortDir === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDir = 'asc';
        }
    }

    public function create(): void
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function edit(int $id): void
    {
        $type = PropertyType::findOrFail($id);
        $this->editingId = $id;
        $this->name = $type->name;
        $this->showModal = true;
    }

    public function save(): void
    {
        $this->validate();

        if ($this->editingId) {
            PropertyType::findOrFail($this->editingId)->update(['name' => $this->name]);
            session()->flash('success', 'Property type updated successfully.');
        } else {
            PropertyType::create(['name' => $this->name]);
            session()->flash('success', 'Property type created successfully.');
        }

        $this->showModal = false;
        $this->resetForm();
    }

    public function confirmDelete(int $id): void
    {
        $this->deletingId = $id;
        $this->showDeleteModal = true;
    }

    public function delete(): void
    {
        if ($this->deletingId) {
            PropertyType::findOrFail($this->deletingId)->delete();
            session()->flash('success', 'Property type deleted successfully.');
        }
        $this->showDeleteModal = false;
        $this->deletingId = null;
    }

    private function resetForm(): void
    {
        $this->reset(['name', 'editingId']);
        $this->resetValidation();
    }

    public function render()
    {
        $propertyTypes = PropertyType::query()
            ->withCount('properties')
            ->when($this->search, fn ($q) => $q->where('name', 'like', "%{$this->search}%"))
            ->orderBy($this->sortBy, $this->sortDir)
            ->paginate($this->perPage);

        return view('livewire.admin.property-types', compact('propertyTypes'))
            ->layout('components.layouts.admin')
            ->title('Property Types');
    }
}
