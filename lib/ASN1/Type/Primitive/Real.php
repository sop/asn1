<?php
declare(strict_types = 1);

namespace ASN1\Type\Primitive;

use ASN1\Element;
use ASN1\Component\Identifier;
use ASN1\Component\Length;
use ASN1\Exception\DecodeException;
use ASN1\Feature\ElementBase;
use ASN1\Type\PrimitiveType;
use ASN1\Type\UniversalClass;

/**
 * Implements <i>REAL</i> type.
 */
class Real extends Element
{
    use UniversalClass;
    use PrimitiveType;
    
    /**
     * Regex pattern to parse NR3 form number conforming to DER.
     *
     * @var string
     */
    const NR3_REGEX = '/^(-?)(\d+)?\.E([+\-]?\d+)$/';
    
    /**
     * Regex pattern to parse PHP exponent number format.
     *
     * @link http://php.net/manual/en/language.types.float.php
     * @var string
     */
    const PHP_EXPONENT_DNUM = '/^'. /* @formatter:off */
        '([+\-]?'. // sign
        '(?:'.
            '\d+'. // LNUM
            '|'.
            '(?:\d*\.\d+|\d+\.\d*)'. // DNUM
        '))[eE]'.
        '([+\-]?\d+)'. // exponent
    '$/'; /* @formatter:on */
    
    /**
     * Number zero represented in NR3 form.
     *
     * @var string
     */
    const NR3_ZERO = ".E+0";
    
    /**
     * Number in NR3 form.
     *
     * @var string
     */
    private $_number;
    
    /**
     * Constructor.
     *
     * @param string $number Number in NR3 form.
     */
    public function __construct(string $number)
    {
        $this->_typeTag = self::TYPE_REAL;
        if (!self::_validateNumber($number)) {
            throw new \InvalidArgumentException(
                "'$number' is not a valid NR3 form real.");
        }
        $this->_number = $number;
    }
    
    /**
     * Initialize from float.
     *
     * @param float $number
     * @return self
     */
    public static function fromFloat(float $number): self
    {
        return new self(self::_decimalToNR3(strval($number)));
    }
    
    /**
     * Get number as a float.
     *
     * @return float
     */
    public function float(): float
    {
        return self::_nr3ToDecimal($this->_number);
    }
    
    /**
     *
     * {@inheritdoc}
     */
    protected function _encodedContentDER(): string
    {
        /* if the real value is the value zero, there shall be no contents
         octets in the encoding. (X.690 07-2002, section 8.5.2) */
        if (self::NR3_ZERO == $this->_number) {
            return "";
        }
        // encode in NR3 decimal encoding
        $data = chr(0x03) . $this->_number;
        return $data;
    }
    
    /**
     *
     * {@inheritdoc}
     * @return self
     */
    protected static function _decodeFromDER(Identifier $identifier,
        string $data, int &$offset): ElementBase
    {
        $idx = $offset;
        $length = Length::expectFromDER($data, $idx)->intLength();
        // if length is zero, value is zero (spec 8.5.2)
        if (!$length) {
            $obj = new self(self::NR3_ZERO);
        } else {
            $bytes = substr($data, $idx, $length);
            $byte = ord($bytes[0]);
            if (0x80 & $byte) { // bit 8 = 1
                $obj = self::_decodeBinaryEncoding($bytes);
            } else if ($byte >> 6 == 0x00) { // bit 8 = 0, bit 7 = 0
                $obj = self::_decodeDecimalEncoding($bytes);
            } else { // bit 8 = 0, bit 7 = 1
                $obj = self::_decodeSpecialRealValue($bytes);
            }
        }
        $offset = $idx + $length;
        return $obj;
    }
    
    /**
     *
     * @todo Implement
     * @param string $data
     */
    protected static function _decodeBinaryEncoding(string $data)
    {
        throw new \RuntimeException(
            "Binary encoding of REAL is not implemented.");
    }
    
    /**
     *
     * @param string $data
     * @throws \RuntimeException
     * @return \ASN1\Type\Primitive\Real
     */
    protected static function _decodeDecimalEncoding(string $data): Real
    {
        $nr = ord($data[0]) & 0x03;
        if ($nr != 0x03) {
            throw new \RuntimeException("Only NR3 form supported.");
        }
        $str = substr($data, 1);
        return new self($str);
    }
    
    /**
     *
     * @todo Implement
     * @param string $data
     */
    protected static function _decodeSpecialRealValue(string $data)
    {
        if (strlen($data) != 1) {
            throw new DecodeException(
                "SpecialRealValue must have one content octet.");
        }
        $byte = ord($data[0]);
        if ($byte == 0x40) { // positive infinity
            throw new \RuntimeException("PLUS-INFINITY not supported.");
        } else if ($byte == 0x41) { // negative infinity
            throw new \RuntimeException("MINUS-INFINITY not supported.");
        } else {
            throw new DecodeException("Invalid SpecialRealValue encoding.");
        }
    }
    
    /**
     * Convert decimal number string to NR3 form.
     *
     * @param string $str
     * @return string
     */
    private static function _decimalToNR3(string $str): string
    {
        // if number is in exponent form
        /** @var $match string[] */
        if (preg_match(self::PHP_EXPONENT_DNUM, $str, $match)) {
            $parts = explode(".", $match[1]);
            $m = ltrim($parts[0], "0");
            $e = intval($match[2]);
            // if mantissa had decimals
            if (count($parts) == 2) {
                $d = rtrim($parts[1], "0");
                $e -= strlen($d);
                $m .= $d;
            }
        } else {
            // explode from decimal
            $parts = explode(".", $str);
            $m = ltrim($parts[0], "0");
            // if number had decimals
            if (count($parts) == 2) {
                // exponent is negative number of the decimals
                $e = -strlen($parts[1]);
                // append decimals to the mantissa
                $m .= $parts[1];
            } else {
                $e = 0;
            }
            // shift trailing zeroes from the mantissa to the exponent
            while (substr($m, -1) === "0") {
                $e++;
                $m = substr($m, 0, -1);
            }
        }
        /* if exponent is zero, it must be prefixed with a "+" sign
         (X.690 07-2002, section 11.3.2.6) */
        if (0 == $e) {
            $es = "+";
        } else {
            $es = $e < 0 ? "-" : "";
        }
        return sprintf("%s.E%s%d", $m, $es, abs($e));
    }
    
    /**
     * Convert NR3 form number to decimal.
     *
     * @param string $str
     * @throws \UnexpectedValueException
     * @return float
     */
    private static function _nr3ToDecimal(string $str): float
    {
        /** @var $match string[] */
        if (!preg_match(self::NR3_REGEX, $str, $match)) {
            throw new \UnexpectedValueException(
                "'$str' is not a valid NR3 form real.");
        }
        $m = $match[2];
        // if number started with minus sign
        $inv = $match[1] == "-";
        $e = intval($match[3]);
        // positive exponent
        if ($e > 0) {
            // pad with trailing zeroes
            $num = $m . str_repeat("0", $e);
        } else if ($e < 0) {
            // pad with leading zeroes
            if (strlen($m) < abs($e)) {
                $m = str_repeat("0", abs($e) - strlen($m)) . $m;
            }
            // insert decimal point
            $num = substr($m, 0, $e) . "." . substr($m, $e);
        } else {
            $num = empty($m) ? "0" : $m;
        }
        // if number is negative
        if ($inv) {
            $num = "-$num";
        }
        return floatval($num);
    }
    
    /**
     * Test that number is valid for this context.
     *
     * @param mixed $num
     * @return bool
     */
    private static function _validateNumber($num): bool
    {
        if (!preg_match(self::NR3_REGEX, $num)) {
            return false;
        }
        return true;
    }
}
