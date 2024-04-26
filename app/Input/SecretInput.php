<?php

namespace App\Input;

use Ramsey\Uuid\Uuid;

class SecretInput
{
    public function __construct(
        protected string $secret,
        protected ?int $ttl,
        protected ?string $passphrase,
    ) {}

    public function getSecretKey(): string
    {
        return Uuid::uuid4();
    }

    public function getTtl(): int
    {
        return $this->ttl ?? 0;
    }

    public function getSecret(): string
    {
        return $this->secret;
    }

    public function getPassphrase(): ?string
    {
        return $this->passphrase ?? null;
    }
}
