<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class PendingVerification extends Component
{
    public function mount(): void
    {
        $this->checkAndRedirect();
    }

    // Called by wire:poll every 5 seconds to detect admin approval in real time
    public function checkStatus(): void
    {
        $this->checkAndRedirect();
    }

    public function logout(): void
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();
        $this->redirect(route('login'));
    }

    private function checkAndRedirect(): void
    {
        $user = Auth::user();

        // Refresh from DB so we always have the latest status
        $user->refresh();

        match ($user->id_verification_status) {
            'none'     => $this->redirect(route('profile.complete')),
            'verified' => $this->redirect(route('renter.home')),
            'rejected' => $this->redirect(route('profile.complete')),
            default    => null, // 'pending' — stay on this page
        };
    }

    public function render()
    {
        return view('livewire.auth.pending-verification', [
            'title' => 'Verification Pending',
        ])->layout('components.layouts.auth');
    }
}
