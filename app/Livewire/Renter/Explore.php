<?php

namespace App\Livewire\Renter;

use App\Models\Property;
use App\Models\PropertyType;
use App\Models\Reservation;
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

    // Reservation form fields
    public bool $showReservationForm = false;
    public string $moveInDate = '';
    public string $moveOutDate = '';
    public string $reservationNotes = '';

    protected $queryString = [
        'search'       => ['except' => ''],
        'propertyType' => ['except' => ''],
        'minPrice'     => ['except' => 0],
        'maxPrice'     => ['except' => 1000000],
    ];

    protected function rules(): array
    {
        return [
            'moveInDate'        => 'required|date|after_or_equal:today',
            'moveOutDate'       => 'nullable|date|after:moveInDate',
            'reservationNotes'  => 'nullable|max:500',
        ];
    }

    protected $messages = [
        'moveInDate.required'       => 'Move-in date is required.',
        'moveInDate.after_or_equal' => 'Move-in date must be today or in the future.',
        'moveOutDate.after'         => 'Move-out date must be after the move-in date.',
    ];

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

        // Calculate total price based on months if move-out date provided
        $moveIn  = \Carbon\Carbon::parse($this->moveInDate);
        $moveOut = $this->moveOutDate ? \Carbon\Carbon::parse($this->moveOutDate) : null;
        $months  = $moveOut ? max(1, (int) $moveIn->diffInMonths($moveOut)) : 1;
        $total   = $property->price * $months;

        Reservation::create([
            'user_id'      => auth()->id(),
            'property_id'  => $this->selectedPropertyId,
            'move_in_date' => $this->moveInDate,
            'move_out_date'=> $this->moveOutDate ?: null,
            'total_price'  => $total,
            'status'       => 'pending',
            'notes'        => $this->reservationNotes ?: null,
        ]);

        $this->dispatch('notify', message: 'Reservation submitted! Awaiting confirmation.');
        $this->closePropertyDetail();
    }

    private function resetReservationForm(): void
    {
        $this->moveInDate        = '';
        $this->moveOutDate       = '';
        $this->reservationNotes  = '';
        $this->resetValidation();
    }

    public function addToFavorites($propertyId): void
    {
        $exists = \App\Models\Favorite::where('user_id', auth()->id())
            ->where('property_id', $propertyId)
            ->exists();

        if (!$exists) {
            \App\Models\Favorite::create([
                'user_id'     => auth()->id(),
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

        $properties    = $query->with(['propertyType', 'images', 'user'])->paginate(12);
        $propertyTypes = PropertyType::all();

        $selectedProperty = null;
        if ($this->selectedPropertyId) {
            $selectedProperty = Property::with(['images', 'propertyType', 'address'])->find($this->selectedPropertyId);
        }

        $favoriteIds = \App\Models\Favorite::where('user_id', auth()->id())
            ->pluck('property_id')
            ->toArray();

        return view('livewire.renter.explore', [
            'properties'       => $properties,
            'propertyTypes'    => $propertyTypes,
            'selectedProperty' => $selectedProperty,
            'favoriteIds'      => $favoriteIds,
        ])->layout('components.layouts.renter')->title('Explore Properties');
    }
}
