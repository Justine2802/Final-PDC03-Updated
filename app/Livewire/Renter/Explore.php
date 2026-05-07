<?php

namespace App\Livewire\Renter;

use App\Models\Favorite;
use App\Models\Property;
use App\Models\PropertyType;
use Livewire\Component;
use Livewire\WithPagination;

class Explore extends Component
{
    use WithPagination;

    public string $search       = '';
    public string $propertyType = '';
    public string $maxPrice     = '';   // '' = no price cap (show all)
    public string $minBedrooms  = '0';
    public string $minBathrooms = '0';


    public function applyFilters(
        string $search,
        string $propertyType,
        string $maxPrice,
        string $minBedrooms,
        string $minBathrooms
    ): void {
        $this->search       = $search;
        $this->propertyType = $propertyType;
        $this->maxPrice     = $maxPrice;
        $this->minBedrooms  = $minBedrooms;
        $this->minBathrooms = $minBathrooms;
        $this->resetPage();
    }

    public function resetFilters(): void
    {
        $this->search       = '';
        $this->propertyType = '';
        $this->maxPrice     = '';
        $this->minBedrooms  = '0';
        $this->minBathrooms = '0';
        $this->resetPage();
    }

    public function viewProperty(int $id): void
    {
        $this->redirect(route('renter.property', $id));
    }

    public function addToFavorites(int $propertyId): void
    {
        $exists = Favorite::where('user_id', auth()->id())
            ->where('property_id', $propertyId)
            ->exists();

        if ($exists) {
            Favorite::where('user_id', auth()->id())->where('property_id', $propertyId)->delete();
            $this->dispatch('notify', message: 'Removed from favourites.');
        } else {
            Favorite::create(['user_id' => auth()->id(), 'property_id' => $propertyId]);
            $this->dispatch('notify', message: 'Added to favourites.');
        }
    }


    public function render()
    {
        // Max price ceiling from the actual data
        $dbMaxPrice = (int) (Property::where('status', true)->max('price') ?? 10000);
        // Round up to a clean number for the slider
        $dbMaxPrice = (int) (ceil($dbMaxPrice / 1000) * 1000);

        $maxPrice  = $this->maxPrice !== '' && is_numeric($this->maxPrice)
            ? (float) $this->maxPrice
            : $dbMaxPrice;
        $minBeds   = is_numeric($this->minBedrooms)  ? (int) $this->minBedrooms  : 0;
        $minBaths  = is_numeric($this->minBathrooms) ? (int) $this->minBathrooms : 0;

        $query = Property::where('status', true);

        if ($this->search) {
            $query->where(fn ($q) => $q
                ->where('title', 'like', "%{$this->search}%")
                ->orWhere('description', 'like', "%{$this->search}%"));
        }

        if ($this->propertyType) {
            $query->where('property_type_id', $this->propertyType);
        }

        $query->where('price', '<=', $maxPrice);

        if ($minBeds > 0) {
            $query->where('bedrooms', '>=', $minBeds);
        }
        if ($minBaths > 0) {
            $query->where('bathrooms', '>=', $minBaths);
        }

        $activeFilterCount =
            ($this->maxPrice !== '' && (float)$this->maxPrice < $dbMaxPrice ? 1 : 0) +
            ($minBeds > 0 ? 1 : 0) +
            ($minBaths > 0 ? 1 : 0);

        return view('livewire.renter.explore', [
            'properties'        => $query->with(['propertyType', 'images', 'user', 'reviews'])->paginate(12),
            'propertyTypes'     => PropertyType::all(),
            'favoriteIds'       => Favorite::where('user_id', auth()->id())->pluck('property_id')->toArray(),
            'activeFilterCount' => $activeFilterCount,
            'dbMaxPrice'        => $dbMaxPrice,
        ])->layout('components.layouts.renter')->title('Explore Properties');
    }
}
