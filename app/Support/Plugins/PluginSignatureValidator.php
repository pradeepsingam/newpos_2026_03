<?php

namespace App\Support\Plugins;

use Illuminate\Validation\ValidationException;

class PluginSignatureValidator
{
    public function validate(PluginManifest $manifest): void
    {
        $publicKey = config('plugins.signing_public_key');

        if (blank($publicKey)) {
            throw ValidationException::withMessages([
                'plugin_zip' => 'Plugin signing public key is not configured.',
            ]);
        }

        $verified = openssl_verify(
            $manifest->unsignedPayload(),
            base64_decode($manifest->signature(), true) ?: '',
            $publicKey,
            OPENSSL_ALGO_SHA256
        );

        if ($verified !== 1) {
            throw ValidationException::withMessages([
                'plugin_zip' => 'Plugin signature verification failed.',
            ]);
        }
    }
}
