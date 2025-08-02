<?php

namespace App\Messenger\TestTopic;

use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;
use Symfony\Component\Messenger\Exception\MessageDecodingFailedException;
use Symfony\Component\Messenger\Envelope;

class TestTopicJsonSerializer implements SerializerInterface
{
    public function decode(array $encodedEnvelope): Envelope
    {
        $body = $encodedEnvelope['body'] ?? '';
        if (empty($body)) {
            throw new MessageDecodingFailedException('Empty Kafka message');
        }

        $data = json_decode($body, true, 512, JSON_THROW_ON_ERROR);

        $message = new TestTopicMessage(
            $data['name'] ?? null,
            $data['payment'] ?? 0,
        );

        return new Envelope($message);
    }

    public function encode(Envelope $envelope): array
    {
        /** @var TestTopicMessage $message */
        $message = $envelope->getMessage();

        $payload = [
            'name' => $message->getName(),
            'payment' => $message->getPayment(),
        ];

        return [
            'body' => json_encode($payload, JSON_THROW_ON_ERROR),
            'headers' => [],
        ];
    }
}
