<?php

namespace App\Messenger\TestTopic;

use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
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
                'name' => $message->getName(),
                'payment' => $message->getPayment(),
            ], JSON_THROW_ON_ERROR) . PHP_EOL, FILE_APPEND);

        echo sprintf(
            "Оплата от %s сумма %d \n",
            $message->getName(),
            $message->getPayment()
        );
    }
}
