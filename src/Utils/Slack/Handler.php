<?php

namespace App\Utils\Slack;

use GuzzleHttp\Client;

class Handler
{
    private $payload;

    public function __construct(Payload $payload)
    {
        $this->payload = $payload;
    }

    public function postToSlack()
    {
        $payload = [
            'text' => $this->payload->getText(),
            'attachments' => []
        ];
        $i = 0;
        foreach ($this->payload->getAttachments() as $attachment) {
            $payload['attachments'][$i] = [
                'color' => $attachment->getColor(),
                'fields' => []
            ];
            foreach ($attachment->getFields() as $field) {
                $payload['attachments'][$i]['fields'][] = [
                    'value' => $field->getValue()
                ];
            }
            $i++;
        }
        $client = new Client();
        $client->post($_ENV['SLACK_INCOMING_WEBHOOK'], [
            'body' => json_encode($payload)
        ]);
    }
}
