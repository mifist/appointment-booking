<?php if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
/*
Plugin Name: Bookly
Plugin URI: http://booking-wp-plugin.com
Description: Bookly Plugin – is a great easy-to-use and easy-to-manage booking tool for service providers who think about their customers. The plugin supports a wide range of services provided by business and individuals who offer reservations through websites. Set up any reservation quickly, pleasantly and easily with Bookly!
Version: 14.0
Author: Ladela Interactive
Author URI: http://booking-wp-plugin.com
Text Domain: bookly
Domain Path: /languages
License: Commercial
*/

if ( version_compare( PHP_VERSION, '5.3.7', '<' ) ) {
    add_action( is_network_admin() ? 'network_admin_notices' : 'admin_notices', create_function( '', 'echo \'<div class="updated"><h3>Bookly</h3><p>To install the plugin - <strong>PHP 5.3.7</strong> or higher is required.</p></div>\';' ) );
} else {
    include_once __DIR__ . '/autoload.php';

    // Fix possible errors (appearing if "Nextgen Gallery" Plugin is installed) when Bookly is being updated.
    add_filter( 'http_request_args', create_function( '$args', '$args[\'reject_unsafe_urls\'] = false; return $args;' ) );

    call_user_func( array( '\Bookly\Lib\Plugin', 'run' ) );
    $app = is_admin() ? '\Bookly\Backend\Backend' : '\Bookly\Frontend\Frontend';
    new $app();
}