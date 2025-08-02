<?php

namespace App\Messenger\TestTopic;

readonly class TestTopicMessage
{
    public function __construct(private string $name,  private int $payment)
    {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPayment(): int
    {
        return $this->payment;
    }


}
