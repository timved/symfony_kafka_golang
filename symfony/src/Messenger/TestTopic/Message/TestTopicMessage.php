<?php

namespace App\Messenger\TestTopic\Message;

readonly class TestTopicMessage
{
    public function __construct(private string $id, private string $name,  private string $payment)
    {
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPayment(): string
    {
        return $this->payment;
    }


}
