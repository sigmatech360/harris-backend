<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stripe;
class PaymentController extends Controller

{
    /**
     * success response method.
     *
     * @return \Illuminate\Http\Response
     */

    public function stripePost(Request $request)
    {
        $validate = validator::make(
            $request->all(),
            [
            'email'    => 'required|email',
            'tahoe_id' => 'required|string',
            'types'    => 'required|array',
            'first_name'    => 'required|string',
            'last_name'    => 'required|string',
            'age'    => 'required|string',
            ]);
            
         if ($validate->fails()) {
            $response =
                [
                    'status' => false,
                    'message' => $validate->errors()
                ];
            return response()->json($response, 200);
        }
        $data = $request->all();
        Stripe\Stripe::setApiKey(env('STRIPE_SECRET'));
        Stripe\Charge::create ([
                "amount" => $request->amount * 100,
                "currency" => "usd",
                "source" => $request->stripeToken,
                "description" => "Test payment from " 
        ]);
        
    }
}