<?php

declare(strict_types=1);

namespace PayByBank\Infrastructure\Http\Gateway\Credential;

use OpenSSLAsymmetricKey;

class IngCredentials
{
    private string $keyId;

    private string $tppCert;

    private string $signKeyPath;

    public function __construct(
        string $signKeyPath,
        string $tppCert,
        string $keyId
    )
    {
        $this->signKeyPath = $signKeyPath;
        $this->tppCert = $tppCert;
        $this->keyId = $keyId;
    }

    /**
     * @return OpenSSLAsymmetricKey
     */
    public function getSignKey(): OpenSSLAsymmetricKey
    {
        return openssl_pkey_get_private('file://' .$this->signKeyPath);
    }

    /**
     * @return string
     */
    public function getTppCert(): string
    {
        openssl_x509_export($this->tppCert, $tppCert);

        return str_replace(PHP_EOL, '', $tppCert);
    }

    /**
     * @return string
     */
    public function getKeyId(): string
    {
        return $this->keyId;
    }
}