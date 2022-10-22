<?php
/*
Plugin Name : Edje API (Test)
Description : Collection of API for Testing.
Plugin URI  : https://github.com/hrsetyono/edje-api-test
Author      : Pixel Studio
Author URI  : https://pixelstudio.id
Version     : 1.0.0

Requires PHP      : 7.3
Tested up to      : 5.9
Requires at least : 5.7
*/

if (!defined('WPINC')) { die; } // Abort if accessed directly
define('H_API_VERSION', '1.0.0');
define('H_API_ASSETS', plugins_url('', __FILE__) . '/assets');

require_once __DIR__ . '/includes/api-register.php';