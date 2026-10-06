<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\CLDirectory;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( RDTheme::$has_breadcrumb ):
	do_action( 'cldirectory_breadcrumb' );
endif;