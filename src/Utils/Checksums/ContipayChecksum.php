<?php

namespace Nigel\SslGenerator\Utils\Checksums;

use Exception;

class ContipayChecksum
{
    /**
     * Generate checksum using OpenSSL signing.
     *
     * @param mixed $data The data to generate the checksum for.
     * @param bool $encode Whether to encode the checksum in base64.
     * @param string $privateKeyPath The path to the file containing the private key.
     * @param int $algo The algorithm to use for signing (default is SHA256).
     * @return string The generated checksum.
     */
    function generateChecksum(mixed $data, bool $encode = true, string $privateKeyPath = "", int $algo = OPENSSL_ALGO_SHA256): string
    {

        if (!empty ($privateKeyPath) && file_exists($privateKeyPath)) {

            $privateKey = file_get_contents($privateKeyPath);

            if (openssl_sign($data, $signature, $privateKey, $algo)) {

                if ($encode) {
                    return base64_encode($signature);
                }

                return $signature;
            } else {

                throw new Exception("Failed to generate signature.");
            }
        } else {

            throw new Exception("Private key file path is empty or invalid.");
        }
    }
}