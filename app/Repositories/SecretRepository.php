<?php

namespace App\Repositories;

use App\Input\SecretInput;
use App\Models\Secret;

class SecretRepository
{
    public function store(SecretInput $input): Secret
    {
        $secret = new Secret();
        $secret->secret_key = $input->getSecretKey();
        $secret->ttl = $input->getTtl();
        $secret->secret = $input->getSecret();
        $secret->passphrase = $input->getPassphrase();

        $secret->save();

        return $secret;
    }

    public function delete(SecretInput $input): bool
    {
        return Secret::where('secret_key', $input->getSecretKey())->delete();
    }
}
