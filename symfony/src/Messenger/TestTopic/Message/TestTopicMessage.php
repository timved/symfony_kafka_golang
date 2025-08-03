<?php

declare(strict_types=1);

namespace App\Messenger\TestTopic\Message;

readonly class TestTopicMessage
{
    public function __construct(
        private ?string $messageKey = null,
        private ?string $messageOffset = null,
        private string $id,
        private string $name,
        private string $payment,
    ) {
    }

    public function getMessageKey(): ?string
    {
        return $this->messageKey;
    }

    public function getMessageOffset(): ?string
    {
        return $this->messageOffset;
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
