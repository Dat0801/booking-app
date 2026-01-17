<?php

namespace App\Livewire\Pages\Admin;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Admin login')]
class Login extends Component
{
    public string $email = '';

    public string $password = '';

    public bool $remember = false;

    public string $errorMessage = '';

    public function login(): void
    {
        $this->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $credentials = [
            'email' => $this->email,
            'password' => $this->password,
        ];

        if (! Auth::attempt($credentials, $this->remember)) {
            $this->errorMessage = 'The provided credentials are incorrect.';

            return;
        }

        $user = Auth::user();

        if (! $user || ! $user->roles()->where('name', 'admin')->exists()) {
            Auth::logout();

            $this->errorMessage = 'You do not have permission to access the admin area.';

            return;
        }

        $this->redirectRoute('admin.dashboard');
    }

    public function render()
    {
        return view('pages.admin.login');
    }
}
