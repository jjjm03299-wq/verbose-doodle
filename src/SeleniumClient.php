<?php

namespace Vendor\Selenium;

class SeleniumClient
{
    private string $webdriver = 'http://localhost:5900';
    private string $api = 'http://localhost:8080';

    public function status(): string
    {
        return file_get_contents($this->webdriver . '/status');
    }

    public function createSession(array $capabilities = []): string
    {
        $payload = json_encode([
            'capabilities' => [
                'alwaysMatch' => $capabilities
            ]
        ]);

        return $this->request(
            'POST',
            $this->webdriver . '/session',
            $payload
        );
    }

    public function deleteSession(string $sessionId): string
    {
        return $this->request(
            'DELETE',
            $this->webdriver . '/session/' . $sessionId
        );
    }

    public function open(string $url): string
    {
        return file_get_contents(
            $this->api . '/open?url=' . urlencode($url)
        );
    }

    private function request(string $method, string $url, ?string $body = null): string
    {
        $options = [
            'http' => [
                'method' => $method,
                'ignore_errors' => true
            ]
        ];

        if ($body !== null) {
            $options['http']['header'] = "Content-Type: application/json\r\n";
            $options['http']['content'] = $body;
        }

        return file_get_contents(
            $url,
            false,
            stream_context_create($options)
        );
    }
}
