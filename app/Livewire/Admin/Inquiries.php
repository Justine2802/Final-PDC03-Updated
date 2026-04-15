<?php

namespace App\Livewire\Admin;

use App\Models\Inquiry;
use Livewire\Component;
use Livewire\WithPagination;

class Inquiries extends Component
{
    use WithPagination;

    public string $search = '';
    public int $perPage = 10;

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatedPerPage(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $inquiries = Inquiry::query()
            ->when($this->search, fn ($q) => $q->where('message', 'like', "%{$this->search}%"))
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.admin.inquiries', compact('inquiries'))
            ->layout('components.layouts.admin')
            ->title('Inquiries');
    }
}
