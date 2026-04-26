<?php

namespace App\Livewire\Renter;

use App\Models\Reservation;
use App\Models\Review;
use Livewire\Component;
use Livewire\WithPagination;

class MyReviews extends Component
{
    use WithPagination;

    // Form state
    public ?int $reviewingReservationId = null;
    public int  $rating = 5;
    public string $comment = '';
    public string $prosInput = '';
    public string $consInput = '';

    // Edit state
    public ?int $editingReviewId = null;

    protected function rules(): array
    {
        return [
            'rating'  => 'required|integer|min:1|max:5',
            'comment' => 'required|min:10|max:1000',
        ];
    }

    protected $messages = [
        'comment.required' => 'Please write a comment.',
        'comment.min'      => 'Comment must be at least 10 characters.',
    ];

    public function openReviewForm(int $reservationId): void
    {
        // Check if already reviewed
        $existing = Review::where('reservation_id', $reservationId)->first();
        if ($existing) {
            // Open edit mode
            $this->editingReviewId        = $existing->id;
            $this->reviewingReservationId = $reservationId;
            $this->rating                 = $existing->rating;
            $this->comment                = $existing->comment ?? '';
            $this->prosInput              = implode(', ', $existing->pros ?? []);
            $this->consInput              = implode(', ', $existing->cons ?? []);
        } else {
            $this->editingReviewId        = null;
            $this->reviewingReservationId = $reservationId;
            $this->rating                 = 5;
            $this->comment                = '';
            $this->prosInput              = '';
            $this->consInput              = '';
        }
        $this->resetValidation();
    }

    public function closeReviewForm(): void
    {
        $this->reviewingReservationId = null;
        $this->editingReviewId        = null;
        $this->rating                 = 5;
        $this->comment                = '';
        $this->prosInput              = '';
        $this->consInput              = '';
        $this->resetValidation();
    }

    public function submitReview(): void
    {
        $this->validate();

        $reservation = Reservation::where('id', $this->reviewingReservationId)
            ->where('user_id', auth()->id())
            ->where('status', 'completed')
            ->first();

        if (!$reservation) {
            $this->dispatch('notify', message: 'Reservation not found or not yet completed.');
            return;
        }

        $pros = array_values(array_filter(array_map('trim', explode(',', $this->prosInput))));
        $cons = array_values(array_filter(array_map('trim', explode(',', $this->consInput))));

        if ($this->editingReviewId) {
            Review::where('id', $this->editingReviewId)
                ->where('renter_id', auth()->id())
                ->update([
                    'rating'  => $this->rating,
                    'comment' => $this->comment,
                    'pros'    => $pros ?: null,
                    'cons'    => $cons ?: null,
                ]);
            $this->dispatch('notify', message: 'Review updated successfully!');
        } else {
            Review::create([
                'property_id'    => $reservation->property_id,
                'renter_id'      => auth()->id(),
                'reservation_id' => $reservation->id,
                'rating'         => $this->rating,
                'comment'        => $this->comment,
                'pros'           => $pros ?: null,
                'cons'           => $cons ?: null,
                'is_verified'    => true,
            ]);
            $this->dispatch('notify', message: 'Review submitted successfully!');
        }

        $this->closeReviewForm();
    }

    public function deleteReview(int $reviewId): void
    {
        Review::where('id', $reviewId)
            ->where('renter_id', auth()->id())
            ->delete();

        $this->dispatch('notify', message: 'Review deleted.');
    }

    public function render()
    {
        // Completed reservations for this renter
        $completedReservations = Reservation::where('user_id', auth()->id())
            ->where('status', 'completed')
            ->with(['property', 'property.images', 'property.propertyType'])
            ->latest()
            ->get();

        // Already-reviewed reservation IDs
        $reviewedIds = Review::where('renter_id', auth()->id())
            ->pluck('reservation_id')
            ->toArray();

        // All reviews by this renter
        $myReviews = Review::where('renter_id', auth()->id())
            ->with(['property', 'reservation'])
            ->latest()
            ->paginate(10);

        return view('livewire.renter.my-reviews', compact('completedReservations', 'reviewedIds', 'myReviews'))
            ->layout('components.layouts.renter')
            ->title('My Reviews');
    }
}
