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
use radiustheme\CLDirectory\Customizer\Controls\Customize_Control_Checkbox_Multiple;
use Rtcl\Helpers\Functions;
use Rtcl\Models\RtclCFGField;
/**
 * Adds the individual sections, settings, and controls to the theme customizer
 */
class RDTheme_Listings_Settings extends RDTheme_Customizer {

	public function __construct() {
		parent::instance();
		$this->populated_default_data();
		// Register Page Controls
		add_action( 'customize_register', [ $this, 'register_listings_controls' ] );
	}

	public function register_listings_controls( $wp_customize ) {
		
		$group_list = $this->custom_field_group_list();
		$fields_list=$this->custom_field_list();
		// Single Listing Layout
		$wp_customize->add_setting(
			'single_listing_style',
			[
				'default'           => $this->defaults['single_listing_style'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_radio_sanitization',
			]
		);
		$wp_customize->add_control(
			'single_listing_style',
			[
				'label'       => esc_html__( 'Listing Details Style', 'cldirectory' ),
				'section'     => 'listing_single_section',
				'description' => esc_html__( 'Select listings details page style', 'cldirectory' ),
				'type'        => 'select',
				'choices'     => [
					'1' => esc_html__( 'Slider Layout', 'cldirectory' ),
					'2' => esc_html__( 'Image Full Width Layout', 'cldirectory' ),
				],
			]
		);

		//Logo Width Height
		$wp_customize->add_setting( 'custom_fields_tab_text',
			[
				'default'           => $this->defaults['custom_fields_tab_text'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_text_sanitization',
			]
		);
		$wp_customize->add_control( 'custom_fields_tab_text',
			[
				'label'           => __( 'Custom Fields Tab Text', 'cldirectory' ),
				'section'         => 'listing_single_section',
				'type'            => 'text',
				'active_callback' => 'rttheme_is_header_btn_enabled',
				'description'     => __( 'This is listing single page go to next section tab text', 'cldirectory' ),
			]
		);

		$wp_customize->add_setting( 'custom_fields_group_types', 
		array(
			'default'           => $this->defaults['custom_fields_group_types'],
			'transport'         => 'refresh',
			'sanitize_callback' => 'sanitize_multiple_checkbox',
		) 
		);
		$wp_customize->add_control( new Customize_Control_Checkbox_Multiple( $wp_customize, 'custom_fields_group_types', 
			array(
				'label'    => __( 'Custom Group Fields', 'cldirectory' ),
				'description'    => __( 'Select custom individual groups. There come only multi checkbox type groups', 'cldirectory' ),
				'section'  => 'listing_single_section',
				'type'  => 'checkbox-multiple',
				'choices'  => $group_list,
			) 
		) );

		$wp_customize->add_setting( 'custom_fields_list_types', 
            array(
                'default'           => $this->defaults['custom_fields_list_types'],
                'transport'         => 'refresh',
                'sanitize_callback' => 'sanitize_multiple_checkbox',
            ) 
        );
        $wp_customize->add_control( new Customize_Control_Checkbox_Multiple( $wp_customize, 'custom_fields_list_types', 
            array(
                'label'    => __( 'Custom Single Fileds', 'cldirectory' ),
                'description'    => __( 'Select custom single fields. There come all fileds without multi checkbox type groups', 'cldirectory' ),
                'section'  => 'listing_single_section',
                'type'  => 'checkbox-multiple',
                'choices'  => $fields_list,
            ) 
        ) );
		
		// Show or Hide Listing sidebar
		$wp_customize->add_setting(
			'listing_detail_sidebar',
			[
				'default'           => $this->defaults['listing_detail_sidebar'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control(
			new Customizer_Switch_Control( $wp_customize, 'listing_detail_sidebar',
				[
					'label'   => esc_html__( 'Listing Sidebar Visibility', 'cldirectory' ),
					'section' => 'listing_single_section',
				]
			) 
		);


		$wp_customize->add_setting(
			'show_related_listing',
			[
				'default'           => $this->defaults['show_related_listing'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control(
			new Customizer_Switch_Control(
				$wp_customize,
				'show_related_listing',
				[
					'label'       => esc_html__( 'Show Related Listing', 'cldirectory' ),
					'description' => esc_html__( 'Show or hide related listing from listing details page', 'cldirectory' ),
					'section'     => 'listing_single_section',
				]
		) );

		$wp_customize->add_setting(
				'show_listing_button_area',
				[
					'default'           => $this->defaults['show_listing_button_area'],
					'transport'         => 'refresh',
					'sanitize_callback' => 'rttheme_switch_sanitization',
				]
		);
		$wp_customize->add_control(
			new Customizer_Switch_Control(
				$wp_customize,
				'show_listing_button_area',
				[
					'label'       => esc_html__( 'Show Button Area', 'cldirectory' ),
					'description' => esc_html__( 'Show or hide button area from single listing page header', 'cldirectory' ),
					'section'     => 'listing_single_section',
				]
		) );

		// Show Store Info on details page
		$wp_customize->add_setting(
			'show_user_info_on_details',
			[
				'default'           => $this->defaults['show_user_info_on_details'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_radio_sanitization',
			]
		);
		$wp_customize->add_control(
			'show_user_info_on_details',
			[
				'label'       => esc_html__( 'Select Owner/Store info', 'cldirectory' ),
				'section'     => 'listing_single_section',
				'description' => esc_html__( 'Show Store or Owner info on Details page', 'cldirectory' ),
				'type'        => 'select',
				'choices'     => [
					'show_listing_info' => esc_html__( 'Show Listing Info', 'cldirectory' ),
					'show_owner_info' => esc_html__( 'Show Owner Info', 'cldirectory' ),
					'show_store_info' => esc_html__( 'Show Store Info', 'cldirectory' ),
				],
			]
		);
		$wp_customize->add_setting(
			'show_owner_store_title',
			[
				'default'           => $this->defaults['show_owner_store_title'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control(
			new Customizer_Switch_Control(
				$wp_customize,
				'show_owner_store_title',
				[
					'label'       => esc_html__( 'Store/Owner Widget Title', 'cldirectory' ),
					'description' => esc_html__( 'Show or hide Store/Owner Widget Title', 'cldirectory' ),
					'section'     => 'listing_single_section',
				]
		) );
		$wp_customize->add_setting(
			'show_owner_store_whatsapp',
			[
				'default'           => $this->defaults['show_owner_store_whatsapp'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control(
			new Customizer_Switch_Control(
				$wp_customize,
				'show_owner_store_whatsapp',
				[
					'label'       => esc_html__( 'Owner Widget What\'s App', 'cldirectory' ),
					'description' => esc_html__( 'Show or hide Owner Widget What\'s App Number', 'cldirectory' ),
					'section'     => 'listing_single_section',
				]
		) );
		$wp_customize->add_setting(
			'show_owner_store_email',
			[
				'default'           => $this->defaults['show_owner_store_email'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control(
			new Customizer_Switch_Control(
				$wp_customize,
				'show_owner_store_email',
				[
					'label'       => esc_html__( 'Store/Owner Widget Email', 'cldirectory' ),
					'description' => esc_html__( 'Show or hide Store/Owner Widget Email', 'cldirectory' ),
					'section'     => 'listing_single_section',
				]
		) );
		$wp_customize->add_setting(
			'show_owner_store_website',
			[
				'default'           => $this->defaults['show_owner_store_website'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control(
			new Customizer_Switch_Control(
				$wp_customize,
				'show_owner_store_website',
				[
					'label'       => esc_html__( 'Store/Owner Widget Website', 'cldirectory' ),
					'description' => esc_html__( 'Show or hide Store/Owner Widget Website', 'cldirectory' ),
					'section'     => 'listing_single_section',
				]
		) );
		$wp_customize->add_setting(
			'show_owner_store_address',
			[
				'default'           => $this->defaults['show_owner_store_address'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control(
			new Customizer_Switch_Control(
				$wp_customize,
				'show_owner_store_address',
				[
					'label'       => esc_html__( 'Store Widget Address', 'cldirectory' ),
					'description' => esc_html__( 'Show or hide Store Widget Address', 'cldirectory' ),
					'section'     => 'listing_single_section',
				]
		) );
		$wp_customize->add_setting(
			'show_owner_store_rating',
			[
				'default'           => $this->defaults['show_owner_store_rating'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control(
			new Customizer_Switch_Control(
				$wp_customize,
				'show_owner_store_rating',
				[
					'label'       => esc_html__( 'Store Widget Rating', 'cldirectory' ),
					'description' => esc_html__( 'Show or hide Store Widget Rating', 'cldirectory' ),
					'section'     => 'listing_single_section',
				]
		) );

		//Listing owner title text
		$wp_customize->add_setting(
			'listing_owner_widget_title',
			[
				'default'           => $this->defaults['listing_owner_widget_title'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_text_sanitization',
			]
		);
		$wp_customize->add_control(
			'listing_owner_widget_title',
			[
				'label'       => esc_html__( 'Listing owner widget title', 'cldirectory' ),
				'description' => esc_html__( 'You may change Listing widget title', 'cldirectory' ),
				'section'     => 'listing_single_section',
				'type'        => 'text',
			]
		);

		// Related Listing Post
		$wp_customize->add_setting('listing_related_heading', array(
            'default' => '',
            'sanitize_callback' => 'esc_html',
        ));
        $wp_customize->add_control(new Customizer_Heading_Control($wp_customize, 'listing_related_heading', array(
            'label' => __( 'Related Listing Settings', 'cldirectory' ),
            'section' => 'listing_single_section',
        )));

		$wp_customize->add_setting(
			'show_related_listing_navigation',
			[
				'default'           => $this->defaults['show_related_listing_navigation'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control(
			new Customizer_Switch_Control(
				$wp_customize,
				'show_related_listing_navigation',
				[
					'label'       => esc_html__( 'Slider Navigation', 'cldirectory' ),
					'description' => esc_html__( 'Show or hide related listing navigation', 'cldirectory' ),
					'section'     => 'listing_single_section',
				]
		) );
		$wp_customize->add_setting(
			'slider_autoplay',
			[
				'default'           => $this->defaults['slider_autoplay'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control(
			new Customizer_Switch_Control(
				$wp_customize,
				'slider_autoplay',
				[
					'label'       => esc_html__( 'Slider Autoplay', 'cldirectory' ),
					'description' => esc_html__( 'Enable or disable related listing slider autoplay', 'cldirectory' ),
					'section'     => 'listing_single_section',
				]
		) );


		// listing archive page settings
		$wp_customize->add_setting( 'listing_grid_cols',
			array(
				'default' => $this->defaults['listing_grid_cols'],
				'transport' => 'refresh',
				'sanitize_callback' => 'rttheme_radio_sanitization'
			)
		);
		$wp_customize->add_control( 'listing_grid_cols',
			array(
				'label' => esc_html__( 'Grid Columns', 'cldirectory' ),
				'section' => 'listing_archive_section',
				'description' => esc_html__( 'Width is defined by the number of columns.', 'cldirectory' ),
				'type' => 'select',
				'choices' => array(
					'1' => esc_html__( '1 Columns', 'cldirectory' ),
					'2' => esc_html__( '2 Columns', 'cldirectory' ),
					'3' => esc_html__( '3 Columns', 'cldirectory' ),
					'4' => esc_html__( '4 Columns', 'cldirectory' ),
					'5' => esc_html__( '5 Columns', 'cldirectory' ),
				),
			)
		);

		$wp_customize->add_setting( 'show_listing_custom_fields',
			[
				'default'           => $this->defaults['show_listing_custom_fields'],
				'transport'         => 'refresh',
				'sanitize_callback' => 'rttheme_switch_sanitization',
			]
		);
		$wp_customize->add_control( new Customizer_Switch_Control( $wp_customize, 'show_listing_custom_fields',
			[
				'label'       => esc_html__( 'Show Listing Custom Field', 'cldirectory' ),
				'description' => esc_html__( 'Show or hide listing custom fields from listing archive page', 'cldirectory' ),
				'section' => 'listing_archive_section',
			]
		) );

	
		$wp_customize->add_setting( 'listing_arexcerpt_limit',
            array(
                'default' => $this->defaults['listing_arexcerpt_limit'],
                'transport' => 'refresh',
                'sanitize_callback' => 'rttheme_text_sanitization',
            )
        );
        $wp_customize->add_control( 'listing_arexcerpt_limit',
            array(
                'label' => __( 'Listing Archive Excerpt Limit', 'cldirectory' ),
                'section' => 'listing_archive_section',
                'type' => 'number',
            )
        );

		$wp_customize->add_setting('map_listing_heading', array(
            'default' => '',
            'sanitize_callback' => 'esc_html',
        ));
        $wp_customize->add_control(new Customizer_Heading_Control($wp_customize, 'map_listing_heading', array(
            'label' => __( 'Map Listing Archive Page', 'cldirectory' ),
            'section' => 'listing_archive_section',
        )));

		$wp_customize->add_setting( 'listing_map_per_page', [
			'transport' => 'refresh',
			'default'           => $this->defaults['listing_map_per_page'],
			'sanitize_callback' => 'rttheme_text_sanitization',
		] );

		$wp_customize->add_control( 'listing_map_per_page', [
			'type'    => 'number',
			'section' => 'listing_archive_section', // Add a default or your own section
			'label'   => __( 'Listing Per Page', 'cldirectory' ), 
		] );


		
	}

	public function custom_field_group_list() {
        $list = [];
        $field = [];
        $group_ids = Functions::get_cfg_ids();
        foreach ( $group_ids as $id ) {
            $atts = [
                'group_ids' => $id
            ];
            if ( $id ) {
                $field_ids   = Functions::get_cf_ids( $atts );
            }
			
            foreach ( $field_ids as $single_field ) {
                $field = new RtclCFGField( $single_field );
				if ( !empty( $field ) && $field->getType() == 'checkbox' ) {
					$list[ $id ] = get_the_title( $id );
				}
            }

            
        }
        return $list;
    }
	public function custom_field_list() {
        $list = [];
        $field = [];
        $group_ids = Functions::get_cfg_ids();
        foreach ( $group_ids as $id ) {
            $atts = [
                'group_ids' => $id
            ];
            if ( $id ) {
                $field_ids   = Functions::get_cf_ids( $atts );
            }
			
            foreach ( $field_ids as $single_field ) {
                $field = new RtclCFGField( $single_field );
				if ( !empty( $field ) && $field->getType() !== 'checkbox' ) {
					$list[ $id ] = get_the_title( $id );
				}
            }

            
        }
        return $list;
    }

}

/**
 * Initialise our Customizer settings only when they're required
 */
if ( class_exists( 'WP_Customize_Control' ) ) {
	new RDTheme_Listings_Settings();
}
