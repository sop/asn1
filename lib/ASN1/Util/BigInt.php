<?php

declare(strict_types = 1);

namespace Sop\ASN1\Util;

/**
 * Class to wrap an integer of arbirtary length.
 */
class BigInt
{
    /**
     * Number as a base 10 integer string.
     *
     * @var string
     */
    private $_num;

    /**
     * Number as an integer type.
     *
     * @internal Lazily initialized
     *
     * @var null|int
     */
    private $_intNum;

    /**
     * Constructor.
     *
     * @param int|string $num Integer number in base 10
     */
    public function __construct($num)
    {
        $this->_num = strval($num);
    }

    /**
     * @return string
     */
    public function __toString(): string
    {
        return $this->base10();
    }

    /**
     * Get the number as a base 10 integer string.
     *
     * @return string
     */
    public function base10(): string
    {
        return $this->_num;
    }

    /**
     * Get the number as an integer.
     *
     * @throws \RuntimeException If number overflows integer size
     *
     * @return int
     */
    public function intVal(): int
    {
        if (!isset($this->_intNum)) {
            $num = $this->gmpObj();
            if (gmp_cmp($num, $this->_intMaxGmp()) > 0) {
                throw new \RuntimeException('Integer overflow.');
            }
            if (gmp_cmp($num, $this->_intMinGmp()) < 0) {
                throw new \RuntimeException('Integer underflow.');
            }
            $this->_intNum = gmp_intval($num);
        }
        return $this->_intNum;
    }

    /**
     * Get the number as a `GMP` object.
     *
     * @throws \RuntimeException if number is not a valid integer
     *
     * @return \GMP
     */
    public function gmpObj(): \GMP
    {
        $num = @gmp_init($this->_num, 10);
        if (false === $num) {
            throw new \RuntimeException("Unable to convert {$this->_num} to integer.");
        }
        return $num;
    }

    /**
     * Get the maximum integer value.
     *
     * @return \GMP
     */
    private function _intMaxGmp(): \GMP
    {
        static $gmp;
        if (!isset($gmp)) {
            $gmp = gmp_init(PHP_INT_MAX, 10);
        }
        return $gmp;
    }

    /**
     * Get the minimum integer value.
     *
     * @return \GMP
     */
    private function _intMinGmp(): \GMP
    {
        static $gmp;
        if (!isset($gmp)) {
            $gmp = gmp_init(PHP_INT_MIN, 10);
        }
        return $gmp;
    }
}
