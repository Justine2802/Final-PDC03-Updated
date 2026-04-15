<?php

namespace App\Livewire\Admin;

use App\Models\Barangay;
use App\Models\City;
use App\Models\Property;
use App\Models\PropertyImage;
use App\Models\PropertyType;
use App\Models\Province;
use App\Models\Region;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Symfony\Component\HttpFoundation\StreamedResponse;

class Properties extends Component
{
    use WithPagination, WithFileUploads;

    public string $search = '';
    public int $perPage = 10;
    public string $sortBy = 'created_at';
    public string $sortDir = 'desc';
    public string $filterStatus = '';
    public string $filterCity = '';
    public string $filterType = '';

    // CRUD state
    public bool $showModal = false;
    public bool $showViewModal = false;
    public bool $showDeleteModal = false;
    public ?int $editingId = null;
    public ?int $viewingId = null;
    public ?int $deletingId = null;

    // Multi-step wizard
    public int $currentStep = 1;
    public int $totalSteps = 5;

    // Step 1: Property Type
    public string $title = '';
    public string $property_type_id = '';
    public bool $status = true;

    // Step 2: Location (Address)
    public string $region_id = '';
    public string $province_id = '';
    public string $city_id = '';
    public string $barangay_id = '';
    public string $street = '';
    public string $zip_code = '';

    // Step 3: Photos
    public array $photos = [];
    public array $existingPhotos = [];

    // Step 4: Property Details
    public string $description = '';
    public string $bedrooms = '';
    public string $bathrooms = '';
    public string $area = '';
    public string $amenities = '';

    // Step 5: Price
    public string $price = '';

    protected function stepRules(): array
    {
        return match ($this->currentStep) {
            1 => [
                'title' => 'required|string|max:255',
                'property_type_id' => 'required|exists:property_types,id',
                'status' => 'boolean',
            ],
            2 => [
                'region_id' => 'required|exists:regions,id',
                'province_id' => 'required|exists:provinces,id',
                'city_id' => 'required|exists:cities,id',
                'barangay_id' => 'required|exists:barangays,id',
                'street' => 'nullable|string|max:255',
                'zip_code' => 'nullable|string|max:10',
            ],
            3 => $this->editingId
                ? ['photos.*' => 'nullable|image|max:5120']
                : ['photos' => 'nullable|array', 'photos.*' => 'nullable|image|max:5120'],
            4 => [
                'description' => 'required|string',
                'bedrooms' => 'required|integer|min:0',
                'bathrooms' => 'required|integer|min:0',
                'area' => 'required|integer|min:1',
                'amenities' => 'nullable|string',
            ],
            5 => [
                'price' => 'required|numeric|min:0',
            ],
            default => [],
        };
    }

    protected function allRules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'property_type_id' => 'required|exists:property_types,id',
            'status' => 'boolean',
            'region_id' => 'required|exists:regions,id',
            'province_id' => 'required|exists:provinces,id',
            'city_id' => 'required|exists:cities,id',
            'barangay_id' => 'required|exists:barangays,id',
            'street' => 'nullable|string|max:255',
            'zip_code' => 'nullable|string|max:10',
            'description' => 'required|string',
            'bedrooms' => 'required|integer|min:0',
            'bathrooms' => 'required|integer|min:0',
            'area' => 'required|integer|min:1',
            'amenities' => 'nullable|string',
            'price' => 'required|numeric|min:0',
        ];
    }

    public function updatingSearch(): void
    {
        $this->resetPage();
    }

    public function updatedPerPage(): void
    {
        $this->resetPage();
    }

    public function sortBy(string $column): void
    {
        if ($this->sortBy === $column) {
            $this->sortDir = $this->sortDir === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortBy = $column;
            $this->sortDir = 'asc';
        }
    }

    public function resetFilters(): void
    {
        $this->reset(['search', 'filterStatus', 'filterCity', 'filterType']);
        $this->resetPage();
    }

    public function updatedRegionId(): void
    {
        $this->province_id = '';
        $this->city_id = '';
        $this->barangay_id = '';
    }

    public function updatedProvinceId(): void
    {
        $this->city_id = '';
        $this->barangay_id = '';
    }

    public function updatedCityId(): void
    {
        $this->barangay_id = '';
        // Auto-fill zip code from city if available
        if ($this->city_id) {
            $city = City::find($this->city_id);
            if ($city && $city->zip_code) {
                $this->zip_code = $city->zip_code;
            }
        }
    }

    public function goToStep(int $step): void
    {
        // Allow navigating back freely, but validate before going forward
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
        if ($this->currentStep > 1) {
            $this->currentStep--;
        }
    }

    public function removePhoto(int $index): void
    {
        array_splice($this->photos, $index, 1);
    }

    public function removeExistingPhoto(int $id): void
    {
        $this->existingPhotos = array_values(array_filter($this->existingPhotos, fn ($p) => $p['id'] !== $id));
    }

    public function create(): void
    {
        $this->resetForm();
        $this->editingId = null;
        $this->currentStep = 1;
        $this->showModal = true;
    }

    public function edit(int $id): void
    {
        $property = Property::with(['images', 'address.barangay.city.province.region'])->findOrFail($id);
        $this->editingId = $id;
        $this->currentStep = 1;
        $this->title = $property->title;
        $this->property_type_id = (string) $property->property_type_id;
        $this->status = (bool) $property->status;

        // Populate address fields from relationship
        if ($property->address) {
            $barangay = $property->address->barangay;
            if ($barangay) {
                $this->barangay_id = (string) $barangay->id;
                $this->city_id = (string) $barangay->city_id;
                $this->province_id = (string) $barangay->city->province_id;
                $this->region_id = (string) $barangay->city->province->region_id;
            }
            $this->street = $property->address->street ?? '';
            $this->zip_code = $property->address->zip_code ?? '';
        }

        $this->description = $property->description;
        $this->bedrooms = (string) $property->bedrooms;
        $this->bathrooms = (string) $property->bathrooms;
        $this->area = (string) $property->area;
        $this->amenities = $property->amenities ?? '';
        $this->price = (string) $property->price;
        $this->photos = [];
        $this->existingPhotos = $property->images->map(fn ($img) => [
            'id' => $img->id,
            'path' => $img->image_path,
        ])->toArray();
        $this->showModal = true;
    }

    public function save(): void
    {
        $this->validate($this->allRules());

        $data = [
            'user_id' => auth()->id(),
            'title' => $this->title,
            'property_type_id' => $this->property_type_id,
            'status' => $this->status,
            'description' => $this->description,
            'bedrooms' => $this->bedrooms,
            'bathrooms' => $this->bathrooms,
            'area' => $this->area,
            'amenities' => $this->amenities ?: '',
            'price' => $this->price,
        ];

        $addressData = [
            'barangay_id' => $this->barangay_id,
            'street' => $this->street ?: null,
            'zip_code' => $this->zip_code ?: null,
        ];

        if ($this->editingId) {
            $property = Property::findOrFail($this->editingId);
            $property->update($data);
            $property->address()->updateOrCreate(
                ['property_id' => $property->id],
                $addressData
            );

            // Remove deleted existing photos
            $keepIds = collect($this->existingPhotos)->pluck('id')->toArray();
            $property->images()->whereNotIn('id', $keepIds)->each(function ($img) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($img->image_path);
                $img->delete();
            });

            $position = count($keepIds);
            session()->flash('success', 'Property updated successfully.');
        } else {
            $property = Property::create($data);
            $property->address()->create($addressData);
            $position = 0;
            session()->flash('success', 'Property created successfully.');
        }

        // Save new uploaded photos
        foreach ($this->photos as $photo) {
            $path = $photo->store('properties', 'public');
            PropertyImage::create([
                'property_id' => $property->id,
                'image_path' => $path,
                'position' => $position++,
            ]);
        }

        $this->showModal = false;
        $this->resetForm();
    }

    public function view(int $id): void
    {
        $this->viewingId = $id;
        $this->showViewModal = true;
    }

    public function confirmDelete(int $id): void
    {
        $this->deletingId = $id;
        $this->showDeleteModal = true;
    }

    public function delete(): void
    {
        if ($this->deletingId) {
            $property = Property::with('images')->findOrFail($this->deletingId);
            foreach ($property->images as $img) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($img->image_path);
            }
            $property->delete();
            session()->flash('success', 'Property deleted successfully.');
        }
        $this->showDeleteModal = false;
        $this->deletingId = null;
    }

    public function export(): StreamedResponse
    {
        $properties = $this->buildQuery()->get();

        return response()->streamDownload(function () use ($properties) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['ID', 'Title', 'Price', 'Street', 'Barangay', 'City', 'Province', 'Region', 'Zip Code', 'Bedrooms', 'Bathrooms', 'Area', 'Status', 'Created']);

            foreach ($properties as $property) {
                $addr = $property->address;
                $brgy = $addr?->barangay;
                $city = $brgy?->city;
                $prov = $city?->province;
                fputcsv($handle, [
                    $property->id,
                    $property->title,
                    $property->price,
                    $addr?->street ?? '',
                    $brgy?->name ?? '',
                    $city?->name ?? '',
                    $prov?->name ?? '',
                    $prov?->region?->name ?? '',
                    $addr?->zip_code ?? '',
                    $property->bedrooms,
                    $property->bathrooms,
                    $property->area,
                    $property->status ? 'Active' : 'Inactive',
                    $property->created_at->format('Y-m-d'),
                ]);
            }

            fclose($handle);
        }, 'properties-' . now()->format('Y-m-d') . '.csv', [
            'Content-Type' => 'text/csv',
        ]);
    }

    public function exportExcel(): StreamedResponse
    {
        $properties = $this->buildQuery()->get();

        return response()->streamDownload(function () use ($properties) {
            $xml  = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
            $xml .= '<?mso-application progid="Excel.Sheet"?>' . "\n";
            $xml .= '<Workbook xmlns="urn:schemas-microsoft-com:office:spreadsheet"';
            $xml .= ' xmlns:ss="urn:schemas-microsoft-com:office:spreadsheet">' . "\n";
            $xml .= '<Worksheet ss:Name="Properties"><Table>' . "\n";

            $xml .= '<Row>';
            foreach (['ID', 'Title', 'Price', 'Street', 'Barangay', 'City', 'Province', 'Region', 'Zip Code', 'Bedrooms', 'Bathrooms', 'Area', 'Status', 'Created'] as $header) {
                $xml .= '<Cell><Data ss:Type="String">' . htmlspecialchars($header, ENT_XML1) . '</Data></Cell>';
            }
            $xml .= '</Row>' . "\n";

            foreach ($properties as $property) {
                $addr = $property->address;
                $brgy = $addr?->barangay;
                $city = $brgy?->city;
                $prov = $city?->province;
                $xml .= '<Row>';
                $xml .= '<Cell><Data ss:Type="Number">' . $property->id . '</Data></Cell>';
                foreach ([
                    $property->title,
                    $property->price,
                    $addr?->street ?? '',
                    $brgy?->name ?? '',
                    $city?->name ?? '',
                    $prov?->name ?? '',
                    $prov?->region?->name ?? '',
                    $addr?->zip_code ?? '',
                    $property->bedrooms,
                    $property->bathrooms,
                    $property->area,
                    $property->status ? 'Active' : 'Inactive',
                    $property->created_at->format('Y-m-d'),
                ] as $value) {
                    $xml .= '<Cell><Data ss:Type="String">' . htmlspecialchars((string) $value, ENT_XML1) . '</Data></Cell>';
                }
                $xml .= '</Row>' . "\n";
            }

            $xml .= '</Table></Worksheet></Workbook>';
            echo $xml;
        }, 'properties-' . now()->format('Y-m-d') . '.xls', [
            'Content-Type' => 'application/vnd.ms-excel',
        ]);
    }

    private function resetForm(): void
    {
        $this->reset(['title', 'description', 'price', 'street', 'zip_code', 'bedrooms', 'bathrooms', 'area', 'amenities', 'property_type_id', 'region_id', 'province_id', 'city_id', 'barangay_id', 'editingId', 'photos', 'existingPhotos', 'currentStep']);
        $this->status = true;
        $this->currentStep = 1;
        $this->resetValidation();
    }

    private function buildQuery()
    {
        return Property::query()
            ->with(['address.barangay.city.province.region', 'propertyType'])
            ->when($this->search, fn ($q) => $q->where(fn ($sq) => $sq
                ->where('title', 'like', "%{$this->search}%")
                ->orWhereHas('address', fn ($aq) => $aq
                    ->where('street', 'like', "%{$this->search}%")
                    ->orWhereHas('barangay', fn ($bq) => $bq
                        ->where('name', 'like', "%{$this->search}%")
                        ->orWhereHas('city', fn ($cq) => $cq->where('name', 'like', "%{$this->search}%"))))))
            ->when($this->filterStatus !== '', fn ($q) => $q->where('status', $this->filterStatus))
            ->when($this->filterCity, fn ($q) => $q->whereHas('address', fn ($aq) => $aq->whereHas('barangay', fn ($bq) => $bq->where('city_id', $this->filterCity))))
            ->when($this->filterType, fn ($q) => $q->where('property_type_id', $this->filterType))
            ->orderBy($this->sortBy, $this->sortDir);
    }

    public function render()
    {
        $properties = $this->buildQuery()->paginate($this->perPage);

        $activeFilterCount = collect([$this->filterStatus !== '' ? $this->filterStatus : null, $this->filterCity, $this->filterType])
            ->filter(fn ($v) => $v !== null && $v !== '')
            ->count();

        $regions = Region::orderBy('name')->get();
        $provinces = $this->region_id ? Province::where('region_id', $this->region_id)->orderBy('name')->get() : collect();
        $cities = $this->province_id ? City::where('province_id', $this->province_id)->orderBy('name')->get() : collect();
        $barangays = $this->city_id ? Barangay::where('city_id', $this->city_id)->orderBy('name')->get() : collect();
        $allCities = City::with('province')->orderBy('name')->get();
        $propertyTypes = PropertyType::all();
        $viewProperty = $this->viewingId ? Property::with(['address.barangay.city.province.region', 'propertyType', 'user', 'images'])->find($this->viewingId) : null;

        return view('livewire.admin.properties', compact('properties', 'activeFilterCount', 'regions', 'provinces', 'cities', 'barangays', 'allCities', 'propertyTypes', 'viewProperty'))
            ->layout('components.layouts.admin')
            ->title('Properties');
    }
}
