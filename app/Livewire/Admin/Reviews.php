<?php

namespace App\Livewire\Admin;

use App\Models\Review;
use Livewire\Component;
use Livewire\WithPagination;

class Reviews extends Component
{
    use WithPagination;

    public string $search = '';
    public string $filterRating = '';
    public string $filterVerified = '';
    public int $perPage = 10;

    public ?int $viewingId = null;

    public function updatingSearch(): void     { $this->resetPage(); }
    public function updatedPerPage(): void     { $this->resetPage(); }
    public function updatedFilterRating(): void   { $this->resetPage(); }
    public function updatedFilterVerified(): void { $this->resetPage(); }

    public function viewReview(int $id): void
    {
        $this->viewingId = $id;
    }

    public function closeView(): void
    {
        $this->viewingId = null;
    }

    public function toggleVerified(int $id): void
    {
        $review = Review::find($id);
        if ($review) {
            $review->update(['is_verified' => !$review->is_verified]);
            $this->dispatch('notify', message: 'Review verification toggled.');
        }
    }

    public function deleteReview(int $id): void
    {
        Review::find($id)?->delete();
        if ($this->viewingId === $id) {
            $this->viewingId = null;
        }
        $this->dispatch('notify', message: 'Review deleted.');
    }

    public function render()
    {
        $reviews = Review::with(['property', 'renter', 'reservation'])
            ->when($this->search, function ($q) {
                $q->whereHas('property', fn ($pq) => $pq->where('title', 'like', "%{$this->search}%"))
                  ->orWhereHas('renter',   fn ($uq) => $uq->where('name',  'like', "%{$this->search}%"))
                  ->orWhere('comment', 'like', "%{$this->search}%");
            })
            ->when($this->filterRating,   fn ($q) => $q->where('rating', $this->filterRating))
            ->when($this->filterVerified !== '', fn ($q) => $q->where('is_verified', (bool) $this->filterVerified))
            ->latest()
            ->paginate($this->perPage);

        $viewing = $this->viewingId
            ? Review::with(['property', 'renter', 'reservation'])->find($this->viewingId)
            : null;

        return view('livewire.admin.reviews', compact('reviews', 'viewing'))
            ->layout('components.layouts.admin')
            ->title('Reviews');
    }
}
