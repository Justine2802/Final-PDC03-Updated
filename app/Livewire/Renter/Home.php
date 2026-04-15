<?php

namespace App\Livewire\Renter;

use App\Models\Inquiry;
use App\Models\Property;
use App\Models\Reservation;
use Livewire\Component;

class Home extends Component
{
    public ?int $propertyId = null;
    public string $inquiryMessage = '';

    protected $queryString = ['propertyId' => ['except' => null]];

    public function submitInquiry(): void
    {
        $this->validate([
            'inquiryMessage' => 'required|min:10|max:1000',
        ]);

        if (!$this->propertyId) {
            $this->dispatch('notify', message: 'Please select a property');
            return;
        }

        $property = Property::find($this->propertyId);
        if (!$property) {
            $this->dispatch('notify', message: 'Property not found');
            return;
        }

        Inquiry::create([
            'user_id' => auth()->id(),
            'property_id' => $this->propertyId,
            'message' => $this->inquiryMessage,
            'status' => 'pending',
        ]);

        $this->dispatch('notify', message: 'Inquiry sent successfully!');
        $this->propertyId = null;
        $this->inquiryMessage = '';
    }

    public function clearProperty(): void
    {
        $this->propertyId = null;
        $this->inquiryMessage = '';
    }

    public function render()
    {
        $user = auth()->user();
        
        $recentReservations = Reservation::where('user_id', $user->id)
            ->with(['property', 'property.propertyType'])
            ->latest()
            ->limit(5)
            ->get();

        $favoriteCount = $user->favorites()->count();
        $inquiryCount = $user->inquiries()->count();
        $reservationCount = Reservation::where('user_id', $user->id)->count();

        $selectedProperty = null;
        if ($this->propertyId) {
            $selectedProperty = Property::with(['images', 'propertyType', 'address'])->find($this->propertyId);
        }

        return view('livewire.renter.home', [
            'recentReservations' => $recentReservations,
            'favoriteCount' => $favoriteCount,
            'inquiryCount' => $inquiryCount,
            'reservationCount' => $reservationCount,
            'selectedProperty' => $selectedProperty,
        ])->layout('components.layouts.renter')->title('Home');
    }
}

