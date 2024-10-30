<?php if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * Plugin Name: Membrane
 * Description: Make your site private by only allowing access to registered users.
 * Author: ampt
 * Version: 0.0.1
 * Author URI: http://notfornoone.com/
 */

if ( ! class_exists( 'Membrane' ) ) :

class Membrane {
	/**
	 * Set up hooks
	 *
	 * @return void
	 */
	public function __construct() {
	    // Allow xmlrpc to run
        if ( '/xmlrpc.php' == $_SERVER['REQUEST_URI'] ) {
            return;
        }

		add_action( 'init', array( $this, 'init' ) );
	}

	/**
	 * Check if the user is authenticated or we are loading an admin page
	 *
	 * @return void
	 */
	public function init() {
		if ( is_admin() ) {
			return;
		}

		if ( $this->is_wp_login() ) {
			return;
		}

		if ( current_user_can( 'read' ) ) {
			return;
		}

		auth_redirect();
	}

	/**
	 * Is the current page wp-login.php
	 *
	 * @return boolean
	 */
	public function is_wp_login() {
		return ( strpos( $_SERVER['REQUEST_URI'], 'wp-login.php' ) !== false );
	}
}

// Let's roll, unless we are running tests
if ( ! isset( $_SERVER['WP_ENV'] ) || 'test' != $_SERVER['WP_ENV'] ) {
    $GLOBALS['membrane'] = new Membrane();
}

endif;
