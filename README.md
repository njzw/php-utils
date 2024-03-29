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

// OR

try {
    $sslFiles = $sslGenerator->generateSSLFiles($domain, $csrDetails);
    echo $sslFiles;
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}

?>
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
    SSL KEY HERE
-----END PRIVATE KEY-----
EOD;

$authKey = "NmZKTkdJdnVDJMWnJMQT";
$reference = "PAYOUT-83bbbe26-1cfa-435d";
$merchantId = $MERCHANT_ID;
$accountNumber = "0782000340";
$amount = "5.0";

$checksum = (new ContipayChecksum())->generateChecksum($authKey . $reference . $merchantId . $accountNumber . $amount, true, openssl_get_privatekey($privKey, ""));

echo $checksum;
?>
```
