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
 * @author       RadiusTheme
 * @package      cl-hotel/templates
 * @version      1.0.0
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Rtcl\Helpers\Functions;
use RadiusTheme\CLDirectory\Listing_Functions;

global $listing;

$address = get_post_meta( $listing->get_id(), 'address', true );
$phone = get_post_meta( $listing->get_id(), 'phone', true );
$whatsapp = get_post_meta( $listing->get_id(), '_rtcl_whatsapp_number', true );
$email = get_post_meta( $listing->get_id(), 'email', true );
$website = get_post_meta( $listing->get_id(), 'website', true );
$website2 = str_replace(['https://', 'http://'], '', $website );
echo '<pre>';
print_r('Bangladesh');
echo '</pre>';
?>
<div class="general-info">
	<div class="list-group">
		<div class="rtcl-listing-side-title">
			<h3><?php esc_html_e( 'General Information', 'cl-hotel' ); ?></h3>
		</div>
		<ul class="info-list">

			<?php if ( $address ){ ?>
				<li>
					<i class="demo-icon rt-custom-icon-rt18-marker"></i>
					<?php echo esc_html( $address ); ?>
				</li>
			<?php } if ( $phone && Functions::check_visibility( $listing->get_author_id(), 'phone' ) ){ ?>
				<li>
					<a class="rtcl-phone-link" href="tel:<?php echo esc_attr( $phone ); ?>" target="_blank">
						<i class="demo-icon rt-custom-icon-phone"></i>
						<?php echo esc_html( $phone ); ?>
					</a>
				</li>
			<?php } if ( $whatsapp && Functions::check_visibility( $listing->get_author_id(), 'whatsapp' ) ){ ?>
				<li>
					<a class="rtcl-whatsapp-link" href="https://api.whatsapp.com/send?phone=<?php echo esc_attr( $whatsapp ); ?>" target="_blank">
						<i class="fab fa-whatsapp"></i>
						<?php echo esc_html( $whatsapp ); ?>
					</a>
				</li>
			<?php } if ( $email && Functions::check_visibility( $listing->get_author_id(), 'email' ) ){ ?>
				<li>
					<a class="rtcl-mailto-link"
						href="mailto:<?php echo esc_attr( $email ); ?>" target="_blank">
						<i class="demo-icon rt-custom-icon-email-icon"></i>
						<?php echo esc_html( $email ); ?>
					</a>
				</li>
			<?php } if ( $website ){ ?>
				<li>
					<a class="rtcl-website-link" href="<?php echo esc_url( $website ); ?>" target="_blank"
						<?php echo Listing_Functions::is_external( $website ) ? ' rel="nofollow"' : ''; ?>>
						<i class="demo-icon rt-custom-icon-glob"></i>
						<?php echo esc_html( $website2 ); ?>
					</a>
				</li>
			<?php } ?>

		</ul>
	</div>
</div>




