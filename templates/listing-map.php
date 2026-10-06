<?php
/**
 * Template Name: Listing Map
 *
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\CLDirectory;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$map_listing_per_page=RDTheme::$options['listing_map_per_page'] ? RDTheme::$options['listing_map_per_page']:'-1';
?>
    <div id="primary" class="listing-map-page">
        <div class="container-fluid ">
			<div class="custom-row">
                <?php if (is_active_sidebar('listing-map-archive-sidebar')){?>
                    <div  id="sticky_sidebar" class="custom-column-one sidebar-widget">
                        <?php dynamic_sidebar('listing-map-archive-sidebar'); ?>
                    </div>
                <?php } ?>
				<div class="custom-column-two">
					<div class="cldirectory-listing-map-wrapper">
						<?php
                            if ( get_the_content() ) {
                                the_content();
                            } else {
                                echo do_shortcode( '[rtcl_listings map="1" paginate="true" limit="' . $map_listing_per_page . '"]' );
                            }
						?>
					</div>
				</div>
			</div>
            
        </div>
    </div>
	<?php get_footer(); ?>