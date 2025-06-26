<?php

namespace App\Livewire\Auth;

use App\Models\User;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;

// Geeft de paginatitel aan (optioneel, handig voor SEO en browser-tabblad)
#[Title('Reset Password')]
class ResetPasswordPage extends Component
{
    // De reset token uit de URL
    public $token;

    // Het e-mailadres komt ook mee via de URL (bijv. ?email=john@example.com)
    #[Url]
    public $email;

    // De nieuwe wachtwoorden ingevuld door de gebruiker
    public $password;
    public $password_confirmation;

    // Hier initialiseren we de token die via de route wordt doorgegeven.
    public function mount($token)
    {
        $this->token = $token;
    }

    // Validatie regels voor het formulier
    protected function rules()
    {
        return [
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed', // 'confirmed' vereist dat password_confirmation overeenkomt
        ];
    }

    /**
     * We valideren de input, proberen het wachtwoord te resetten,
     * en tonen feedback of redirecten op basis van het resultaat.
     */
    public function save()
    {
        // Stap 1: Valideer de invoer
        $this->validate();

        // Stap 2: Probeer het wachtwoord te resetten via Laravel's Password::reset helper
        $status = Password::reset(
            [
                'email' => $this->email,
                'token' => $this->token,
                'password' => $this->password,
                'password_confirmation' => $this->password_confirmation,
            ],
            function (User $user, string $password) {
                // Stap 3: Werk het wachtwoord van de gebruiker bij
                $user->forceFill([
                    'password' => Hash::make($password),
                ])->setRememberToken(Str::random(60)); // Nieuwe remember token voor veiligheid

                $user->save();

                // Stap 4: Event eventueel voor extra logs/mails
                event(new PasswordReset($user));
            }
        );

        // Stap 5: Afhandelen van het resultaat
        if ($status === Password::PASSWORD_RESET) {
            // Succesvol: geef feedback en stuur gebruiker naar loginpagina
            session()->flash('status', 'Password reset successfully. You can now log in.');
            return redirect()->to('/login');
        } else {
            // Mislukt: toon een foutmelding bij het emailveld
            $this->addError('email', __($status));
        }
    }


    public function render()
    {
        return view('livewire.auth.reset-password-page');
    }
}
