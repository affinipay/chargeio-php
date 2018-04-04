<?php

require_once 'lib/ChargeIO.php';
require(dirname(__FILE__) . '/TestCase.php');

ChargeIO::setCredentials(new ChargeIO_Credentials('<public_key>', '<secret_key>'));
ChargeIO::setDebug(true);
