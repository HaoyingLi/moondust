<?php

define('ROOT_DIR', __DIR__ . '/../');
define('ENV', 'DEV');
define('SYS_UNIXTIME', time());


require '../vendor/autoload.php';

include_once '../Loader.php';

spl_autoload_register(['\Moon\Loader', 'autoload'], true, true);

\Moon\Loader::run();