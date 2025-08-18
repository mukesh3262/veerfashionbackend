<?php

declare(strict_types=1);

namespace App\Jobs;

use Google_Client;
use GuzzleHttp\Client;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class FcmPushJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(
        public string $token,
        public string $title,
        public string $body,
        public ?array $data = null,
        public ?string $image = null
    ) {}

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $message = [
            'to' => $this->token,
            'notification' => [
                'title' => $this->title,
                'body' => $this->body,
                'image' => $this->image,
            ],
            'data' => $this->data,
        ];

        $projectId = config('services.fcm.project_id');

        $client = new Client();
        $client->post("https://fcm.googleapis.com/v1/projects/{$projectId}/messages:send", [
            'headers' => [
                'Authorization' => 'Bearer '.$this->getAccessToken(),
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'message' => [
                    'token' => $message['to'],
                    'notification' => $message['notification'],
                    'data' => $message['data'],
                ],
            ],
        ]);
    }

    /**
     * Fetches the access token from the service account credentials
     */
    private function getAccessToken(): string
    {
        $credentialsPath = base_path(config('services.fcm.json_path'));

        // Instantiate the Google Client with the service account credentials
        $client = new Google_Client();
        $client->setAuthConfig($credentialsPath);

        // Add the scope for Firebase Cloud Messaging
        $client->addScope(\Google\Service\FirebaseCloudMessaging::FIREBASE_MESSAGING);

        // Fetch the access token
        $token = $client->fetchAccessTokenWithAssertion();

        // Return the access token
        return $token['access_token'];
    }
}
