<?php
/**
 * new listing email notification to owner
 * This template can be overridden by copying it to yourtheme/classified-listing/emails/new-post-notification-user.php
 *
 * @var RtclEmail $email
 * @var array     $data
 * @var Listing   $listing
 * @author        RadiusTheme
 * @package       ClassifiedListing/Templates/Emails
 * @version       1.3.0
 *
 */

use Rtcl\Models\Listing;
use Rtcl\Models\RtclEmail;
use Rtcl\Helpers\Functions;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @hooked RtclEmails::email_header() Output the email header
 */
do_action( 'rtcl_email_header', $email ); ?>
    <p><?php esc_html_e( 'Hi Administrator,', 'cldirectory' ); ?></p>
    <p><?php
    // translators: %s: website/blog name.
    printf( esc_html__( 'A listing on your website %s received a message.', 'cldirectory' ), esc_html( Functions::get_blogname() ) ) ?></p>
    <p><?php
    // translators: %1$s: listing URL, %2$s: listing title.
    echo wp_kses( sprintf( __( '<strong>Listing :</strong> <a href="%1$s">%2$s</a>', 'cldirectory' ), esc_url( $listing->get_the_permalink() ), esc_html( $listing->get_the_title() ) ), [ 'strong' => [], 'a' => [ 'href' => [] ] ] ); ?></p>
    <p><?php
    // translators: %s: sender name.
    echo wp_kses( sprintf( __( '<strong>Sender name:</strong> %s', 'cldirectory' ), esc_html( $data['name'] ) ), [ 'strong' => [] ] ); ?></p>
    <p><?php
    // translators: %s: sender email address.
    echo wp_kses( sprintf( __( '<strong>Sender email:</strong> %s', 'cldirectory' ), esc_html( $data['email'] ) ), [ 'strong' => [] ] ); ?></p>
    <p><?php
    // translators: %s: sender phone number.
    echo wp_kses( sprintf( __( '<strong>Sender phone:</strong> %s', 'cldirectory' ), esc_html( $data['phone'] ) ), [ 'strong' => [] ] ); ?></p>
    <p><?php
    // translators: %s: sender message text.
    echo wp_kses( sprintf( __( '<strong>Sender message:</strong> %s', 'cldirectory' ), esc_html( $data['message'] ) ), [ 'strong' => [] ] ); ?></p>
<?php

/**
 * @hooked RtclEmails::email_footer() Output the email footer
 */
do_action( 'rtcl_email_footer', $email );
