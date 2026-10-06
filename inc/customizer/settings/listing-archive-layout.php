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

use radiustheme\CLDirectory\Customizer\RDTheme_Customizer;
use radiustheme\CLDirectory\Customizer\Controls\Customizer_Image_Radio_Control;
use radiustheme\CLDirectory\Helper;

/**
 * Adds the individual sections, settings, and controls to the theme customizer
 */
class RDTheme_Listing_Archive_Layout_Settings extends RDTheme_Customizer {

	public function __construct() {
		parent::instance();
		$this->populated_default_data();
		// Register Page Controls
		add_action( 'customize_register', [ $this, 'register_listing_archive_layout_controls' ] );
	}

	public function register_listing_archive_layout_controls( $wp_customize ) {
		// Layout
		$wp_customize->add_setting( 'listing_archive_layout',
			[
				'default'           => $this->defaults['listing_archive_layout'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_radio_sanitization',
			]
		);
		$wp_customize->add_control( new Customizer_Image_Radio_Control( $wp_customize, 'listing_archive_layout',
			[
				'label'       => esc_html__( 'Layout', 'cldirectory' ),
				'description' => esc_html__( 'Select the default template layout for listing archive', 'cldirectory' ),
				'section'     => 'listing_archive_layout_section',
				'choices'     => [
					'left-sidebar'  => [
						'image' => trailingslashit( get_template_directory_uri() ) . 'assets/img/sidebar-left.png',
						'name'  => esc_html__( 'Left Sidebar', 'cldirectory' ),
					],
					'full-width'    => [
						'image' => trailingslashit( get_template_directory_uri() ) . 'assets/img/sidebar-full.png',
						'name'  => esc_html__( 'Full Width', 'cldirectory' ),
					],
					'right-sidebar' => [
						'image' => trailingslashit( get_template_directory_uri() ) . 'assets/img/sidebar-right.png',
						'name'  => esc_html__( 'Right Sidebar', 'cldirectory' ),
					],
				],
			]
		) );
		// Sidebar
		$wp_customize->add_setting( 'listing_archive_sidebar',
			[
				'default'           => $this->defaults['listing_archive_sidebar'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_text_sanitization',
			]
		);
		$wp_customize->add_control( 'listing_archive_sidebar', [
			'type'    => 'select',
			'section' => 'listing_archive_layout_section',
			'label'   => esc_html__( 'Custom Sidebar', 'cldirectory' ),
			'choices' => Helper::custom_sidebar_fields(),
		] );
		// Top bar
		$wp_customize->add_setting( 'listing_archive_top_bar',
			[
				'default'           => $this->defaults['listing_archive_top_bar'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_text_sanitization',
			]
		);
		$wp_customize->add_control( 'listing_archive_top_bar', [
			'type'    => 'select',
			'section' => 'listing_archive_layout_section',
			'label'   => esc_html__( 'Top Bar', 'cldirectory' ),
			'choices' => [
				'default' => esc_html__( 'Default', 'cldirectory' ),
				'on'      => esc_html__( 'Enable', 'cldirectory' ),
				'off'     => esc_html__( 'Disable', 'cldirectory' ),
			],
		] );
		// Header Layout
		$wp_customize->add_setting( 'listing_archive_header_style',
			[
				'default'           => $this->defaults['listing_archive_header_style'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_text_sanitization',
			]
		);
		$wp_customize->add_control( 'listing_archive_header_style', [
			'type'    => 'select',
			'section' => 'listing_archive_layout_section',
			'label'   => esc_html__( 'Header Layout', 'cldirectory' ),
			'choices' => [
				'default' => esc_html__( 'Default', 'cldirectory' ),
				'1'       => esc_html__( 'Layout 1', 'cldirectory' ),
			],
		] );



		//Header width
		$wp_customize->add_setting( 'listing_archive_header_width', [
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'rttheme_text_sanitization',
			'default'           => $this->defaults['listing_archive_header_width'],
		] );

		$wp_customize->add_control( 'listing_archive_header_width', [
			'type'    => 'select',
			'section' => 'listing_archive_layout_section', // Add a default or your own section
			'label'   => __( 'Header Width', 'cldirectory' ),
			'choices' => [
				'default'   => __( 'Default', 'cldirectory' ),
				'box-width' => __( 'Box width', 'cldirectory' ),
				'fullwidth' => __( 'Fullwidth', 'cldirectory' ),
			],
		] );

		// Transparent Header
		$wp_customize->add_setting( 'listing_archive_tr_header',
			[
				'default'           => $this->defaults['listing_archive_tr_header'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_text_sanitization',
			]
		);
		$wp_customize->add_control( 'listing_archive_tr_header', [
			'type'    => 'select',
			'section' => 'listing_archive_layout_section',
			'label'   => esc_html__( 'Transparent Header', 'cldirectory' ),
			'choices' => [
				'default' => esc_html__( 'Default', 'cldirectory' ),
				'on'      => esc_html__( 'Enable', 'cldirectory' ),
				'off'     => esc_html__( 'Disable', 'cldirectory' ),
			],
		] );
		// Breadcrumb
		$wp_customize->add_setting( 'listing_archive_breadcrumb',
			[
				'default'           => $this->defaults['listing_archive_breadcrumb'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_text_sanitization',
			]
		);
		$wp_customize->add_control( 'listing_archive_breadcrumb', [
			'type'    => 'select',
			'section' => 'listing_archive_layout_section',
			'label'   => esc_html__( 'Breadcrumb', 'cldirectory' ),
			'choices' => [
				'default' => esc_html__( 'Default', 'cldirectory' ),
				'on'      => esc_html__( 'Enable', 'cldirectory' ),
				'off'     => esc_html__( 'Disable', 'cldirectory' ),
			],
		] );
		// Padding Top
		$wp_customize->add_setting( 'listing_archive_padding_top',
			[
				'default'           => $this->defaults['listing_archive_padding_top'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_text_sanitization',
			]
		);
		$wp_customize->add_control( 'listing_archive_padding_top',
			[
				'label'       => esc_html__( 'Content Padding Top', 'cldirectory' ),
				'description' => esc_html__( 'Listing Archive Content Padding Top(Use px unit after digit)', 'cldirectory' ),
				'section'     => 'listing_archive_layout_section',
				'type'        => 'text',
			]
		);
		// Padding Bottom
		$wp_customize->add_setting( 'listing_archive_padding_bottom',
			[
				'default'           => $this->defaults['listing_archive_padding_bottom'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_text_sanitization',
			]
		);
		$wp_customize->add_control( 'listing_archive_padding_bottom',
			[
				'label'       => esc_html__( 'Content Padding Bottom', 'cldirectory' ),
				'description' => esc_html__( 'Listing Archive Content Padding Bottom(Use px unit after digit)', 'cldirectory' ),
				'section'     => 'listing_archive_layout_section',
				'type'        => 'text',
			]
		);
		// Footer Layout
		$wp_customize->add_setting( 'listing_archive_footer_style',
			[
				'default'           => $this->defaults['listing_archive_footer_style'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_text_sanitization',
			]
		);
		$wp_customize->add_control( 'listing_archive_footer_style', [
			'type'    => 'select',
			'section' => 'listing_archive_layout_section',
			'label'   => esc_html__( 'Footer Layout', 'cldirectory' ),
			'choices' => [
				'default' => esc_html__( 'Default', 'cldirectory' ),
				'1'       => esc_html__( 'Layout 1', 'cldirectory' ),
				'2'       => esc_html__( 'Layout 2', 'cldirectory' ),
			],
		] );
	}

}

/**
 * Initialise our Customizer settings only when they're required
 */
if ( class_exists( 'WP_Customize_Control' ) ) {
	new RDTheme_Listing_Archive_Layout_Settings();
}
