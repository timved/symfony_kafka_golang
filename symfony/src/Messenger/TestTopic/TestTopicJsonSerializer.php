<?php

declare(strict_types=1);

namespace App\Messenger\TestTopic;

use App\Messenger\TestTopic\Message\TestTopicMessage;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\Exception\MessageDecodingFailedException;
use Symfony\Component\Messenger\Transport\Serialization\SerializerInterface;

class TestTopicJsonSerializer implements SerializerInterface
{
    public function decode(array $encodedEnvelope): Envelope
    {
        //     [
        //         'body' => '<payload>',
        //         'headers' => [
        //             'key' => '<Kafka Key>',
        //             'offset' => 42,
        //             'partition' => 0,
        //             'topic' => 'test',
        //             // ... другие заголовки
        //         ],
        //         // messenger стандартные поля:
        //         'message' => ...
        //     ]
        $body = $encodedEnvelope['body'] ?? '';
        $headers = $encodedEnvelope['headers'] ?? [];
        $kafkaKey = $headers['key'] ?? null;
        $kafkaOffset = $headers['offset'] ?? null;

        if (empty($body)) {
            throw new MessageDecodingFailedException('Empty Kafka message');
        }

        $data = json_decode($body, true, 512, JSON_THROW_ON_ERROR);

        $message = new TestTopicMessage(
            $kafkaKey,
            $kafkaOffset,
            $data['id'],
            $data['name'],
            $data['payment'],
        );

        return new Envelope($message);
    }

    public function encode(Envelope $envelope): array
    {
        /** @var TestTopicMessage $message */
        $message = $envelope->getMessage();

        $payload = [
            'id' => $message->getId(),
            'name' => $message->getName(),
            'payment' => $message->getPayment(),
        ];

        $headers = [];
        if ($message->getMessageKey()) {
            $headers['key'] = $message->getMessageKey();
        }

        if ($message->getMessageOffset()) {
            $headers['offset'] = $message->getMessageOffset();
        }

        return [
            'key' => $message->getMessageKey(),
            'headers' => $headers,
            'body' => json_encode($payload, JSON_THROW_ON_ERROR),
        ];
    }
}
