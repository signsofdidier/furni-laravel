<?php

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Password;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Forgot Password')]
class ForgotPasswordPage extends Component
{
    public $email; // Email veld dat in het formulier zit

    public function save(){
        // Valideer dat er een geldig email-adres is ingevuld en dat die user bestaat
        $this->validate([
           'email' => 'required|email|max:255|exists:users,email'
        ]);

        // Probeer de reset-link te versturen via Laravel's Password facade
        $status = Password::sendResetLink([
            'email' => $this->email
        ]);

        // IF SUCCESS, laat bericht zien en maak veld leeg
        if($status === Password::RESET_LINK_SENT){
            session()->flash('success', 'We have e-mailed your password reset link!');
            $this->email='';
        }
    }

    // Render de blade view
    public function render()
    {
        return view('livewire.auth.forgot-password-page');
    }
}
