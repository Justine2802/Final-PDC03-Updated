<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class VerifyEmail extends Component
{
    public bool $resent = false;

    public function resend(): void
    {
        if (Auth::user()->hasVerifiedEmail()) {
            $this->redirect(route('profile.complete'));
            return;
        }

        Auth::user()->sendEmailVerificationNotification();
        $this->resent = true;
    }

    public function logout(): void
    {
        Auth::logout();
        session()->invalidate();
        session()->regenerateToken();
        $this->redirect(route('login'));
    }

    public function render()
    {
        // If already verified, send them to complete their profile
        if (Auth::user()?->hasVerifiedEmail()) {
            return redirect()->route('profile.complete');
        }

        return view('livewire.auth.verify-email', [
            'title' => 'Verify Email'
        ])->layout('components.layouts.auth');
    }
}
