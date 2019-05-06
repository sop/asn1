<?php

require_once dirname(__DIR__) . '/vendor/autoload.php';
// set default timezone
if (empty(ini_get('date.timezone'))) {
    ini_set('date.timezone', 'UTC');
}
