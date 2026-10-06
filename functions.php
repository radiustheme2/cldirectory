<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */


if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( ! isset( $content_width ) ) {
	$content_width = 1240;
}

define( 'CLDIRECTORY_BASE_URL',    get_template_directory_uri(). '/' );
define( 'CLDIRECTORY_ASSETS_URL',  CLDIRECTORY_BASE_URL . 'assets/' );
define( 'CLDIRECTORY_CSS_URL',     CLDIRECTORY_ASSETS_URL . 'css/' );
define( 'CLDIRECTORY_JS_URL',      CLDIRECTORY_ASSETS_URL . 'js/' );

class ClDirectory_Main {

	public $theme = 'cldirectory';
	public $action = 'cldirectory_theme_init';

	public function __construct() {
		add_action( 'init', [ $this, 'load_textdomain' ] );
		$this->includes();	
	}

	public function load_textdomain() {
		load_theme_textdomain( $this->theme, get_template_directory() . '/languages' );
	}

	public function includes() {
		require_once get_template_directory() . '/inc/constants.php';
		require_once get_template_directory() . '/inc/helper.php';
		require_once get_template_directory() . '/inc/includes.php';

		do_action( $this->action );
	}
}

new ClDirectory_Main;



