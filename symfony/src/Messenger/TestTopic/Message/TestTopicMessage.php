<?php

declare(strict_types=1);

namespace App\Messenger\TestTopic\Message;

readonly class TestTopicMessage
{
    public function __construct(
        private string $id,
        private string $name,
        private string $payment,
        private ?string $messageKey = null,
        private ?int $messageOffset = null,
    ) {
    }

    public function getMessageKey(): ?string
    {
        return $this->messageKey;
    }

    public function getMessageOffset(): ?int
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
