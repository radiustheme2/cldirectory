<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Rtcl\Helpers\Functions;
use Rtcl\Models\Listing;
use RtclPro\Controllers\Hooks\TemplateHooks;
use RtclPro\Helpers\Fns;
use radiustheme\CLDirectory\Listing_Functions;
use radiustheme\CLDirectory\Helper;

$data = [
	'template'              => 'elementor/listing-ads-slider/slider-header',
	'view'                  => $view,
	'style'                 => $style,
	'instance'              => $instance,
	'the_loops'             => $the_loops,
	'default_template_path' => $default_template_path,
];
$data = apply_filters( 'rtcl_el_listing_slider_filter_data', $data );
Functions::get_template( $data['template'], $data, '', $data['default_template_path'] );

// $i = 0 ;
while ( $the_loops->have_posts() ) :

	$the_loops->the_post();
	$_id                 = get_the_ID();
	$post_meta           = get_post_meta( $_id );
	$listing             = new Listing( $_id );
	$listing_title       = null;
	$listing_meta        = null;
	$listing_description = null;
	$img                 = null;
	$labels              = null;
	$u_info              = null;
	$time                = null;
	$location            = null;
	$category            = null;
	$price               = null;
	$img_position_class  = '';
	$types               = null;
	$custom_field		 = null;
	$phone = get_post_meta( $listing->get_id(), 'phone', true );
	// $i++;
	?>

	<div <?php Functions::listing_class( [ 'rtcl-widget-listing-item listing-box listing-item rtcl-listing-item', 'listing-item swiper-slide-customize', $img_position_class ] ); ?> style="" >
		

		<?php if ( $instance['rtcl_show_image'] ) { ?>
			<div class="listing-thumb">
				<?php Helper::get_formated_business_hour($listing); ?>
				<?php 
				$image_size    = $instance['rtcl_thumb_image_size'];
				$the_thumbnail = $listing->get_the_thumbnail( $image_size );
				if($the_thumbnail){ ?>
					<a href="<?php the_permalink(); ?>" class="rtcl-media grid-view-img">
						<?php echo wp_kses_post($the_thumbnail); ?>
					</a>
				<?php }
				if ( $listing && Fns::is_enable_mark_as_sold() && Fns::is_mark_as_sold( $listing->get_id() ) ) {
					echo wp_kses_post( '<span class="rtcl-sold-out">' . apply_filters( 'rtcl_sold_out_banner_text', esc_html__( "Sold Out", 'cldirectory' ) ) . '</span>' );
				}
				?>
				<div class="listing-action-items">
					<?php  if ( $instance['rtcl_show_user'] ){
							Helper::get_listing_author_info( $listing,false); 
						}
					?>
					<div class="cldirectory-listing-action">
						<?php if ( Fns::is_enable_compare() && $instance['rtcl_show_compare']) {
							$compare_ids    = ! empty( $_SESSION['rtcl_compare_ids'] ) ? $_SESSION['rtcl_compare_ids'] : [];
							$selected_class = '';
							if ( is_array( $compare_ids ) && in_array( $listing->get_id(), $compare_ids ) ) {
								$selected_class = ' selected';
							}
							?>
							<a class="rtcl-compare <?php echo esc_attr( $selected_class ); ?>" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-trigger="hover" title="<?php esc_attr_e( "Compare", "cldirectory" ) ?>" href="#" data-listing_id="<?php echo absint( $listing->get_id() ) ?>">
								<i class="compare-cl-icon"></i>
							</a>
						<?php } ?>
						<?php if($instance['rtcl_show_favourites']){?>
								<span data-bs-toggle="tooltip" data-bs-placement="top" data-bs-trigger="hover" title="<?php esc_attr_e( "Add to Favourite", "cldirectory" ) ?>">
									<?php echo wp_kses_post( Listing_Functions::get_favourites_link( $listing->get_id() ) ); 
									?>
								</span>
							<?php }
							?>
						<?php if ( Fns::is_enable_quick_view() && $instance['rtcl_show_quick_view']) { ?>
							<div class="rtcl-quick-view rtcl-btn" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-trigger="hover" title="<?php esc_attr_e( "Quick View", "cldirectory" ) ?>"
							data-listing_id="<?php echo absint( $listing->get_id() ) ?>"><i
									class="eye-alt-cl-icon"></i></div>
						<?php } ?>			
					</div>
				</div>
			</div>
		<?php } ?>
		<div class="item-content">
			<?php if ( $instance['rtcl_show_category'] ) {
					Helper::get_listing_category( $listing );
				}
			?>
			<?php if ( $instance['rtcl_show_title'] ) {
						echo '<h3 class="' . esc_attr( apply_filters( 'rtcl_listing_loop_title_classes', 'listing-title rtcl-listing-title' ) ) . '"><a href="' . esc_url( $listing->get_the_permalink() ) . '">' . esc_html( $listing->get_the_title() ) . '</a></h3>';
					} 
			?>	
			<?php if ( $instance['rtcl_show_labels'] ) {
					$listing->the_badges();
				} 
			?>
			<ul class="entry-meta">
				<?php if ( $listing->has_location() && $instance['rtcl_show_location']): ?>
					<li><i class="map-marker-cl-icon"></i><?php $listing->the_locations(); ?></li>
				<?php endif; ?>
				<li><i class="phone-call-cl-icon"></i><a href="tel:<?php echo esc_attr($phone); ?>"><?php echo esc_html($phone); ?></a></li>
				<?php if ( $instance['rtcl_show_date'] ): ?>
					<li class="updated"><i class="far fa-clock"></i><?php $listing->the_time(); ?></li>
				<?php endif; ?>
				<?php if ( $instance['rtcl_show_views'] ): ?>
					<li class="rt-views">
						<i class="far fa-eye"></i>
						<?php
						// translators: %s: number of views.
						echo esc_html( sprintf( _n( "%s view", "%s views", $listing->get_view_counts(), 'cldirectory' ),
							number_format_i18n( $listing->get_view_counts() ) ) ); ?>
					</li>
				<?php endif; ?>
			</ul>
			<?php if ( $instance['rtcl_show_description'] ) {
					$excerpt = get_the_excerpt( $_id );
					printf(
						'<div class="listing-excerpt"> %s </div>',
						wp_kses_post(wpautop( $excerpt ))
					);
				} 
			?>	
			<?php if (rtcl()->has_pro()) {
					if (!empty($instance['rtcl_show_custom_fields'])) {
							TemplateHooks::loop_item_listable_fields();
					}
				}
			?>
			<div class="listing-footer">
				<?php if ( $instance['rtcl_show_price'] ) { ?>
					<div class="listing-price">
						<?php //Functions::get_template( 'listing/loop/price' );
						    echo wp_kses_post( $listing->get_price_html() );
                        ?>
					</div>
				<?php } ?>
			</div>
		</div>
	</div>

<?php endwhile; ?>
<?php wp_reset_postdata(); ?>


<?php

$data = [
	'template'              => 'elementor/listing-ads-slider/slider-footer',
	'view'                  => $view,
	'style'                 => $style,
	'instance'              => $instance,
	'the_loops'             => $the_loops,
	'default_template_path' => $default_template_path,
];
$data = apply_filters( 'rtcl_el_listing_slider_filter_data', $data );
Functions::get_template( $data['template'], $data, '', $data['default_template_path'] );
