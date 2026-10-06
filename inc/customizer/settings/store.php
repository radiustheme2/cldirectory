<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\CLDirectory\Customizer\Settings;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use radiustheme\CLDirectory\Customizer\Controls\Customizer_Switch_Control;
use radiustheme\CLDirectory\Customizer\RDTheme_Customizer;
use radiustheme\CLDirectory\Customizer\Controls\Customizer_Heading_Control;

/**
 * Adds the individual sections, settings, and controls to the theme customizer
 */
class RDTheme_Store_Settings extends RDTheme_Customizer {

	public function __construct() {
		parent::instance();
		$this->populated_default_data();
		// Register Page Controls
		add_action( 'customize_register', [ $this, 'register_listings_controls' ] );
	}

	public function register_listings_controls( $wp_customize ) {
		// Show or Hide Listing sidebar
		$wp_customize->add_setting(
			'show_ad_count',
			[
				'default'           => $this->defaults['show_ad_count'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control(
			new Customizer_Switch_Control( $wp_customize, 'show_ad_count',
				[
					'label'   => esc_html__( 'Show Ad Count', 'cldirectory' ),
					'section' => 'store_archive_section',
				]
			) 
		);
        $wp_customize->add_setting(
			'show_store_views',
			[
				'default'           => $this->defaults['show_store_views'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control(
			new Customizer_Switch_Control( $wp_customize, 'show_store_views',
				[
					'label'   => esc_html__( 'Show Store Views', 'cldirectory' ),
					'section' => 'store_archive_section',
				]
			) 
		);
        $wp_customize->add_setting(
			'show_store_ratings',
			[
				'default'           => $this->defaults['show_store_ratings'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control(
			new Customizer_Switch_Control( $wp_customize, 'show_store_ratings',
				[
					'label'   => esc_html__( 'Show Store Ratings', 'cldirectory' ),
					'section' => 'store_archive_section',
				]
			) 
		);
        $wp_customize->add_setting(
			'show_store_location',
			[
				'default'           => $this->defaults['show_store_location'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control(
			new Customizer_Switch_Control( $wp_customize, 'show_store_location',
				[
					'label'   => esc_html__( 'Show Store Location', 'cldirectory' ),
					'section' => 'store_archive_section',
				]
			) 
		);
        $wp_customize->add_setting(
			'show_store_excerpt',
			[
				'default'           => $this->defaults['show_store_excerpt'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control(
			new Customizer_Switch_Control( $wp_customize, 'show_store_excerpt',
				[
					'label'   => esc_html__( 'Show Store Excerpt', 'cldirectory' ),
					'section' => 'store_archive_section',
				]
			) 
		);
        $wp_customize->add_setting(
			'show_store_phone',
			[
				'default'           => $this->defaults['show_store_phone'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control(
			new Customizer_Switch_Control( $wp_customize, 'show_store_phone',
				[
					'label'   => esc_html__( 'Show Store Phone', 'cldirectory' ),
					'section' => 'store_archive_section',
				]
			) 
		);
        $wp_customize->add_setting(
			'show_store_mail',
			[
				'default'           => $this->defaults['show_store_mail'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control(
			new Customizer_Switch_Control( $wp_customize, 'show_store_mail',
				[
					'label'   => esc_html__( 'Show Store Email', 'cldirectory' ),
					'section' => 'store_archive_section',
				]
			) 
		);
        $wp_customize->add_setting(
			'show_store_webaddress',
			[
				'default'           => $this->defaults['show_store_webaddress'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control(
			new Customizer_Switch_Control( $wp_customize, 'show_store_webaddress',
				[
					'label'   => esc_html__( 'Show Store Website Link', 'cldirectory' ),
					'section' => 'store_archive_section',
				]
			) 
		);

	}


}

/**
 * Initialise our Customizer settings only when they're required
 */
if ( class_exists( 'WP_Customize_Control' ) ) {
	new RDTheme_Store_Settings();
}
