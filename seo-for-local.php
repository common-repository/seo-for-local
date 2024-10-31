<?php
/**
 * Plugin Name:     Local SEO For Divi & Gutenberg Blocks
 * Plugin URI:      https://wptools.app/wordpress-plugin/local-seo-for-divi-gutenberg-blocks/
 * Description:     Grow your business & let us take care of the technical "Local SEO" stuff
 * Author:          wpt00ls
 * Text Domain:     seo-for-local
 * Domain Path:     /languages
 * Version:         9.4.0
 *
  *
 * @package         Local_Seo_With_Store_Locator
 */

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/freemius.php';

$loader = \WPT\MLSL\Loader::getInstance();

$loader['plugin_name']    = 'Local SEO For Divi & Gutenberg Blocks';
$loader['plugin_version'] = '9.4.0';
$loader['plugin_dir']     = __DIR__;
$loader['plugin_slug']    = basename( __DIR__ );
$loader['plugin_url']     = plugins_url( '/' . $loader['plugin_slug'] );
$loader['plugin_file']    = __FILE__;

$loader->run();
