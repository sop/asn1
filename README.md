[![Build Status](https://travis-ci.org/sop/asn1.svg?branch=master)](https://travis-ci.org/sop/asn1)
[![Coverage Status](https://coveralls.io/repos/github/sop/asn1/badge.svg?branch=master)](https://coveralls.io/github/sop/asn1?branch=master)
[![License](https://poser.pugx.org/sop/asn1/license)](https://github.com/sop/asn1/blob/master/LICENSE)

# ASN.1
A PHP library for X.690 Abstract Syntax Notation One (ASN.1)
Distinguished Encoding Rules (DER) encoding and decoding.

## Installation
This library is available on
[Packagist](https://packagist.org/packages/sop/asn1).

    composer require sop/asn1

## Usage
The general idea is that each ASN.1 type has it's corresponding PHP class,
that knows the details of encoding and decoding of the specific type.

To decode DER data, use `fromDER` static method of the expected type.
To encode object to DER, use `toDER` instance method.
Exception shall be thrown on errors.


## Code examples
Here are some simple usage examples. Namespaces are omitted for brevity.

### Encode
Encode a sequence containing a UTF-8 string, an integer
and an explicitly tagged OID.

```php
$seq = new Sequence(
	new UTF8String("Hello"),
	new Integer(42),
	new ExplicitlyTaggedType(
		1, new ObjectIdentifier("1.3.6.1.3"))
);
echo bin2hex($seq->toDER());
```

Outputs:

    30120c0548656c6c6f02012aa10606042b060103

### Decode
Decode DER encoding from above.

```php
$seq = Sequence::fromDER(hex2bin($hexder));
echo $seq->at(0)->str() . ", " .
	$seq->at(1)->number() .", " .
	$seq->at(2)->explicit()->oid();
```

Outputs:

    Hello, 42, 1.3.6.1.3

## License
This project is licensed under the MIT License.
