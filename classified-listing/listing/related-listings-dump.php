<?php
/**
 * @var WP_Query $rtcl_related_query
 * @var array    $slider_options
 * @version       1.0.0
 *
 * @author        RadiusTheme
 * @package       classified-listing/templates
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Rtcl\Helpers\Functions;
use radiustheme\CLDirectory\RDTheme;

if ( ! $rtcl_related_query->have_posts() ) {
	return;
}

$view = Functions::get_option_item( 'rtcl_archive_listing_settings', 'default_view', 'list' );

?>
<div class="related-listing cldirectory-listing">
    <!-- Header -->
    <div class="row">
        <div class="col-md-12">
            <div class="title-inner-wrapper">
                <h2 class="rt-section-title"><?php echo esc_html__( 'Explore Similar Listings', 'cldirectory' ); ?></h2>
                <div class="rt-heading-shape"></div>
            </div>
        </div>
    </div>

    <!-- Listing Content -->
    <div class="rtcl-related-slider-wrap rtcl-listings rtcl-<?php echo esc_attr( $view ); ?>-view">
        <div class="cldirectory-related-slider" id="cldirectory-related-slider" data-options="<?php echo esc_attr( wp_json_encode( $slider_options ) ); ?>">
            <div class="swiper-wrapper">
                <?php
                global $post;
                while ($rtcl_related_query->have_posts()):
                    $rtcl_related_query->the_post();
                    global $listing;
                    $listing = rtcl()->factory->get_listing(get_the_ID());
                    ?>
                    <div class="swiper-slide rtcl-related-slider-item">
                        <div class="listing-box listing-item rtcl-listing-item">
                            <?php do_action('rtcl_listing_loop_item_start'); ?>
                            <?php do_action( 'rtcl_listing_loop_item' ); ?>
                        </div>
                    </div>
                <?php endwhile;
                wp_reset_postdata();
                ?>
            </div>
            <?php if(RDTheme::$options['show_related_listing_navigation']){ ?>
                <div class="swiper-button-prev"><i class="fa-solid fa-chevron-left"></i></div>
                <div class="swiper-button-next"><i class="fa-solid fa-chevron-right"></i></div>
            <?php } ?>
        </div>
    </div>
</div>