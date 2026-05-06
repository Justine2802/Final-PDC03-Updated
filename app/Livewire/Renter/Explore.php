<?php

namespace App\Livewire\Renter;

use App\Models\Favorite;
use App\Models\Property;
use App\Models\PropertyType;
use App\Models\Reservation;
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

    public ?int $selectedPropertyId = null;

    public bool $showReservationForm = false;
    public string $moveInDate       = '';
    public string $moveOutDate      = '';
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

    public function showPropertyDetail(int $id): void
    {
        $this->selectedPropertyId = $id;
        $this->showReservationForm = false;
        $this->resetReservationForm();
    }

    public function closePropertyDetail(): void
    {
        $this->selectedPropertyId = null;
        $this->showReservationForm = false;
        $this->resetReservationForm();
    }

    public function openReservationForm(): void  { $this->showReservationForm = true; }
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

        Reservation::create([
            'user_id'       => auth()->id(),
            'property_id'   => $this->selectedPropertyId,
            'move_in_date'  => $this->moveInDate,
            'move_out_date' => $this->moveOutDate ?: null,
            'total_price'   => $property->price * $months,
            'status'        => 'pending',
            'notes'         => $this->reservationNotes ?: null,
        ]);

        $this->dispatch('notify', message: 'Reservation submitted! Awaiting confirmation.');
        $this->closePropertyDetail();
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

    public function sendInquiry(int $propertyId): void
    {
        $this->redirect(route('renter.home') . '?propertyId=' . $propertyId);
    }

    private function resetReservationForm(): void
    {
        $this->moveInDate       = '';
        $this->moveOutDate      = '';
        $this->reservationNotes = '';
        $this->resetValidation();
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

        $selectedProperty = $this->selectedPropertyId
            ? Property::with(['images', 'propertyType', 'address'])->find($this->selectedPropertyId)
            : null;

        return view('livewire.renter.explore', [
            'properties'        => $query->with(['propertyType', 'images', 'user'])->paginate(12),
            'propertyTypes'     => PropertyType::all(),
            'selectedProperty'  => $selectedProperty,
            'favoriteIds'       => Favorite::where('user_id', auth()->id())->pluck('property_id')->toArray(),
            'activeFilterCount' => $activeFilterCount,
            'dbMaxPrice'        => $dbMaxPrice,
        ])->layout('components.layouts.renter')->title('Explore Properties');
    }
}
