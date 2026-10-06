<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.5
 */

namespace radiustheme\CLDirectory;


if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Rtcl\Models\Listing;
use Rtcl\Helpers\Functions;
use Rtcl\Models\RtclCFGField;
use Rtcl\Controllers\Hooks\Filters;
use Rtcl\Services\FormBuilder\FBField;
use Rtcl\Controllers\Hooks\TemplateHooks;
use Rtcl\Resources\Options as RtclOptions;
use radiustheme\CLDirectory_Core\YelpReview;
use RtclStore\Controllers\Hooks\TemplateHooks as StoreHooks;
use RtclPro\Controllers\Hooks\TemplateHooks as ProTemplateHooks;
use RadiusTheme\RadiusBooking\Integrations\ClassifiedListing\ListingFrontendHooks as RtrbFrontendHooks;

class Listing_Functions {

	protected static $instance = null;

	public function __construct() {
		add_action( 'after_setup_theme', [ $this, 'theme_support' ] );
		add_action( 'init', [ $this, 'classifiedads_rtcl_filter' ] );
		add_action( 'init', [ $this, 'classifiedads_rtcl_action' ] );
		add_action( 'template_redirect', [ $this, 'disable_lazy_load' ] );
		add_action( 'the_content', [ $this, 'rt_set_post_view_count' ], 9999 );

		add_action( 'cldirectory_listing_grid_search_filer', [ $this, 'listing_map_filter' ] );
		add_action( 'wp_ajax_delete_listing_logo_attachment', [ $this, 'delete_listing_logo_attachment' ] );
		add_action( 'wp_ajax_delete_food_attachment', [ $this, 'delete_food_attachment' ] );

		add_filter( 'rtcl_store_the_excerpt', [ $this, 'rtcl_store_the_excerpt' ] );
		add_filter( 'rtcl_format_price_range', [ $this, 'rtcl_format_price_range' ], 10, 3 );

		add_filter( 'rtcl_general_settings_options', [ $this, 'rtcl_general_settings_options' ] );
		add_action( 'rtcl_listing_form_after_save_or_update', [
			$this,
			'listing_form_save'
		], 12, 5 ); // save extra listing form fields

		add_filter( 'rtcl_get_icon_list', [ $this, 'rtcl_get_icon_list_modify' ] );

		add_action( 'rtcl_map_localized_options', [__CLASS__, 'cldirectory_map_localized_options' ] );


		add_filter( 'rtcl_business_hours_display_options', function( $options ){
			$options['open_text']          = esc_html__( 'Open', 'cldirectory' );
			$options['close_text']         = esc_html__( 'Close', 'cldirectory' );
			$options['open_today_text']    = esc_html__( 'Open Today (24 Hours)', 'cldirectory' );
			$options['closed_today_text']  = esc_html__( 'Closed Today', 'cldirectory' );
			$options['open_status_text']   = esc_html__( 'We are currently open.', 'cldirectory' );
			$options['close_status_text']  = esc_html__( 'Sorry, we are currently closed.', 'cldirectory' );

			return $options;
		});

	}

	/**
	 * @param Listing $listing
	 */
	public static function cldirectory_map_localized_options( $options ) {
		//Map Center Points Settings
		$options['cluster_options']['center'] = [
			"lat" => '39.7641279',
			"lng" => '-104.0195573',
		];
		$options['cluster_options']['zoom'] = 4;
		return $options;
	}

	function rtcl_general_settings_options( $options ) {

		unset( $options['load_bootstrap'] );

		return $options;
	}

	/**
	 * @param $items
	 *
	 * @return array
	 */
	public static function group_menu_items( $items ) {
		$grouped       = [];
		$current_group = - 1;

		foreach ( $items as $item ) {
			// New group start
			if ( isset( $item['enable_group_title'] ) && $item['enable_group_title'] === 'yes' ) {
				$current_group ++;
				$grouped[ $current_group ] = [
					'group_title' => $item['menu_name'],
					'items'       => []
				];
			} else {
				// Add menu item to current group
				if ( $current_group >= 0 ) {
					$grouped[ $current_group ]['items'][] = $item;
				}
			}
		}

		return $grouped;
	}

	function rtcl_format_price_range( $price, $from, $to ) {
		// translators: %1$s: price from value, %2$s: price to value.
		$price = sprintf(
			// translators: %1$s: price from value, %2$s: price to value.
			_x(
				'<div class="rtcl-price-range"><span class="price-from">%1$s</span> <span class="dash">&ndash;</span> <span class="price-to">%2$s</span></div>',
				'Price range: from-to',
				'cldirectory'
			),
			is_numeric( $from ) ? Functions::price( $from ) : $from,
			is_numeric( $to ) ? Functions::price( $to ) : $to
		);

		return $price;
	}

	function disable_lazy_load() {
		$is_listing_archive = Functions::is_listings() || Functions::is_listing_taxonomy();
		if ( $is_listing_archive || is_singular( 'rtcl_listing' ) ) {
			add_filter( 'wp_lazy_loading_enabled', '__return_false' );
		}
	}

	public function rtcl_store_the_excerpt( $excerpt ) {
		$excerpt = wp_trim_words( $excerpt, 20, '' );

		return $excerpt;
	}

	public function rt_set_post_view_count( $content ) {
		//Set store View Count 
		if ( is_singular() ) {
			$rt_store_views_key = 'rt_post_views_count';
			$store_view_count   = get_post_meta( get_the_ID(), $rt_store_views_key, true );
			if ( '' == $store_view_count ) {
				$store_view_count = 0;
				delete_post_meta( get_the_ID(), $rt_store_views_key );
				add_post_meta( get_the_ID(), $rt_store_views_key, $store_view_count );
			} else {
				$store_view_count ++;
				update_post_meta( get_the_ID(), $rt_store_views_key, $store_view_count );
			}
		}

		return $content;
	}

	public static function rt_get_post_view_count( $storeID ) {
		//Get store View Count 
		$rt_store_views_key = 'rt_post_views_count';
		$store_view_count   = get_post_meta( $storeID, $rt_store_views_key, true );
		return $store_view_count;
	}

	public static function instance() {
		if ( null == self::$instance ) {
			self::$instance = new self;
		}

		return self::$instance;
	}

	public function theme_support() {
		add_theme_support( 'rtcl' );
	}


	public function classifiedads_rtcl_filter() {

		add_filter(
			'rtcl_loop_listing_per_page',
			function( $per_page ) {
				$post_per_pare = Functions::get_option_item( 'rtcl_archive_listing_settings', 'listing_top_per_page', 2 );
				if ( isset( $_GET['layout'] ) && 'fullwidth' == $_GET['layout'] ) {
					$per_page = $per_page < 10 ? 9 : 12;
					$per_page -= absint( $post_per_pare );
				}
				return $per_page;
			}
		);

		//modify related slider options

		add_filter( 'rtcl_related_slider_options', function( $options ) {
			$options['autoplay']                            = RDTheme::$options['slider_autoplay'];
			$options['breakpoints']['768']['slidesPerView'] = 2;
			$options['breakpoints']['992']['slidesPerView'] = 3;

			return $options;
		} );

		// Change Grid Column for listing
		add_filter( 'rtcl_listings_grid_columns_class', function () {
			$per_row   = Functions::get_listings_per_row();
			$col_class = sprintf(
				'columns-%d tab-columns-%d mobile-columns-%d',
				intval( $per_row['desktop'] ?? 3 ),
				intval( $per_row['tablet'] ?? 2 ),
				intval( $per_row['mobile'] ?? 1 )
			);
			if ( is_page_template( 'templates/listing-map.php' ) ) {
				$columns   = 'columns-'.RDTheme::$options['listing_grid_cols'];
			} else {
				$columns = $col_class;
			}
			return $columns;
		} );

		// Change Grid Column for store
		add_filter(
			'rtcl_stores_grid_columns_class',
			function() {
				$columns = 'columns-2';
				if ( Helper::has_sidebar() ) {
					$columns = 'columns-1';
				}

				return $columns;
			}
		);

		// Override Related Listing Item Number
		add_filter(
			'rtcl_related_slider_options',
			function( $options ) {
				$options['items']  = 3;
				$options['margin'] = 30;

				return $options;
			}
		);
		// Modify Add to favorite text
		add_filter(
			'rtcl_text_add_to_favourite',
			function( $text ) {
				$text = '';

				return $text;
			}
		);
		// Modify remove from favorite text
		add_filter(
			'rtcl_text_remove_from_favourite',
			function( $text ) {
				$text = '';

				return $text;
			}
		);
		// Remove report abuse text
		add_filter(
			'rtcl_text_report_abuse',
			function( $text ) {
				return '';
			}
		);

		add_filter(
			'get_the_archive_title',
			function( $title ) {
				if ( is_post_type_archive( 'rtcl_listing' ) ) {
					$title = esc_html__( 'Our Latest Listings', 'cldirectory' );
				}

				return $title;
			}
		);

		// Override page template
		add_filter( 'template_include', [ $this, 'template_include' ] );

		/* = Override plugin options*/
		add_filter( 'rtcl_get_listing_display_options', [ $this, 'listing_extra_options' ] );
		add_filter( 'rtcl_get_listing_detail_page_display_options', [ $this, 'listing_details_extra_options' ] );

		// Listing/Directory Settings
		add_filter( 'rtcl_general_settings_options', [ $this, 'cldirectory_custom_listing_options' ] );

		add_filter( 'rtcl_bootstrap_dequeue', '__return_false' );

	}


	public function rtcl_get_icon_list_modify( $icons_lists ) {
		$new_icons = [
			" bars-cl-icon",
			" drinking-glass-cl-icon",
			" map-marker-cl-icon",
			" phone-call-cl-icon",
			" user-alt-cl-icon",
			" share-cl-icon",
			" list-cl-icon",
			" printer-cl-icon",
			" seater-cl-icon",
			" smoke-cl-icon",
			" envelope-cl-icon",
			" long-arrow-right-light-cl-icon",
			" search-cl-icon",
			" hotel-cl-icon",
			" compare-cl-icon",
			" long-arrow-left-cl-icon",
			" briefcase-cl-icon",
			" map-marker-alt-cl-icon",
			" angle-left-cl-icon",
			" check-cl-icon",
			" bookmark-cl-icon",
			" wifi-cl-icon",
			" globe-cl-icon",
			" map-location-cl-icon",
			" spoon-plate-cl-icon",
			" travel-bag-cl-icon",
			" heart-cl-icon",
			" circle-check-cl-icon",
			" user-alt-1-cl-icon",
			" mobile-cl-icon",
			" swimming-cl-icon",
			" store-cl-icon",
			" star-cl-icon",
			" gallery-cl-icon",
			" car-cl-icon",
			" card-cl-icon",
			" tag-cl-icon",
			" cinema-cl-icon"
		];

		return array_merge( $icons_lists, $new_icons );
	}

	public function listing_map_filter( $atts ) {
		$loc_text = esc_attr__( 'Select Location', 'cldirectory' );
		$cat_text = esc_attr__( 'Select Category', 'cldirectory' );

		$class = is_page_template( 'templates/listing-map.php' ) ? 'listing-map-filter' : 'page-filter';
		global $wp;
		?>
        <div class="listing-grid-box <?php echo esc_attr( $class ); ?>">
            <form action="<?php echo esc_url( home_url( $wp->request ) ); ?>"
                  class="advance-search-form map-search-form rtcl-widget-search-form is-preloader">
                <div class="search-box">
                    <div class="search-item search-keyword">
                        <div class="input-group">
                            <input type="text" data-type="listing" name="q" class="rtcl-autocomplete form-control"
                                   placeholder="<?php esc_attr_e( 'Enter Keyword here ...', 'cldirectory' ); ?>"
                                   value="<?php if ( isset( $_GET['q'] ) ) {
								       echo esc_attr( $_GET['q'] );
							       } ?>"/>
                        </div>
                    </div>
                    <div class="search-item search-select rtin-category">
						<?php
						wp_dropdown_categories(
							[
								'show_option_none'  => $cat_text,
								'option_none_value' => '',
								'taxonomy'          => rtcl()->category,
								'name'              => 'rtcl_category',
								'id'                => 'rtcl-category-search-' . wp_rand(),
								'class'             => 'select2 rtcl-category-search',
								'selected'          => get_query_var( 'rtcl_category' ),
								'hierarchical'      => true,
								'value_field'       => 'slug',
								'depth'             => Functions::get_category_depth_limit(),
								'show_count'        => false,
								'hide_empty'        => false,
							]
						);
						?>
                    </div>
					<?php if ( method_exists( 'Rtcl\Helpers\Functions', 'location_type' ) && 'local' === Functions::location_type() ): ?>
                        <div class="search-item search-select rtin-location">
							<?php
							wp_dropdown_categories(
								[
									'show_option_none'  => $loc_text,
									'option_none_value' => '',
									'taxonomy'          => rtcl()->location,
									'name'              => 'rtcl_location',
									'id'                => 'rtcl-location-search-' . wp_rand(),
									'class'             => 'select2 rtcl-location-search',
									'selected'          => get_query_var( 'rtcl_location' ),
									'hierarchical'      => true,
									'value_field'       => 'slug',
									'depth'             => Functions::get_location_depth_limit(),
									'show_count'        => false,
									'hide_empty'        => false,
								]
							);
							?>
                        </div>
					<?php endif; ?>
                </div>
                <div class="search-box-2">
                    <div class="distance-search search-item">
						<?php
						$rs_data = RtclOptions::radius_search_options();
						?>
                        <div class="form-group ws-item ws-location">
                            <h4 class="inner-title"><?php esc_html_e( "Select a location", "cldirectory" ); ?></h4>
                            <div class="rtcl-geo-address-field">
                                <input type="text" name="geo_address" autocomplete="off"
                                       value="<?php echo ! empty( $_GET['geo_address'] ) ? esc_attr( $_GET['geo_address'] ) : '' ?>"
                                       placeholder="<?php esc_attr_e( "Select a location", "cldirectory" ) ?>"
                                       class="form-control rtcl-geo-address-input"/>
                                <i class="rtcl-get-location rtcl-icon rtcl-icon-target"></i>
                                <input type="hidden" class="latitude" name="center_lat"
                                       value="<?php echo ! empty( $_GET['center_lat'] ) ? esc_attr( $_GET['center_lat'] ) : '' ?>">
                                <input type="hidden" class="longitude" name="center_lng"
                                       value="<?php echo ! empty( $_GET['center_lng'] ) ? esc_attr( $_GET['center_lng'] ) : '' ?>">
                            </div>
                            <div class="rtcl-range-slider-field">
                                <div class="rtcl-range-label">
                                    <h4 class="inner-title"><?php esc_html_e( 'Radius', 'cldirectory' ); ?></h4>
                                    <span class="rtcl-range-value">
										<?php echo ! empty( $_GET['distance'] ) ? absint( $_GET['distance'] ) : 30 ?>
										<?php in_array( $rs_data['units'], [
											'km',
											'kilometers'
										] ) ? esc_html_e( "km", "cldirectory" ) : esc_html_e( "Miles", "cldirectory" ); ?></span>
                                </div>
                                <input type="range" class="form-control-range rtcl-range-slider-input" name="distance"
                                       min="0"
                                       max="<?php echo absint( $rs_data['max_distance'] ) ?>"
                                       value="<?php echo esc_attr( isset( $_GET['distance'] ) ? sanitize_text_field( wp_unslash( $_GET['distance'] ) ) : $rs_data['default_distance'] ); ?>">
                            </div>
                        </div>
                    </div>
                    <div class="search-item price-item-box">
						<?php if ( RDTheme::$options['listing_price_search_type'] == 'range' ) { ?>
                            <div class="price-range">
                                <h4 class="inner-title"><?php esc_html_e( 'Price Range', 'cldirectory' ); ?></h4>
								<?php
								$currency  = Functions::get_currency_symbol();
								$data_form = '';
								$data_to   = '';
								if ( isset( $_GET['filters']['price']['min'] ) ) {
									$data_form .= sprintf( "data-from=%s", absint( $_GET['filters']['price']['min'] ) );
								}
								if ( isset( $_GET['filters']['price']['max'] ) && ! empty( $_GET['filters']['price']['max'] ) ) {
									$data_to .= sprintf( "data-to=%s", absint( $_GET['filters']['price']['max'] ) );
								}
								$min_price = RDTheme::$options['listing_widget_min_price'];
								$max_price = RDTheme::$options['listing_widget_max_price'];
								?>
                                <input type="number"
                                       class="ion-rangeslider" <?php echo esc_attr( $data_form ); ?> <?php echo esc_attr( $data_form ); ?> <?php echo esc_attr( $data_to ); ?>
                                       data-min="<?php echo isset( $min_price ) && ! empty( $min_price ) ? esc_attr( $min_price ) : 0; ?>"
                                       data-max="<?php echo isset( $max_price ) && ! empty( $max_price ) ? esc_attr( $max_price ) : 80000; ?>"
                                       data-prefix="<?php echo esc_html( $currency ) ?>"/>
                                <input type="hidden" class="min-volumn" name="filters[price][min]"
                                       value="<?php if ( isset( $_GET['filters']['price']['min'] ) ) {
									       echo absint( $_GET['filters']['price']['min'] );
								       } ?>">
                                <input type="hidden" class="max-volumn" name="filters[price][max]"
                                       value="<?php if ( isset( $_GET['filters']['price']['max'] ) ) {
									       echo absint( $_GET['filters']['price']['max'] );
								       } ?>">
                            </div>
						<?php } else { ?>
                            <!-- Price fields -->
                            <div class="form-group">
                                <h4 class="inner-title"><?php esc_html_e( 'Price Range', 'cldirectory' ); ?></h4>
                                <div class="row">
                                    <div class="col-md-6 col-xs-6">
                                        <input type="text" name="filters[price][min]" class="form-control"
                                               placeholder="<?php esc_attr_e( 'min', 'cldirectory' ); ?>"
                                               value="<?php if ( isset( $_GET['filters']['price'] ) ) {
											       echo esc_attr( $_GET['filters']['price']['min'] );
										       } ?>">
                                    </div>
                                    <div class="col-md-6 col-xs-6">
                                        <input type="text" name="filters[price][max]" class="form-control"
                                               placeholder="<?php esc_attr_e( 'max', 'cldirectory' ); ?>"
                                               value="<?php if ( isset( $_GET['filters']['price'] ) ) {
											       echo esc_attr( $_GET['filters']['price']['max'] );
										       } ?>">
                                    </div>
                                </div>
                            </div>
						<?php } ?>
                    </div>
                    <div class="search-item-custom-field">
						<?php
						$hide_group_id = isset( RDTheme::$options['custom_fields_search_items'] ) ? RDTheme::$options['custom_fields_search_items'] : array();

						$all_group_ids = Functions::get_cfg_ids();
						$args          = [
							'is_searchable'     => true,
							'exclude_group_ids' => $hide_group_id,
						];
						$fields_id     = Functions::get_cf_ids( $args );

						$html = '';
						foreach ( $fields_id as $field ) {
							$html .= self::get_advanced_search_field_html( $field, true );
						}
						Functions::print_html( $html, true );
						?>
                    </div>
                </div>

                <div class="search-item search-btn">
                    <button type="submit" class="submit-btn">
                        <i class="search-cl-icon"></i>
						<?php esc_html_e( 'Search', 'cldirectory' ); ?>
                    </button>
                </div>

            </form>
        </div>
		<?php
	}

	public function listing_extra_options( $options ) {
		$options['phone'] = esc_html__( 'Phone', 'cldirectory' );
		$options['book_button'] = esc_html__( 'Booking Button', 'classilist' );
		return $options;
	}

	public function listing_details_extra_options( $options ) {

		$options['phone'] = esc_html__( 'Phone', 'cldirectory' );

		return $options;
	}

	public function classifiedads_rtcl_action() {

		remove_action( 'rtcl_edit_account_form_end', [ TemplateHooks::class, 'edit_account_form_submit_button' ], 10 );
		add_action( 'rtcl_edit_account_form_end', [ $this, 'edit_account_form_submit_button' ], 10 );
		remove_action( 'rtcl_after_listing_loop_item', [ ProTemplateHooks::class, 'sold_out_banner' ] );
		remove_action( 'rtcl_before_main_content', [ TemplateHooks::class, 'breadcrumb' ], 6 );
		remove_action( 'rtcl_before_main_content', [ TemplateHooks::class, 'output_main_wrapper_start' ], 8 );
		remove_action( 'rtcl_after_main_content', [ TemplateHooks::class, 'output_content_wrapper_end' ], 15 );
		remove_action( 'rtcl_sidebar', [ TemplateHooks::class, 'output_main_wrapper_end' ], 15 );
		remove_action( 'rtcl_quick_view_summary', [ ProTemplateHooks::class, 'quick_view_summary_custom_fields' ], 40 );
		remove_action( 'rtcl_listing_form_end', [ TemplateHooks::class, 'listing_form_submit_button' ], 50 );

		// Account page my listing hook
		remove_action( 'rtcl_my_listing_actions', [ ProTemplateHooks::class, 'my_listing_mark_as_sold_button' ], 40 );

		// Listing archive page hook
		remove_action( 'rtcl_listing_loop_item', [ TemplateHooks::class, 'loop_item_listing_title' ], 20 );
		remove_action( 'rtcl_listing_loop_item', [ TemplateHooks::class, 'loop_item_meta' ], 50 );
		remove_action( 'rtcl_listing_loop_item', [ TemplateHooks::class, 'loop_item_excerpt' ], 70 );
		remove_action( 'rtcl_listing_loop_item', [ TemplateHooks::class, 'listing_price' ], 80 );

		add_action( 'rtcl_listing_loop_item', [ $this, 'loop_item_listing_title' ], 20 );
		add_action( 'rtcl_listing_loop_item', [ $this, 'loop_item_meta' ], 50 );
		add_action( 'rtcl_listing_loop_item', [ __CLASS__, 'loop_item_excerpt' ], 70 );
		add_action( 'rtcl_listing_loop_item', [ __CLASS__, 'loop_item_footer' ], 80 );


		// Store Archive Hooks

		remove_action( 'rtcl_before_store_loop_item', [ StoreHooks::class, 'open_store_link' ], 10 );
		remove_action( 'rtcl_after_store_loop_item', [ StoreHooks::class, 'close_store_link' ], 5 );
		remove_action( 'rtcl_store_loop_item', [ StoreHooks::class, 'store_meta' ], 20 );
		remove_action( 'rtcl_store_loop_item', [ StoreHooks::class, 'loop_item_store_title' ], 10 );


		add_action( 'rtcl_store_loop_item', [ __CLASS__, 'store_meta' ], 20 );
		add_action( 'rtcl_store_loop_item', [ __CLASS__, 'store_excerpt' ], 25 );

		// store enable pagination by force
		add_action( 'template_redirect', [$this,'store_enable_pagination'], 0 );

		/* ===== Radius Booking ===== */
		if ( class_exists( RtrbFrontendHooks::class ) ) {
			// Primary hooks (core CL template).
			remove_action( 'rtcl_single_listing_sidebar', array( RtrbFrontendHooks::class, 'renderBookNowButton' ), 30 );
			// Fallback hooks used by various themes.
			remove_action( 'rtcl_after_single_listing_sidebar', array( RtrbFrontendHooks::class, 'renderBookNowButton' ), 30 );
			remove_action( 'rtcl_single_listing_inner_sidebar', array( RtrbFrontendHooks::class, 'renderBookNowButton' ), 30 );
		}
	}

	public function store_enable_pagination() {
		if ( is_singular( 'store' ) ) {
			remove_action( 'template_redirect', 'redirect_canonical' );
		}
	}

	public static function store_meta() {
		global $store;
		?>
		<?php if ( $store->get_address() || $store->get_website() || $store->get_phone()  ): ?>
            <ul class="entry-meta">
				<?php if ( $store->get_address() && RDTheme::$options['show_store_location']): ?>
                    <li><i class="icon-location2"></i><?php echo esc_html( $store->get_address() ); ?></li>
				<?php endif; ?>
				<?php if ( $stor_view_count = self::rt_get_post_view_count( $store->get_id() ) ) :
                    ?>

					<?php if(RDTheme::$options['show_store_views']){ ?>
                        <li>
                            <i class="icon-view"></i>
							<?php
							$label = $stor_view_count < 10 ? esc_html__( 'View: ', 'cldirectory' ) : esc_html__( 'Views: ', 'cldirectory' );
							echo wp_kses_post( sprintf(
								"<span class='count-label'>%s</span><span class='count-number'>%s</span>",
								$label,
								Helper::rt_number_shorten( $stor_view_count, 1 )
							) );
							?>
                        </li>
					<?php } ?>
				<?php endif; ?>
				<?php if ( $store->get_phone() && RDTheme::$options['show_store_phone']): ?>
                    <li>
                        <a class="item-btn"  href="tel:<?php echo esc_html( $store->get_phone() ); ?>">
                            <i class="icon-call"></i>
                            <span><?php echo esc_html($store->get_phone()); ?></span>
                        </a>
                    </li>
				<?php endif; ?>
				<?php if ( $store->get_email() && RDTheme::$options['show_store_mail']): ?>
                    <li>
                        <a class="item-btn" href="mailto:<?php echo esc_attr( $store->get_email() ); ?>" >
                            <i class="icon-envelop"></i>
                            <span><?php echo esc_html($store->get_email()); ?></span>
                        </a>
                    </li>
				<?php endif; ?>
				<?php if ( $store->get_website() && RDTheme::$options['show_store_webaddress']): ?>
                    <li>
                        <a class="item-btn" href="<?php echo esc_url( $store->get_website() ); ?>">
                            <i class="icon-web"></i>
                            <span><?php echo esc_html($store->get_website()); ?></span>
                        </a>
                    </li>
				<?php endif; ?>
            </ul>
		<?php endif; ?>
		<?php
	}
	public static function store_excerpt() {
		global $store;
		?>
		<?php if(RDTheme::$options['show_store_excerpt']): ?>
            <div class='store-excerpt'>
				<?php $store->the_excerpt() ?>
            </div>
		<?php endif; ?>
		<?php
	}

	public function cldirectory_custom_listing_options( $options ) {
		$options['custom_listing_section']              = [
			'title' => esc_html__( 'Listing Settings', 'cldirectory' ),
			'type'  => 'section',
		];
		$options['enable_restaurant_listing']           = [
			'title' => esc_html__( 'Enable Restaurant Listing', 'cldirectory' ),
			'type'  => 'checkbox',
			'label' => esc_html__( 'Add restaurant listing features.', 'cldirectory' ),
		];
		$options['cldirectory_food_list_section_label'] = [
			'title'   => esc_html__( 'Food List Section Label', 'cldirectory' ),
			'type'    => 'text',
			'default' => 'Food Menu List',
		];
		$options['custom_listing_section_2']            = [
			'title' => esc_html__( 'Top Author Page Settings', 'cldirectory' ),
			'type'  => 'section',
		];
		$options['cldirectory_top_author_roles']        = [
			'title'   => esc_html__( 'Which author role do you want to show?', 'cldirectory' ),
			'type'    => 'multi_checkbox',
			'default' => '',
			'options' => self::get_all_roles(),
			'description' => esc_html__( 'Select one or more user roles to display on the top author page.', 'cldirectory' ),
		];
		$options['cldirectory_top_author_per_page']     = [
			'title'   => esc_html__( 'Author\'s per page', 'cldirectory' ),
			'type'    => 'number',
			'default' => 10,
		];

		return $options;
	}

	public static function loop_item_listing_title() {
		global $listing;

		if ( isset( $_GET['view'] ) && in_array( $_GET['view'], [ 'grid', 'list' ], true ) ) {
			$view = esc_attr( $_GET['view'] );
		} else {
			$view = Functions::get_option_item( 'rtcl_archive_listing_settings', 'default_view', 'list' );
		}
		if ( 'list' == $view ) {
			Helper::get_formated_business_hour( $listing ); ?>
            <div class="listing-price">
				<?php Functions::get_template( 'listing/loop/price' ); ?>
            </div>
		<?php }
		// Grid cards show the category above the title; the footer keeps only the price.
		if ( 'grid' == $view ) {
			Helper::get_listing_category( $listing );
		}
		echo '<h2 class="' . esc_attr( apply_filters( 'rtcl_listing_loop_title_classes', 'listing-title rtcl-listing-title' ) ) . '"><a href="' . esc_url( $listing->get_the_permalink() ) . '">' . esc_html( $listing->get_the_title() ) . '</a></h2>';
	}

	public static function loop_item_meta() {
		global $listing;
		if ( isset( $_GET['view'] ) && in_array( $_GET['view'], [ 'grid', 'list' ], true ) ) {
			$view = esc_attr( $_GET['view'] );
		} else {
			$view = Functions::get_option_item( 'rtcl_archive_listing_settings', 'default_view', 'list' );
		}
		if ( 'grid' == $view ) {
			$listing->the_meta();
		}
	}

	public static function loop_item_excerpt() {
		global $listing;
		if ( $listing->can_show_excerpt() ) {
			$length  = RDTheme::$options['listing_arexcerpt_limit'];
			$excerpt = Helper::cldirectory_excerpt( $length );
			echo "<div class='listing-excerpt'>";
			echo wp_kses_post( $excerpt );
			echo "</div>";
		}

	}

	public static function loop_item_footer() {
		global $listing;
		$classilist_archive_settings = Functions::get_option( 'rtcl_archive_listing_settings' );
		$classilist_show_book_button = ! empty( $classilist_archive_settings['display_options'] ) && in_array( 'book_button', (array) $classilist_archive_settings['display_options'], true );
		if ( isset( $_GET['view'] ) && in_array( $_GET['view'], [ 'grid', 'list' ], true ) ) {
			$view = esc_attr( $_GET['view'] );
		} else {
			$view = Functions::get_option_item( 'rtcl_archive_listing_settings', 'default_view', 'list' );
		}
		?>
        <div class="listing-footer">
			<?php if ( 'grid' == $view ) { ?>
                <div class="listing-price">
					<?php Functions::get_template( 'listing/loop/price' ); ?>
                </div>
			<?php }
			if ( 'list' == $view ) { ?>
				<?php Helper::get_listing_author_info( $listing, false );
				$listing->the_meta();
				?>
			<?php } ?>
        </div>
		<?php
		if ( function_exists( 'rtrb_cl_booking_button' ) ) {
			if ( $classilist_show_book_button ) {
				rtrb_cl_booking_button();
			}
		}
		?>
		<?php
	}


	public function edit_account_form_submit_button() {
		?>
        <div class="form-group row">
            <div class="offset-sm-3 col-sm-9">
                <input type="submit" name="submit" class="btn btn-primary"
                       value="<?php esc_attr_e( 'Update Account', 'cldirectory' ); ?>"/>
            </div>
        </div>
		<?php
	}

	public function listing_archive_title() {
		?>
        <div class="col-8">
            <h2 class="heading-title"><?php echo wp_kses_post( get_the_archive_title() ); ?></h2>
        </div>
		<?php
	}

	/**
	 * Check if a URL is external to the current site.
	 *
	 * @param string|null $url The URL to check.
	 *
	 * @return bool
	 */
	public static function is_external( $url ) {
		if ( empty( $url ) ) {
			return false;
		}

		$site_url = str_replace( 'www.', '', (string) wp_parse_url( site_url(), PHP_URL_HOST ) );
		$url      = str_replace( 'www.', '', (string) wp_parse_url( $url, PHP_URL_HOST ) );

		if ( empty( $url ) ) {
			return false;
		}

		if ( strcasecmp( $url, $site_url ) === 0 ) {
			return false;
		}

		return true;
	}

	public function template_include( $template ) {

		if ( Functions::is_account_page() ) {
			$new_template = Helper::get_custom_listing_template( 'listing-account', false );
			$new_template = locate_template( [ $new_template ] );

			return $new_template;
		}
		if ( Functions::is_listing_form_page() ) {
			$new_template = Helper::get_custom_listing_template( 'listing-form', false );
			$new_template = locate_template( [ $new_template ] );

			return $new_template;
		}

		return $template;
	}

	public static function get_advanced_search_field_html( $field_id, $show_field_name = false ) {
		$field      = new RtclCFGField( $field_id );
		$field_html = null;

		if ( $field_id && $field ) {
			$id = "rtcl_{$field->getType()}_{$field->getFieldId()}";

			switch ( $field->getType() ) {
				case 'text':
					$field_html = sprintf(
						'<div class="search-item"><h4 class="inner-title">%s</h4><input type="text" class="rtcl-text form-control rtcl-cf-field" id="%s" name="filters[_field_%d]" placeholder="%s" value="" /></div>',
						$show_field_name ? esc_attr( $field->getLabel() ) : '',
						$id,
						absint( $field->getFieldId() ),
						esc_attr( $field->getPlaceholder() )
					);
					break;
				case 'textarea':
					$field_html = sprintf(
						'<div class="search-item"><h4 class="inner-title">%s</h4><textarea class="rtcl-textarea form-control rtcl-cf-field" id="%s" name="filters[_field_%d]" rows="%d" placeholder="%s"></textarea></div>',
						$show_field_name ? esc_attr( $field->getLabel() ) : '',
						$id,
						absint( $field->getFieldId() ),
						absint( $field->getRows() ),
						esc_attr( $field->getPlaceholder() )
					);
					break;
				case 'select':
					$options      = $field->getOptions();
					$choices      = ! empty( $options['choices'] ) && is_array( $options['choices'] ) ? $options['choices'] : [];
					$options_html = '<option value="">' . esc_html( $field->getLabel() ) . '</option>';

					if ( ! empty( $choices ) ) {
						foreach ( $choices as $key => $choice ) {
							$_attr = '';
							if ( isset( $_GET['filters'][ '_field_' . $field->getFieldId() ] ) && $_GET['filters'][ '_field_' . $field->getFieldId() ] == $choice ) {
								$_attr .= ' selected';
							}
							$options_html .= sprintf( '<option value="%s"%s>%s</option>', $key, $_attr, $choice );
						}
					}

					$field_html
						= sprintf(
						'<div class="search-item search-select"><h4 class="inner-title">%s</h4><select name="filters[_field_%d]" id="%s" data-placeholder="%s" class="select2">%s</select></div>',
						$show_field_name ? esc_attr( $field->getLabel() ) : '',
						absint( $field->getFieldId() ),
						$id,
						$field->getLabel(),
						$options_html
					);
					break;
				case 'checkbox':
					$options       = $field->getOptions();
					$value         = isset( $_GET['filters'][ '_field_' . $field->getFieldId() ] ) ? $_GET['filters'][ '_field_' . $field->getFieldId() ] : [];
					$choices       = ! empty( $options['choices'] ) && is_array( $options['choices'] ) ? $options['choices'] : [];
					$check_options = null;
					if ( ! empty( $choices ) ) {
						foreach ( $choices as $key => $choice ) {
							$_attr = '';
							if ( in_array( $key, $value ) ) {
								$_attr .= ' checked="checked"';
							}
							$check_options .= sprintf(
								'<div class="form-check"><input class="form-check-input" id="%s" type="checkbox" name="filters[_field_%d][]" value="%s"%s><label class="form-check-label" for="%s">%s</label></div>',
								$id . $key,
								absint( $field->getFieldId() ),
								$key,
								$_attr,
								$id . $key,
								$choice
							);
						}
					}
					$field_html = sprintf( '<h4 class="inner-title">%s</h4><div class="search-item checkbox-wrapper">%s</div>', $show_field_name ? esc_attr( $field->getLabel() ) : '', $check_options );
					break;
				case 'radio':
					$options       = $field->getOptions();
					$choices       = ! empty( $options['choices'] ) && is_array( $options['choices'] ) ? $options['choices'] : [];
					$check_options = null;
					if ( ! empty( $choices ) ) {
						foreach ( $choices as $key => $choice ) {
							$check_options .= sprintf(
								'<div class="form-check"><input class="form-check-input" id="%s" type="radio" name="filters[_field_%d]" value="%s"><label class="form-check-label" for="%s">%s</label></div>',
								$id . $key,
								absint( $field->getFieldId() ),
								$key,
								$id . $key,
								$choice
							);
						}
					}
					$field_html = sprintf( '<div class="search-item search-type"><h4 class="inner-title">%s</h4><div class="search-check-box">%s</div></div>', $show_field_name ? esc_attr( $field->getLabel() ) : '', $check_options );
					break;
				case 'number':
					$hidden_field = sprintf(
						'<input type="hidden" class="min-volumn" name="filters[_field_%d][min]" value="%s">',
						absint( $field->getFieldId() ),
						isset( $_GET['filters'][ '_field_' . $field->getFieldId() ]['min'] ) ? absint( $_GET['filters'][ '_field_' . $field->getFieldId() ]['min'] ) : ''
					);
					$hidden_field .= sprintf(
						'<input type="hidden" class="max-volumn" name="filters[_field_%d][max]" value="%s">',
						absint( $field->getFieldId() ),
						isset( $_GET['filters'][ '_field_' . $field->getFieldId() ]['max'] ) ? absint( $_GET['filters'][ '_field_' . $field->getFieldId() ]['max'] ) : ''
					);

					$field_html = sprintf(
						'<div class="search-item">
                                                <div class="price-range">
                                                    <label>%s</label>
                                                    <input type="number" class="ion-rangeslider" id="%s" data-step="%s" %s %s data-min="%d" data-max="%s" />
                                                    %s
                                                </div>
                                             </div>',
						esc_attr( $field->getLabel() ),
						$id,
						$field->getStepSize() ? esc_attr( $field->getStepSize() ) : 'any',
						isset( $_GET['filters'][ '_field_' . $field->getFieldId() ]['min'] ) ? sprintf(
							'data-from="%s"',
							absint( $_GET['filters'][ '_field_' . $field->getFieldId() ]['min'] )
						) : '',
						isset( $_GET['filters'][ '_field_' . $field->getFieldId() ]['max'] ) && ! empty( $_GET['filters'][ '_field_' . $field->getFieldId() ]['max'] ) ? sprintf(
							'data-to="%s"',
							absint( $_GET['filters'][ '_field_' . $field->getFieldId() ]['max'] )
						) : '',
						$field->getMin() !== '' ? absint( $field->getMin() ) : '',
						! empty( $field->getMax() ) ? absint( $field->getMax() ) : absint( $field->getMin() ) + 100,
						$hidden_field
					);
					break;
				case 'url':
					$field_html = sprintf(
						'<input type="url" class="rtcl-url form-control rtcl-cf-field" id="%s" name="filters[_field_%d]" placeholder="%s" value="" />',
						$id,
						absint( $field->getFieldId() ),
						esc_attr( $field->getPlaceholder() )
					);
					break;
			}
		}

		return $field_html;
	}

	public static function get_favourites_link( $post_id ) {
		$has_favourites = get_option( 'rtcl_general_settings' );
		if ( isset( $has_favourites['has_favourites'] ) && 'yes' !== $has_favourites['has_favourites'] ) {
			return;
		}
		if ( is_user_logged_in() ) {
			if ( $post_id == 0 ) {
				global $post;
				$post_id = $post->ID;
			}

			$favourites = (array) get_user_meta( get_current_user_id(), 'rtcl_favourites', true );

			if ( in_array( $post_id, $favourites ) ) {
				return '<a href="javascript:void(0)" class="rtcl-favourites rtcl-active" data-id="' . $post_id . '"><span class="rtcl-icon rtcl-icon-heart"></span><span class="favourite-label">'
				       . \Rtcl\Helpers\Text::remove_from_favourite() . '</span></a>';
			} else {
				return '<a href="javascript:void(0)" class="rtcl-favourites" data-id="' . $post_id . '"><i class="rtcl-icon rtcl-icon-heart-empty"></i><span class="favourite-label">'
				       . \Rtcl\Helpers\Text::add_to_favourite() . '</span></a>';
			}
		} else {
			return '<a href="javascript:void(0)" class="rtcl-require-login"><i class="rtcl-icon rtcl-icon-heart-empty"></i><span class="favourite-label">' . \Rtcl\Helpers\Text::add_to_favourite()
			       . '</span></a>';
		}
	}

	public function delete_listing_logo_attachment() {
		if ( $_POST['attachment_id'] && $_POST['post_id'] ) {
			delete_post_meta( $_POST['post_id'], 'listing_logo_img' );
			wp_delete_attachment( $_POST['attachment_id'] );
			echo 'success';
		} else {
			echo 'error';
		}
		wp_die();
	}

	//Extra Form Field Added

	public function listing_form_save( $listing, $type, $cat_id, $new_listing_status, $data ) {
		$files = $data['files'];

		$logoImage = $files['listing_logo_img'];

		$raw_data = $data['data'];

		if ( ! empty( $logoImage ) && empty( $_POST['logo_attachment_id'] ) ) {
			$logoID = $this->get_listing_attachment_id( $listing->get_id(), $logoImage );
		} elseif ( isset( $_POST['logo_attachment_id'] ) ) {
			$logoID = $_POST['logo_attachment_id'];
		}

		if ( $logoID ) {
			update_post_meta( $listing->get_id(), 'listing_logo_img', $logoID );
		}

		// Food Menu
		$sanitized_data = [];
		if ( isset( $_POST['cldirectory_food_list'] ) ) {
			$raw_data = $_POST['cldirectory_food_list'];
			$count    = 0;

			$food_images = [];
			if ( ! empty( $files['cldirectory_food_images'] ) ) {
				$attachmentData = $files['cldirectory_food_images'];
				foreach ( $attachmentData as $file_key => $images ) {
					foreach ( $images as $key => $values ) {
						foreach ( $values as $food_list ) {
							foreach ( $food_list as $index => $name ) {
								$food_images[ $key ][ $index ][ $file_key ] = $name;
							}
						}
					}
				}
			}

			foreach ( $raw_data as $group_no => $foods_group ) {
				$foods_menu = [];

				foreach ( $foods_group as $key => $value ) {

					if ( $key === 'gtitle' ) {
						$foods_menu[ $key ] = sanitize_text_field( $foods_group['gtitle'] );
					}

					if ( $key === 'food_list' ) {
						foreach ( $foods_group['food_list'] as $index => $data ) {

							foreach ( $data as $data_key => $value ) {

								$attach_id = 0;

								if ( $data_key === 'title' || $data_key === 'foodprice' ) {
									$foods_menu[ $key ][ $index ][ $data_key ] = sanitize_text_field( $value );
								} elseif ( $data_key === 'description' ) {
									$foods_menu[ $key ][ $index ][ $data_key ] = sanitize_textarea_field( $value );
								} elseif ( $data_key === 'attachment_id' ) {
									$attach_id = $value;
								}

								if ( ! empty( $food_images[ $group_no ][ $index ] ) && empty( $attach_id ) ) {
									$attach_id = $this->get_listing_attachment_id( $listing->get_id(), $food_images[ $group_no ][ $index ] );
								}

								if ( ! empty( $attach_id ) ) {
									$foods_menu[ $key ][ $index ]['attachment_id'] = $attach_id;
								}

							}
						}

					}

				}

				if ( ! empty( $foods_menu ) ) {
					$sanitized_data[] = $foods_menu;
				}
			}


		}

		if ( empty( $sanitized_data ) ) {
			delete_post_meta( $listing->get_id(), 'cldirectory_food_list' );

		} else {
			update_post_meta( $listing->get_id(), 'cldirectory_food_list', $sanitized_data );
		}


	}

	public function get_listing_attachment_id( $post_id, $file ) {
		if ( $file['error'] !== UPLOAD_ERR_OK ) {
			__return_false();
		}

		if ( ! function_exists( 'wp_generate_attachment_metadata' ) ) {
			get_template_part( ABSPATH . "/wp-admin" . '/includes/image.php' );
			get_template_part( ABSPATH . "/wp-admin" . '/includes/file.php' );
			get_template_part( ABSPATH . "/wp-admin" . '/includes/media.php' );
		}

		Filters::beforeUpload();
		// you can use WP's wp_handle_upload() function:
		$status = wp_handle_upload(
			$file,
			[
				'test_form' => false,
			]
		);
		Filters::afterUpload();

		if ( $status && ! isset( $status['error'] ) ) {
			// $filename should be the path to a file in the upload directory.
			$filename = $status['file'];

			// Check the type of tile. We'll use this as the 'post_mime_type'.
			$filetype = wp_check_filetype( basename( $filename ), null );

			// Get the path to the upload directory.
			$wp_upload_dir = wp_upload_dir();

			// Prepare an array of post data for the attachment.
			$attachment = [
				'guid'           => $wp_upload_dir['url'] . '/' . basename( $filename ),
				'post_mime_type' => $filetype['type'],
				'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $filename ) ),
				'post_content'   => '',
				'post_status'    => 'inherit',
			];
			// Insert the attachment.
			$attach_id = wp_insert_attachment( $attachment, $filename );
		}

		return isset( $attach_id ) ? $attach_id : 0;
	}

	public static function add_single_listing_custom_field() {
		global $listing;
		$listing->the_custom_fields();
	}


	//listing category icon

	public static function cldirectory_cat_icon( $term_id, $icon_type = null ) {
		$cat_img  = $cat_icon = $icon = null;
		$image_id = get_term_meta( $term_id, '_rtcl_image', true );
		if ( $image_id ) {
			$image_attributes = wp_get_attachment_image_src( (int) $image_id, 'medium' );
			$image            = $image_attributes[0];
			if ( '' !== $image ) {
				$cat_img = sprintf( '<img src="%s" class="rtcl-cat-img" alt="%s"/>', $image, esc_attr__( 'Category Image', 'cldirectory' ) );
			}
		}
		$icon_id = get_term_meta( $term_id, '_rtcl_icon', true );
		if ( $icon_id ) {
			$cat_icon = sprintf( '<span class="rtcl-cat-icon rtcl-icon rtcl-icon-%s"></span>', $icon_id );
		}

		$icon = $icon_type == 'icon' ? $cat_icon : $cat_img;

		return $icon;
	}

	// listing details slider layout

	public static function listing_details_slider() {
		global $listing;
		$images      = $listing->get_images();
		$slider_data = [
			'allowSlideNext' => true,
			'allowSlidePrev' => true,
			"loop"           => false,
			'autoplay'       => array(
				'delay' => 3000,
			),
			'auto'           => false,
			"speed"          => 1000,
			"spaceBetween"   => 0,
			'breakpoints'    => array(
				'0'    => array( 'slidesPerView' => 1 ),
				'576'  => array( 'slidesPerView' => 1 ),
				'768'  => array( 'slidesPerView' => 1 ),
				'992'  => array( 'slidesPerView' => 1 ),
				'1200' => array( 'slidesPerView' => 1 ),
				'1600' => array( 'slidesPerView' => 1 )
			),
		];
		if ( is_rtl() ) {
			$slider_data['rtl'] = true;
		}
		$data['slider_data'] = json_encode( $slider_data );
		$total_gallery_item  = count( $images );
		if ( $total_gallery_item ) {
			$section_class = $total_gallery_item > 1 ? "details-slider-wrap" : "details-feature-image-wrap";
			?>
            <div class="<?php echo esc_attr( $section_class ); ?> single-details-thumb">
				<?php if ( $total_gallery_item >= 3 && Functions::is_gallery_slider_enabled() ) { ?>
                    <div class="rt-global-slider" data-options="<?php echo esc_attr( $data['slider_data'] ); ?>">
                        <div class="swiper-wrapper">
							<?php
							foreach ( $images as $index => $image ) :
								$img_url = wp_get_attachment_image_url( $image->ID, $size = 'rtcl-gallery' );
								?>
                                <div class="swiper-slide  photoswip-item">
                                    <div class="slide-item">
                                        <a href="<?php echo esc_url( $img_url ); ?>">
											<?php echo wp_get_attachment_image( $image->ID, 'rtcl-gallery' ); ?>
                                        </a>
                                    </div>
                                </div>
							<?php endforeach;
							?>
                        </div>
                        <div class="el-swiper-pagination"></div>
                    </div>
				<?php } else if ( $total_gallery_item > 1 ) { ?>
                    <div class="row no-gutters">
						<?php foreach ( $images as $index => $image ) {
							$img_url = wp_get_attachment_image_url( $image->ID, $size = 'full' );
							?>
                            <div class="col-md-6 image-fit">
                                <div class="swiper-slide nav-item photoswip-item">
                                    <a href="<?php echo esc_url( $img_url ); ?>">
										<?php echo wp_get_attachment_image( $image->ID, 'rtcl-gallery' ); ?>
                                    </a>
                                </div>
                            </div>
						<?php } ?>
                    </div>
				<?php } else { ?>
                    <div class="row">
						<?php foreach ( $images as $index => $image ) { ?>
                            <div class="col-md-12 image-fit-full">
								<?php echo wp_get_attachment_image( $image->ID, 'full' ); ?>
                            </div>
						<?php } ?>
                    </div>
				<?php } ?>
            </div>
		<?php }
	}

	// listing details banner layout

	public static function listing_details_banner() {
		global $listing;
		$imgUrl = get_the_post_thumbnail_url( $listing->get_id(), 'full' );
		if ( empty( $imgUrl ) ) {
			$images = $listing->get_images();
			if ( ! empty( $images ) ) {
				$total_gallery_image = count( $images );
				$total_gallery_item  = $total_gallery_image;
				if ( $total_gallery_item ) {
					foreach ( $images as $index => $image ) {
						$img_url = wp_get_attachment_image_url( $image->ID, 'full' );
					}
				}
			} else {
				$img_url = '';
			}
		} elseif ( ! empty( $imgUrl ) ) {
			$img_url = $imgUrl;
		} else {
			$img_url = '';
		}
		//Inner Page Banner Area Start Here
		?>
        <div class="inner-page-banner1 bg-common inner-page-top-margin"
             data-bg-image="<?php echo esc_url( $img_url ); ?>">
            <div class="heading-wrapper">
                <div class="container">
                    <!--Listing Heading-->
					<?php Helper::get_custom_listing_template( 'listing-heading' ); ?>
                    <!--End Listing Heading-->
                </div>
            </div>

        </div>

	<?php }


	// category slug
	public static function cldirectory_selected_category( $category_id ) {
		$to_get_slug = get_term( $category_id, 'rtcl_category' );

		$parent_id = $to_get_slug->parent;

		if ( ! empty( $parent_id ) ) {
			$to_get_slug = get_term( $parent_id, 'rtcl_category' );
		} else {
			$to_get_slug = get_term( $category_id, 'rtcl_category' );
		}
		$cat_slug = $to_get_slug->slug;

		return $cat_slug;
	}

	// enable restaurant listing function
	public static function is_enable_restaurant_listing() {
		return Functions::get_option_item( 'rtcl_general_settings', 'enable_restaurant_listing', false, 'checkbox' );
	}

	// delete food listing image
	public function delete_food_attachment() {
		if ( $_POST['attachment_id'] && $_POST['post_id'] ) {
			delete_post_meta( $_POST['post_id'], 'cldirectory_food_list' );
			wp_delete_attachment( $_POST['attachment_id'] );
			echo 'success';
		} else {
			echo 'error';
		}
		wp_die();
	}

	// all roles
	public static function get_all_roles() {
		$all_roles = [];
		$roles     = get_editable_roles();
		foreach ( $roles as $role => $roledetails ) {
			$all_roles[ $role ] = $roledetails['name'];
		}
		return $all_roles;
	}

	public static function check_custom_group_field_set() {
		global $listing;
		$groups_id        = isset( RDTheme::$options['custom_fields_group_types'] ) ? RDTheme::$options['custom_fields_group_types'] : [];
		$groups_id        = array_filter( $groups_id );
		$fields_available = false;
		if ( ! empty( $groups_id ) ) {
			foreach ( $groups_id as $group_id ) {
				$atts = [
					'group_ids' => $group_id
				];

				if ( $group_id ) {
					$field_ids = Functions::get_cf_ids( $atts );
				}
				foreach ( $field_ids as $single_field ) {
					$field = new RtclCFGField( $single_field );
					$value = $field->getFormattedCustomFieldValue( $listing->get_id() );
					if ( $value ) {
						return true;
					}
				}
			}

			return $fields_available;
		}
	}

	public static function check_fb_custom_group_field_set() {
		global $listing;
		$fields_available = false;
		$form             = $listing->getForm();
		if ( $form ) {
			$fields = $form->getFieldAsGroup( FBField::CUSTOM );
			if ( count( $fields ) ) {
				foreach ( $fields as $fieldName => $field ) {
					$field = new FBField( $field );
					$value = $field->getFormattedCustomFieldValue( $listing->get_id() );
					if ( ! empty( $value ) ) {
						return true;
					}
				}
			}
		}

		return $fields_available;
	}

	/**
	 * @param Listing $listing
	 * @param $phone
	 * @param $whatsapp_number
	 */
	public static function cldirectory_seller_phone_number( $listing, $number ) {
		if ( is_a( $listing, Listing::class ) ) {
			$phone = $number;
			if ( $phone ) {
				$mobileClass   = wp_is_mobile() ? " rtcl-mobile" : null;
				$phone_options = [];
				if ( $phone ) {
					$phone_options = [
						'safe_phone'   => mb_substr( $phone, 0, mb_strlen( $phone ) - 3 ) . apply_filters( 'rtcl_phone_number_placeholder', 'XXX' ),
						'phone_hidden' => mb_substr( $phone, - 3 )
					];
				}

				$phone_options = apply_filters( 'rtcl_phone_number_options', $phone_options, [
					'phone'           => $phone
				] )
				?>
                <div class='rtcl-list-group-item reveal-phone<?php echo esc_attr( $mobileClass ); ?>'
                     data-options="<?php
				     // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				     echo htmlspecialchars( wp_json_encode( $phone_options ) ); ?>"
                     data-id="<?php echo esc_attr( $listing->get_id() ); ?>">
                    <div class='media'>
                        <div class='media-body'>
                            <div class='numbers'>
                                <?php
                                    if ( $phone ) {
                                        echo esc_html( $phone_options['safe_phone'] );
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
				<?php
			}
		}
	}

	/**
	 * @param Listing $listing
	 * @param $phone
	 * @param $whatsapp_number
	 */
	public static function cldirectory_seller_whatsapp_number( $listing, $number ) {
		if ( is_a( $listing, Listing::class ) ) {
			$whatsapp_number = $number;
			if ( $whatsapp_number && ! Functions::is_field_disabled( 'whatsapp_number' ) ) {
				$mobileClass   = wp_is_mobile() ? " rtcl-mobile" : null;
				$whatsapp_options = [];
				if ( $whatsapp_number && ! Functions::is_field_disabled( 'whatsapp_number' ) ) {
					$whatsapp_options['safe_whatsapp_number'] = mb_substr( $whatsapp_number, 0, mb_strlen( $whatsapp_number ) - 3 )
					                                            . apply_filters( 'rtcl_phone_number_placeholder', 'XXX' );
					$whatsapp_options['whatsapp_hidden']      = mb_substr( $whatsapp_number, - 3 );
				}
				$whatsapp_options = apply_filters( 'rtcl_phone_number_options', $whatsapp_options, [
					'whatsapp_number' => $whatsapp_number
				] )
				?>
                <div class='rtcl-list-group-item reveal-phone<?php echo esc_attr( $mobileClass ); ?>'
                     data-options="<?php
				     // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
				     echo htmlspecialchars( wp_json_encode( $whatsapp_options ) ); ?>"
                     data-id="<?php echo esc_attr( $listing->get_id() ); ?>">
                    <div class='media'>
                        <div class='media-body'>
                            <div class='numbers'>
                                <?php
                                    if ( $whatsapp_number ) {
                                        echo esc_html( $whatsapp_options['safe_whatsapp_number'] );
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
				<?php
			}
		}
	}
}

Listing_Functions::instance();