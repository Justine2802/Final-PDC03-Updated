<?php

namespace App\Livewire\Renter;

use App\Models\Reservation;
use Livewire\Component;
use Livewire\WithPagination;

class MyReservations extends Component
{
    use WithPagination;

    public string $statusFilter = '';
    public ?int $cancellingId = null;

    public function confirmCancel(int $id): void
    {
        $this->cancellingId = $id;
    }

    public function cancelReservation(): void
    {
        $reservation = Reservation::where('id', $this->cancellingId)
            ->where('user_id', auth()->id())
            ->whereIn('status', ['pending', 'confirmed'])
            ->first();

        if (!$reservation) {
            $this->dispatch('notify', message: 'Reservation not found or cannot be cancelled.');
            $this->cancellingId = null;
            return;
        }

        $reservation->update([
            'status'       => 'cancelled',
            'cancelled_at' => now(),
        ]);

        $this->cancellingId = null;
        $this->dispatch('notify', message: 'Reservation cancelled successfully.');
    }

    public function dismissCancel(): void
    {
        $this->cancellingId = null;
    }

    public function render()
    {
        $query = Reservation::where('user_id', auth()->id())
            ->with(['property', 'property.images', 'property.propertyType', 'property.address']);

        if ($this->statusFilter) {
            $query->where('status', $this->statusFilter);
        }

        $reservations = $query->latest()->paginate(9);

        return view('livewire.renter.my-reservations', [
            'reservations' => $reservations,
        ])->layout('components.layouts.renter')->title('My Reservations');
    }
}
