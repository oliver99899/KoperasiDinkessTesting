<?php

namespace App\Models\Concerns;

use App\Support\HmacSigner;

trait HasSignature
{
    public function refreshSignature(): void
    {
        $this->signature = HmacSigner::sign($this->signaturePayload());
    }

    public function signatureIsValid(): bool
    {
        return hash_equals((string) $this->signature, HmacSigner::sign($this->signaturePayload()));
    }

    abstract protected function signaturePayload(): string;
}
