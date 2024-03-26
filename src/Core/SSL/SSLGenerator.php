<?php

namespace Nigel\Utils\Core\SSL;

class SSLGenerator
{
    protected $rootFolder;

    public function __construct(string $rootFolder = "")
    {
        $this->rootFolder = rtrim($rootFolder ?: __DIR__, '/') . '/';
    }

    protected function generatePrivateKey(int $keyLength = 2048)
    {
        return openssl_pkey_new([
            "digest_alg" => "sha512",
            "private_key_bits" => $keyLength,
            "private_key_type" => OPENSSL_KEYTYPE_RSA,
        ]);
    }

    /**
     * Generate SSL files and return their paths as a JSON string.
     *
     * @param string $domain
     * @param array $csrDetails
     * @param int $keyLength
     * @return string JSON-encoded string containing paths to generated SSL files
     */
    public function generateSSLFiles(string $domain, array $csrDetails, int $keyLength = 2048): string
    {
        $privateKey = $this->generatePrivateKey($keyLength);

        $csr = openssl_csr_new($csrDetails, $privateKey);
        if (!$csr) {
            throw new \RuntimeException("Failed to generate CSR");
        }

        $cert = openssl_csr_sign($csr, null, $privateKey, 365);
        if (!$cert) {
            throw new \RuntimeException("Failed to sign CSR");
        }

        $privateKeyPath = $this->rootFolder . 'private.key';
        openssl_pkey_export_to_file($privateKey, $privateKeyPath);

        $csrPath = $this->rootFolder . 'certificate.csr';
        openssl_csr_export_to_file($csr, $csrPath);

        $certPath = $this->rootFolder . 'certificate.crt';
        openssl_x509_export_to_file($cert, $certPath);

        return json_encode([
            'privateKeyPath' => $privateKeyPath,
            'csrPath' => $csrPath,
            'certPath' => $certPath
        ]);
    }

    /**
     * Generate SSL strings and return them as an array.
     *
     * @param string $domain
     * @param array $csrDetails
     * @param int $keyLength
     * @return array
     */
    public function generateSSLStrings(string $domain, array $csrDetails, int $keyLength = 2048): array
    {
        $privateKey = $this->generatePrivateKey($keyLength);

        $csr = openssl_csr_new($csrDetails, $privateKey);
        if (!$csr) {
            throw new \RuntimeException("Failed to generate CSR");
        }

        $cert = openssl_csr_sign($csr, null, $privateKey, 365);
        if (!$cert) {
            throw new \RuntimeException("Failed to sign CSR");
        }

        openssl_pkey_export($privateKey, $privateKeyString);
        openssl_csr_export($csr, $csrString);
        openssl_x509_export($cert, $certString);

        return [
            'privateKey' => $privateKeyString,
            'csr' => $csrString,
            'cert' => $certString
        ];
    }
}
