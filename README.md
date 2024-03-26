PHP Util Scripts

---

# 1. SSL Certificate Generator

This script is a PHP utility for generating SSL certificates, including private keys, certificate signing requests (CSRs), and certificates themselves.

## Example

```php
<?php

use Nigel\Utils\Core\SSL\SSLGenerator;

require_once './app/bootstrap.php';

header('Content-type: application/json');

$domain = 'example.com';

$rootFolder = rtrim(__DIR__, '/') . '/files/';
$sslGenerator = new SSLGenerator($rootFolder);

$csrDetails = array(
    "countryName" => "ZW",
    "stateOrProvinceName" => "Harare",
    "localityName" => "Harare",
    "organizationName" => "Example Inc",
    "organizationalUnitName" => "IT Department",
    "commonName" => $domain,
    "emailAddress" => "admin@$domain",
);

try {
    $ssStrings = $sslGenerator->generateSSLStrings($domain, $csrDetails);
    echo "Private Key:\n\n" . $ssStrings['privateKey'] . "\n\n" .
        "CSR:\n\n" . $ssStrings['csr'] . "\n\n" .
        "Cert:\n\n" . $ssStrings['cert'];
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}
?>

// OR

try {
    $sslFiles = $sslGenerator->generateSSLFiles($domain, $csrDetails);
    echo $sslFiles;
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}
```

## 2. Contipay Checksum Generator

This PHP script generates a checksum using Contipay's checksum algorithm for transaction verification. The generated checksum is essential for ensuring the integrity and authenticity of the transaction data.

### Example

```php
<?php
use Nigel\Utils\Core\Checksums\ContipayChecksum;

require_once './app/bootstrap.php';

$privKey = <<<EOD
-----BEGIN PRIVATE KEY-----
MIIEvAIBADANBgkqhkiG9w0BAQEFAASCBKYwggSiAgEAAoIBAQCjOoxBZnGPxftk
Om63IgnmdRY5VpINfaE8gLW2T91wvBSGXtH6p8lrC/EbKOo3uyXCToKsntm5R1RU
go5ZgFmeyKn1QPzMCw2bWepAS02uyTEYhLvj2R/KvgFDXeKNIBx8LXRhU/ej7DFX
9XVobxd3L7JwVbrvLmUzA073Jgl1f00qb9LA6NuxmwP3gstB3z28k4fGGsr22E+h
vw0fa5KlUNgcZTMVLQdTryvb7Sf1XN4pWdzXMae7ryI6dkILaXN//cLOTHu01DC4
6A+dOR2ku+GCvdVBH23ZF2O5HwW7dg3XQ/MVZB53eKyey2c2I1/Jz2mjdtapL4eU
xcgbI7HtAgMBAAECggEAL7P/o2leDOeOZadSDgFLpWdYnF85henOQlFXSbWjgLvm
v7JcaYW5rqgpyYc2lB0Elvm85Nfzx1saQSx6j5ucFXSNfxiECNm+G0W22R1o0YkI
6SJ5isg8q1LGKbr5Z8cXrA7YsU0J1YVEknEw49l7oSB2ZLAZVqdXegYggMhEAWtA
TVe8AnbxNCXe60myvkDKlUuGGp4X+KIGVbKw22nR12jLz9/pcKwmcLDfXdASPkpK
42fhd+Us6jG3CmngzD57mh/vIpVf6loelToLSTumJGlm5JBgaNB7qpZybinJpnB7
H6aDv4sE2yQfvzVburyNr8walelHDRdCYs323uq+3QKBgQDRLLJPCXOZEeoQdPvH
WGg93ztVVT7NDIUlz3i6KaSofjnWKwgUel7f273VCNAjc43V1vhyhcd3jB/UT/3y
+QaowQoLLEPfSzW4XDITAhYJJEpRdld7jQQtXPeOgnlbZZOwReDKdhhWg1/Naj+s
KNlVHA7OZhALoi3eBAMi6IN7YwKBgQDHxMzoUfvOr3m8MvN3jkLY6jAX8owpc8Sd
Zr6VmhBAM0VsyDafASsBsrxeZOCuuAp+5WWWUnLg8mbULRnO3O5qcuDvPf7LK7GS
NRy89AI/f9/9K1obgj040z4llrgqnqyL1uVJBVJ8SUCmmHgR7hNh3FzS3oEf7HGp
29HEQxWmbwKBgC1L8JENCutq5bUKoDta+bfsT3z7KM5el7bIZuxpeC7EpuGqD+Xf
WgxAGau4bNpAe8aC9upV7gwFXB8t82BabQa+Rt6Eh24ja06xKGbVXNA9+5oIdola
0DzClRlXR9By/rh9aWBuknapnGVvTLqLXgUAPnSTxYW/aQ9a7xLwJwjhAoGAKBlN
1DhYpjU851UG3/GzY10myDfMgKmXRs0P5nGlX22rOtm5dRND8bRR8VSocQnKOYyM
Zq8oFhUyJNbkvkxEoyNqGTvFgDzGCQYWPatxJBPj/yqLjMgIx/ZHHD5zu0Jcejlp
js29r+r4SjiCqX6zb0pPa5h0LNdGPYU9Rvr8RosCgYBUuOVB0TIu/eLwntU0y7zB
+phk/efD1qLWpLN3zhj9LOaohKwxLf2Tr1fJ6tOo2fg39dM69AOgMt1m5CmMK1/h
/V1U+UAp0AyU+pj5tDhsPgH6mpXQQGqjpmyxrlaZEctlKH9zZ/Q+qevBAFiBqI0o
wwSbd4IigAYMGzgg3qVH1Q==
-----END PRIVATE KEY-----
EOD;

$authKey = "NmZKTkdJdnVDJMWnJMQT09";
$reference = "PAYOUT-83bbbe26-1cfa-435d";
$merchantId = 83;
$accountNumber = "0782000340";
$amount = "5.0";

$checksum = (new ContipayChecksum())->generateChecksum($authKey . $reference . $merchantId . $accountNumber . $amount, true, openssl_get_privatekey($privKey, ""));

echo $checksum;
?>
```
