<?php

namespace App\Livewire\Admin;

use App\Models\Barangay;
use App\Models\City;
use App\Models\Property;
use App\Models\PropertyImage;
use App\Models\PropertyType;
use App\Models\Province;
use App\Models\Region;
use Illuminate\Support\Facades\Storage;
use Livewire\Component;
use Livewire\WithFileUploads;

class PropertyDetail extends Component
{
    use WithFileUploads;

    public Property $property;
    public bool $showDeleteConfirm = false;

    // ── Edit wizard ───────────────────────────────────────────────────────
    public bool $showEditModal = false;
    public int  $currentStep   = 1;
    public int  $totalSteps    = 5;

    // Step 1
    public string $title           = '';
    public string $property_type_id = '';
    public bool   $status           = true;

    // Step 2
    public string $region_id   = '';
    public string $province_id = '';
    public string $city_id     = '';
    public string $barangay_id = '';
    public string $street      = '';
    public string $zip_code    = '';

    // Step 3
    public array $photos        = [];
    public array $existingPhotos = [];

    // Step 4
    public string $description = '';
    public string $bedrooms    = '';
    public string $bathrooms   = '';
    public string $area        = '';
    public string $amenities   = '';

    // Step 5
    public string $price = '';

    protected function stepRules(): array
    {
        return match ($this->currentStep) {
            1 => ['title' => 'required|string|max:255', 'property_type_id' => 'required|exists:property_types,id', 'status' => 'boolean'],
            2 => ['region_id' => 'required|exists:regions,id', 'province_id' => 'required|exists:provinces,id', 'city_id' => 'required|exists:cities,id', 'barangay_id' => 'required|exists:barangays,id', 'street' => 'nullable|string|max:255', 'zip_code' => 'nullable|string|max:10'],
            3 => ['photos.*' => 'nullable|image|max:5120'],
            4 => ['description' => 'required|string', 'bedrooms' => 'required|integer|min:0', 'bathrooms' => 'required|integer|min:0', 'area' => 'required|integer|min:1', 'amenities' => 'nullable|string'],
            5 => ['price' => 'required|numeric|min:0'],
            default => [],
        };
    }

    protected function allRules(): array
    {
        return [
            'title' => 'required|string|max:255', 'property_type_id' => 'required|exists:property_types,id', 'status' => 'boolean',
            'region_id' => 'required|exists:regions,id', 'province_id' => 'required|exists:provinces,id', 'city_id' => 'required|exists:cities,id', 'barangay_id' => 'required|exists:barangays,id',
            'street' => 'nullable|string|max:255', 'zip_code' => 'nullable|string|max:10',
            'description' => 'required|string', 'bedrooms' => 'required|integer|min:0', 'bathrooms' => 'required|integer|min:0', 'area' => 'required|integer|min:1', 'amenities' => 'nullable|string',
            'price' => 'required|numeric|min:0',
        ];
    }

    public function mount(int $id): void
    {
        $this->property = Property::with([
            'images', 'propertyType', 'address.barangay.city.province.region', 'user', 'reviews',
        ])->findOrFail($id);
    }

    // ── Geographic cascades ───────────────────────────────────────────────
    public function updatedRegionId(): void   { $this->province_id = $this->city_id = $this->barangay_id = ''; }
    public function updatedProvinceId(): void { $this->city_id = $this->barangay_id = ''; }
    public function updatedCityId(): void
    {
        $this->barangay_id = '';
        if ($this->city_id && $city = City::find($this->city_id)) {
            $this->zip_code = $city->zip_code ?? $this->zip_code;
        }
    }

    // ── Wizard navigation ─────────────────────────────────────────────────
    public function openEdit(): void
    {
        $this->resetWizard();
        $p = $this->property;
        $this->title            = $p->title;
        $this->property_type_id = (string) $p->property_type_id;
        $this->status           = (bool) $p->status;
        $this->description      = $p->description;
        $this->bedrooms         = (string) $p->bedrooms;
        $this->bathrooms        = (string) $p->bathrooms;
        $this->area             = (string) $p->area;
        $this->amenities        = $p->amenities ?? '';
        $this->price            = (string) $p->price;
        $this->photos           = [];
        $this->existingPhotos   = $p->images->map(fn ($i) => ['id' => $i->id, 'path' => $i->image_path])->toArray();

        if ($p->address) {
            $brgy = $p->address->barangay;
            if ($brgy) {
                $this->barangay_id = (string) $brgy->id;
                $this->city_id     = (string) $brgy->city_id;
                $this->province_id = (string) $brgy->city->province_id;
                $this->region_id   = (string) $brgy->city->province->region_id;
            }
            $this->street   = $p->address->street   ?? '';
            $this->zip_code = $p->address->zip_code ?? '';
        }

        $this->showEditModal = true;
    }

    public function closeEdit(): void
    {
        $this->showEditModal = false;
        $this->resetWizard();
    }

    public function goToStep(int $step): void
    {
        if ($step > $this->currentStep) {
            $this->validate($this->stepRules());
        }
        $this->currentStep = $step;
    }

    public function nextStep(): void
    {
        $this->validate($this->stepRules());
        if ($this->currentStep < $this->totalSteps) {
            $this->currentStep++;
        }
    }

    public function prevStep(): void
    {
        if ($this->currentStep > 1) $this->currentStep--;
    }

    public function removePhoto(int $index): void      { array_splice($this->photos, $index, 1); }
    public function removeExistingPhoto(int $id): void { $this->existingPhotos = array_values(array_filter($this->existingPhotos, fn ($p) => $p['id'] !== $id)); }

    public function save(): void
    {
        $this->validate($this->allRules());

        $this->property->update([
            'title'            => $this->title,
            'property_type_id' => $this->property_type_id,
            'status'           => $this->status,
            'description'      => $this->description,
            'bedrooms'         => $this->bedrooms,
            'bathrooms'        => $this->bathrooms,
            'area'             => $this->area,
            'amenities'        => $this->amenities ?: '',
            'price'            => $this->price,
        ]);

        $this->property->address()->updateOrCreate(
            ['property_id' => $this->property->id],
            ['barangay_id' => $this->barangay_id, 'street' => $this->street ?: null, 'zip_code' => $this->zip_code ?: null]
        );

        // Remove deleted photos
        $keepIds  = collect($this->existingPhotos)->pluck('id')->toArray();
        $this->property->images()->whereNotIn('id', $keepIds)->each(function ($img) {
            Storage::disk('public')->delete($img->image_path);
            $img->delete();
        });

        // Save new photos
        $position = count($keepIds);
        foreach ($this->photos as $photo) {
            PropertyImage::create([
                'property_id' => $this->property->id,
                'image_path'  => $photo->store('properties', 'public'),
                'position'    => $position++,
            ]);
        }

        // Reload the property so the page reflects updates
        $this->property = Property::with([
            'images', 'propertyType', 'address.barangay.city.province.region', 'user', 'reviews',
        ])->find($this->property->id);

        $this->showEditModal = false;
        $this->resetWizard();
        $this->dispatch('notify', message: 'Property updated successfully.');
    }

    // ── Delete ────────────────────────────────────────────────────────────
    public function delete(): void
    {
        foreach ($this->property->images as $img) {
            Storage::disk('public')->delete($img->image_path);
        }
        $this->property->delete();
        session()->flash('success', 'Property deleted successfully.');
        $this->redirect(route('admin.properties'));
    }

    private function resetWizard(): void
    {
        $this->reset(['title', 'property_type_id', 'region_id', 'province_id', 'city_id', 'barangay_id', 'street', 'zip_code', 'photos', 'existingPhotos', 'description', 'bedrooms', 'bathrooms', 'area', 'amenities', 'price']);
        $this->status      = true;
        $this->currentStep = 1;
        $this->resetValidation();
    }

    public function render()
    {
        $regions   = Region::orderBy('name')->get();
        $provinces = $this->region_id   ? Province::where('region_id',   $this->region_id)->orderBy('name')->get()   : collect();
        $cities    = $this->province_id ? City::where('province_id',     $this->province_id)->orderBy('name')->get()  : collect();
        $barangays = $this->city_id     ? Barangay::where('city_id',     $this->city_id)->orderBy('name')->get()      : collect();

        return view('livewire.admin.property-detail', [
            'regions'       => $regions,
            'provinces'     => $provinces,
            'cities'        => $cities,
            'barangays'     => $barangays,
            'propertyTypes' => PropertyType::all(),
        ])->layout('components.layouts.admin')->title($this->property->title);
    }
}
