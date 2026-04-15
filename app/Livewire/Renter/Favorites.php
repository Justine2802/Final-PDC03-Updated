<?php

namespace App\Livewire\Renter;

use App\Models\Favorite;
use App\Models\Property;
use Livewire\Component;
use Livewire\WithPagination;

class Favorites extends Component
{
    use WithPagination;

    public ?int $selectedPropertyId = null;

    public function removeFavorite($propertyId): void
    {
        Favorite::where('user_id', auth()->id())
            ->where('property_id', $propertyId)
            ->delete();

        $this->dispatch('notify', message: 'Property removed from favorites');
    }

    public function showPropertyDetail($propertyId): void
    {
        $this->selectedPropertyId = $propertyId;
    }

    public function closePropertyDetail(): void
    {
        $this->selectedPropertyId = null;
    }

    public function sendInquiry($propertyId): void
    {
        $this->redirect(route('renter.home') . '?propertyId=' . $propertyId);
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
            'favorites' => $favorites,
            'selectedProperty' => $selectedProperty,
        ])->layout('components.layouts.renter')->title('My Favorites');
    }
}
