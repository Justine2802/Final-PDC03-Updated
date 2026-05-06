<?php

namespace App\Livewire\Auth;

use App\Models\Barangay;
use App\Models\City;
use App\Models\Province;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithFileUploads;

class CompleteProfile extends Component
{
    use WithFileUploads;

    public string $date_of_birth = '';
    public string $address_line  = '';
    public string $province_id   = '';
    public string $city_id       = '';
    public string $barangay_id   = '';
    public string $id_type       = '';
    public string $id_number     = '';
    public $id_image = null;

    protected array $rules = [
        'date_of_birth' => 'required|date|before:today',
        'address_line'  => 'required|string|max:255',
        'province_id'   => 'required|exists:provinces,id',
        'city_id'       => 'required|exists:cities,id',
        'barangay_id'   => 'required|exists:barangays,id',
        'id_type'       => 'required|string',
        'id_number'     => 'required|string|min:4|max:50',
        'id_image'      => 'required|image|max:5120',
    ];

    protected array $messages = [
        'date_of_birth.before' => 'Date of birth must be in the past.',
        'province_id.required' => 'Please select a province.',
        'province_id.exists'   => 'Please select a valid province.',
        'city_id.required'     => 'Please select a city.',
        'city_id.exists'       => 'Please select a valid city.',
        'barangay_id.required' => 'Please select a barangay.',
        'barangay_id.exists'   => 'Please select a valid barangay.',
        'id_image.required'    => 'Please upload a photo of your government ID.',
        'id_image.image'       => 'The file must be an image (JPG, PNG, etc.).',
        'id_image.max'         => 'The ID image may not be larger than 5 MB.',
    ];

    public function mount(): void
    {
        $user = Auth::user();

        if (in_array($user->id_verification_status, ['pending', 'verified'])) {
            $this->redirect(route('profile.pending'));
        }

        if ($user->id_verification_status === 'rejected') {
            $this->date_of_birth = $user->date_of_birth?->format('Y-m-d') ?? '';
            $this->address_line  = $user->address_line ?? '';
            $this->province_id   = (string) ($user->province_id ?? '');
            $this->city_id       = (string) ($user->city_id ?? '');
            $this->barangay_id   = (string) ($user->barangay_id ?? '');
            $this->id_type       = $user->id_type ?? '';
            $this->id_number     = $user->id_number ?? '';
        }
    }

    // Reset dependent dropdowns when province changes
    public function updatedProvinceId(): void
    {
        $this->city_id     = '';
        $this->barangay_id = '';
    }

    // Reset barangay when city changes
    public function updatedCityId(): void
    {
        $this->barangay_id = '';
    }

    public function submit(): void
    {
        $this->validate();

        $imagePath = $this->id_image->store('id-images', 'public');

        Auth::user()->update([
            'date_of_birth'          => $this->date_of_birth,
            'address_line'           => $this->address_line,
            'province_id'            => $this->province_id,
            'city_id'                => $this->city_id,
            'barangay_id'            => $this->barangay_id,
            'id_type'                => $this->id_type,
            'id_number'              => $this->id_number,
            'id_image'               => $imagePath,
            'id_verification_status' => 'pending',
            'id_rejection_reason'    => null,
        ]);

        session()->flash('just_submitted', true);
        $this->redirect(route('profile.pending'));
    }

    public function render()
    {
        return view('livewire.auth.complete-profile', [
            'title'          => 'Complete Your Profile',
            'rejectionReason' => Auth::user()->id_rejection_reason,
            'isRejected'     => Auth::user()->id_verification_status === 'rejected',
            'existingImage'  => Auth::user()->id_image,
            'provinces'      => Province::orderBy('name')->get(),
            'cities'         => $this->province_id
                ? City::where('province_id', $this->province_id)->orderBy('name')->get()
                : collect(),
            'barangays'      => $this->city_id
                ? Barangay::where('city_id', $this->city_id)->orderBy('name')->get()
                : collect(),
        ])->layout('components.layouts.auth');
    }
}
