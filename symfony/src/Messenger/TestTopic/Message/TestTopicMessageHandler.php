<?php

declare(strict_types=1);

namespace App\Messenger\TestTopic\Message;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;

#[AsMessageHandler]
readonly class TestTopicMessageHandler
{
    public function __construct(private ParameterBagInterface $params)
    {
    }

    public function __invoke(TestTopicMessage $message): void
    {
        $logPath = $this->params->get('kernel.project_dir') . '/var/kafka/test_topic.log';

        file_put_contents($logPath, json_encode([
            'offset' => $message->getMessageOffset(),
            'key' => $message->getMessageKey(),
            'id' => $message->getId(),
            'name' => $message->getName(),
            'payment' => $message->getPayment(),
            'datetime' => (new \DateTimeImmutable())->setTimezone(new \DateTimeZone('Europe/Moscow'))->format('d.m.Y H:i:s'),
        ], JSON_THROW_ON_ERROR) . PHP_EOL, FILE_APPEND);
    }
}
