<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Plan;
use Auth;
use Illuminate\Http\Request;

class PlanController extends Controller
{
    public function index() {
        $plans = Plan::all();
        return view('plan.index', ['plans' => $plans]);
    }

    public function create() {
        return view('plan.create');
    }

    public function store(Request $request) {
        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
        $price = $stripe->prices->create([
            'currency' => 'usd',
            'unit_amount' => $request->price * 100,
            'recurring' => ['interval' => 'month'],
            'product_data' => ['name' => $request->name],
        ]);

        Plan::create([
            'name' => $request->name,
            'price'=> $request->price,
            'stripe_product_id' => $price->product,
            'stripe_price_id' => $price->id
        ]);
        return redirect()->route('plan.index');
    }

    public function buy(Request $request, $id) {
        $currentUser = Auth::user();
        $plan = Plan::find($id);

        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
        $subscription = $stripe->subscriptions->create([
            'customer' => $currentUser->stripe_id,
            'items' => [['price' => $plan->stripe_price_id]],
        ]);

        $subs = $currentUser->subscriptions()->create([
            'user_id' => $currentUser->id,
            'stripe_id' => $subscription->id,
            'stripe_status' => $subscription->status,
            'stripe_price' => $plan->stripe_price_id,
            'quantity' => 1,
        ]);

        $stripe->subscriptionItems->create([
            'subscription' => $subscription->id,
            'price' => 'price_1Mr6rdLkdIwHu7ixwPmiybbR',
            'quantity' => 2,
        ]);
    }
    
    public function setPaymentMethod() {
        return view('payment.create');
    }

    public function paymentMethod(Request $request){
        $currentUser = Auth::user();

        $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
        
        $stripe->paymentMethods->attach(
            $request->payment_method,
            [
                'customer' => $currentUser->stripe_id,
            ]
        );

        $stripe->customers->update(
            $currentUser->stripe_id,
            ['invoice_settings' => ['default_payment_method' => $request->payment_method]]
        );

        return redirect()->route('plan.index');
    }
}
