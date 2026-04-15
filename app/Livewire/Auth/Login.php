<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Login extends Component
{
    public string $email = '';
    public string $password = '';
    public bool $remember = false;

    protected array $rules = [
        'email' => 'required|email',
        'password' => 'required|min:6',
    ];

    public function login(): void
    {
        $this->validate();

        if (Auth::attempt(['email' => $this->email, 'password' => $this->password], $this->remember)) {
            $user = Auth::user();

            session()->regenerate();

            if ($user->role === 'admin') {
                $this->redirect(route('admin.dashboard'));
            } else {
                $this->redirect(route('renter.home'));
            }
            return;
        }

        $this->addError('email', 'The provided credentials do not match our records.');
    }

    public function render()
    {
        return view('livewire.auth.login')
            ->layout('components.layouts.auth')
            ->title('Login');
    }
}
