<?php
/**
 * Content wrappers
 *
 * @package     ClassifiedListing/Templates
 * @version     1.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

use Rtcl\Helpers\Functions;
use radiustheme\CLDirectory\RDTheme;

$templateClass = 'content-area';
if ( Functions::is_listing() ) {
	$templateClass .= ' single-listing';
}
?>

<div id="primary" class="<?php echo esc_attr( $templateClass ); ?>">