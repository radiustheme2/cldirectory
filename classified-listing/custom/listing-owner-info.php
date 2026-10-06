<?php
/**
 * @var string  $address
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
 * @author      RadiusTheme
 * @package     cl-hotel/templates
 * @version     1.0.0
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use RtclPro\Helpers\Fns;
use Rtcl\Helpers\Functions;
use RadiusTheme\CLDirectory\Helper;
use RadiusTheme\CLDirectory\Listing_Functions;

global $listing;
$ownerEmail = '';

$owner_id = $listing->get_owner_id();
$ownerEmail = $listing->get_owner_email();
$ownerPhone = get_user_meta( $owner_id, '_rtcl_phone', true );
$website = get_user_meta( $owner_id, '_rtcl_website', true );
$owmerAddress = get_user_meta( $owner_id, '_rtcl_address', true );
$ownerWhatsapp = get_user_meta( $owner_id, '_rtcl_whatsapp_number', true );
$ownerWebsite = str_replace(['https://', 'http://'], '', $website );
$ownerUrl = get_author_posts_url($owner_id);

$status = apply_filters( 'rtcl_user_offline_text', esc_html__( 'Offline Now', 'cl-hotel' ) );
if ( class_exists('RtclPro') && Fns::is_online( $listing->get_owner_id() ) ) {
	$status = apply_filters( 'rtcl_user_online_text', esc_html__( 'Online Now', 'cl-hotel' ) );
}
?>
<div class="rtcl-listing-user-info">
    <h3 class="listing-entry-inner-title"><?php esc_html_e( "Owner Information", 'cl-hotel' ); ?></h3>
    <div class="list-group">
        <div class="member-header">
            <div class="member-img">
                <a  href="<?php echo method_exists( $listing, 'get_the_author_url' ) ? esc_url( $listing->get_the_author_url() ) : '#';?>">
				    <?php Helper::get_listing_author_image( $listing,105 ); ?>
                </a>
			    <?php
			    $status = apply_filters( 'rtcl_user_offline_text', esc_html__( 'Offline Now', 'cldirectory' ) );
			    $status_class = 'offline-now';
			    if ( Fns::is_online( $listing->get_owner_id() ) ) {
				    $status = apply_filters( 'rtcl_user_online_text', esc_html__( 'Online Now', 'cldirectory' ) );
				    $status_class = 'online-now';

			    }
			    ?>
            </div>
            <div class="member-title">
                <h4>
                    <a href="<?php echo method_exists( $listing, 'get_the_author_url' ) ? esc_url( $listing->get_the_author_url() ) : '#';?>">
					    <?php echo esc_html( $listing->get_author_name() ); ?>
                        <div class="rtin-user-item">
						    <?php do_action('rtcl_after_author_meta', $listing->get_owner_id() ); ?>
                        </div>
                    </a>
                </h4>

                <div class="rtin-box-item cldirectory-user-status <?php echo esc_attr( $status_class ); ?>">
                    <span><?php echo esc_html( $status ); ?></span>
                </div>
            </div>
        </div>

        <div class="member-content">
            <ul class="info-list">
                <?php if ( $owmerAddress ){ ?>
                    <li>
                        <i class="fa-solid fa-location-dot"></i>
                        <span class="info-text"><?php echo esc_html( $owmerAddress ); ?></span>
                    </li>
                <?php } if ( $ownerEmail && Functions::check_visibility( $listing->get_author_id(), 'email' ) ){ ?>
                    <li>
                        <a class="rtcl-phone-link" href="mailto:<?php echo esc_attr( $ownerEmail ); ?>" target="_blank" title="<?php echo esc_attr( $ownerEmail ); ?>">
                            <i class="fa-regular fa-envelope"></i>
                            <span class="info-text"><?php echo esc_html( $ownerEmail ); ?></span>
                        </a>
                    </li>
                <?php } if ( $ownerPhone && Functions::check_visibility( $listing->get_author_id(), 'phone' ) ){ ?>
                    <li>
                        <a class="rtcl-phone-link" href="tel:<?php echo esc_attr( $ownerPhone ); ?>" target="_blank">
                            <i class="fa-solid fa-phone"></i>
                            <?php Listing_Functions::cldirectory_seller_phone_number( $listing, $ownerPhone ); ?>
                        </a>
                    </li>
                <?php } if ( $ownerWhatsapp && Functions::check_visibility( $listing->get_author_id(), 'whatsapp' ) ){ ?>
                    <li>
                        <a class="rtcl-whatsapp-link"
                            href="https://api.whatsapp.com/send?phone=<?php echo esc_attr( $ownerWhatsapp ); ?>" target="_blank">
                            <i class="fa-brands fa-whatsapp"></i>
                            <?php Listing_Functions::cldirectory_seller_whatsapp_number( $listing, $ownerWhatsapp ); ?>
                        </a>
                    </li>
                <?php } if ( $website ){ ?>
                    <li>
                        <a class="rtcl-website-link" href="<?php echo esc_url( $website ); ?>" target="_blank">
                            <i class="fa-solid fa-globe"></i>
                            <span class="info-text"><?php echo esc_html( $ownerWebsite ); ?></span>
                        </a>
                    </li>
                <?php } ?>
            </ul>
            <?php do_action( 'rtcl_single_listing_social_profiles' ); ?>
        </div>
    </div>
</div>
    