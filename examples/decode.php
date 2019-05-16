<?php
/**
 * Decode DER encoding produced by `encode.php`.
 *
 * php decode.php $(php encode.php)
 */

declare(strict_types = 1);

use Sop\ASN1\Type\UnspecifiedType;

require_once dirname(__DIR__) . '/vendor/autoload.php';

$der = hex2bin($argv[1]);
$seq = UnspecifiedType::fromDER($der)->asSequence();
$greeting = $seq->at(0)->asUTF8String()->string();
$answer = $seq->at(1)->asInteger()->intNumber();
$type = $seq->at(2)->asTagged()->asExplicit()->asObjectIdentifier()->oid();
printf("%s, %d, %s\n", $greeting, $answer, $type);
