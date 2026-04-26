<?php

namespace App\Livewire\Renter;

use Livewire\Component;
use Livewire\WithFileUploads;

class Profile extends Component
{
    use WithFileUploads;

    public string $name  = '';
    public string $email = '';
    public string $phone = '';
    public string $bio   = '';
    public $avatar = null;

    protected function rules(): array
    {
        return [
            'name'   => 'required|string|min:2|max:255',
            // Ignore the current user's own email in the unique check
            'email'  => 'required|email|unique:users,email,' . auth()->id(),
            'phone'  => 'nullable|string|min:7|max:20',
            'bio'    => 'nullable|string|max:1000',
            'avatar' => 'nullable|image|max:2048',
        ];
    }

    public function mount(): void
    {
        $user        = auth()->user();
        $this->name  = $user->name;
        $this->email = $user->email;
        $this->phone = $user->phone ?? '';
        $this->bio   = $user->bio ?? '';
    }

    public function updateProfile(): void
    {
        $data = $this->validate();

        // Handle avatar upload
        if ($this->avatar) {
            // Delete old avatar if exists
            $user = auth()->user();
            if ($user->avatar && \Illuminate\Support\Facades\Storage::disk('public')->exists($user->avatar)) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($user->avatar);
            }

            $path = $this->avatar->store('avatars', 'public');
            $data['avatar'] = $path;
        } else {
            // Don't overwrite avatar if no new file was uploaded
            unset($data['avatar']);
        }

        auth()->user()->update($data);

        // Reset the temp upload so preview returns to saved state
        $this->avatar = null;
        $this->resetValidation('avatar');

        // Reload name/email from DB in case they changed
        $user        = auth()->user()->fresh();
        $this->name  = $user->name;
        $this->email = $user->email;

        $this->dispatch('notify', message: 'Profile updated successfully!');
    }

    public function removeAvatar(): void
    {
        $user = auth()->user();
        if ($user->avatar && \Illuminate\Support\Facades\Storage::disk('public')->exists($user->avatar)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($user->avatar);
        }
        $user->update(['avatar' => null]);
        $this->avatar = null;
        $this->dispatch('notify', message: 'Avatar removed.');
    }

    public function render()
    {
        return view('livewire.renter.profile')
            ->layout('components.layouts.renter')->title('My Profile');
    }
}
