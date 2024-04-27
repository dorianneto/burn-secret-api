<?php

namespace App\Input;

use Exception;
use Ramsey\Uuid\Uuid;

class SecretInput
{
    public function __construct(
        protected ?string $secret = null,
        protected ?string $secretKey = null,
        protected ?int $ttl = null,
        protected ?string $passphrase = null,
    ) {}

    public function getSecretKey(): string
    {
        return $this->secretKey ?? Uuid::uuid4();
    }

    public function getTtl(): int
    {
        return $this->ttl ?? 0;
    }

    public function getSecret(): string
    {
        return $this->secret ?? throw new Exception('Secret cannot be null');
    }

    public function getPassphrase(): ?string
    {
        return $this->passphrase ?? null;
    }
}
