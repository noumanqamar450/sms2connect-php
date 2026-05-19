<?php

declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';

use NoumanQamar\SMS2Connect\SMS2Connect;

try {
    // 1. Initialize SDK safely
    $sms = new SMS2Connect('YOUR_ACTUAL_API_KEY_HERE');

    // 2. Test Sending SMS
    echo "Testing sendSMS...\n";
    $smsResult = $sms->sendSMS('YourBrand', '+923001234567', 'Your PHP 8.3 App OTP is 556677');
    print_r($smsResult);

    // 3. Test Checking Balance
    echo "\nTesting getBalance...\n";
    $balance = $sms->getBalance();
    print_r($balance);

    // 4. Test Checking Delivery Status
    echo "\nTesting getDeliveryStatus...\n";
    $status = $sms->getDeliveryStatus('msg_abc123xyz');
    print_r($status);

} catch (Exception $e) {
    echo "An Error Occurred: " . $e->getMessage() . "\n";
}