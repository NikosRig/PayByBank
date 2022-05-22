<?php

declare(strict_types=1);

namespace PayByBank\Infrastructure\Http\Gateway\ING;

use OpenSSLAsymmetricKey;

class IngCredentials
{
    private string $keyId;

    private string $tppCert;

    private string $signKeyPath;

    private string $redirectUrl;

    public function __construct(
        string $signKeyPath,
        string $tppCert,
        string $keyId,
        string $redirectUrl
    ) {
        $this->signKeyPath = $signKeyPath;
        $this->tppCert = $tppCert;
        $this->keyId = $keyId;
        $this->redirectUrl = $redirectUrl;
    }

    /**
     * @return string
     */
    public function getRedirectUrl(): string
    {
        return $this->redirectUrl;
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
