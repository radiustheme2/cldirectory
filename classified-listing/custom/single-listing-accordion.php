<?php
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
$images=$listing->get_images();
$total_gallery_item = count( $images );

$hide_listing_map   = get_post_meta( get_the_ID(), 'hide_map', true );
$groups_id = isset( RDTheme::$options['custom_fields_group_types'] ) ? RDTheme::$options['custom_fields_group_types'] : [];

?>
<div class="cldirectory-listing-single-accordion"  id="clproperty_listing_single_accordion">
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
    if (  Functions::isEnableFb()){
	        $listing->custom_fields();

		    $form = $listing->getForm();
		    $menu_list = [
			    'form'       => $form,
			    'listing_id' => $listing->get_id()
		    ];
		    Helper::get_custom_listing_template( 'c-fields_food_menu', true, $menu_list );
        ?>
    <?php } else {
	    if(!empty($groups_id)){
		    Helper::get_custom_listing_template( 'cfg-individual' );
	    }
	    if(!empty(RDTheme::$options['custom_fields_list_types'])){ ?>
		    <?php Helper::get_custom_listing_template('cfg-details');; ?>
	    <?php }

	    Helper::get_custom_listing_template( 'food-menu' );
    }
    ?>

    <?php if(Helper::listing_single_style()==2 && $total_gallery_item >= 2){ ?>
        <div class="cldirectory-accordion-item" id="listing-gallery">
            <div class="accordion-header" id="clproperty_listing_gallery_heading">
                <h2 class="mb-0">
                    <button class="btn" data-bs-toggle="collapse" data-bs-target="#clproperty_listing_gallery" aria-expanded="true" aria-controls="clproperty_listing_gallery">
                    <?php esc_html_e( 'Gallery', 'cldirectory' ); ?>
                    </button>
                </h2>
            </div>
            <div id="clproperty_listing_gallery" class="collapse accordion-collapse show" aria-labelledby="clproperty_listing_gallery_heading">
                <div class="cldirectory-accordion-content">
                    <?php $listing->the_gallery(); ?>
                </div>
            </div>
        </div>
    <?php  } ?>

    <!-- Video -->
    <?php if ( ! empty( $video_urls ) ) { ?>
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

    <?php do_action( 'rtcl_single_listing_review' ); ?>

</div>