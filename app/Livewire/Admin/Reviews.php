<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;

class Reviews extends Component
{
    use WithPagination;

    public int $perPage = 10;

    public function updatedPerPage(): void
    {
        $this->resetPage();
    }

    public function render()
    {
        $reviews = \App\Models\Review::latest()->paginate($this->perPage);

        return view('livewire.admin.reviews', compact('reviews'))
            ->layout('components.layouts.admin')
            ->title('Reviews');
    }
}
