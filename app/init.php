<?php

if (!session_id()) {
    session_start();
}

date_default_timezone_set($_ENV['TIMEZONE']);

require_once 'config/config.php';


require_once 'core/flasher.php';
require_once 'core/App.php';
require_once 'core/Controller.php';
require_once 'core/Constants.php';
require_once 'core/Database.php';
require_once 'core/Model.php';
require_once 'core/Validator.php';

