<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 2.0.0
 */

namespace radiustheme\CLDirectory;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


if ( ! defined( 'RT_DEBUG' ) || ! constant( 'RT_DEBUG' ) ) {
	Helper::requires( 'updater/theme-updater.php' );
	Helper::requires( 'updater/lc-utility.php' );
	Helper::requires( 'updater/lc-helper.php' );
}
//Helper::requires( 'updater/theme-updater.php' );
Helper::requires( 'class-tgm-plugin-activation.php' );
Helper::requires( 'tgm-config.php' );
Helper::requires( 'general.php' );
Helper::requires( 'scripts.php' );
Helper::requires( 'layout-settings.php' );

// WooCommerce
if ( class_exists( 'WooCommerce' ) ) {
	Helper::requires( 'woo-functions.php' );
	Helper::requires( 'woo-hooks.php' );
}

if ( class_exists( 'Rtcl' ) ) {
	Helper::requires( 'custom/functions.php', 'classified-listing' );

	/* = Old listing data migration to form builder = */
	Helper::requires( 'data-migration.php' );

	/* = Form builder single layout shortcodes = */
	Helper::requires( 'shortcode.php' );
}

// Add Customizer
Helper::requires( 'customizer/customizer-default-data.php' );
Helper::requires( 'customizer/init.php' );
Helper::requires( 'rdtheme.php' );

