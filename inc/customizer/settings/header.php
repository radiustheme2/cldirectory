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
use radiustheme\CLDirectory\Customizer\Controls\Customizer_Switch_Control;
use radiustheme\CLDirectory\Customizer\Controls\Customizer_Image_Radio_Control;
use radiustheme\CLDirectory\Helper;
use WP_Customize_Color_Control;
use radiustheme\CLDirectory\Customizer\Controls\Customizer_Alfa_Color;

/**
 * Adds the individual sections, settings, and controls to the theme customizer
 */
class RDTheme_Header_Settings extends RDTheme_Customizer {

	public function __construct() {
		parent::instance();
		$this->populated_default_data();
		// Add Controls
		add_action( 'customize_register', [ $this, 'register_header_controls' ] );
	}

	public function register_header_controls( $wp_customize ) {
		// Header Style
		$wp_customize->add_setting( 'header_style',
			[
				'default'           => $this->defaults['header_style'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_radio_sanitization',
			]
		);
		$wp_customize->add_control( new Customizer_Image_Radio_Control( $wp_customize, 'header_style',
			[
				'label'       => __( 'Header Layout', 'cldirectory' ),
				'description' => esc_html__( 'Select the header style', 'cldirectory' ),
				'section'     => 'header_main_section',
				'choices'     => Helper::get_cldirectory_header_list('header'),
			]
		) );


		//Header width
		$wp_customize->add_setting( 'header_width', [
			'capability'        => 'edit_theme_options',
			'sanitize_callback' => 'rttheme_text_sanitization',
			'default'           => $this->defaults['header_width'],
		] );

		$wp_customize->add_control( 'header_width', [
			'type'    => 'select',
			'section' => 'header_main_section', // Add a default or your own section
			'label'   => __( 'Header Width', 'cldirectory' ),
			'choices' => [
				'box-width' => __( 'Box width', 'cldirectory' ),
				'fullwidth' => __( 'Fullwidth', 'cldirectory' ),
			],
		] );

		// Top bar
		$wp_customize->add_setting( 'top_bar',
			[
				'default'           => $this->defaults['top_bar'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control( new Customizer_Switch_Control( $wp_customize, 'top_bar',
			[
				'label'   => __( 'Top Bar', 'cldirectory' ),
				'section' => 'header_main_section',
			]
		) );

		// Sticky Header Control
		$wp_customize->add_setting( 'sticky_header',
			[
				'default'           => $this->defaults['sticky_header'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control( new Customizer_Switch_Control( $wp_customize, 'sticky_header',
			[
				'label'       => __( 'Sticky Header', 'cldirectory' ),
				'description' => __( 'Show header at the top when scrolling down', 'cldirectory' ),
				'section'     => 'header_main_section',
			]
		) );

		// Transparent Header
		$wp_customize->add_setting( 'tr_header',
			[
				'default'           => $this->defaults['tr_header'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control( new Customizer_Switch_Control( $wp_customize, 'tr_header',
			[
				'label'       => __( 'Transparent Header', 'cldirectory' ),
				'description' => __( 'You have to enable Banner or Slider in page to make it work properly', 'cldirectory' ),
				'section'     => 'header_main_section',
			]
		) );


		// Button Control
		$wp_customize->add_setting( 'header_btn',
			[
				'default'           => $this->defaults['header_btn'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control( new Customizer_Switch_Control( $wp_customize, 'header_btn',
			[
				'label'   => __( 'Header Right Button', 'cldirectory' ),
				'section' => 'header_main_section',
			]
		) );

		// Button Text
		$wp_customize->add_setting( 'header_btn_txt',
			[
				'default'           => $this->defaults['header_btn_txt'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_text_sanitization',
			]
		);
		$wp_customize->add_control( 'header_btn_txt',
			[
				'label'           => __( 'Button Text', 'cldirectory' ),
				'section'         => 'header_main_section',
				'type'            => 'text',
				'active_callback' => 'rttheme_is_header_btn_enabled',
			]
		);
		// Button URL
		$wp_customize->add_setting( 'header_btn_url',
			[
				'default'           => $this->defaults['header_btn_url'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_url_sanitization',
			]
		);
		$wp_customize->add_control( 'header_btn_url',
			[
				'label'           => __( 'Button Link', 'cldirectory' ),
				'section'         => 'header_main_section',
				'type'            => 'url',
				'active_callback' => 'rttheme_is_header_btn_enabled',
			]
		);


		// Header Login Icon Visibility
		$wp_customize->add_setting( 'header_login_icon',
			[
				'default'           => $this->defaults['header_login_icon'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control( new Customizer_Switch_Control( $wp_customize, 'header_login_icon',
			[
				'label'   => __( 'Header Login Icon Visibility', 'cldirectory' ),
				'section' => 'header_main_section',
			]
		) );



		// Header Fav Icon
		$wp_customize->add_setting( 'header_fav_icon',
			[
				'default'           => $this->defaults['header_fav_icon'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control( new Customizer_Switch_Control( $wp_customize, 'header_fav_icon',
			[
				'label'   => __( 'Header Favourite Icon Visibility', 'cldirectory' ),
				'section' => 'header_main_section',
			]
		) );


		// Header Compare Icon
		$wp_customize->add_setting( 'header_compare_icon',
			[
				'default'           => $this->defaults['header_compare_icon'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control( new Customizer_Switch_Control( $wp_customize, 'header_compare_icon',
			[
				'label'   => __( 'Header Compare Icon Visibility', 'cldirectory' ),
				'section' => 'header_main_section',
			]
		) );

		// Header Cart Icon
		$wp_customize->add_setting( 'header_search_icon',
			[
				'default'           => $this->defaults['header_search_icon'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control( new Customizer_Switch_Control( $wp_customize, 'header_search_icon',
			[
				'label'   => __( 'Header Search  Form', 'cldirectory' ),
				'section' => 'header_main_section',
			]
		) );

		// Mobile Header Topbar
		$wp_customize->add_setting( 'header_mobile_topbar',
			[
				'default'           => $this->defaults['header_mobile_topbar'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control( new Customizer_Switch_Control( $wp_customize, 'header_mobile_topbar',
			[
				'label'   => __( 'Mobile Header Topbar', 'cldirectory' ),
				'section' => 'header_main_section',
			]
		) );

	}

}

/**
 * Initialise our Customizer settings only when they're required
 */
if ( class_exists( 'WP_Customize_Control' ) ) {
	new RDTheme_Header_Settings();
}
