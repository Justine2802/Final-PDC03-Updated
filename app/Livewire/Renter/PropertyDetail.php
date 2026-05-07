<?php

namespace App\Livewire\Renter;

use App\Models\Favorite;
use App\Models\Inquiry;
use App\Models\Property;
use App\Models\Reservation;
use Livewire\Component;

class PropertyDetail extends Component
{
    public Property $property;
    public bool $isFavorited        = false;
    public bool $showReservation    = false;
    public bool $showInquiry        = false;
    public string $inquiryMessage   = '';
    public string $moveInDate       = '';
    public string $moveOutDate      = '';
    public string $reservationNotes = '';

    protected function rules(): array
    {
        return [
            'moveInDate'       => 'required|date|after_or_equal:today',
            'moveOutDate'      => 'nullable|date|after:moveInDate',
            'reservationNotes' => 'nullable|max:500',
            'inquiryMessage'   => 'required|min:10|max:1000',
        ];
    }

    protected array $messages = [
        'moveInDate.required'       => 'Move-in date is required.',
        'moveInDate.after_or_equal' => 'Move-in date must be today or in the future.',
        'moveOutDate.after'         => 'Move-out date must be after the move-in date.',
    ];

    public function mount(int $id): void
    {
        $this->property = Property::with([
            'images',
            'propertyType',
            'address.barangay.city.province.region',
            'user',
            'reviews.renter',
        ])->findOrFail($id);

        $this->isFavorited = Favorite::where('user_id', auth()->id())
            ->where('property_id', $id)
            ->exists();
    }

    public function toggleFavorite(): void
    {
        if ($this->isFavorited) {
            Favorite::where('user_id', auth()->id())
                ->where('property_id', $this->property->id)
                ->delete();
            $this->isFavorited = false;
            $this->dispatch('notify', message: 'Removed from favourites.');
        } else {
            Favorite::create(['user_id' => auth()->id(), 'property_id' => $this->property->id]);
            $this->isFavorited = true;
            $this->dispatch('notify', message: 'Added to favourites.');
        }
    }

    public function submitReservation(): void
    {
        $this->validate();

        $moveIn  = \Carbon\Carbon::parse($this->moveInDate);
        $moveOut = $this->moveOutDate ? \Carbon\Carbon::parse($this->moveOutDate) : null;
        $months  = $moveOut ? max(1, (int) $moveIn->diffInMonths($moveOut)) : 1;

        Reservation::create([
            'user_id'       => auth()->id(),
            'property_id'   => $this->property->id,
            'move_in_date'  => $this->moveInDate,
            'move_out_date' => $this->moveOutDate ?: null,
            'total_price'   => $this->property->price * $months,
            'status'        => 'pending',
            'notes'         => $this->reservationNotes ?: null,
        ]);

        $this->dispatch('notify', message: 'Reservation submitted! Awaiting confirmation.');
        $this->showReservation = false;
        $this->moveInDate = $this->moveOutDate = $this->reservationNotes = '';
        $this->resetValidation();
    }

    public function submitInquiry(): void
    {
        $this->validateOnly('inquiryMessage');

        Inquiry::create([
            'user_id'     => auth()->id(),
            'property_id' => $this->property->id,
            'message'     => $this->inquiryMessage,
            'status'      => 'pending',
        ]);

        $this->dispatch('notify', message: 'Inquiry sent successfully!');
        $this->showInquiry    = false;
        $this->inquiryMessage = '';
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.renter.property-detail')
            ->layout('components.layouts.renter')
            ->title($this->property->title);
    }
}
