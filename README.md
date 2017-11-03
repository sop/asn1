[![Build Status](https://travis-ci.org/sop/asn1.svg?branch=master)](https://travis-ci.org/sop/asn1)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/sop/asn1/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/sop/asn1/?branch=master)
[![Coverage Status](https://coveralls.io/repos/github/sop/asn1/badge.svg?branch=master)](https://coveralls.io/github/sop/asn1?branch=master)
[![License](https://poser.pugx.org/sop/asn1/license)](https://github.com/sop/asn1/blob/master/LICENSE)

# ASN.1

A PHP library for X.690 Abstract Syntax Notation One (ASN.1)
Distinguished Encoding Rules (DER) encoding and decoding.

## Requirements

-   PHP >=7.0
-   gmp
-   mbstring

## Installation

This library is available on
[Packagist](https://packagist.org/packages/sop/asn1).

    composer require sop/asn1

## Usage

The general idea is that each ASN.1 type has its corresponding PHP class,
that knows the details of encoding and decoding the specific type.

To decode DER data, use `fromDER` static method of the expected type.
To encode object to DER, use `toDER` instance method.

All objects are immutable and method chaining is promoted for the fluency
of the API. Exception shall be thrown on errors.

## Code Examples

Here are some simple usage examples. Namespaces are omitted for brevity.

### Encode

Encode a sequence containing a UTF-8 string, an integer
and an explicitly tagged object identifier.

```php
$seq = new Sequence(
    new UTF8String("Hello"),
    new Integer(42),
    new ExplicitlyTaggedType(
        1, new ObjectIdentifier("1.3.6.1.3"))
);
$der = $seq->toDER();
echo bin2hex($der);
```

Outputs:

    30120c0548656c6c6f02012aa10606042b060103

### Decode

Decode DER encoding from above.

```php
$seq = Sequence::fromDER($der);
echo $seq->at(0)->asUTF8String()->string() . "\n";
echo $seq->at(1)->asInteger()->number() . "\n";
echo $seq->at(2)->asTagged()->asExplicit()
    ->asObjectIdentifier()->oid() . "\n";
```

Outputs:

    Hello
    42
    1.3.6.1.3

### Real-World Examples

See the following for more practical real-world usage examples.

-   EC Private Key
    -   [Decode](https://github.com/sop/crypto-util/blob/1.5.1/lib/CryptoUtil/ASN1/EC/ECPrivateKey.php#L67)
    -   [Encode](https://github.com/sop/crypto-util/blob/1.5.1/lib/CryptoUtil/ASN1/EC/ECPrivateKey.php#L192)
-   X.501 Attribute
    -   [Decode](https://github.com/sop/x501/blob/0.3.0/lib/X501/ASN1/Attribute.php#L54)
    -   [Encode](https://github.com/sop/x501/blob/0.3.0/lib/X501/ASN1/Attribute.php#L108)
-   X.509 Certificate (`TBSCertificate` sequence)
    -   [Decode](https://github.com/sop/x509/blob/0.3.0/lib/X509/Certificate/TBSCertificate.php#L127)
    -   [Encode](https://github.com/sop/x509/blob/0.3.0/lib/X509/Certificate/TBSCertificate.php#L534)

## License

This project is licensed under the MIT License.
