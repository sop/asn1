<?php

namespace ASN1\Type;

use ASN1\Element;


/**
 * Base class for all types representing a point in time.
 */
abstract class TimeType extends Element
{
	/**
	 * Date and time.
	 *
	 * @var \DateTimeImmutable $_dateTime
	 */
	protected $_dateTime;
	
	/**
	 * Constructor
	 *
	 * @param \DateTimeImmutable $dt
	 */
	public function __construct(\DateTimeImmutable $dt) {
		$this->_dateTime = $dt;
	}
	
	/**
	 * Get time.
	 *
	 * @return \DateTimeImmutable
	 */
	public function dateTime() {
		return $this->_dateTime;
	}
}
