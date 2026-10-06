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
use Rtcl\Helpers\Functions;
use radiustheme\CLDirectory\Customizer\Controls\Customizer_Heading_Control;

use radiustheme\CLDirectory\Customizer\Controls\Customize_Control_Checkbox_Multiple;

/**
 * Adds the individual sections, settings, and controls to the theme customizer
 */
class RDTheme_Listings_Search extends RDTheme_Customizer {

	public function __construct() {
		parent::instance();
		$this->populated_default_data();
		// Register Page Controls
		add_action( 'customize_register', [ $this, 'register_listings_controls' ] );
	}

	public function register_listings_controls( $wp_customize ) {
		$group_list = $this->custom_field_group_list();

		// Map Search custom advanced fields
        $wp_customize->add_setting('map_search', array(
            'default' => '',
            'sanitize_callback' => 'esc_html',
        ));
        $wp_customize->add_control(new Customizer_Heading_Control($wp_customize, 'map_search', array(
            'label' => esc_html__( 'Widget Search Filter', 'cldirectory' ),
            'section' => 'listing_search_section',
        )));

        $wp_customize->add_setting( 'custom_fields_search_items', 
            array(
                'default'           => $this->defaults['custom_fields_search_items'],
                'transport'         => 'refresh',
                'sanitize_callback' => 'sanitize_multiple_checkbox',
            ) 
        );
        $wp_customize->add_control( new Customize_Control_Checkbox_Multiple( $wp_customize, 'custom_fields_search_items', 
            array(
                'label'    => __( 'Hide Custom Fileds', 'cldirectory' ),
                'description'    => __( 'Select which type you don\'t want to display in filter search.', 'cldirectory' ),
                'section'  => 'listing_search_section',
                'type'  => 'checkbox-multiple',
                'choices'  => $group_list,
            ) 
        ) );
        $wp_customize->add_setting( 'listing_price_search_type',
        array(
            'default' => $this->defaults['listing_price_search_type'],
            'transport' => 'refresh',
            'sanitize_callback' => 'rttheme_radio_sanitization'
        )
        );
        $wp_customize->add_control( 'listing_price_search_type',
            array(
                'label'    => esc_html__( 'Price Search Type', 'cldirectory' ),
                'section' => 'listing_search_section',
                'description'    => esc_html__( '2 price search are available, they are input box and range slider.', 'cldirectory' ),
                'type' => 'select',
                'choices'  => array(
                    'input' => 'Input Search',
                    'range' => 'Range Search',
                ),
            )
        );
        //Listing Min Price
		$wp_customize->add_setting(
			'listing_widget_min_price',
			[
				'default'           => $this->defaults['listing_widget_min_price'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_text_sanitization',
			]
		);
		$wp_customize->add_control(
			'listing_widget_min_price',
			[
				'label'       => esc_html__( 'Map Filter Widget min price ', 'cldirectory' ),
				'description' => esc_html__( 'This settings for listing map search width', 'cldirectory' ),
				'section'     => 'listing_search_section',
				'type'        => 'text',
			]
		);
		//Listing Max Price
		$wp_customize->add_setting(
			'listing_widget_max_price',
			[
				'default'           => $this->defaults['listing_widget_max_price'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_text_sanitization',
			]
		);
		$wp_customize->add_control(
			'listing_widget_max_price',
			[
				'label'       => esc_html__( 'Map Filter Widget max price ', 'cldirectory' ),
				'description' => esc_html__( 'This settings for listing map search width', 'cldirectory' ),
				'section'     => 'listing_search_section',
				'type'        => 'text',
			]
		);
		
	}

	public function custom_field_group_list() {

        $list = [];
        $group_ids = Functions::get_cfg_ids();
        foreach ( $group_ids as $id ) {
            $list[ $id ] = get_the_title( $id );
        }
        return $list;
	}

}

/**
 * Initialise our Customizer settings only when they're required
 */
if ( class_exists( 'WP_Customize_Control' ) ) {
	new RDTheme_Listings_Search();
}
