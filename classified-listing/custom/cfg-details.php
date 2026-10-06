<?php
/**
 * This file is for showing listing header
 *
 * @version 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use radiustheme\CLDirectory\RDTheme;
use Rtcl\Helpers\Functions;
use Rtcl\Models\RtclCFGField;

global $listing;

$groups_id = isset( RDTheme::$options['custom_fields_list_types'] ) ? RDTheme::$options['custom_fields_list_types'] : [];



$field_ids = [];

if ( ! empty( $groups_id ) ) {
	foreach ( $groups_id as $group_id ) {
		$temp_ids  = Functions::get_cf_ids_by_cfg_id( $group_id );
		$field_ids = array_merge( $field_ids, $temp_ids );
	}
}

$fieldExist = false;

if(!empty($field_ids)){
    $count=1;
    ?>
    
    <?php foreach ($field_ids as $single_field) {
        $field=new RtclCFGField($single_field);
        $value=$field->getFormattedCustomFieldValue($listing->get_id());
        $icon=$field->getIconClass() ? $field->getIconClass():' rtcl-icon-empire';
        if ( ! $value || empty( $value ) ) {
            continue;
        }
        if ( $field->getType() !== 'checkbox' && $count==1 ) {
            $fieldExist = true;
            ?>
            <div class="cldirectory-accordion-item">
                <div class="accordion-header" id="clproperty_listing_extra_features_heading">
                    <h2 class="rtcl-field-<?php echo esc_attr( $field->getType() ) ?>  mb-0">
                        <button class="btn" data-bs-toggle="collapse" data-bs-target="#clproperty_listing_extra_features-<?php echo esc_attr($count); ?>" aria-expanded="true" aria-controls="clproperty_listing_extra_features-<?php echo esc_attr($count); ?>">
                            <?php esc_html_e("Extra Features","cldirectory"); ?>
                        </button>
                    </h2>   
                </div>
                <div id="clproperty_listing_extra_features-<?php echo esc_attr($count); ?>" class="collapse accordion-collapse show" aria-labelledby="clproperty_listing_extra_features-<?php echo esc_attr($count); ?>">
                <ul  class="amenitesi-list single-cfg-list cldirectory-accordion-content">
        <?php
        }
            if($field->getType() !=='checkbox'){ 
                $count ++;
                ?>
                <li class="groups-without-checkbox id-<?php echo esc_attr( $single_field ); ?>">
                    <div class="amenities-icon">
                        <i class="rtcl-icon rtcl-icon-<?php echo esc_attr( $icon ) ?>"></i>
                    </div>
                    <div class="amenities-content">
                        <?php if ( $field->getLabel() ): ?>
                            <h3 class="heading-title rtcl-field-<?php echo esc_attr( $field->getType() ) ?>">
                                <?php echo esc_html( $field->getLabel() ); ?>
                            </h3>
                        <?php endif; ?>
                        <span class="cfp-value">
                            <?php
                                if ( ! empty( $value ) ): ?>
                                    <?php if ( $field->getType() === 'url' ):
                                        $nofollow = ! empty( $field->getNofollow() ) ? ' rel="nofollow"' : ''; ?>
                                        <a href="<?php echo esc_url( $value ); ?>"
                                            target="<?php echo esc_attr( $field->getTarget() ) ?>"
                                                    <?php echo esc_html( $nofollow ) ?>><?php echo esc_html( $field->getLabel() ) ?></a>
                                    <?php else: ?>
                                        <?php Functions::print_html( $value ); ?>
                                    <?php endif; ?>
                                <?php endif;
                            ?>
                        </span>
                    </div>
                </li>
            <?php }
    } ?>
<?php }
if ( $fieldExist ) {
	?>  
		</ul>
        </div>
	</div>
	<?php
}
?>
