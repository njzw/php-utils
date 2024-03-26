<?php

namespace Nigel\Utils\Core\Checksums;

use Exception;

class ContipayChecksum
{
    function generateChecksum($data, bool $encode = true, $privateKey = "", int $algo = OPENSSL_ALGO_SHA256): string
    {
        if (!empty ($privateKey)) {

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