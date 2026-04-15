<?php

namespace App\Livewire\Renter;

use Livewire\Component;
use Livewire\WithFileUploads;

class Profile extends Component
{
    use WithFileUploads;

    public string $name = '';
    public string $email = '';
    public string $phone = '';
    public string $bio = '';
    public $avatar = null;

    protected array $rules = [
        'name' => 'required|string|min:2|max:255',
        'email' => 'required|email|unique:users,email',
        'phone' => 'required|string|min:7|max:20',
        'bio' => 'nullable|string|max:1000',
        'avatar' => 'nullable|image|max:2048',
    ];

    public function mount(): void
    {
        $user = auth()->user();
        $this->name = $user->name;
        $this->email = $user->email;
        $this->phone = $user->phone;
        $this->bio = $user->bio ?? '';
    }

    public function updateProfile(): void
    {
        $data = $this->validate();
        
        if ($this->avatar) {
            $path = $this->avatar->store('avatars', 'public');
            $data['avatar'] = $path;
        }

        auth()->user()->update($data);

        $this->dispatch('notify', message: 'Profile updated successfully!');
    }

    public function render()
    {
        return view('livewire.renter.profile')
            ->layout('components.layouts.renter')->title('My Profile');
    }
}
