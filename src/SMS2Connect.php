<?php

declare(strict_types=1);

namespace NoumanQamar\SMS2Connect;

use Exception;

class SMS2Connect
{
    private string $baseUrl = 'https://api.sms2connect.com/v1';

    /**
     * Initializes the SMS2Connect client using PHP 8+ Constructor Promotion.
     * 
     * @param string $apiKey Your secret SMS2Connect API Key.
     */
    public function __construct(
        private readonly string $apiKey
    ) {
        if (empty($this->apiKey)) {
            throw new Exception('SMS2Connect SDK Error: Secret API key is required.');
        }
    }

    /**
     * Internal central HTTP request handler using native cURL.
     */
    private function request(string $endpoint, string $method = 'GET', ?array $body = null): array
    {
        $url = $this->baseUrl . $endpoint;

        if ($method === 'POST' && $body !== null) {
            $payload = json_encode(array_merge(['api_key' => $this->apiKey], $body), JSON_THROW_ON_ERROR);
        } else if ($method === 'GET') {
            $separator = str_contains($url, '?') ? '&' : '?';
            $url .= $separator . http_build_query(['api_key' => $this->apiKey]);
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);

        if ($method === 'POST' && isset($payload)) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json',
                'Content-Length: ' . strlen($payload)
            ]);
        }

        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        if (curl_errno($ch)) {
            $errorMsg = curl_error($ch);
            curl_close($ch);
            throw new Exception("SMS2Connect cURL Error: {$errorMsg}");
        }

        curl_close($ch);

        $data = json_decode((string)$response, true);

        if ($httpCode >= 400) {
            throw new Exception("SMS2Connect API Response Error (HTTP {$httpCode}): " . ($response ?: 'Empty Response'));
        }

        return $data ?? [];
    }

    /**
     * Sends an SMS or automated verification OTP via the gateway.
     */
    public function sendSMS(string $senderId, string $mobile, string $message): array
    {
        if (empty($senderId) || empty($mobile) || empty($message)) {
            throw new Exception('SMS2Connect SDK Error: senderId, mobile, and message parameters are all mandatory.');
        }

        return $this->request('/send-sms', 'POST', [
            'sender_id' => $senderId,
            'mobile' => $mobile,
            'message' => $message
        ]);
    }

    /**
     * Queries active account credit balances.
     */
    public function getBalance(): array
    {
        return $this->request('/balance', 'GET');
    }

    /**
     * Evaluates the delivery status of a specific message transaction.
     */
    public function getDeliveryStatus(string $messageId): array
    {
        if (empty($messageId)) {
            throw new Exception('SMS2Connect SDK Error: messageId parameter must be provided.');
        }

        return $this->request("/delivery-status/{$messageId}", 'GET');
    }
}