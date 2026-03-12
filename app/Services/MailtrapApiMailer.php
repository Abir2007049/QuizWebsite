<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use RuntimeException;

class MailtrapApiMailer
{
    public function isConfigured(): bool
    {
        return (bool) config('services.mailtrap.token');
    }

    public function send(string $toEmail, string $subject, string $html, ?string $text = null, ?string $toName = null): void
    {
        $token = config('services.mailtrap.token');
        $endpoint = config('services.mailtrap.endpoint');
        $fromAddress = config('services.mailtrap.from_address');
        $fromName = config('services.mailtrap.from_name');

        if (!$token || !$endpoint || !$fromAddress) {
            throw new RuntimeException('Mailtrap API is not configured.');
        }

        $payload = [
            'from' => [
                'email' => $fromAddress,
                'name' => $fromName,
            ],
            'to' => [[
                'email' => $toEmail,
                'name' => $toName ?: $toEmail,
            ]],
            'subject' => $subject,
            'html' => $html,
            'text' => $text ?: strip_tags($html),
        ];

        Http::withToken($token)
            ->acceptJson()
            ->timeout(15)
            ->post($endpoint, $payload)
            ->throw();
    }
}