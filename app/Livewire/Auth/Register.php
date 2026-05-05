<?php

namespace App\Livewire\Auth;

use App\Mail\WelcomeUser;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class Register extends Component
{
    public string $name = '';
    public string $email = '';
    public string $phone = '';
    public string $password = '';
    public string $password_confirmation = '';

    protected array $rules = [
        'name'     => 'required|string|min:2|max:255',
        'email'    => 'required|email|unique:users',
        'phone'    => 'required|string|min:7|max:20',
        'password' => 'required|min:8|confirmed',
    ];

    public function register(): void
    {
        $this->validate();

        $user = User::create([
            'name'     => $this->name,
            'email'    => $this->email,
            'phone'    => $this->phone,
            'password' => Hash::make($this->password),
            'role'     => 'renter',
            'status'   => 'active',
        ]);

        // Send a welcome email — visible in Mailpit at http://localhost:8025
        Mail::to($user->email)->send(new WelcomeUser($user));

        // Also send Laravel's built-in email verification link
        $user->sendEmailVerificationNotification();

        Auth::login($user);
        session()->regenerate();
        $this->redirect(route('verification.notice'));
    }

    public function render()
    {
        return view('livewire.auth.register', [
            'title' => 'Register'
        ])->layout('components.layouts.auth');
    }
}
