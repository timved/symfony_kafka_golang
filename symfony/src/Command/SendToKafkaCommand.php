<?php

declare(strict_types=1);

namespace App\Command;

use App\Messenger\TestTopic\Message\TestTopicMessage;
use Faker\Factory;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Messenger\MessageBusInterface;

#[AsCommand(
    name: 'app:send-kafka',
    description: 'Отправка сообщений в кафку',
)]
class SendToKafkaCommand extends Command
{
    public function __construct(private readonly MessageBusInterface $messageBus)
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->testKafka();

        return self::SUCCESS;
    }

    private function testKafka(): void
    {
        $faker = Factory::create('ru_RU');
        for ($i = 1000; $i <= 1002; ++$i) {
            $this->messageBus->dispatch(new TestTopicMessage('partition_1', $faker->text(7), $i));
        }
    }
}
