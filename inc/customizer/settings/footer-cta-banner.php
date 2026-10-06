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
use WP_Customize_Media_Control;
use WP_Customize_Color_Control;

/**
 * Adds the individual sections, settings, and controls to the theme customizer
 */
class RDTheme_Footer_CTA_Banner_Settings extends RDTheme_Customizer {

	public function __construct() {
		parent::instance();
		$this->populated_default_data();
		// Add Controls
		add_action( 'customize_register', [ $this, 'register_error_controls' ] );
	}

	public function register_error_controls( $wp_customize ) {
		$wp_customize->add_setting( 'footer_cta_banner_section',
			[
				'default'           => $this->defaults['footer_cta_banner_section'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control( new Customizer_Switch_Control( $wp_customize, 'footer_cta_banner_section',
			[
				'label'   => __( 'Show Footer CTA Banner ?', 'cldirectory' ),
				'section' => 'footer_cta_banner_section',
			]
		) );
		$wp_customize->add_setting( 'footer_cta_banner_title',
			[
				'default'           => $this->defaults['footer_cta_banner_title'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'sanitize_text_field',
			]
		);
		$wp_customize->add_control( 'footer_cta_banner_title',
			[
				'label'   => __( 'Footer CTA Banner Title', 'cldirectory' ),
				'section' => 'footer_cta_banner_section',
				'type'    => 'text',
			]
		);
		$wp_customize->add_setting( 'footer_cta_banner_text',
			[
				'default'           => $this->defaults['footer_cta_banner_text'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'sanitize_textarea_field',
			]
		);
		$wp_customize->add_control( 'footer_cta_banner_text',
			[
				'label'   => __( 'Footer CTA Banner Text', 'cldirectory' ),
				'section' => 'footer_cta_banner_section',
				'type'    => 'textarea',
			]
		);

		$wp_customize->add_setting( 'footer_cta_btn_text',
			[
				'default'           => $this->defaults['footer_cta_btn_text'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'sanitize_text_field',
			]
		);
		$wp_customize->add_control( 'footer_cta_btn_text',
			[
				'label'   => __( 'Footer CTA Button Text', 'cldirectory' ),
				'section' => 'footer_cta_banner_section',
				'type'    => 'text',
			]
		);
		
		$wp_customize->add_setting( 'footer_cta_btn_url',
			[
				'default'           => $this->defaults['footer_cta_btn_url'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'esc_url',
			]
		);
		$wp_customize->add_control( 'footer_cta_btn_url',
			[
				'label'   => __( 'Button Url', 'cldirectory' ),
				'section' => 'footer_cta_banner_section',
				'type'    => 'url',
			]
		);
	}

}

/**
 * Initialise our Customizer settings only when they're required
 */
if ( class_exists( 'WP_Customize_Control' ) ) {
	new RDTheme_Footer_CTA_Banner_Settings();
}
