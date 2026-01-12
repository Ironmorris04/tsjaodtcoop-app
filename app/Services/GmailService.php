<?php

namespace App\Services;

use Google\Client;
use Google\Service\Gmail;
use Google\Service\Gmail\Message;

class GmailService
{
    protected Client $client;
    protected Gmail $service;

    public function __construct()
    {
        $this->client = new Client();
        $this->client->setApplicationName('Laravel Gmail API');
        $this->client->setScopes([Gmail::GMAIL_SEND]);
        $this->client->setAuthConfig(storage_path('app/google/render-google-client-secret.json'));
        $this->client->setAccessType('offline');

        $tokenPath = storage_path('app/google/token.json');

        // Load existing token if available
        if (file_exists($tokenPath)) {
            $accessToken = json_decode(file_get_contents($tokenPath), true);
            $this->client->setAccessToken($accessToken);
        }

        // Refresh token if expired
        if ($this->client->isAccessTokenExpired()) {
            if ($this->client->getRefreshToken()) {
                $this->client->fetchAccessTokenWithRefreshToken($this->client->getRefreshToken());
            } else {
                // First time authentication: manual copy-paste of code
                $authUrl = $this->client->createAuthUrl();
                echo "Open this URL in your browser:\n$authUrl\n";
                echo "Enter verification code: ";
                $authCode = trim(fgets(STDIN));
                $accessToken = $this->client->fetchAccessTokenWithAuthCode($authCode);
                file_put_contents($tokenPath, json_encode($accessToken));
                $this->client->setAccessToken($accessToken);
            }
        }

        $this->service = new Gmail($this->client);
    }

    /**
     * Send an email using Gmail API
     *
     * @param string $to Recipient email
     * @param string $subject Email subject
     * @param string $body Email body (HTML or plain text)
     */
    public function sendEmail(string $to, string $subject, string $body): Message
    {
        // Prepare raw MIME message
        $rawMessage = "From: me\r\n";
        $rawMessage .= "To: $to\r\n";
        $rawMessage .= "Subject: $subject\r\n";
        $rawMessage .= "MIME-Version: 1.0\r\n";
        $rawMessage .= "Content-Type: text/html; charset=UTF-8\r\n\r\n";
        $rawMessage .= $body;

        // Encode in base64url format for Gmail API
        $encodedMessage = rtrim(strtr(base64_encode($rawMessage), '+/', '-_'), '=');

        $message = new Message();
        $message->setRaw($encodedMessage);

        return $this->service->users_messages->send('me', $message);
    }
}
