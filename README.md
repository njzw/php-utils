PHP Util Scripts

## Installation

```
 composer require nigel/utils
```


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

## 3. Phone Helper

This script utilizes a custom wrapper around the 'giggsey/libphonenumber-for-php' library to format and validate phone numbers.


### Example

```php
<?php

use Nigel\Utils\Core\Phone\Phone;

require_once './app/bootstrap.php';

header('Content-type: application/json');

$phone = "0782000340"; // Zimbabwe Econet Number

$test = (new Phone($phone, 'ZW'))->internationalFormat();


echo $test;


```

---

### Response

```

263782000340

```

---

### Available Methods

1. isValid()

- check if number is valid or not


2. internationalFormat()

- get international format from the parsed number

3. nationalFormat()

- get national format from the parsed number

4. getCountry()

- get country name from the parsed number

5. providerInfo()

- get provider info from the parsed number

6. timeZoneInfo()

- get timezone info from the parsed number

---
