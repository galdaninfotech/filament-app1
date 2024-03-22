<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Omnipay\Omnipay;
use RealRashid\SweetAlert\Facades\Alert;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Game;
use App\Models\User;
use App\Models\Ticket;

class PaymentController extends Controller
{
    private $gateway;

    public function __construct() {
        $this->gateway = Omnipay::create('PayPal_Rest');
        $this->gateway->setClientId(env('PAYPAL_SANDBOX_CLIENT_ID'));
        $this->gateway->setSecret(env('PAYPAL_SANDBOX_CLIENT_SECRET'));
        $this->gateway->setTestMode(true);
    }

    public function payment(Request $request)
    {
        // save in session, on successful transaction later save it to database
        $request->session()->put('newTickets', json_decode($request->tickets, true));

        try {
            $response = $this->gateway->purchase(array(
                'amount' => $request->amount,
                'currency' => env('PAYPAL_CURRENCY'),
                'returnUrl' => url('success'),
                'cancelUrl' => url('error')
            ))->send();

            if ($response->isRedirect()) {
                $response->redirect();
            }
            else{
                return $response->getMessage();
            }

        } catch (\Throwable $th) {
            return $th->getMessage();
        }
    }

    public function success(Request $request)
    {
        if ($request->input('paymentId') && $request->input('PayerID')) {
            $transaction = $this->gateway->completePurchase(array(
                'payer_id' => $request->input('PayerID'),
                'transactionReference' => $request->input('paymentId')
            ));

            $response = $transaction->send();
            if ($response->isSuccessful()) {
                Alert::toast('Success Title', 'Payment was Successfull.');
                $arr = $response->getData();
                $user = Auth::user();

                $payment = new Payment();
                $payment->payment_id = $arr['id'];
                $payment->payer_id = $arr['payer']['payer_info']['payer_id'];
                $payment->payer_email = $arr['payer']['payer_info']['email'];
                $payment->amount = $arr['transactions'][0]['amount']['total'];
                $payment->currency = env('PAYPAL_CURRENCY');
                $payment->payment_status = $arr['state'];
                $payment->user_id = $user->id;
                $payment->save();

                // Create a new game user

                $game = Game::where('active', true)->first();
                $game->users()->attach($user->id);

                // Save new tickets for the user
                $newTickets = $request->session()->get('newTickets');
                foreach ($newTickets as $newTicket) {
                    $ticket = new Ticket();
                    $ticket->user_id = $user->id; // Associate the ticket with the logged-in user
                    $ticket->game_id = $game->id;
                    $ticket->object = $newTicket['numbers'];
                    $ticket->status = 'Active';
                    $ticket->comment = 'Some comments here..';
                    $ticket->save();
                }

                $request->session()->remove('newTickets');
                return redirect('dashboard')->with('status', 'Payment is Successfull. Your Transaction Id is : ' . $arr['id']);

            }
            else{
                return $response->getMessage();
            }
        }
        else{
            return 'Payment declined!!';
        }
    }

    public function error()
    {
        return redirect()->route('dashboard')->with('message', 'User declined the payment!');
    }

}
