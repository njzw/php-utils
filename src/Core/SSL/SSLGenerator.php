<?php

namespace Nigel\Utils\Core\SSL;

class SSLGenerator
{
    protected $rootFolder;

    public function __construct()
    {
        $this->rootFolder = rtrim(__DIR__, '/') . '/';
    }


    /**
     * Generate SSL files including private key, CSR, and self-signed certificate.
     *
     * @param string $domain The domain name for the certificate.
     * @param array $csrDetails The details for the CSR fields.
     * @param int $keyLength The length of the private key in bits (default is 2048).
     * @return array An associative array containing the paths to the generated files.
     */
    public function generateSSLFiles(string $domain, array $csrDetails, int $keyLength = 2048): array
    {
        // Generate a new private key.
        $privateKey = openssl_pkey_new(
            array(
                "digest_alg" => "sha512",
                "private_key_bits" => $keyLength,
                "private_key_type" => OPENSSL_KEYTYPE_RSA,
            )
        );

        $csr = openssl_csr_new($csrDetails, $privateKey);

        $cert = openssl_csr_sign($csr, null, $privateKey, 365);

        $privateKeyPath = $this->rootFolder . 'private.key';
        openssl_pkey_export_to_file($privateKey, $privateKeyPath);

        $csrPath = $this->rootFolder . 'certificate.csr';
        openssl_csr_export_to_file($csr, $csrPath);

        $certPath = $this->rootFolder . 'certificate.crt';
        openssl_x509_export_to_file($cert, $certPath);

        return array(
            'privateKeyPath' => $privateKeyPath,
            'csrPath' => $csrPath,
            'certPath' => $certPath
        );
    }
}