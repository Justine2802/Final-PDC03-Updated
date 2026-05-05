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
            $this->redirect(route('renter.home'));
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
        // If already verified, send them home
        if (Auth::user()?->hasVerifiedEmail()) {
            return redirect()->route('renter.home');
        }

        return view('livewire.auth.verify-email', [
            'title' => 'Verify Email'
        ])->layout('components.layouts.auth');
    }
}
