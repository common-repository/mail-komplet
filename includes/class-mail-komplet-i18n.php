<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://www.webkomplet.cz/
 * @since      1.0.0
 *
 * @package    Mail_Komplet
 * @subpackage Mail_Komplet/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Mail_Komplet
 * @subpackage Mail_Komplet/includes
 * @author     Webkomplet, s.r.o. <martin.polak@webkomplet.cz>
 */
class Mail_Komplet_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'mail-komplet',
			false,
		    dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);
	}



}
