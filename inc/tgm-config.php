<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 3.2.0
 */

namespace radiustheme\CLDirectory;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class TGM_Config {

	public $base;
	public $path;

	public function __construct() {
		$this->base = 'cldirectory';
		$this->path = Constants::$theme_plugins_dir;

		add_action( 'tgmpa_register', [ $this, 'register_required_plugins' ] );
	}

	public function register_required_plugins() {
		$plugins = [
			// Repository
			[
				'name'     => 'Elementor Website Builder',
				'slug'     => 'elementor',
				'required' => true,
			],
			[
				'name'     => 'Classified Listing – Classified ads & Business Directory Plugin',
				'slug'     => 'classified-listing',
				'required' => true,
			],
			[
				'name'     => 'Classified Listing Toolkits',
				'slug'     => 'classified-listing-toolkits',
				'required' => true,
			],
			[
				'name'     => 'Review Schema',
				'slug'     => 'review-schema',
				'required' => false,
			],
			[
				'name'     => 'WP Fluent Forms',
				'slug'     => 'fluentform',
				'required' => false,
			],
			[
				'name'     => 'Radius Booking',
				'slug'     => 'radius-booking',
				'required' => false,
			],
			[
				'name'      => 'Easy Demo Importer',
				'slug'      => 'easy-demo-importer',
				'required'  => false,
			],

			// Bundled
			[
				'name'     => 'CLDirectory Core',
				'slug'     => 'cldirectory-core',
				'source'   => 'cldirectory-core.3.2.0.zip',
				'required' => true,
				'version'  => '3.2.0',
			],
			[
				'name'     => 'RT Framework',
				'slug'     => 'rt-framework',
				'source'   => 'rt-framework.zip',
				'required' => true,
				'version'  => '2.9',
			],
			[
				'name'     => 'Classified Listing Pro',
				'slug'     => 'classified-listing-pro',
				'source'   => 'classified-listing-pro.4.2.5.zip',
				'required' => true,
				'version'  => '4.2.5',
			],
			[
				'name'     => 'Classified Listing Store',
				'slug'     => 'classified-listing-store',
				'source'   => 'classified-listing-store.3.2.1.zip',
				'required' => true,
				'version'  => '3.2.1',
			],
			[
				'name'     => 'Review Schema Pro',
				'slug'     => 'review-schema-pro',
				'source'   => 'review-schema-pro.2.0.1.zip',
				'required' => false,
				'version'  => '2.0.1',
			],
		];

		$config = [
			'id'           => $this->base,            // Unique ID for hashing notices for multiple instances of TGMPA.
			'default_path' => $this->path,              // Default absolute path to bundled plugins.
			'menu'         => $this->base . '-install-plugins', // Menu slug.
			'has_notices'  => true,                    // Show admin notices or not.
			'dismissable'  => true,                    // If false, a user cannot dismiss the nag message.
			'dismiss_msg'  => '',                      // If 'dismissable' is false, this message will be output at top of nag.
			'is_automatic' => false,                    // Automatically activate plugins after installation or not.
			'message'      => '',                      // Message to output right before the plugins table.
		];

		tgmpa( $plugins, $config );
	}

}

new TGM_Config;