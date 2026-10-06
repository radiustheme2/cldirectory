<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\CLDirectory;

if ( ! defined( 'ABSPATH' ) ) exit;

use Rtcl\Helpers\Functions;
use RtclPro\Helpers\Fns;
use Rtrs\Models\Review;
use Rtcl\Helpers\Link;
use Rtcl\Controllers\BusinessHoursController as BHS;
use Rtcl\Controllers\BusinessHoursController;

class Helper {

	public static function has_sidebar() {
		return ( self::has_full_width() ) ? false : true;
	}
	
	public static function has_full_width() {
		$theme_option_full_width = ( RDTheme::$layout == 'full-width' ) ? true : false;
		$not_active_sidebar      = ! is_active_sidebar( 'sidebar' );
		$bool                    = $theme_option_full_width || $not_active_sidebar;

		return $bool;
	}

	public static function the_layout_class() {
	
		$fullwidth_col = ( RDTheme::$options['blog_style'] == 'style1' && is_home() ) ? 'col-sm-10 offset-sm-1 col-12' : 'col-sm-12 col-12';

		$layout_class = self::has_sidebar() ? 'col-lg-9 col-sm-12 col-12' : $fullwidth_col;

		if ( RDTheme::$layout == 'left-sidebar' ) {
			$layout_class .= ' order-lg-2';
		} else {
			$layout_class .= ' order-lg-1';
        }
		echo esc_attr( apply_filters( 'cldirectory_layout_class', $layout_class ) );
	}

	public static function the_sidebar_class() {
		$sidebar_class = self::has_sidebar() ? 'col-lg-3 col-sm-12 sidebar-break-lg' : 'col-sm-12 col-12';
        $sidebar_class .= is_post_type_archive('rtcl_listing') ? ' listing-archive-sidebar' : ' ';
		if ( RDTheme::$layout == 'left-sidebar' ) {
			$sidebar_class .= ' order-lg-1';
		} else {
			$sidebar_class .= ' order-lg-2';
        }
		echo esc_attr( apply_filters( 'cldirectory_sidebar_class', $sidebar_class ) );
	}

	public static function comments_callback( $comment, $args, $depth ) {
		$args2 = get_defined_vars();
		Helper::get_template_part( 'template-parts/comments-callback', $args2 );
	}

	public static function requires( $filename, $dir = false ) {
		if ( $dir ) {
			$child_file = get_stylesheet_directory() . '/' . $dir . '/' . $filename;

			if ( file_exists( $child_file ) ) {
				$file = $child_file;
			} else {
				$file = get_template_directory() . '/' . $dir . '/' . $filename;
			}
		} else {
			$child_file = get_stylesheet_directory() . '/inc/' . $filename;

			if ( file_exists( $child_file ) ) {
				$file = $child_file;
			} else {
				$file = Constants::$theme_inc_dir . $filename;
			}
		}
		if ( file_exists( $file ) ) {
			require_once $file;
		} else {
			return false;
		}
	}

	public static function get_file( $path ) {
		$file = get_stylesheet_directory_uri() . $path;
		if ( ! file_exists( $file ) ) {
			$file = get_template_directory_uri() . $path;
		}

		return $file;
	}

	public static function get_img( $filename ) {
		$path = '/assets/img/' . $filename;

		return self::get_file( $path );
	}

	public static function get_css( $filename ) {
		$path = '/assets/css/' . $filename . '.css';

		return self::get_file( $path );
	}
	public static function custom_font_css($filename){
		$path = '/assets/custom-icons/css/' . $filename . '.css';

		return self::get_file( $path );
	}
	public static function get_maybe_rtl_css( $filename ) {
		if ( is_rtl() ) {
			$path = '/assets/css-rtl/' . $filename . '.css';

			return self::get_file( $path );
		} else {
			return self::get_css( $filename );
		}
	}

	public static function get_rtl_css( $filename ) {
		$path = '/assets/css-rtl/' . $filename . '.css';

		return self::get_file( $path );
	}

	public static function get_js( $filename ) {
		$path = '/assets/js/' . $filename . '.js';

		return self::get_file( $path );
	}

	public static function get_template_part( $template, $args = [] ) {
		extract( $args );
		$template = '/' . $template . '.php';
		if ( file_exists( get_stylesheet_directory() . $template ) ) {
			$file = get_stylesheet_directory() . $template;
		} else {
			$file = get_template_directory() . $template;
		}
		if ( file_exists( $file ) ) {
			require $file;
		} else {
			return false;
		}
	}

	/**
	 * Get all sidebar list
	 *
	 * @return array
	 */
	public static function custom_sidebar_fields(): array {
		$base                                      = 'cldirectory';
		$sidebar_fields                            = [];
		$sidebar_fields['sidebar']                 = esc_html__( 'Sidebar', 'cldirectory' );
		$sidebar_fields['listing-archive-sidebar'] = esc_html__( 'Listing Archive Sidebar', 'cldirectory' );
		$sidebar_fields['store-sidebar']           = esc_html__( 'Store/Store Sidebar', 'cldirectory' );
		$sidebar_fields['listing-author-sidebar']           = esc_html__( 'Author Sidebar', 'cldirectory' );
		if ( class_exists( 'WooCommerce' ) ) {
			$sidebar_fields['woocommerce-archive-sidebar'] = esc_html__( 'WooCommerce Archive Sidebar', 'cldirectory' );
			$sidebar_fields['woocommerce-single-sidebar']  = esc_html__( 'WooCommerce Single Sidebar', 'cldirectory' );
		}
		$sidebars = get_option( "{$base}_custom_sidebars", [] );
		
		if ( $sidebars ) {
			foreach ( $sidebars as $sidebar ) {
				$sidebar_fields[ $sidebar['id'] ] = $sidebar['name'];
			}
		}
 
		return $sidebar_fields;
	}

	/**
	 * Get site header list
	 *
	 * @param  string  $return_type
	 *
	 * @return array
	 */
	public static function get_cldirectory_header_list( $return_type = '' ): array {
		if ( 'header' === $return_type ) {
			return [
				'1' => [
					'image' => trailingslashit( get_template_directory_uri() ) . 'assets/img/header-1.png',
					'name'  => __( 'Style 1', 'cldirectory' ),
				],
				'2' => [
					'image' => trailingslashit( get_template_directory_uri() ) . 'assets/img/header-2.png',
					'name'  => __( 'Style 2', 'cldirectory' ),
				],
			];
		} else {
			return [
				'default' => esc_html__( 'Default', 'cldirectory' ),
				'1'       => esc_html__( 'Layout 1', 'cldirectory' ),
			];
		}
	}

	public static function get_custom_listing_template( $template, $echo = true, $args = [] ) {

		$template = 'classified-listing/custom/' . $template;

		if ( $echo ) {
			self::get_template_part( $template, $args );
		} else {
			$template .= '.php';

			return $template;
		}
	}

	public static function get_custom_store_template( $template, $echo = true, $args = [] ) {
		$template = 'classified-listing/store/custom/' . $template;
		if ( $echo ) {
			self::get_template_part( $template, $args );
		} else {
			$template .= '.php';

			return $template;
		}
	}

	public static function is_chat_enabled() {
		if ( RDTheme::$options['header_chat_icon'] && class_exists( 'Rtcl' ) ) {
			if ( Fns::is_enable_chat() ) {
				return true;
			}
		}

		return false;
	}

	public static function get_primary_color() {
		return apply_filters( 'rdtheme_primary_color', RDTheme::$options['primary_color'] );
	}
	public static function get_primary_dark_color(){
		return apply_filters( 'rdtheme_primary_dark_color', RDTheme::$options['primary_dark'] );
	}
	public static function get_secondary_color() {
		return apply_filters( 'rdtheme_secondary_color', RDTheme::$options['secondary_color'] );
	}

	public static function get_body_color() {
		return apply_filters( 'rdtheme_body_color', RDTheme::$options['body_color'] );
	}

	public static function wp_set_temp_query( $query ) {
		global $wp_query;
		$temp     = $wp_query;
		$wp_query = $query;

		return $temp;
	}

	public static function wp_reset_temp_query( $temp ) {
		global $wp_query;
		$wp_query = $temp;
		wp_reset_postdata();
	}
	public static function cldirectory_excerpt( $limit ) {
	    $excerpt = explode(' ', get_the_excerpt(), $limit);
	    if (count($excerpt)>=$limit) {
	        array_pop($excerpt);
	        $excerpt = implode(" ",$excerpt).'';
	    } else {
	        $excerpt = implode(" ",$excerpt);
	    }
	    $excerpt = preg_replace('`[[^]]*]`','',$excerpt);
	    return $excerpt;
	}
	public static function hex2rgb( $hex ) {
		$hex = str_replace( "#", "", $hex );
		if ( strlen( $hex ) == 3 ) {
			$r = hexdec( substr( $hex, 0, 1 ) . substr( $hex, 0, 1 ) );
			$g = hexdec( substr( $hex, 1, 1 ) . substr( $hex, 1, 1 ) );
			$b = hexdec( substr( $hex, 2, 1 ) . substr( $hex, 2, 1 ) );
		} else {
			$r = hexdec( substr( $hex, 0, 2 ) );
			$g = hexdec( substr( $hex, 2, 2 ) );
			$b = hexdec( substr( $hex, 4, 2 ) );
		}
		$rgb = "$r, $g, $b";

		return $rgb;
	}

	public static function socials() {
		$rdtheme_socials = [
			'facebook'  => [
				'icon' => 'fa-brands fa-facebook-f',
				'url'  => RDTheme::$options['facebook'],
			],
			'twitter'   => [
				'icon' => 'fa-brands fa-twitter',
				'url'  => RDTheme::$options['twitter'],
			],
			'linkedin'  => [
				'icon' => 'fa-brands fa-linkedin-in',
				'url'  => RDTheme::$options['linkedin'],
			],
			'youtube'   => [
				'icon' => 'fa-brands fa-youtube',
				'url'  => RDTheme::$options['youtube'],
			],
			'pinterest' => [
				'icon' => 'fab fa-pinterest',
				'url'  => RDTheme::$options['pinterest'],
			],
			'instagram' => [
				'icon' => 'fa-brands fa-instagram',
				'url'  => RDTheme::$options['instagram'],
			],
			'skype'     => [
				'icon' => 'fab fa-skype',
				'url'  => RDTheme::$options['skype'],
			],
		];

		return array_filter( $rdtheme_socials, [ __CLASS__, 'filter_social' ] );
	}

	public static function post_share_on_social() {
		$sharer = [];
		if ( RDTheme::$options['social_facebook'] ) {
			$sharer[] = 'facebook';
		}
		if ( RDTheme::$options['social_twitter'] ) {
			$sharer[] = 'twitter';
		}
		if ( RDTheme::$options['social_linkedin'] ) {
			$sharer[] = 'linkedin';
		}
		if ( RDTheme::$options['social_pinterest'] ) {
			$sharer[] = 'pinterest';
		}
		if ( RDTheme::$options['social_tumblr'] ) {
			$sharer[] = 'tumblr';
		}
		if ( RDTheme::$options['social_reddit'] ) {
			$sharer[] = 'reddit';
		}
		if ( RDTheme::$options['social_vk'] ) {
			$sharer[] = 'vk';
		}
		return $sharer;
	}

	public static function filter_social( $args ) {
		return ( $args['url'] != '' );
	}

	// Get user social info

	public static function get_user_social_info( $social_links ) {
		if ( count( $social_links ) < 1 && ! is_array( $social_links ) ) {
			return;
		}
		ob_start();
		?>
        <ul class="agent-social">
            <li class="social-item">
                <a href="#" class="social-hover-icon social-link">
                    <i class="fas fa-share-alt"></i>
                </a>
                <ul class="team-social-dropdown">
					<?php foreach ( $social_links as $icon ) : ?>

                        <li class="social-item">
                            <a
                                    href="<?php echo esc_html( $icon['social_link'] ) ?>"
                                    class="social-link" target="_blank"
                                    title="<?php echo esc_html( $icon['social_title'] ) ?>">
								<?php \Elementor\Icons_Manager::render_icon( $icon['social_icon'], [ 'aria-hidden' => 'true' ] ); ?>
                            </a>
                        </li>

					<?php endforeach; ?>
                </ul>
            </li>
        </ul>
		<?php
		echo wp_kses_post( ob_get_clean() );
	}

	// Time Elapsed
	public static function time_elapsed_string() {
		$ptime = get_the_time( 'U' );
		$etime = time() - $ptime;

		if ( $etime < 1 ) {
			return '0 seconds';
		}

		$a        = [
			365 * 24 * 60 * 60 => 'year',
			30 * 24 * 60 * 60  => 'month',
			24 * 60 * 60       => 'day',
			60 * 60            => 'hour',
			60                 => 'minute',
			1                  => 'second',
		];
		$a_plural = [
			'year'   => 'years',
			'month'  => 'months',
			'day'    => 'days',
			'hour'   => 'hours',
			'minute' => 'minutes',
			'second' => 'seconds',
		];

		foreach ( $a as $secs => $str ) {
			$d = $etime / $secs;
			if ( $d >= 1 ) {
				$r = round( $d );

				return $r . ' ' . ( $r > 1 ? $a_plural[ $str ] : $str ) . ' ago';
			}
		}
	}

	//Post reading time calculate
	public static function reading_time_count( $content = '', $is_zero = false ) {
		global $post;
		$post_content = $content ? $content : $post->post_content; // WordPress users only
		$word         = str_word_count( wp_strip_all_tags( strip_shortcodes( $post_content ) ) );
		$m            = floor( $word / 200 );
		$s            = floor( $word % 200 / ( 200 / 60 ) );

		if ( $is_zero && $m < 10 ) {
			$m = '0' . $m;
		}
		if ( $is_zero && $s < 10 ) {
			$s = '0' . $s;
		}

		if ( $m < 1 ) {
			return sprintf(
				// translators: %s: number of seconds.
				_n( '%s second', '%s seconds', $s, 'cldirectory' ),
				$s
			);
		}

		$text = __( 'read', 'cldirectory' );
		return sprintf(
			// translators: %s: number of minutes.
			       _n( '%s min', '%s mins', $m, 'cldirectory' ),
			       $m
		       ) . " " . $text;
	}

	// Modify Color
	public static function rt_modify_color( $hex, $steps ) {
		$steps = max( - 255, min( 255, $steps ) );
		// Format the hex color string
		$hex = str_replace( '#', '', $hex );
		if ( strlen( $hex ) == 3 ) {
			$hex = str_repeat( substr( $hex, 0, 1 ), 2 ) . str_repeat( substr( $hex, 1, 1 ), 2 ) . str_repeat( substr( $hex, 2, 1 ), 2 );
		}
		// Get decimal values
		$r = hexdec( substr( $hex, 0, 2 ) );
		$g = hexdec( substr( $hex, 2, 2 ) );
		$b = hexdec( substr( $hex, 4, 2 ) );
		// Adjust number of steps and keep it inside 0 to 255
		$r     = max( 0, min( 255, $r + $steps ) );
		$g     = max( 0, min( 255, $g + $steps ) );
		$b     = max( 0, min( 255, $b + $steps ) );
		$r_hex = str_pad( dechex( $r ), 2, '0', STR_PAD_LEFT );
		$g_hex = str_pad( dechex( $g ), 2, '0', STR_PAD_LEFT );
		$b_hex = str_pad( dechex( $b ), 2, '0', STR_PAD_LEFT );

		return '#' . $r_hex . $g_hex . $b_hex;
	}

	// Number Shorten
	public static function rt_number_shorten( $number, $precision = 3, $divisors = null ) {
		if ( $number < 1000 ) {
			return $number;
		}
		// Setup default $divisors if not provided
		if ( ! isset( $divisors ) ) {
			$divisors = [
				pow( 1000, 0 ) => '', // 1000^0 == 1
				pow( 1000, 1 ) => esc_html__( 'K', 'cldirectory' ), // Thousand
				pow( 1000, 2 ) => esc_html__( 'M', 'cldirectory' ), // Million
				pow( 1000, 3 ) => esc_html__( 'B', 'cldirectory' ), // Billion
				pow( 1000, 4 ) => esc_html__( 'T', 'cldirectory' ), // Trillion
				pow( 1000, 5 ) => esc_html__( 'Qa', 'cldirectory' ), // Quadrillion
				pow( 1000, 6 ) => esc_html__( 'Qi', 'cldirectory' ) // Quintillion
			];
		}

		// Loop through each $divisor and find the
		// lowest amount that matches
		foreach ( $divisors as $divisor => $shorthand ) {
			if ( abs( $number ) < ( $divisor * 1000 ) ) {
				// We found a match!
				break;
			}
		}

		// We found our match, or there were no matches.
		// Either way, use the last defined value for $divisor.
		return number_format( $number / $divisor, $precision ) . $shorthand;
	}

	//Custom pagination for page template
	static function cldirectory_list_posts_pagination( $query = '' ) {
		if ( ! $query ) {
			global $query;
		}
		if ( $query->max_num_pages > 1 ) :
			$big   = 999999999; // need an unlikely integer
			$items = paginate_links( [
				'base'      => str_replace( $big, '%#%', esc_url( get_pagenum_link( $big ) ) ),
				'format'    => '?paged=%#%',
				'prev_next' => true,
				'current'   => max( 1, get_query_var( 'paged' ) ),
				'total'     => $query->max_num_pages,
				'type'      => 'array',
				'prev_text' => '<i class="angle-left-cl-icon"></i>',
				'next_text' => '<i class="angle-right-cl-icon"></i>',
			] );

			$pagination = '<div class="pagination-number"><ul class="pagination clearfix"><li>';
			$pagination .= join( "</li><li>", (array) $items );
			$pagination .= "</li></ul></div>";

			return $pagination;
		endif;

		return;
	}

	/**Listing Details Page Layout */
	public static function listing_single_style() {
		$opt_layout  = ! empty( RDTheme::$options['single_listing_style'] ) ? RDTheme::$options['single_listing_style'] : '1';
		$meta_layout = get_post_meta( get_the_id(), 'listing_layout', true );

		if ( ! $meta_layout || 'default' == $meta_layout ) {
			return $opt_layout;
		} else {
			return $meta_layout;
		}
	}

	/**
	 * Get Listing author image
	 *
	 * @param       $listing
	 * @param  int  $size
	 */
	static public function get_listing_author_image( $listing, $size = 48, $default = 'Author', $args = [] ) {
		
		$owner_id   = $listing->get_owner_id();
		$pp_id      = absint( get_user_meta( $owner_id, '_rtcl_pp_id', true ) );
		if ($pp_id) {
		?>
		  
		  <?php if ( $listing->can_add_user_link() && ! is_author() ) : ?>
			<a class="directory-author-image" href="<?php echo esc_url( $listing->get_the_author_url() ); ?>">
			  <?php echo wp_get_attachment_image( $pp_id, [ $size, $size ] ); ?>
                <div class="rtin-user-item">
					<?php do_action('rtcl_after_author_meta', $listing->get_owner_id() ); ?>
                </div>
			</a>
			<?php else:
			  echo wp_get_attachment_image( $pp_id, [ $size, $size ] );
			  endif;
			?>
		 
		<?php } else { ?>
			<?php if ( $listing->can_add_user_link() && ! is_author() ) : ?>
			<a class="directory-author-image" href="<?php echo esc_url( $listing->get_the_author_url() ); ?>">
				<?php echo wp_kses_post( get_avatar( $listing->get_author_id(), $size ) ); ?>
                <div class="rtin-user-item">
					<?php do_action('rtcl_after_author_meta', $listing->get_owner_id() ); ?>
                </div>
			</a>
			<?php else:
			echo wp_kses_post( get_avatar( $listing->get_author_id(), $size ) );
			endif;
			?>
		  
		<?php }
		
	}

	/**
     * Get Cllisting thumb carousel markup
	 * @param          $listing_id
	 * @param  string  $size
	 */

	
	public static function get_feature_listing_text($listing){
		if(!$listing->is_featured()){
			return;
		}
		$label = Functions::get_option_item( 'rtcl_general_listing_label_settings', 'listing_featured_label' );
		$label = $label ?: esc_html__( "Featured", "cldirectory" );
		echo '<span class="badge rtcl-badge-featured">' . esc_html( $label ) . '</span>';
	}

	//listing listable fields

	public static function cldirectory_listing_listable_fields($listing){
		global $listing;

		$category_id = Functions::get_term_child_id_for_a_post($listing->get_categories());
								// Get custom fields
		$custom_field_ids = Functions::get_custom_field_ids($category_id);
	
		$fields = array();
		if (!empty($custom_field_ids)) {
			$args = array(
				'post_type'        => rtcl()->post_type_cf,
				'post_status'      => 'publish',
				'posts_per_page'   => -1,
				'post__in'         => $custom_field_ids,
				'orderby'          => 'menu_order',
				'order'            => 'ASC',
				'suppress_filters' => false,
				'meta_query'       => array(
					array(
						'key'     => '_listable',
						'compare' => '=',
						'value'   => 1,
					)
				)
			);
			$args = apply_filters( 'rtcl_loop_item_listable_fields', $args, $listing );
			$fields = get_posts($args);
		}
		Functions::get_template("listing/listable-fields", array(
			'fields'     => $fields,
			'listing_id' => $listing->get_id()
		), '', rtclPro()->get_plugin_template_path());
	}

	/**Listing Author Info */
	public static function get_listing_author_info($listing,$display_name=true){
		if( class_exists( Review::class ) ){
			$average_rating = Review::getAvgRatings( get_the_ID() );
			$rating_count   = Review::getTotalRatings( get_the_ID() );
		} else {
			$average_rating = $listing->get_average_rating();
			$rating_count   = $listing->get_rating_count();
			
		}
		?>
		<div class="listing-review">
			<?php 
				if($listing->can_show_user()) {
					self::get_listing_author_image( $listing ); 
				}
			?>
			<div class="cldirectory-author-info">
				<?php if($listing->can_show_user() && $display_name==true) { ?>
					<span class="directory-author-name">
						<?php if ( $listing->can_add_user_link() && ! is_author() ) : ?>
							<a class="author-link" href="<?php echo esc_url( $listing->get_the_author_url() ); ?>">
							    <?php echo esc_html( $listing->get_author_name() ); ?>
                                <div class="rtin-user-item">
		                            <?php do_action('rtcl_after_author_meta', $listing->get_owner_id() ); ?>
                                </div>
							</a>
						<?php else: ?>
							<?php echo wp_kses_post( $listing->get_author_name() ); ?>
						<?php endif; ?>
					</span>
				<?php } if ( ! empty( $rating_count ) ): ?>
					<div class="directory-ratings">
						
						<div class="rating-wrapper">
							<div class="average-rating">
								<?php $text=$rating_count > 1 ? __('(Ratings)','cldirectory'):__('(Rating)','cldirectory'); ?>
								<?php 
									echo esc_html($average_rating." ".$text);
								?>
							</div>
							<div class="item-icon">
								<?php echo wp_kses_post( Functions::get_rating_html( $average_rating, $rating_count ) ); ?>
								<span class="item-text"><?php echo wp_kses_post( apply_filters( 'cldirectory_rating_count_format', sprintf(
								// translators: %s: rating count number.
								__( '(<span>%s</span>)', 'cldirectory' ), esc_html( $rating_count ) ) ) ); ?></span>
							</div>
						</div>
						
						
					</div>
				<?php endif; ?>
			</div>
		</div>
	  <?php
	}

	// get formated business hour
	public static function get_formated_business_hour($listing){
		$listing_id = $listing->get_id();
		$allBhs = BHS::get_business_hours( $listing_id );
		if ( empty( $allBhs['bhs'] ) ) {
			return;
		}
		$business_hours   = $allBhs['bhs'];
		if(Functions::is_enable_business_hours() && ! empty( BusinessHoursController::get_business_hours( $listing->get_id() ) )){
			if (BHS::openStatus($business_hours)) {
				$onoff = '<div class="item-status status-open active"><i class="far fa-clock"></i> '. esc_html__( 'Open', 'cldirectory' ).'</div>';
			} else {
				$onoff = '<div class="item-status status-close"><i class="far fa-clock"></i> '. esc_html__( 'Close', 'cldirectory' ).'</div>';
			}
			?>
			<div class="store-open-close">
				<?php echo wp_kses_post( $onoff ); ?>
			</div>
		<?php }
	}

	// Listing Category
	public static function get_listing_category($listing){ ?>
		<div class="listing-category">
			<?php

            if ( $listing->has_category() && $listing->can_show_category() ):

				$category = $listing->get_categories();
				$category = end( $category );
				$term_id = $category->term_id;

				?>
				<a href="<?php echo esc_url( Link::get_category_page_link( $category ) ); ?>" class="category-list">
					<?php echo wp_kses_post( Listing_Functions::cldirectory_cat_icon( $term_id, 'icon' ) ); ?><?php echo esc_html( $category->name ); ?>
				</a>
			<?php endif; ?>
		</div>
		<?php 
	}

	
}