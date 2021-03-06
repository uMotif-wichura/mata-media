<?php
// This is global bootstrap for autoloading
echo sprintf("Running MATA-MEDIA tests in: %s\n\r", __DIR__);

defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'test');

defined('YII_TEST_ENTRY_URL') or define('YII_TEST_ENTRY_URL', '/index.php');
defined('YII_TEST_ENTRY_FILE') or define('YII_TEST_ENTRY_FILE', __DIR__ . '/../application/web/index.php');

defined('VENDOR_DIR') or define('VENDOR_DIR', __DIR__ . '/../../../../../vendor');

require_once(VENDOR_DIR . '/autoload.php');
require_once(VENDOR_DIR . '/yiisoft/yii2/Yii.php');
require_once(dirname(__DIR__) . '/codeception/components/User.php');
// $kernel = \AspectMock\Kernel::getInstance();

// $kernel->init([
//     'debug' => true,
//     'cacheDir' => __DIR__.'/_data/cache',
//     'includePaths' => [VENDOR_DIR . "/yiisoft/yii2"],
//     'interceptFunctions' => true
// ]);

$_SERVER['SCRIPT_FILENAME'] = YII_TEST_ENTRY_FILE;
$_SERVER['SCRIPT_NAME']     = YII_TEST_ENTRY_URL;
$_SERVER['SERVER_NAME']     = 'localhost';

Yii::setAlias('@tests', dirname(__DIR__));
Yii::setAlias('@mata/media', realpath(__DIR__ . '..'));


