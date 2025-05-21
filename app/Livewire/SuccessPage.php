<?php

namespace App\Livewire;

use App\Models\Order;
use Livewire\Attributes\Title;
use Livewire\Attributes\Url;
use Livewire\Component;
use Stripe\Checkout\Session;
use Stripe\Stripe;

#[Title('Success - E-Commerce')]
class SuccessPage extends Component
{
    #[Url] //neemt de url van de pagina en zet deze in public $session_id
    public $session_id;

    public function render()
    {
        // haal de laatste bestelling op van de ingelogde user
        $latest_order = Order::with('address')->where('user_id', auth()->user()->id)->latest()->first();

        if($this->session_id){
            Stripe::setApiKey(env('STRIPE_SECRET'));

            //geeft ALLE informatie van de bestelling via stripe session
            $session_info = Session::retrieve($this->session_id);

            // Als de session info failed betaling geeft ga je naar de cancel pagina, als de betaling succes is ga je naar de success pagina.
            if($session_info->payment_status != 'paid'){
                $latest_order->payment_status = 'failed';
                $latest_order->save();
                return redirect('/cancel');
            }else if($session_info->payment_status == 'paid'){
                $latest_order->payment_status = 'paid';
                $latest_order->save();
            }
        }

        return view('livewire.success-page', [
            'order' => $latest_order,
        ]);
    }
}
