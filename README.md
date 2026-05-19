# SMS2Connect PHP SDK (PHP 8.3+)

A modern, strictly typed, zero-dependency PHP wrapper for the SMS2Connect API Gateway.

This package utilizes modern PHP 8.3 features such as constructor property promotion, strict declarations, and read-only properties to deliver a secure and clean developer experience.

---

# Features

- PHP 8.3+ Optimized
- Zero Dependencies
- Native cURL Integration
- Native JSON Handling
- SMS Broadcasting
- OTP Verification Support
- Delivery Status Tracking
- Account Balance Monitoring
- Structured Exception Handling
- Strict Typing Support

---

# Requirements

Ensure your environment includes:

- PHP 8.3 or higher
- PHP Extension: `ext-curl`
- PHP Extension: `ext-json`

---

# Installation

Install the package using Composer.

```bash
composer require noumanqamar/sms2connect-php
```

---

# Initialization

```php
<?php

declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';

use YourUsername\SMS2Connect\SMS2Connect;

$apiKey = getenv('SMS2CONNECT_API_KEY')
    ?: 'YOUR_SECRET_API_KEY';

$smsClient = new SMS2Connect($apiKey);
```

---

# API Methods Reference

## 1. Send SMS / Verification OTP

### Method Signature

```php
public function sendSMS(
    string $senderId,
    string $mobile,
    string $message
): array
```

### Example Usage

```php
<?php

try {

    $result = $smsClient->sendSMS(
        senderId: 'YourBrand',
        mobile: '+923001234567',
        message: 'Your verification OTP code is 556677. Valid for 5 minutes.'
    );

    print_r($result);

    /*
      Example Response:

      Array
      (
          [status] => success
          [message_id] => msg_f38d72b10a
          [recipient] => +923001234567
      )
    */

} catch (\Exception $e) {

    echo $e->getMessage();
}
```

---

## 2. Check Account Balance

### Method Signature

```php
public function getBalance(): array
```

### Example Usage

```php
<?php

try {

    $accountBalance =
        $smsClient->getBalance();

    print_r($accountBalance);

    /*
      Example Response:

      Array
      (
          [status] => success
          [balance] => 24500
          [currency] => PKR
          [sms_remaining] => 24500
          [expiry_date] => 2027-12-31
      )
    */

} catch (\Exception $e) {

    echo $e->getMessage();
}
```

---

## 3. Delivery Status Tracking

### Method Signature

```php
public function getDeliveryStatus(
    string $messageId
): array
```

### Example Usage

```php
<?php

try {

    $messageId = 'msg_f38d72b10a';

    $trackingReport =
        $smsClient->getDeliveryStatus($messageId);

    print_r($trackingReport);

    /*
      Example Response:

      Array
      (
          [status] => success
          [message_id] => msg_f38d72b10a
          [delivery_status] => delivered
          [sent_time] => 2026-05-18T11:20:00Z
          [delivered_time] => 2026-05-18T11:20:03Z
      )
    */

} catch (\Exception $e) {

    echo $e->getMessage();
}
```

---

# Defensive Error Handling

```php
<?php

try {

    $smsClient->sendSMS(
        senderId: '',
        mobile: '',
        message: ''
    );

} catch (\Exception $sdkException) {

    echo $sdkException->getMessage();
}
```

---

# Security Best Practices

## Keep Secrets Sealed

❌ Incorrect

```php
$apiKey = 'my-secret-key';
```

✅ Correct

```php
$apiKey = getenv('SMS2CONNECT_API_KEY');
```

---

# Example Environment Variable

```env
SMS2CONNECT_API_KEY=your_secret_api_key
```

---

# License

MIT License

---

# Created By Nouman Qamar

https\://proofile.link

