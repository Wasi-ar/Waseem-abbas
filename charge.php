<?php
require_once 'vendor/autoload.php';  
\Stripe\Stripe::setApiKey('your_secret_key');


$token = $_POST['stripeToken'];
$total_price = $_POST['total_price'];

try {
   
    $charge = \Stripe\Charge::create([
        'amount' => $total_price * 100, 
        'currency' => 'usd',
        'description' => 'Order Payment',
        'source' => $token,
    ]);

    
    header('Location: Thanku.php?payment_status=success');
} catch (\Stripe\Exception\CardException $e) {
    
    echo 'Error: '. $e->getError()->message;
}
?>