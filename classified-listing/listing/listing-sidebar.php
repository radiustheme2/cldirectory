<?php
/**
 * @author        RadiusTheme
 * @package       classified-listing/templates
 * @version       1.1.4
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Rtcl\Helpers\Link;
use RtclPro\Helpers\Fns;
use Rtcl\Helpers\Functions;
use radiustheme\CLDirectory\Helper;
use radiustheme\CLDirectory\RDTheme;
use RtclStore\Helpers\Functions as StoreFunctions;


global $listing;
$sidebar_position = Functions::get_option_item( 'rtcl_single_listing_settings', 'detail_page_sidebar_position', 'right' );

$sidebar_class = [
	'col-lg-3 col-md-12 offset-lg-0',
	'order-2',
];
if ( $sidebar_position == "left" ) {
	$sidebar_class   = array_diff( $sidebar_class, [ 'order-2' ] );
	$sidebar_class[] = 'order-1';
} elseif ( $sidebar_position == "bottom" ) {
	$sidebar_class   = array_diff( $sidebar_class, [ 'col-lg-3 col-md-12 offset-lg-0 ' ] );
	$sidebar_class[] = 'rtcl-listing-bottom-sidebar';
}

?>

<!-- Seller / User Information -->
<div id="sticky_sidebar" class="<?php echo esc_attr( implode( ' ', $sidebar_class ) ); ?>">
    <div class="listing-sidebar">
	    <?php
	    if ( function_exists( 'rtrb_cl_booking_button' ) ) {
		    rtrb_cl_booking_button();
	    }
	    ?>
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

	    <?php if (Fns::registered_user_only('listing_seller_information') && !is_user_logged_in()) { } else { ?>
		    <?php if(is_user_logged_in()){ ?>
                <div class="rtcl-chat-website-link">
				    <?php
				    if ( Fns::is_enable_chat() && is_user_logged_in() ):
					    $chat_btn_class = [ 'rtcl-chat-link' ];
					    $chat_url = Link::get_my_account_page_link('chat');
					    $is_chat = 'rtcl-contact-link';
					    $chat_label = 'Live Chat';
					    if ( is_user_logged_in() && $listing->get_author_id() !== get_current_user_id() ) {
						    $chat_url   = '#';
						    $chat_label = __('Quick Chat','cldirectory');
						    $is_chat    = 'rtcl-contact-seller';
						    array_push( $chat_btn_class, 'rtcl-contact-seller' );
					    }
					    ?>
                        <span class='<?php echo esc_attr( $is_chat ); ?>'>
                            <a class="<?php echo esc_attr( implode( ' ', $chat_btn_class ) ) ?>"
                               href="<?php echo esc_url( $chat_url ) ?>" data-listing_id="<?php the_ID() ?>">
                                <i class="reply-cl-icon"></i><?php echo esc_html($chat_label ); ?>
                                <span class="rtcl-chat-unread-count"></span>
                            </a>
                        </span>
				    <?php endif; ?>
                </div>
		    <?php } ?>
	    <?php } ?>

		<?php 
			$email = get_post_meta($listing->get_id(),'email',true);
			$has_contact_form=Functions::get_option_item( 'rtcl_single_listing_settings', 'has_contact_form', false, 'checkbox' );
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
</div>

