<?php
// Ensure library/ is on include_path
// directory setup and class loading
set_include_path('.' . PATH_SEPARATOR . '../library/'
. PATH_SEPARATOR . '../library/Pas/'
. PATH_SEPARATOR . '../library/Luracast/'
. PATH_SEPARATOR . '../app/models'
. PATH_SEPARATOR . '../app/forms/'
. PATH_SEPARATOR . get_include_path());

/* include 'Zend/Loader.php';
Zend_Loader::registerAutoload();
 */

include '/home/beowulf2/library/Zend/Loader/Autoloader.php';
$autoloader = Zend_Loader_Autoloader::getInstance();
$autoloader->setDefaultAutoloader(
create_function('$class',"include str_replace('_', '/', \$class) . '.php';")
);
$autoloader->registerNamespace('Pas_');
$autoloader->registerNamespace('ZendX_');
$autoloader->suppressNotFoundWarnings(false);
$autoloader->setFallbackAutoloader(true);
require_once '/home/beowulf2/library/restler/vendor/restler.php';
require_once '/home/beowulf2/library/Pas/Api/Say.php';
require_once '/home/beowulf2/library/Pas/Api/GetObjects.php';
use Luracast\Restler\Restler;

$r = new Restler();
$r->setAPIVersion(1);
$r->setSupportedFormats('JsonFormat', 'XmlFormat');
$r->addAPIClass('Pas_Api_Say');
$r->addAPIClass('Bmi');
$r->addAPIClass('Pas_Api_GetObjects');
var_dump($r);

$r->handle(); //serve the response
