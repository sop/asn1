<?php

require_once dirname(__DIR__) . "/vendor/autoload.php";
// set default timezone
if (empty(ini_get("date.timezone"))) {
    ini_set("date.timezone", "UTC");
}
// backwards compatibility on PHPUnit 6
if (!class_exists("PHPUnit_Framework_TestCase")) {
    class_alias("PHPUnit\Framework\TestCase", "PHPUnit_Framework_TestCase");
}
if (!class_exists("PHPUnit_Framework_Error_Warning")) {
    class_alias("PHPUnit\Framework\Error\Warning",
        "PHPUnit_Framework_Error_Warning");
}
