Step 2: Install the Stripe Package for Laravel

composer require stripe/stripe-php

Step 3: Configure Stripe Keys //.env 

STRIPE_KEY=<stripe-key>
STRIPE_SECRET=<stripe-secret>
    
Step 4 : Create a new Stripe charge.

$charge = \Stripe\Charge::create([
    'customer' => $customer->id,
    'amount' => $amount,
    'currency' => 'usd',
]);


Step 5: Test the Integration

php artisan serve
