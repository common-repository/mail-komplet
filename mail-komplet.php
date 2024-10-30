<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://www.webkomplet.cz/
 * @since             1.0.0
 * @package           Mail_Komplet
 *
 * @wordpress-plugin
 * Plugin Name:       Mail Komplet
 * Plugin URI:        https://cs.wordpress.org/plugins/mail-komplet/
 * Description:       This plugin will connect your WooCommerce shop to your account on Mail Komplet.
 * Version:           1.1.2
 * Author:            Webkomplet, s.r.o.
 * Author URI:        https://www.webkomplet.cz/
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       mail-komplet
 * Domain Path:       /languages/
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Check if WooCommerce is active
 **/
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
    // Put your plugin code here
    
    /**
     * Currently plugin version.
     * Start at version 1.0.0 and use SemVer - https://semver.org
     * Rename this for your plugin and update it as you release new versions.
     */
    define( 'MAILKOMPLET_PLUGIN_NAME_VERSION', '1.1.1' );
    
    /**
     * The code that runs during plugin activation.
     * This action is documented in includes/class-mail-komplet-activator.php
     */
    function activate_mail_komplet() {
        require_once plugin_dir_path( __FILE__ ) . 'includes/class-mail-komplet-activator.php';
        Mail_Komplet_Activator::activate();
    }
    
    /**
     * The code that runs during plugin deactivation.
     * This action is documented in includes/class-mail-komplet-deactivator.php
     */
    function deactivate_mail_komplet() {
        require_once plugin_dir_path( __FILE__ ) . 'includes/class-mail-komplet-deactivator.php';
        Mail_Komplet_Deactivator::deactivate();
    }
    
    register_activation_hook( __FILE__, 'activate_mail_komplet' );
    register_deactivation_hook( __FILE__, 'deactivate_mail_komplet' );
    
    /**
     * The core plugin class that is used to define internationalization,
     * admin-specific hooks, and public-facing site hooks.
     */
    require plugin_dir_path( __FILE__ ) . 'includes/class-mail-komplet.php';
    
    /**
     * Begins execution of the plugin.
     *
     * Since everything within the plugin is registered via hooks,
     * then kicking off the plugin from this point in the file does
     * not affect the page life cycle.
     *
     * @since    1.0.0
     */
    function run_mail_komplet() {
        
        $plugin = new Mail_Komplet();
        $plugin->run();
        
    }
    run_mail_komplet();
}
