<?php
/**
 * @var string  $phone
 * @var string  $whatsapp_number
 * @var string  $email
 * @var string  $website
 * @var array   $phone_options
 * @var bool    $has_contact_form
 * @var string  $email_to_seller_form
 * @var Listing $listing
 * @var array   $locations
 * @var int     $listing_id Listing id
 * @author        RadiusTheme
 * @package       classified-listing/templates
 * @version       1.0.0
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

global $listing;

use \radiustheme\CLDirectory\RDTheme;

?>

<div class="widget-contact-form widget">
	<?php if(RDTheme::$options['show_owner_store_title']){ ?>
		<h3 class="listing-entry-inner-title"><?php esc_html_e( "Posted By", 'cldirectory' ); ?></h3>
	<?php } ?>
	<?php do_action( 'rtcl_add_user_information', $listing_id ); ?>
	<?php do_action( 'rtcl_after_single_listing_sidebar', $listing->get_id() ); ?>
</div>


