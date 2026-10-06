<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\CLDirectory\Customizer;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Adds the individual sections, settings, and controls to the theme customizer
 */
class RDTheme_Customizer {

	// Get our default values
	protected $defaults;
	protected static $instance = null;

	public function __construct() {
		// Register Panels
		add_action( 'customize_register', [ $this, 'add_customizer_panels' ] );
		// Register sections
		add_action( 'customize_register', [ $this, 'add_customizer_sections' ] );
	}

	public static function instance() {
		if ( null == self::$instance ) {
			self::$instance = new self();
			//self::populated_default_data();
		}

		return self::$instance;
	}

	public function populated_default_data() {
		$this->defaults = rttheme_generate_defaults();
	}

	/**
	 * Customizer Panels
	 */
	public function add_customizer_panels( $wp_customize ) {
		// Layout Panel
		$wp_customize->add_panel( 'rttheme_layouts_defaults',
			[
				'title'       => esc_html__( 'Layout Settings', 'cldirectory' ),
				'description' => esc_html__( 'Adjust the overall layout for your site.', 'cldirectory' ),
				'priority'    => 17,
			]
		);
		// Color Panel
		$wp_customize->add_panel( 'rttheme_color_panel',
			[
				'title'       => esc_html__( 'Color', 'cldirectory' ),
				'description' => esc_html__( 'Change site color', 'cldirectory' ),
				'priority'    => 15,
			]
		);
		// Add Listing Settings Section
		$wp_customize->add_panel( 'listings_panel',
			[
				'title'    => esc_html__( 'Listing Settings', 'cldirectory' ),
				'priority' => 15,
			]
		);
	}

	/**
	 * Customizer sections
	 */
	public function add_customizer_sections( $wp_customize ) {
		// Rename the default Colors section
		$wp_customize->get_section( 'colors' )->title = 'Background';
		// Move the default Colors section to our new Colors Panel
		$wp_customize->get_section( 'colors' )->panel = 'colors_panel';
		// Change the Priority of the default Colors section so it's at the top of our Panel
		$wp_customize->get_section( 'colors' )->priority = 10;
		// Add General Section
		$wp_customize->add_section( 'general_section',
			[
				'title'    => esc_html__( 'General', 'cldirectory' ),
				'priority' => 10,
			]
		);
		// Add Header Main Section
		$wp_customize->add_section( 'header_main_section',
			[
				'title'    => esc_html__( 'Header', 'cldirectory' ),
				'priority' => 11,
			]
		);
		// Add Header Main Section
		$wp_customize->add_section( 'breadcrumb_section',
			[
				'title'    => esc_html__( 'Breadcrumb', 'cldirectory' ),
				'priority' => 13,
			]
		);
		// Add Footer Section
		$wp_customize->add_section( 'footer_section',
			[
				'title'    => esc_html__( 'Footer', 'cldirectory' ),
				'priority' => 12,
			]
		);
		// Add Color Section
		$wp_customize->add_section( 'site_color_section',
			[
				'title'    => esc_html__( 'Site Color', 'cldirectory' ),
				'panel'    => 'rttheme_color_panel',
				'priority' => 10,
			]
		);
		$wp_customize->add_section( 'header_color_section',
			[
				'title'    => esc_html__( 'Header Color', 'cldirectory' ),
				'panel'    => 'rttheme_color_panel',
				'priority' => 12,
			]
		);
		$wp_customize->add_section( 'breadcrumb_color_section',
			[
				'title'    => esc_html__( 'Breadcrumb Color', 'cldirectory' ),
				'panel'    => 'rttheme_color_panel',
				'priority' => 13,
			]
		);
		$wp_customize->add_section( 'footer_color_section',
			[
				'title'    => esc_html__( 'Footer Color', 'cldirectory' ),
				'panel'    => 'rttheme_color_panel',
				'priority' => 14,
			]
		);
		// Add Blog Layout Section
		$wp_customize->add_section( 'blog_layout_section',
			[
				'title'    => esc_html__( 'Blog Layout', 'cldirectory' ),
				'priority' => 10,
				'panel'    => 'rttheme_layouts_defaults',
			]
		);
		// Add Single Post Layout Section
		$wp_customize->add_section( 'single_post_layout_section',
			[
				'title'    => esc_html__( 'Single Post Layout', 'cldirectory' ),
				'priority' => 10,
				'panel'    => 'rttheme_layouts_defaults',
			]
		);
		// Add Pages Layout Section
		$wp_customize->add_section( 'page_layout_section',
			[
				'title'    => esc_html__( 'Pages Layout', 'cldirectory' ),
				'priority' => 15,
				'panel'    => 'rttheme_layouts_defaults',
			]
		);
		// Add Error Layout Section
		$wp_customize->add_section( 'error_layout_section',
			[
				'title'    => esc_html__( 'Error Layout', 'cldirectory' ),
				'priority' => 15,
				'panel'    => 'rttheme_layouts_defaults',
			]
		);
		// Add Listing Layout Section
		$wp_customize->add_section( 'listing_archive_layout_section',
			[
				'title'    => esc_html__( 'Listing Archive Layout', 'cldirectory' ),
				'priority' => 20,
				'panel'    => 'rttheme_layouts_defaults',
			]
		);
		// Add Listing Single Layout Section
		$wp_customize->add_section( 'listing_single_layout_section',
			[
				'title'    => esc_html__( 'Listing Single Layout', 'cldirectory' ),
				'priority' => 21,
				'panel'    => 'rttheme_layouts_defaults',
			]
		);
		// Add Author Page Layout Section
		$wp_customize->add_section( 'author_page_layout_section',
			[
				'title'    => esc_html__( 'Author Page Layout', 'cldirectory' ),
				'priority' => 22,
				'panel'    => 'rttheme_layouts_defaults',
			]
		);
		// Add Listing Layout Section

		// Add Listing Single Layout Section
		$wp_customize->add_section( 'store_single_layout_section',
			[
				'title'    => esc_html__( 'Store Single Layout', 'cldirectory' ),
				'priority' => 31,
				'panel'    => 'rttheme_layouts_defaults',
			]
		);
		// Add Store Layout Section
		$wp_customize->add_section( 'store_archive_layout_section',
			[
				'title'    => esc_html__( 'Store Archive Layout', 'cldirectory' ),
				'priority' => 30,
				'panel'    => 'rttheme_layouts_defaults',
			]
		);
		
		// Add Blog Archive Section
		$wp_customize->add_section( 'blog_archive_section',
			[
				'title'    => esc_html__( 'Blog Settings', 'cldirectory' ),
				'priority' => 15,
			]
		);
		// Add Single Post Section
		$wp_customize->add_section( 'single_post_section',
			[
				'title'    => esc_html__( 'Post Details Settings', 'cldirectory' ),
				'priority' => 16,
			]
		);
		
		// Contact Info
		$wp_customize->add_section( 'contact_info_section',
			[
				'title'    => esc_html__( 'Contact & Social', 'cldirectory' ),
				'priority' => 17,
			]
		);
		// Contact Info
		$wp_customize->add_section( 'footer_cta_banner_section',
			[
				'title'    => esc_html__( 'Footer CTA Banner Section', 'cldirectory' ),
				'priority' => 18,
			]
		);
		// Contact Info
		$wp_customize->add_section( 'social_share_section',
			[
				'title'    => esc_html__( 'Social Share Settings', 'cldirectory' ),
				'priority' => 19,
			]
		);
		//listing settings section

		
		
		$wp_customize->add_section( 'listing_archive_section',
			[
				'title'    => esc_html__( 'Listing Archive Settings', 'cldirectory' ),
				'panel'    => 'listings_panel',
				'priority' => 1,
			]
		);
		$wp_customize->add_section( 'store_archive_section',
			[
				'title'    => esc_html__( 'Store Archive Settings', 'cldirectory' ),
				'panel'    => 'listings_panel',
				'priority' => 2,
			]
		);

		$wp_customize->add_section( 'listing_single_section',
			[
				'title'    => esc_html__( 'Listing Single Settings', 'cldirectory' ),
				'panel'    => 'listings_panel',
				'priority' => 4,
			]
		);


		$wp_customize->add_section( 'listing_common_section',
			[
				'title'    => esc_html__( 'Listing Common Settings', 'cldirectory' ),
				'panel'    => 'listings_panel',
				'priority' => 7,
			]
		);
		$wp_customize->add_section( 'listing_search_section',
			[
				'title'    => esc_html__( 'Listing Search Settings', 'cldirectory' ),
				'panel'    => 'listings_panel',
				'priority' => 8,
			]
		);

		// Contact Info
		$wp_customize->add_section( 'woocommerce_common_section',
			[
				'title'    => esc_html__( 'WooCommerce Common', 'cldirectory' ),
				'priority' => 1,
				'panel'    => 'woocommerce',
			]
		);

		// Add Error Page Section
		$wp_customize->add_section( 'error_section',
			[
				'title'    => esc_html__( 'Error Page', 'cldirectory' ),
				'priority' => 19,
			]
		);
	}

}
