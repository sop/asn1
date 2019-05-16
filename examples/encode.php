<?php
/**
 * Encode a sequence containing a UTF-8 string, an integer
 * and an explicitly tagged object identifier, conforming to the following
 * ASN.1 specification.
 *
 *      Example ::= SEQUENCE {
 *          greeting    UTF8String,
 *          answer      INTEGER,
 *          type    [1] EXPLICIT OBJECT IDENTIFIER
 *      }
 *
 * php encode.php
 */

declare(strict_types = 1);

use Sop\ASN1\Type\Constructed\Sequence;
use Sop\ASN1\Type\Primitive\Integer;
use Sop\ASN1\Type\Primitive\ObjectIdentifier;
use Sop\ASN1\Type\Primitive\UTF8String;
use Sop\ASN1\Type\Tagged\ExplicitlyTaggedType;

require_once dirname(__DIR__) . '/vendor/autoload.php';

$seq = new Sequence(
    new UTF8String('Hello'),
    new Integer(42),
    new ExplicitlyTaggedType(
        1, new ObjectIdentifier('1.3.6.1.3'))
);
$der = $seq->toDER();
printf("%s\n", bin2hex($der));
