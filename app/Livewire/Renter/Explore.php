<?php

namespace App\Livewire\Renter;

use App\Models\Property;
use App\Models\PropertyType;
use Livewire\Component;
use Livewire\WithPagination;

class Explore extends Component
{
    use WithPagination;

    public string $search = '';
    public string $propertyType = '';
    public float $minPrice = 0;
    public float $maxPrice = 1000000;
    public int $minBedrooms = 0;
    public int $maxBedrooms = 10;
    public int $minBathrooms = 0;
    public int $maxBathrooms = 10;
    public ?int $selectedPropertyId = null;

    protected $queryString = [
        'search' => ['except' => ''],
        'propertyType' => ['except' => ''],
        'minPrice' => ['except' => 0],
        'maxPrice' => ['except' => 1000000],
    ];

    public function showPropertyDetail($propertyId): void
    {
        $this->selectedPropertyId = $propertyId;
    }

    public function closePropertyDetail(): void
    {
        $this->selectedPropertyId = null;
    }

    public function addToFavorites($propertyId): void
    {
        $exists = \App\Models\Favorite::where('user_id', auth()->id())
            ->where('property_id', $propertyId)
            ->exists();

        if (!$exists) {
            \App\Models\Favorite::create([
                'user_id' => auth()->id(),
                'property_id' => $propertyId,
            ]);
            $this->dispatch('notify', message: 'Added to favorites');
        } else {
            \App\Models\Favorite::where('user_id', auth()->id())
                ->where('property_id', $propertyId)
                ->delete();
            $this->dispatch('notify', message: 'Removed from favorites');
        }
    }

    public function sendInquiry($propertyId): void
    {
        $this->redirect(route('renter.home') . '?propertyId=' . $propertyId);
    }

    public function resetFilters(): void
    {
        $this->reset(['search', 'propertyType', 'minPrice', 'maxPrice', 'minBedrooms', 'maxBedrooms', 'minBathrooms', 'maxBathrooms']);
        $this->resetPage();
    }

    public function render()
    {
        $query = Property::where('status', true);

        if ($this->search) {
            $query->where('title', 'like', "%{$this->search}%")
                ->orWhere('description', 'like', "%{$this->search}%");
        }

        if ($this->propertyType) {
            $query->where('property_type_id', $this->propertyType);
        }

        $query->whereBetween('price', [$this->minPrice, $this->maxPrice])
            ->whereBetween('bedrooms', [$this->minBedrooms, $this->maxBedrooms])
            ->whereBetween('bathrooms', [$this->minBathrooms, $this->maxBathrooms]);

        $properties = $query->with(['propertyType', 'images', 'user'])
            ->paginate(12);

        $propertyTypes = PropertyType::all();

        $selectedProperty = null;
        if ($this->selectedPropertyId) {
            $selectedProperty = Property::with(['images', 'propertyType', 'address'])->find($this->selectedPropertyId);
        }

        return view('livewire.renter.explore', [
            'properties' => $properties,
            'propertyTypes' => $propertyTypes,
            'selectedProperty' => $selectedProperty,
        ])->layout('components.layouts.renter')->title('Explore Properties');
    }
}
