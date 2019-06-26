# [ASN.1](https://sop.github.io/asn1/)

[![Build Status](https://travis-ci.org/sop/asn1.svg?branch=master)](https://travis-ci.org/sop/asn1)
[![Scrutinizer Code Quality](https://scrutinizer-ci.com/g/sop/asn1/badges/quality-score.png?b=master)](https://scrutinizer-ci.com/g/sop/asn1/?branch=master)
[![Coverage Status](https://coveralls.io/repos/github/sop/asn1/badge.svg?branch=master)](https://coveralls.io/github/sop/asn1?branch=master)
[![License](https://poser.pugx.org/sop/asn1/license)](https://github.com/sop/asn1/blob/master/LICENSE)

A PHP library for X.690 Abstract Syntax Notation One (ASN.1)
Distinguished Encoding Rules (DER) encoding and decoding.

## Requirements

- PHP >=7.2
- gmp
- mbstring

## Installation

This library is available on
[Packagist](https://packagist.org/packages/sop/asn1).

```sh
composer require sop/asn1
```

## Usage

The general idea is that each ASN.1 type has its corresponding PHP class,
that knows the details of encoding and decoding the specific type.

To decode DER data, use `fromDER` static method of the expected type.
To encode object to DER, use `toDER` instance method.

Many methods return an `UnspecifiedType` object, that works as an intermediate
wrapper with accessor methods ensuring type safety.

All objects are immutable and method chaining is promoted for the fluency
of the API. Exception shall be thrown on errors.

## [Code Examples](https://github.com/sop/asn1/tree/master/examples)

Here are some simple usage examples. Namespaces are omitted for brevity.

### [Encode](https://github.com/sop/asn1/blob/master/examples/encode.php)

Encode a sequence containing a UTF-8 string, an integer
and an explicitly tagged object identifier, conforming to the following
ASN.1 specification:

```asn.1
Example ::= SEQUENCE {
    greeting    UTF8String,
    answer      INTEGER,
    type    [1] EXPLICIT OBJECT IDENTIFIER
}
```

```php
$seq = new Sequence(
    new UTF8String('Hello'),
    new Integer(42),
    new ExplicitlyTaggedType(
        1, new ObjectIdentifier('1.3.6.1.3'))
);
$der = $seq->toDER();
```

### [Decode](https://github.com/sop/asn1/blob/master/examples/decode.php)

Decode DER encoding from above.

```php
$seq = UnspecifiedType::fromDER($der)->asSequence();
$greeting = $seq->at(0)->asUTF8String()->string();
$answer = $seq->at(1)->asInteger()->intNumber();
$type = $seq->at(2)->asTagged()->asExplicit()->asObjectIdentifier()->oid();
```

### Real-World Examples

See the following for more practical real-world usage examples.

- EC Private Key
  - [Decode](https://github.com/sop/crypto-types/blob/a27fa76d5f5e8c4596cb65a7be9d02a08421ba1e/lib/CryptoTypes/Asymmetric/EC/ECPrivateKey.php#L72)
  - [Encode](https://github.com/sop/crypto-types/blob/a27fa76d5f5e8c4596cb65a7be9d02a08421ba1e/lib/CryptoTypes/Asymmetric/EC/ECPrivateKey.php#L206)
- X.501 Attribute
  - [Decode](https://github.com/sop/x501/blob/c6bdb04673d5c04b9d49f83020e75b8ba7a20064/lib/X501/ASN1/Attribute.php#L55)
  - [Encode](https://github.com/sop/x501/blob/c6bdb04673d5c04b9d49f83020e75b8ba7a20064/lib/X501/ASN1/Attribute.php#L114)
- X.509 Certificate (`TBSCertificate` sequence)
  - [Decode](https://github.com/sop/x509/blob/f762c743b6930af4f45ef857ccc9f6199980a92e/lib/X509/Certificate/TBSCertificate.php#L130)
  - [Encode](https://github.com/sop/x509/blob/f762c743b6930af4f45ef857ccc9f6199980a92e/lib/X509/Certificate/TBSCertificate.php#L576)

## ASN.1 References

- [ITU-T X.690 07/2002](https://www.itu.int/ITU-T/studygroups/com17/languages/X.690-0207.pdf)
- [ITU-T X.690 08/2015](https://www.itu.int/rec/T-REC-X.690-201508-I/en)
- Hosted by [OSS Nokalva](http://www.oss.com/asn1/resources/books-whitepapers-pubs/asn1-books.html)
  - [ASN.1 — Communication Between Heterogeneous Systems by Olivier Dubuisson](http://www.oss.com/asn1/resources/books-whitepapers-pubs/dubuisson-asn1-book.PDF)
  - [ASN.1 Complete by Professor John Larmouth](http://www.oss.com/asn1/resources/books-whitepapers-pubs/larmouth-asn1-book.pdf)

## License

This project is licensed under the MIT License.
