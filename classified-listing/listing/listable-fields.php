<?php
/**
 *
 * @var array $fields
 * @var int   $listing_id
 * @version       1.0.0
 *
 * @author        RadiusTheme
 * @package       classified-listing/templates
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Rtcl\Helpers\Functions;
use Rtcl\Models\RtclCFGField;
use radiustheme\CLDirectory\RDTheme; 
if ( count( $fields ) ) :
	ob_start();
	foreach ( $fields as $field ) :

		$field = new RtclCFGField( $field->ID ); 
		$value = $field->getFormattedCustomFieldValue( $listing_id );

		if ( $value ) :
			if ('checkbox' === $field->getType()){
				$facilities = Functions::get_cf_data( $field );
				$data      = $facilities['value'];
				$options   = $facilities['options']['choices'];
				foreach ($options as $key => $check_box_value) {
					if (in_array($key, $data)) {
						echo '<li class="checked"><i class="fa-solid fa-check"></i>' . esc_html( $check_box_value ) . '</li>';
					} else {
						echo '<li class="cross"><i class="fa-solid fa-xmark"></i>' . esc_html( $check_box_value ) . '</li>';
					}
				}
			}else{
                ?>
                <li class="not-checkbox-type">
					<?php if ( $field->getIconClass() ): ?>
                        <i class='<?php echo esc_html( $field->getIconClass() ); ?>'></i>
					<?php else:
						if ('checkbox' !== $field->getType()){
							?>
                            <span class='listable-label'><?php echo esc_html( $field->getLabel().':' ); ?></span>
						<?php } ?>
					<?php endif; ?>
                    <span class='listable-value'>
                        <span class="value">
                            <?php
                            $value = strlen( $value ) == 1 ? '<span>0</span>' . $value : $value;
                            echo wp_kses_post( stripslashes_deep( $value ) );
                            ?>
                        </span>
                </span>
                </li>
            <?php }
			?>

		<?php endif;
	endforeach;

	$fields_html = ob_get_clean();
    $show_listing_custom_field = RDTheme::$options['show_listing_custom_fields'];
	if ( $fields_html && $show_listing_custom_field) {
		printf( '<div class="listable-items"><ul class="listing-features">%s</ul></div>', wp_kses_post( $fields_html ) );
	}
endif;
