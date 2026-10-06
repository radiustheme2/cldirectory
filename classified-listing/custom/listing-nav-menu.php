<?php

namespace radiustheme\CLDirectory;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Rtcl\Helpers\Functions;
use RtclMarketplace\Hooks\ActionHooks;

global $listing;
$video_urls = [];
if ( ! Functions::is_video_urls_disabled() ) {
	$video_urls = get_post_meta( $listing->get_id(), '_rtcl_video_urls', true );
	$video_urls = ! empty( $video_urls ) && is_array( $video_urls ) ? $video_urls : [];
}
$single_listing_style = Helper::listing_single_style();

$images=$listing->get_images();
$total_gallery_item = count( $images );

$cats_ids = $listing->get_category_ids();


foreach ($cats_ids as $key => $value) {
	$cat_id = $value;
}
$cat = Listing_Functions::cldirectory_selected_category( $cat_id );
$cat = ($cat == 'restaurant' || $cat == 'restaurants' ) ? true : false;

$foodList = get_post_meta( $listing->get_id(), "cldirectory_food_list", true );

$general_settings = Functions::get_option( 'rtcl_general_settings' );

$food_menu_list_title =$general_settings['cldirectory_food_list_section_label']  ?:__('Restaurant Menu\'s','cldirectory');

?>

<div class="container">
    <div class="listing-menu-content">
        <ul class='listing-nav-menu'>
            <?php if ( $listing->get_the_content() ) { ?>
                <li><a class="active" href="#listing-description"><?php esc_html_e('Description','cldirectory'); ?></a></li>
            <?php } ?>
            <?php if((!empty(RDTheme::$options['custom_fields_list_types']) || !empty(RDTheme::$options['custom_fields_group_types'])) && Listing_Functions::check_custom_group_field_set() && ! Listing_Functions::check_fb_custom_group_field_set()){ ?>
                <li><a href="#listing-amenites">
                    <?php
                        if (!empty(RDTheme::$options['custom_fields_tab_text'])) {
                            echo esc_html(RDTheme::$options['custom_fields_tab_text']);
                        } else {
                            esc_html_e( 'Amenities', 'cldirectory' );
                        }
                    ?>
                </a></li>
            <?php } ?>
            <?php if (  Functions::isEnableFb() && Listing_Functions::check_fb_custom_group_field_set()) { ?>
                <li><a href="#listing-fb-custom-feilds">
                    <?php
                        if (!empty(RDTheme::$options['custom_fields_tab_text'])) {
                            echo esc_html(RDTheme::$options['custom_fields_tab_text']);
                        } else {
                            esc_html_e( 'Amenities', 'cldirectory' );
                        }
                    ?>
                </a></li>
            <?php } ?>

            <?php if($total_gallery_item >=2){  ?>
                <li><a href="#listing-gallery"><?php esc_html_e('Gallery','cldirectory'); ?></a></li>
            <?php } ?>
            <?php if ( method_exists('Rtcl\Helpers\Functions','has_map') && Functions::has_map() ): ?>
                <li><a href="#map-location"><?php esc_html_e('Map Location','cldirectory'); ?></a></li>
            <?php endif; ?>

            <?php if ( ! empty( $video_urls ) ): ?>
                <li><a href="#listing-video"><?php esc_html_e('Video','cldirectory'); ?></a></li>
            <?php endif; ?>
            <?php if ( $cat == true && Listing_Functions::is_enable_restaurant_listing() && $foodList){ ?>
                <li><a href="#listing-menu-item">
                    <?php 
                        echo esc_html($food_menu_list_title);
                     ?>
                    </a>
                </li>
            <?php } ?>
            <li><a href="#comments"><?php esc_html_e('Review','cldirectory'); ?></a></li>
        </ul>
        <div class="listing-nav-right-side">
            <?php
            if ( $listing->can_show_price() && $single_listing_style ==2): ?>
                <?php $max_price=get_post_meta( $listing->get_id(), '_rtcl_max_price', true );
                    $price_type=$listing->get_pricing_type();
                    ?>
                    <?php $widget_title=$max_price && $price_type=='range' ? __('Pricing Range','cldirectory') :__('Price','cldirectory'); ?>
                    <div class="listing-price"><?php echo wp_kses_post( $listing->get_price_html() ); ?></div>
            <?php endif; ?>
            <?php
			    if ( class_exists('RtclMarketplace') ) {
				    ActionHooks::add_buy_button();
			    }
            ?>
        </div>
    </div>
</div>
