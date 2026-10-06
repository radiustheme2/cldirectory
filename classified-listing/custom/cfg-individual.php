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

$groups_id = isset( RDTheme::$options['custom_fields_group_types'] ) ? RDTheme::$options['custom_fields_group_types'] : [];


$groups_id = array_filter( $groups_id );



if ( ! empty( $groups_id ) ) {
    $count = 1;
    foreach ( $groups_id as $group_id ) { 
        
        ?>
            <?php 

            $fieldExist = false;

            $atts = [
                'group_ids' => $group_id
            ];

            if ( $group_id ) {
                $field_ids   = Functions::get_cf_ids( $atts );
            }

            foreach ( $field_ids as $single_field ) {
                $field = new RtclCFGField( $single_field );
                $value = $field->getFormattedCustomFieldValue( $listing->get_id() );
                if ( ! $value || empty( $value ) ) {
                    continue;
                }
            ?>
                <div class="cldirectory-accordion-item" id="listing-amenites">
                    <?php if ( $field->getLabel() ): ?>
                        <div class="accordion-header" id="clproperty_listing_amenites_heading">
                            <h2 class="rtcl-field-<?php echo esc_attr( $field->getType() ) ?>  mb-0">
                                <button class="btn" data-bs-toggle="collapse" data-bs-target="#clproperty_listing_amenites-<?php echo esc_attr($count); ?>" aria-expanded="true" aria-controls="clproperty_listing_amenites-<?php echo esc_attr($count); ?>">
                                    <?php echo esc_html( $field->getLabel() ); ?>
                                </button>
                            </h2>   
                        </div>
					
					<?php endif; ?>
                    <div id="clproperty_listing_amenites-<?php echo esc_attr($count); ?>" class="collapse accordion-collapse show" aria-labelledby="clproperty_listing_amenites-<?php echo esc_attr($count); ?>" >
                        <div class="cldirectory-accordion-content">
                            <?php if ( $field->getType() === 'checkbox' ) { $count ++; ?>
                                <div class="cfp-value checkbox">
                                    <?php
                                        $facilities = Functions::get_cf_data( $single_field );
            
                                        $data      = $facilities['value'];
                                        $options   = $facilities['options']['choices'];
                                        echo "<ul>";
                                        foreach ( $options as $key => $value ) {
                                            if ( in_array( $key, $data ) === true ) {
                                                echo '<li><span class="icon"><i class="fa-solid fa-circle-check"></i></span>' . esc_html( $value ) . '</li>';
                                            }
                                        }
                                        echo "</ul>";
                                        ?>
                                        <?php
                                    ?>
                                </div>
                            <?php } else { $count ++; ?>
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
                                    <?php endif; ?>
                                </span>		
                            <?php } ?>
                        </div>
                        
                    </div>
                </div>
        
        <?php }
    }
}
