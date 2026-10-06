<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.3.5
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use radiustheme\CLDirectory\Helper;
use radiustheme\CLDirectory\Listing_Functions;
use radiustheme\CLDirectory\RDTheme;
use RtclPro\Helpers\Fns;
use Rtcl\Helpers\Functions;

global $listing;

$custom_field_ids = Functions::get_custom_field_ids( $listing->get_last_child_category_id() );
$video_urls       = [];
if ( ! Functions::is_video_urls_disabled() ) {
	$video_urls = get_post_meta( $listing->get_id(), '_rtcl_video_urls', true );
	$video_urls = ! empty( $video_urls ) && is_array( $video_urls ) ? $video_urls : [];
}
$hide_listing_map   = get_post_meta( get_the_ID(), 'hide_map', true );
$group_id           = isset( RDTheme::$options['custom_group_individual'] ) ? RDTheme::$options['custom_group_individual'] : 0;
$get_class_by_title = get_post_field( 'post_name', $group_id );
$get_class_by_title .= ' single-layout-2';


?>

<div id="rtcl-listing-<?php the_ID(); ?>" <?php Functions::listing_class( $get_class_by_title, $listing ); ?>>
    <div class="listing-content-top" id="listing-content-top">
        <?php Listing_Functions::listing_details_banner(); ?>
    </div>
    
    <div class="rtcl-single-nav-menu-wrapper">
        <?php Helper::get_custom_listing_template( 'listing-nav-menu'); ?>
    </div>
    
    <div class="rtcl-widget-border-enable rtcl-widget-is-sticky">
        <div class="rtcl-main-content-wrapper">
            <div class="cldirectory-content-bottom">
                <div class="container">
                    <div class="row rtcl-listing-content-area">
                        <!-- Main content -->
                        <div class="<?php echo esc_attr( implode( ' ', $content_class ) ); ?>">
                            <div class="rtcl-single-listing-details">
                                
                                <?php Helper::get_custom_listing_template( 'single-listing-accordion' ); ?>

                                <?php if ( RDTheme::$options['listing_detail_sidebar'] && $sidebar_position === "bottom" ): ?>
                                    <!-- Sidebar -->
                                    <?php do_action( 'rtcl_single_listing_sidebar' ); ?>
                                <?php endif; ?>
                                
                            </div>
                            
                        </div>

                        <?php if ( RDTheme::$options['listing_detail_sidebar'] && in_array( $sidebar_position, [ 'left', 'right' ] ) ): ?>
                            <?php do_action( 'rtcl_single_listing_sidebar' ); ?>
                        <?php endif; ?>
                    </div>
                </div>
                <!-- Related Listing -->
                <?php if ( RDTheme::$options['show_related_listing']) : ?>
                    <div class="cldirectory-related-listing">
                        <div class="container">
                            <div class="row">
                                <div class="col-12 listing-title-wrap-enable">
                                    <?php $listing->the_related_listings(); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
</div>