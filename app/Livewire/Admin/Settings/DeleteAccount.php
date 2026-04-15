<?php

namespace App\Livewire\Admin\Settings;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class DeleteAccount extends Component
{
    public string $password = '';
    public bool $showConfirmation = false;

    public function confirmDelete(): void
    {
        $this->showConfirmation = true;
    }

    public function cancelDelete(): void
    {
        $this->showConfirmation = false;
        $this->reset('password');
        $this->resetValidation();
    }

    public function delete(): void
    {
        $this->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = Auth::user();

        Auth::logout();

        $user->delete();

        session()->invalidate();
        session()->regenerateToken();

        $this->redirect(route('login'));
    }

    public function render()
    {
        return view('livewire.admin.settings.delete-account')
            ->layout('components.layouts.admin')
            ->title('Delete Account - Settings');
    }
}
