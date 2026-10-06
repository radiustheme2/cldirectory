<?php
/**
 * jetpack.
 *
 * @link https://jetpack.com/
 */

namespace radiustheme\CLDirectory;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Rtcl\Helpers\Functions;
use RtclStore\Helpers\Functions as StoreFunctions;

/**
 * ThemeJetpack Class
 */
class Shortcode {

	protected static $instance = null;

	public static $shortcode_list = [
		'header'    => [
			'tag'      => 'cldirectory_listing_header',
			'callback' => 'render_listing_header',
		],
		'header_2'    => [
			'tag'      => 'cldirectory_listing_header_2',
			'callback' => 'render_listing_header_2',
		],
		'sidebar'    => [
			'tag'      => 'cldirectory_listing_sidebar',
			'callback' => 'render_listing_sidebar',
		],
        'description' => [
			'tag'      => 'cldirectory_listing_description',
			'callback' => 'render_listing_description',
		],
        'custom_fields' => [
			'tag'      => 'cldirectory_listing_custom_fields',
			'callback' => 'render_listing_custom_fields',
		],
        'food_menu' => [
			'tag'      => 'cldirectory_listing_food_menu',
			'callback' => 'render_listing_food_menu',
		],
        'video' => [
			'tag'      => 'cldirectory_listing_video',
			'callback' => 'render_listing_video',
		],
        'map' => [
			'tag'      => 'cldirectory_listing_map',
			'callback' => 'render_listing_map',
		],
        'review' => [
			'tag'      => 'cldirectory_listing_review',
			'callback' => 'render_listing_review',
		],
	];

	/**
	 * register default hooks and actions for WordPress
	 *
	 * @return
	 */
	public function __construct() {
		add_filter(
			'rtcl/fb/single_layout/fields',
			function ( $fields ) {
				$shortcode_hints = '';
				foreach ( self::$shortcode_list as $label => $shortcode ) {
					$shortcode_hints .= '<br><b>' . ucfirst( $label ) . ":</b> [{$shortcode['tag']}]";
				}

				$fields['shortcode']['editor']['hints']['value'] = 'Available Shortcodes: ' . $shortcode_hints;

				return $fields;
			}
		);

		foreach ( self::$shortcode_list as $shortcode ) {
			add_shortcode( $shortcode['tag'], [ $this, $shortcode['callback'] ] );
		}
	}

	public static function instance() {
		if ( null == self::$instance ) {
			self::$instance = new self;
		}
		return self::$instance;
	}

	/**
	 * @return false|string
	 */
	public function render_listing_header() {
		global $listing;

		if ( ! $listing ) {
			return '';
		}

		if ( is_singular( 'rtcl_listing' ) ) {
			ob_start();
			?>

            <div class="cldirectory-content-top">
                <!--Listing Heading-->
				<?php Helper::get_custom_listing_template( 'listing-heading' ); ?>

                <!--Listing Slider-->
				<?php Listing_Functions::listing_details_slider(); ?>
            </div>
			<?php
			return ob_get_clean();
		}

		return '';
	}

	/**
	 * @return false|string
	 */
	public function render_listing_header_2() {
		global $listing;

		if ( ! $listing ) {
			return '';
		}

		if ( is_singular( 'rtcl_listing' ) ) {
			ob_start();
			?>

            <div class="listing-content-top" id="listing-content-top">
				<?php Listing_Functions::listing_details_banner(); ?>
            </div>

            <div class="rtcl-single-nav-menu-wrapper">
				<?php Helper::get_custom_listing_template( 'listing-nav-menu'); ?>
            </div>

			<?php
			return ob_get_clean();
		}

		return '';
	}

	/**
	 * Listing sidebar
	 *
	 * @return false|string
	 */
	public function render_listing_sidebar() {
		global $listing;

		if ( ! $listing ) {
			return '';
		}

		if ( is_singular( 'rtcl_listing' ) ) {
			ob_start();

			?>
            <div class="listing-sidebar">
				<?php
				if ( $listing->can_show_user() ) {
					if ( (RDTheme::$options['show_user_info_on_details'] === 'show_store_info') && class_exists('RtclStore')) {
						if ( StoreFunctions::get_user_store( $listing->get_owner_id() ) ) {
							Helper::get_custom_listing_template( 'listing-store-info' );
						}
					} elseif ( (RDTheme::$options['show_user_info_on_details'] === 'show_owner_info')) {
						Helper::get_custom_listing_template( 'listing-owner-info' );
					} else {
						Helper::get_custom_listing_template( 'listing-content-info' );
					}
				}
				?>
				<?php do_action('rtcl_single_listing_business_hours'); ?>
				<?php
				$email = get_post_meta($listing->get_id(),'email',true);
				$has_contact_form = Functions::get_option_item( 'rtcl_single_listing_settings', 'has_contact_form', false, 'checkbox' );
				?>
				<?php if ( $email && $has_contact_form) : ?>
                    <div class='rtcl-do-email'>
						<?php $listing->email_to_seller_form(); ?>
                    </div>
				<?php endif; ?>
				<?php do_action( 'rtcl_after_single_listing_sidebar', $listing->get_id() ); ?>
				<?php if ( is_active_sidebar( 'single-listing-sidebar' ) ): ?>
                    <aside class="sidebar-widget">
						<?php dynamic_sidebar( 'single-listing-sidebar' ); ?>
                    </aside>
				<?php endif; ?>
            </div>

            <?php
			return ob_get_clean();
		}

		return '';
	}

	/**
	 * @return false|string
	 */
	public function render_listing_description() {
		global $listing;

		if ( ! $listing ) {
			return '';
		}

		if ( is_singular( 'rtcl_listing' ) ) {
			ob_start();
			?>

            <!-- Description -->
			<?php if ( $listing->get_the_content() ) { ?>
                <div class="cldirectory-accordion-item" id="listing-description">
                    <div class="accordion-header" id="clproperty_listing_des_heading">
                        <h2 class="mb-0">
                            <button class="btn" data-bs-toggle="collapse" data-bs-target="#clproperty_listing_des" aria-expanded="true" aria-controls="clproperty_listing_des">
								<?php esc_html_e( 'Description', 'cldirectory' ); ?>
                            </button>
                        </h2>
                    </div>
                    <div id="clproperty_listing_des" class="collapse accordion-collapse show" aria-labelledby="clproperty_listing_des_heading">
                        <div class="cldirectory-accordion-content">
							<?php $listing->the_content(); ?>
                        </div>
                    </div>
                </div>
			<?php } ?>

			<?php
			return ob_get_clean();
		}

		return '';
	}

	/**
	 * @return false|string
	 */
	public function render_listing_custom_fields() {
		global $listing;

		if ( ! $listing ) {
			return '';
		}

		if ( is_singular( 'rtcl_listing' ) ) {
			ob_start();

			if (  Functions::isEnableFb() && Listing_Functions::check_fb_custom_group_field_set()) {
				$listing->custom_fields();
			} else {
				$groups_id = isset( RDTheme::$options['custom_fields_group_types'] ) ? RDTheme::$options['custom_fields_group_types'] : [];
				if(!empty( $groups_id )){
					Helper::get_custom_listing_template( 'cfg-individual' );
				}
				if(!empty(RDTheme::$options['custom_fields_list_types'])){
					Helper::get_custom_listing_template('cfg-details');
				}
			}

			return ob_get_clean();
		}

		return '';
	}

	/**
	 * @return false|string
	 */
	public function render_listing_food_menu() {
		global $listing;

		if ( ! $listing ) {
			return '';
		}

		if ( is_singular( 'rtcl_listing' ) ) {
			ob_start();

			if (  Functions::isEnableFb() && Listing_Functions::check_fb_custom_group_field_set()){
                $form = $listing->getForm();
                $menu_list = [
                    'form'       => $form,
                    'listing_id' => $listing->get_id()
                ];
                Helper::get_custom_listing_template( 'c-fields_food_menu', true, $menu_list );
			} else {
				Helper::get_custom_listing_template( 'food-menu' );
			}

			return ob_get_clean();
		}

		return '';
	}

	/**
	 * @return false|string
	 */
	public function render_listing_video() {
		global $listing;

		if ( ! $listing ) {
			return '';
		}

		if ( is_singular( 'rtcl_listing' ) ) {
			ob_start();

			$video_urls       = [];
			if ( ! Functions::is_video_urls_disabled() ) {
				$video_urls = get_post_meta( $listing->get_id(), '_rtcl_video_urls', true );
				$video_urls = ! empty( $video_urls ) && is_array( $video_urls ) ? $video_urls : [];
			}

			?>

            <!-- Video -->
			<?php if ( ! empty( $video_urls ) ) {?>
                <div class="cldirectory-accordion-item" id="listing-video">
                    <div class="accordion-header" id="clproperty_listing_video_heading">
                        <h2 class="mb-0">
                            <button class="btn" data-bs-toggle="collapse" data-bs-target="#clproperty_listing_video" aria-expanded="true" aria-controls="clproperty_listing_video">
								<?php esc_html_e( 'Video', 'cldirectory' ); ?>
                            </button>
                        </h2>
                    </div>
                    <div id="clproperty_listing_video" class="collapse accordion-collapse show" aria-labelledby="clproperty_listing_video_heading" >
                        <div class="cldirectory-accordion-content">
                            <div class="ratio ratio-16x9">
                                <iframe class="rtcl-lightbox-iframe" src="<?php echo esc_url( Functions::get_sanitized_embed_url( $video_urls[0] ) ); ?>"></iframe>
                            </div>
                        </div>
                    </div>
                </div>
			<?php } ?>

			<?php
			return ob_get_clean();
		}

		return '';
	}

	/**
	 * @return false|string
	 */
	public function render_listing_map() {
		global $listing;

		if ( ! $listing ) {
			return '';
		}

		if ( is_singular( 'rtcl_listing' ) ) {
			ob_start();

			$hide_listing_map   = get_post_meta( get_the_ID(), 'hide_map', true );

			?>

            <!-- Map -->
			<?php if ( method_exists( 'Rtcl\Helpers\Functions', 'has_map' ) && Functions::has_map() && ! $hide_listing_map ){ ?>
                <div class="cldirectory-accordion-item" id="map-location">
                    <div class="accordion-header" id="clproperty_listing_map_heading">
                        <h2 class="mb-0">
                            <button class="btn" data-bs-toggle="collapse" data-bs-target="#clproperty_listing_map" aria-expanded="true" aria-controls="clproperty_listing_map">
								<?php esc_html_e( 'Map Location', 'cldirectory' ); ?>
                            </button>
                        </h2>
                    </div>
                    <div id="clproperty_listing_map" class="collapse accordion-collapse show" aria-labelledby="clproperty_listing_map_heading">
                        <div class="cldirectory-accordion-content">
							<?php do_action( 'rtcl_single_listing_content_end', $listing ); ?>
                        </div>
                    </div>
                </div>
			<?php } ?>

			<?php
			return ob_get_clean();
		}

		return '';
	}

    /**
	 * @return false|string
	 */
	public function render_listing_review() {
		global $listing;

		if ( ! $listing ) {
			return '';
		}

		if ( is_singular( 'rtcl_listing' ) ) {
			ob_start();

            do_action( 'rtcl_single_listing_review' );

			return ob_get_clean();
		}

		return '';
	}

}

Shortcode::instance();
