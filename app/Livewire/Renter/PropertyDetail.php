<?php

namespace App\Livewire\Renter;

use App\Models\Property;
use Livewire\Component;

class PropertyDetail extends Component
{
    public ?Property $property = null;
    public int $currentImageIndex = 0;

    public function mount($propertyId = null)
    {
        if ($propertyId) {
            $this->property = Property::with(['images', 'propertyType', 'address'])->findOrFail($propertyId);
        }
    }

    public function nextImage()
    {
        if ($this->property && $this->property->images->count() > 0) {
            $this->currentImageIndex = ($this->currentImageIndex + 1) % $this->property->images->count();
        }
    }

    public function previousImage()
    {
        if ($this->property && $this->property->images->count() > 0) {
            $this->currentImageIndex = ($this->currentImageIndex - 1 + $this->property->images->count()) % $this->property->images->count();
        }
    }

    public function close()
    {
        $this->property = null;
        $this->currentImageIndex = 0;
    }

    public function render()
    {
        return view('livewire.renter.property-detail');
    }
}
