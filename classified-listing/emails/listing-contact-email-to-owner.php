<?php
/**
 * new listing email notification to owner
 * This template can be overridden by copying it to yourtheme/classified-listing/emails/new-post-notification-user.php
 *
 * @var RtclEmail $email
 * @var Listing   $listing
 * @var array     $data
 * @author        RadiusTheme
 * @package       ClassifiedListing/Templates/Emails
 * @version       1.3.0
 *
 */

use Rtcl\Models\Listing;
use Rtcl\Models\RtclEmail;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * @hooked RtclEmails::email_header() Output the email header
 */
do_action( 'rtcl_email_header', $email ); ?>
    <p><?php
    // translators: %s: listing owner name.
    printf( esc_html__( 'Hi %s,', 'cldirectory' ), esc_html( $listing->get_owner_name() ) ); ?></p>
    <p><?php
    // translators: %1$s: listing URL, %2$s: listing title.
    echo wp_kses( sprintf( __( 'You have received a reply from your listing at <a href="%1$s">%2$s</a>', 'cldirectory' ),
			esc_url( $listing->get_the_permalink() ),
			esc_html( $listing->get_the_title() ) ), [ 'a' => [ 'href' => [] ] ] ) ?></p>
    <p><?php
    // translators: %s: sender name.
    echo wp_kses( sprintf( __( '<strong>Name:</strong> %s', 'cldirectory' ), esc_html( $data['name'] ) ), [ 'strong' => [] ] ); ?></p>
    <p><?php
    // translators: %s: sender email address.
    echo wp_kses( sprintf( __( '<strong>Email:</strong> %s', 'cldirectory' ), esc_html( $data['email'] ) ), [ 'strong' => [] ] ); ?></p>
    <p><?php
    // translators: %s: sender phone number.
    echo wp_kses( sprintf( __( '<strong>Phone:</strong> %s', 'cldirectory' ), esc_html( $data['phone'] ) ), [ 'strong' => [] ] ); ?></p>
    <p><?php
    // translators: %s: sender message text.
    echo wp_kses( sprintf( __( '<strong>Message:</strong> %s', 'cldirectory' ), esc_html( $data['message'] ) ), [ 'strong' => [] ] ); ?></p>
<?php

/**
 * @hooked RtclEmails::email_footer() Output the email footer
 */
do_action( 'rtcl_email_footer', $email );
