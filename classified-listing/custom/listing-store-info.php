<?php
/**
 * @var int     $listing_id Listing id
 * @author       RadiusTheme
 * @package      cl-hotel/templates
 * @version      1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Rtcl\Helpers\Functions;
use RadiusTheme\CLDirectory\Listing_Functions;
use RtclStore\Helpers\Functions as StoreFunctions;

$listing = rtcl()->factory->get_listing( $listing_id );
$store = StoreFunctions::get_user_store($listing->get_owner_id());

if ( ! $store ) {
	return;
}

$storeSlogan = $store->get_the_slogan();
$storeAddress = $store->get_address();
$storeEmail = $store->get_email();
$storePhone = $store->get_phone();
$storeWhatsapp = $store->get_whatsapp();
$website = $store->get_website();
$storeWebsite = str_replace(['https://', 'http://'], '', $website );

?>

<div class="rtcl-listing-side-title">
	<h3><?php esc_html_e( "Store Information", 'cl-hotel' ); ?></h3>
</div>
<div class="list-group">
    <div class="listing-author listing-store">
        <div class="author-logo-wrapper store-information">
            <div class="author-avatar">
                <?php $store->the_logo('full'); ?>
            </div>
        </div>
        <div class="author-info-wrapper">
            <h4 class="author-name">
                <?php
                    $store->the_title();
                    do_action( 'rtcl_after_store_title', $store );
                ?>
            </h4>
            <div class="rtin-user-item">
                <?php echo esc_html( $storeSlogan ); ?>
            </div>
        </div>
    </div>
    <ul class="info-list">
        <?php if ( $storeAddress ){ ?>
            <li>
                <i class="demo-icon rt-custom-icon-home-icon"></i>
                <?php echo esc_html( $storeAddress ); ?>
            </li>
        <?php } if ( $storeEmail && Functions::check_visibility( $listing->get_author_id(), 'email' ) ){ ?>
            <li>
                <a class="rtcl-phone-link" href="mailto:<?php echo esc_attr( $storeEmail ); ?>" target="_blank">
                    <i class="envelope-cl-icon"></i>
                    <?php echo esc_html( $storeEmail ); ?>
                </a>
            </li>
        <?php } if ( $storePhone && Functions::check_visibility( $listing->get_author_id(), 'phone' ) ){ ?>
            <li>
                <a class="rtcl-phone-link" href="tel:<?php echo esc_attr( $storePhone ); ?>" target="_blank">
                    <i class="phone-call-cl-icon"></i>
                    <?php echo esc_html( $storePhone ); ?>
	                <?php Listing_Functions::cldirectory_seller_whatsapp_number( $listing, $storePhone ); ?>
                </a>
            </li>
        <?php } if ( $storeWhatsapp && Functions::check_visibility( $listing->get_author_id(), 'whatsapp' ) ){ ?>
            <li>
                <a class="rtcl-phone-link" href="tel:<?php echo esc_attr( $storeWhatsapp ); ?>" target="_blank">
                    <i class="fa-brands fa-whatsapp"></i>
			        <?php Listing_Functions::cldirectory_seller_whatsapp_number( $listing, $storeWhatsapp ); ?>
                </a>
            </li>
        <?php } if ( $website ){ ?>
            <li>
                <a class="rtcl-website-link" href="<?php echo esc_url( $website ); ?>" target="_blank"
                    <?php echo Listing_Functions::is_external( $website ) ? ' rel="nofollow"' : ''; ?>>
                    <i class="globe-cl-icon"></i>
                    <?php echo esc_html( $storeWebsite ); ?>
                </a>
            </li>
        <?php } ?>
    </ul>
</div>
    