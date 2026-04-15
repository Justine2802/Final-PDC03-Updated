<?php

namespace App\Livewire\Admin;

use App\Models\Barangay;
use App\Models\City;
use App\Models\Province;
use App\Models\Region;
use Livewire\Component;
use Livewire\WithPagination;

class Cities extends Component
{
    use WithPagination;

    public string $search = '';
    public int $perPage = 10;
    public string $sortBy = 'name';
    public string $sortDir = 'asc';
    public string $filterRegion = '';
    public string $filterProvince = '';

    // CRUD state
    public bool $showModal = false;
    public bool $showDeleteModal = false;
    public bool $showBarangayModal = false;
    public ?int $editingId = null;
    public ?int $deletingId = null;
    public ?int $managingBarangaysCityId = null;

    // City form fields
    public string $regionId = '';
    public string $provinceId = '';
    public string $name = '';
    public string $zip_code = '';

    // Barangay form
    public string $newBarangay = '';

    protected function rules(): array
    {
        return [
            'regionId' => 'required|exists:regions,id',
            'provinceId' => 'required|exists:provinces,id',
            'name' => 'required|string|max:255',
            'zip_code' => 'nullable|string|max:10',
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

    public function updatedFilterRegion(): void
    {
        $this->filterProvince = '';
        $this->resetPage();
    }

    public function updatedFilterProvince(): void
    {
        $this->resetPage();
    }

    public function updatedRegionId(): void
    {
        $this->provinceId = '';
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

    public function resetFilters(): void
    {
        $this->reset(['search', 'filterRegion', 'filterProvince']);
        $this->resetPage();
    }

    public function create(): void
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function edit(int $id): void
    {
        $city = City::with('province')->findOrFail($id);
        $this->editingId = $id;
        $this->regionId = (string) $city->province->region_id;
        $this->provinceId = (string) $city->province_id;
        $this->name = $city->name;
        $this->zip_code = $city->zip_code ?? '';
        $this->showModal = true;
    }

    public function save(): void
    {
        $this->validate();

        $data = [
            'province_id' => $this->provinceId,
            'name' => $this->name,
            'zip_code' => $this->zip_code ?: null,
        ];

        if ($this->editingId) {
            City::findOrFail($this->editingId)->update($data);
            session()->flash('success', 'City updated successfully.');
        } else {
            City::create($data);
            session()->flash('success', 'City created successfully.');
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
            City::findOrFail($this->deletingId)->delete();
            session()->flash('success', 'City deleted successfully.');
        }
        $this->showDeleteModal = false;
        $this->deletingId = null;
    }

    // Barangay management
    public function manageBarangays(int $cityId): void
    {
        $this->managingBarangaysCityId = $cityId;
        $this->newBarangay = '';
        $this->showBarangayModal = true;
    }

    public function addBarangay(): void
    {
        $this->validate(['newBarangay' => 'required|string|max:255']);

        Barangay::create([
            'city_id' => $this->managingBarangaysCityId,
            'name' => $this->newBarangay,
        ]);

        $this->newBarangay = '';
    }

    public function deleteBarangay(int $id): void
    {
        Barangay::findOrFail($id)->delete();
    }

    private function resetForm(): void
    {
        $this->reset(['regionId', 'provinceId', 'name', 'zip_code', 'editingId']);
        $this->resetValidation();
    }

    public function render()
    {
        $cities = City::query()
            ->with('province.region')
            ->withCount('barangays')
            ->when($this->search, fn ($q) => $q->where('name', 'like', "%{$this->search}%"))
            ->when($this->filterProvince, fn ($q) => $q->where('province_id', $this->filterProvince))
            ->when($this->filterRegion && !$this->filterProvince, fn ($q) => $q->whereHas('province', fn ($pq) => $pq->where('region_id', $this->filterRegion)))
            ->orderBy($this->sortBy, $this->sortDir)
            ->paginate($this->perPage);

        $regions = Region::orderBy('name')->get();

        // Filter bar provinces
        $filterProvinces = $this->filterRegion
            ? Province::where('region_id', $this->filterRegion)->orderBy('name')->get()
            : collect();

        // Modal provinces (for create/edit form)
        $modalProvinces = $this->regionId
            ? Province::where('region_id', $this->regionId)->orderBy('name')->get()
            : collect();

        // Barangay modal data
        $barangayCity = $this->managingBarangaysCityId
            ? City::with(['barangays' => fn ($q) => $q->orderBy('name')])->find($this->managingBarangaysCityId)
            : null;

        return view('livewire.admin.cities', compact('cities', 'regions', 'filterProvinces', 'modalProvinces', 'barangayCity'))
            ->layout('components.layouts.admin')
            ->title('Cities');
    }
}
