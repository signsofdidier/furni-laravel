<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('Register')]
class RegisterPage extends Component
{

    public $name;
    public $email;
    public $password;
    public $password_confirmation;

    // register user
    public function save(){
        $this->validate([
            'name' => 'required|max:255',
            'email' => 'required|email|unique:users|max:255',
            'password' => 'required|min:6|max:255|confirmed',
            'password_confirmation' => 'required|min:6|max:255'
        ]);

        //save to database
        $user = User::create([
            'password' => Hash::make($this->password),
            'email' => $this->email,
            'name' => $this->name,
        ]);

        // zend verification email
        $user->sendEmailVerificationNotification();

        // login user
        // uitzetten als ze eerst moeten verificeren voor inloggen
        auth()->login($user);

        // intended redirect naar de pagina waar je vandaan komt
        //return redirect()->intended(route('profile'));
        return redirect()->route('verification.notice');
    }

    public function render()
    {
        return view('livewire.auth.register-page');
    }
}
