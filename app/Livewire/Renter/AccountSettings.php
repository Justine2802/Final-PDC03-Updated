<?php

namespace App\Livewire\Renter;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class AccountSettings extends Component
{
    public string $currentPassword = '';
    public string $newPassword = '';
    public string $newPasswordConfirmation = '';
    public string $toDeletePassword = '';

    public function changePassword(): void
    {
        $this->validate([
            'currentPassword' => 'required',
            'newPassword' => 'required|min:8|confirmed',
        ]);

        if (!Hash::check($this->currentPassword, auth()->user()->password)) {
            $this->addError('currentPassword', 'Current password is incorrect.');
            return;
        }

        auth()->user()->update([
            'password' => Hash::make($this->newPassword),
        ]);

        $this->reset(['currentPassword', 'newPassword', 'newPasswordConfirmation']);
        $this->dispatch('notify', message: 'Password changed successfully!');
    }

    public function deleteAccount(): void
    {
        $this->validate([
            'toDeletePassword' => 'required',
        ]);

        if (!Hash::check($this->toDeletePassword, auth()->user()->password)) {
            $this->addError('toDeletePassword', 'Password is incorrect.');
            return;
        }

        auth()->user()->delete();
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();
        
        $this->redirect(route('login'));
    }

    public function render()
    {
        return view('livewire.renter.account-settings')
            ->layout('components.layouts.renter')->title('Account Settings');
    }
}
