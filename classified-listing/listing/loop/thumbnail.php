<?php
/**
 * Listing Thumbnail
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use radiustheme\CLDirectory\Helper;
use Rtcl\Helpers\Functions;
use RtclPro\Helpers\Fns;
use radiustheme\CLDirectory\Listing_Functions;

global $listing;

if ( isset( $_GET['view'] ) && in_array( $_GET['view'], [ 'grid', 'list' ], true ) ) {
	$view = esc_attr( $_GET['view'] );
} else {
	$view = Functions::get_option_item( 'rtcl_archive_listing_settings', 'default_view', 'list' );
}

?>
<div class="listing-thumb">
	<div class="listing-thumb-inner">
		<?php if('grid'==$view){ 
			Helper::get_formated_business_hour($listing);
		} 
		if('list'==$view){ ?>
			<?php Helper::get_listing_category($listing); ?>
		<?php }
		$images = Functions::get_listing_images( $listing->get_id() );  
		foreach ( $images as $index => $image ): ?>
			<a href="<?php the_permalink(); ?>" class="rtcl-media grid-view-img">
				<?php echo wp_get_attachment_image( $image->ID, 'rtcl-thumbnail' ); ?>
			</a>
		<?php 
			break;
		endforeach;
		
		if ( $listing && Fns::is_enable_mark_as_sold() && Fns::is_mark_as_sold( $listing->get_id() ) ) {
			echo wp_kses_post( '<span class="rtcl-sold-out">' . apply_filters( 'rtcl_sold_out_banner_text', esc_html__( "Sold Out", 'cldirectory' ) ) . '</span>' );
		}
		?>
		<div class="listing-action-items">
			<?php if('grid'==$view){ 
				Helper::get_listing_author_info( $listing,false); 
			} ?>
			<div class="cldirectory-listing-action">
				<?php if ( Fns::is_enable_compare() ) {
					$compare_ids    = ! empty( $_SESSION['rtcl_compare_ids'] ) ? $_SESSION['rtcl_compare_ids'] : [];
					$selected_class = '';
					if ( is_array( $compare_ids ) && in_array( $listing->get_id(), $compare_ids ) ) {
						$selected_class = ' selected';
					}
					?>
					<a class="rtcl-compare <?php echo esc_attr( $selected_class ); ?>" 
					data-bs-toggle="tooltip" data-bs-placement="top" data-bs-trigger="hover" title="<?php esc_attr_e( "Compare", "cldirectory" ) ?>" href="#" data-listing_id="<?php echo absint( $listing->get_id() ) ?>">
						<i class="compare-cl-icon"></i>
					</a>
				<?php } ?>
				<span data-bs-toggle="tooltip" data-bs-placement="top" data-bs-trigger="hover" title="<?php esc_attr_e( "Add to Favourite", "cldirectory" ) ?>">
					<?php echo wp_kses_post( Listing_Functions::get_favourites_link( $listing->get_id() ) ); ?>
				</span>
				<?php if ( Fns::is_enable_quick_view() ) { ?>
					<div class="rtcl-quick-view rtcl-btn" data-bs-toggle="tooltip" data-bs-placement="top" data-bs-trigger="hover" title="<?php esc_attr_e( "Quick View", "cldirectory" ) ?>"
					data-listing_id="<?php echo absint( $listing->get_id() ) ?>"><i
							class="eye-alt-cl-icon"></i></div>
				<?php } ?>			
			</div>
		</div>
	</div>
</div>
