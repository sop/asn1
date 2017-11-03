<?php

declare(strict_types = 1);

namespace ASN1\Type;

use ASN1\Element;

/**
 * Base class for all string types.
 */
abstract class StringType extends Element
{
    /**
     * String value.
     *
     * @var string $_string
     */
    protected $_string;
    
    /**
     * Constructor.
     *
     * @param string $string
     * @throws \InvalidArgumentException
     */
    public function __construct(string $string)
    {
        if (!$this->_validateString($string)) {
            throw new \InvalidArgumentException(
                sprintf("Not a valid %s string.",
                    self::tagToName($this->_typeTag)));
        }
        $this->_string = $string;
    }
    
    /**
     * Get the string value.
     *
     * @return string
     */
    public function string(): string
    {
        return $this->_string;
    }
    
    /**
     * Check whether string is valid for the concrete type.
     *
     * @param string $string
     * @return bool
     */
    protected function _validateString(string $string): bool
    {
        // Override in derived classes
        return true;
    }
}
