<?php

namespace App\Livewire\Renter;

use App\Models\Favorite;
use App\Models\Property;
use App\Models\Reservation;
use Livewire\Component;
use Livewire\WithPagination;

class Favorites extends Component
{
    use WithPagination;

    public ?int $selectedPropertyId = null;

    // Reservation form
    public bool $showReservationForm = false;
    public string $moveInDate = '';
    public string $moveOutDate = '';
    public string $reservationNotes = '';

    protected function rules(): array
    {
        return [
            'moveInDate'       => 'required|date|after_or_equal:today',
            'moveOutDate'      => 'nullable|date|after:moveInDate',
            'reservationNotes' => 'nullable|max:500',
        ];
    }

    protected $messages = [
        'moveInDate.required'       => 'Move-in date is required.',
        'moveInDate.after_or_equal' => 'Move-in date must be today or in the future.',
        'moveOutDate.after'         => 'Move-out date must be after the move-in date.',
    ];

    public function removeFavorite($propertyId): void
    {
        Favorite::where('user_id', auth()->id())
            ->where('property_id', $propertyId)
            ->delete();

        // Close modal if open for this property
        if ($this->selectedPropertyId === (int) $propertyId) {
            $this->selectedPropertyId = null;
            $this->showReservationForm = false;
        }

        $this->dispatch('notify', message: 'Property removed from favorites');
    }

    public function showPropertyDetail($propertyId): void
    {
        $this->selectedPropertyId = $propertyId;
        $this->showReservationForm = false;
        $this->resetReservationForm();
    }

    public function closePropertyDetail(): void
    {
        $this->selectedPropertyId = null;
        $this->showReservationForm = false;
        $this->resetReservationForm();
    }

    public function openReservationForm(): void
    {
        $this->showReservationForm = true;
    }

    public function closeReservationForm(): void
    {
        $this->showReservationForm = false;
        $this->resetReservationForm();
    }

    public function submitReservation(): void
    {
        $this->validate();

        $property = Property::find($this->selectedPropertyId);
        if (!$property) {
            $this->dispatch('notify', message: 'Property not found.');
            return;
        }

        $moveIn  = \Carbon\Carbon::parse($this->moveInDate);
        $moveOut = $this->moveOutDate ? \Carbon\Carbon::parse($this->moveOutDate) : null;
        $months  = $moveOut ? max(1, (int) $moveIn->diffInMonths($moveOut)) : 1;
        $total   = $property->price * $months;

        Reservation::create([
            'user_id'       => auth()->id(),
            'property_id'   => $this->selectedPropertyId,
            'move_in_date'  => $this->moveInDate,
            'move_out_date' => $this->moveOutDate ?: null,
            'total_price'   => $total,
            'status'        => 'pending',
            'notes'         => $this->reservationNotes ?: null,
        ]);

        $this->dispatch('notify', message: 'Reservation submitted! Awaiting confirmation.');
        $this->closePropertyDetail();
    }

    public function sendInquiry($propertyId): void
    {
        $this->redirect(route('renter.home') . '?propertyId=' . $propertyId);
    }

    private function resetReservationForm(): void
    {
        $this->moveInDate        = '';
        $this->moveOutDate       = '';
        $this->reservationNotes  = '';
        $this->resetValidation();
    }

    public function render()
    {
        $favorites = Favorite::where('user_id', auth()->id())
            ->with(['property', 'property.propertyType', 'property.images', 'property.address'])
            ->latest()
            ->paginate(12);

        $selectedProperty = null;
        if ($this->selectedPropertyId) {
            $selectedProperty = Property::with(['images', 'propertyType', 'address'])->find($this->selectedPropertyId);
        }

        return view('livewire.renter.favorites', [
            'favorites'        => $favorites,
            'selectedProperty' => $selectedProperty,
        ])->layout('components.layouts.renter')->title('My Favorites');
    }
}
