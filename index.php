<?php
/**
 * @package Z Multi-Languages
 */
/*
Plugin Name: Z Multi-Languages
Description: With one click, translate any page, post or custom type post to unlimited languages/locales.
Version: 0.1.1
Requires at least: 5.0
Requires PHP: 5.6.20
Author: Hatem Azzouzi
Author URI: https://www.elasticweb.link
License: GPLv2 or later
Text Domain: zmultilang
*/

/* @var $injector zmultilang\src\classes\resources\Injector */
global $injector;
$injector = require __DIR__ . '/conf/bootstrap.php';

/* @var $zmultilang zmultilang\src\classes\ZMultiLang */
$zmultilang = $injector->resource(\zmultilang\src\classes\ZMultiLang::class);

