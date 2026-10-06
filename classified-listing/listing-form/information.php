<?php
/**
 * Login Form Information
 *
 * @var Listing $listing
 * @var int     $title_limit
 * @var array   $hidden_fields
 * @var string  $selected_type
 * @var string  $title
 * @var string  $price_type
 * @var string  $price
 * @var string  $post_content
 * @var string  $editor
 * @var int     $category_id
 * @var int     $post_id
 * @var int     $description_limit
 * @author        RadiusTheme
 * @package       cldirectory/templates
 * @version       1.0.0
 *
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Rtcl\Helpers\Functions;
use Rtcl\Models\Listing;
use Rtcl\Resources\Options;
use radiustheme\CLDirectory\Listing_Functions;

?>
<div class="rtcl-post-details rtcl-post-section">
    <div class="rtcl-post-listing-logo rtcl-post-section">
        <div class="rtcl-post-section-title">
            <h3><i class="rtcl-icon rtcl-icon-picture"></i><?php esc_html_e( 'Listing/Author/Company Logo', 'cldirectory' ); ?></h3>
        </div>
        
        <?php $logoID = get_post_meta( $post_id, "listing_logo_img", true ); ?>
        <?php if ( ! empty( $logoID ) ): ?>
            <div class="logo-image">
                <input name="logo_attachment_id" type="hidden" value="<?php echo esc_attr( $logoID ); ?>">
                <?php echo wp_get_attachment_image( $logoID, 'full' ); ?>
                <div class="remove-logo-image">
                    <a href="#" data-post_id="<?php echo esc_attr( $post_id ); ?>" data-attachment_id="<?php echo esc_attr( $logoID ); ?>">
                        <i class="rtcl-icon rtcl-icon-trash"></i>
                    </a>
                </div>
            </div>
        <?php endif; ?>
        <div class="logo-input-wrapper <?php echo esc_attr( $logoID ? 'd-none' : '' ); ?>">
            <input name="listing_logo_img" class="cldirectory-logo-image" type="file"/>
        </div>
    </div>

    <div class="rtcl-post-section-title">
        <h3>
            <i class="rtcl-icon rtcl-icon-picture"></i><?php esc_html_e( "Listing Information", "cldirectory" ); ?>
        </h3>
    </div>
    <div class="form-group">
        <label for="rtcl-title"><?php esc_html_e( 'Title', 'cldirectory' ); ?><span
                    class="require-star">*</span></label>
        <input type="text"
			<?php echo esc_attr( $title_limit ? 'data-max-length="3" maxlength="' . $title_limit . '"' : '' ); ?>
               class="form-control"
               value="<?php echo esc_attr( $title ); ?>"
               id="rtcl-title"
               name="title"
               required/>
		<?php
		if ( $title_limit ) {
			echo wp_kses_post( sprintf( '<div class="rtcl-hints">%s</div>',
				apply_filters( 'rtcl_listing_title_character_limit_hints',
					// translators: %s: maximum character limit number.
					sprintf( __( "Character limit <span class='target-limit'>%s</span>", 'cldirectory' ), $title_limit )
				) ) );
		}
		?>
    </div>
	<?php if ( ! in_array( 'price', $hidden_fields ) ):
		$listingPricingTypes = Options::get_listing_pricing_types();
		?>
        <div id="rtcl-pricing-wrap">
            <?php if (!Functions::is_pricing_disabled()) { ?>
                <div class="rtcl-post-section-title">
                    <h3><?php esc_html_e( "Pricing:", "cldirectory" ); ?></h3>
                </div>
                <div class="rtcl-from-group rtcl-checkbox-list rtcl-checkbox-inline rtcl-listing-pricing-types">
                    <?php
                    foreach ( $listingPricingTypes as $type_id => $type ) {
                        $checked = ( $listing_pricing === $type_id ) ? 'checked' : '';
                        ?>
                        <div class="rtcl-checkbox rtcl-listing-pricing-type">
                            <input type="radio" name="_rtcl_listing_pricing"
                                   id="_rtcl_listing_pricing_<?php echo esc_attr( $type_id ) ?>"
                                <?php echo esc_attr( $checked ); ?>
                                   value="<?php echo esc_attr( $type_id ) ?>">
                            <label for="_rtcl_listing_pricing_<?php echo esc_attr( $type_id ) ?>">
                                <?php echo esc_html( $type ); ?>
                            </label>
                        </div>
                        <?php
                    }
                    ?>
                </div>
            <?php } ?>
            <div id="rtcl-pricing-items" class="<?php echo esc_attr( 'rtcl-pricing-' . $listing_pricing ) ?>">
				<?php if ( ! Functions::is_price_type_disabled() ): ?>
                    <div class="form-group rtcl-pricing-item rtcl-from-group">
                        <label for="rtcl-price-type">
							<?php esc_html_e( 'Price Type', 'cldirectory' ); ?>
                            <span class="require-star">*</span>
                        </label>
                        <select class="form-control" id="rtcl-price-type" name="price_type">
							<?php
							$price_types = Options::get_price_types();
							foreach ( $price_types as $key => $type ) {
								$slt = $price_type == $key ? " selected" : null;
								printf( '<option value="%s"%s>%s</option>', esc_attr( $key ), ( $price_type == $key ? ' selected' : '' ), esc_html( $type ) );
							}
							?>
                        </select>
                    </div>
				<?php endif; ?>
	            <?php do_action( 'rtcl_listing_form_price_items', $listing ); ?>
                <div id="rtcl-price-items"
                     class="rtcl-pricing-item<?php echo ! Functions::is_price_type_disabled() ? ' rtcl-price-type-' . esc_attr( $price_type ) : '' ?>">
                    <div class="form-group rtcl-price-item" id="rtcl-price-wrap">
                        <div class="price-wrap">
                            <label
                                    for="rtcl-price"><?php echo wp_kses_post( sprintf( '<span class="price-label">%s [<span class="rtcl-currency-symbol">%s</span>]</span>',
					                esc_html__( "Price", 'cldirectory' ),
					                apply_filters( 'rtcl_listing_price_currency_symbol', Functions::get_currency_symbol(), $listing )
				                ) ); ?>
                                <span
                                        class="require-star">*</span></label>
                            <input type="text"
                                   class="form-control rtcl-price"
                                   value="<?php echo esc_attr( $listing ? $listing->get_price() : '' ); ?>"
                                   name="price"
                                   id="rtcl-price"<?php echo esc_attr( ! $price_type || $price_type == 'fixed' ? " required" : '' ) ?>>
                        </div>
                        <div class="price-wrap rtcl-max-price rtcl-hide">
                            <label for="rtcl-max-price"><?php echo wp_kses_post( sprintf( '<span class="price-label">%s [<span class="rtcl-currency-symbol">%s</span>]</span>',
					                esc_html__( "Max Price", 'cldirectory' ),
					                apply_filters( 'rtcl_listing_price_currency_symbol', Functions::get_currency_symbol(), $listing )
				                ) ); ?><span
                                        class="require-star">*</span></label>
                            <input type="text"
                                   class="form-control rtcl-price"
                                   value="<?php echo esc_attr( $listing ? $listing->get_max_price() : '' ); ?>"
                                   name="_rtcl_max_price"
                                   id="rtcl-max-price"<?php echo esc_attr( ! $price_type || $price_type == 'fixed' ? " required" : '' ) ?>>
                        </div>
                    </div>
	                <?php do_action( 'rtcl_listing_form_price_unit', $listing, $category_id ); ?>
                </div>
            </div>
        </div>
	<?php endif; ?>
    <div id="rtcl-custom-fields-list" data-post_id="<?php echo esc_attr( $post_id ); ?>">
		<?php do_action( 'wp_ajax_rtcl_custom_fields_listings', $post_id, $category_id ); ?>
    </div>
    <?php if ( ! in_array( 'description', $hidden_fields ) ): ?>
        <div class="form-group">
            <label for="description"><?php esc_html_e( 'Description', 'cldirectory' ); ?></label>
			<?php

			if ( 'textarea' == $editor ) { ?>
                <textarea
                        id="description"
                        name="description"
                        class="form-control"
                        <?php echo esc_attr( $description_limit ? 'maxlength="' . $description_limit . '"' : '' ); ?>
                        rows="8"><?php Functions::print_html( $post_content ); ?></textarea>
				<?php
			} else {
				wp_editor(
					$post_content,
					'description',
					[
						'media_buttons' => false,
						'editor_height' => 200,
					]
				);
			}

			if ( $description_limit ) {
				echo wp_kses_post( sprintf( '<div class="rtcl-hints">%s</div>',
					apply_filters( 'rtcl_listing_description_character_limit_hints',
						// translators: %s: maximum character limit number.
						sprintf( __( "Character limit <span class='target-limit'>%s</span>", 'cldirectory' ), $description_limit )
					) ) );
			}
			?>
        </div>
	<?php endif; ?>
    <!-- Restaurant -->
    <?php 
        $category = Listing_Functions::cldirectory_selected_category( $category_id );
        $category = ($category == 'restaurant' || $category == 'restaurants' ) ? true : false;
        if ( $category == true && Listing_Functions::is_enable_restaurant_listing() ){
            $generalSettings = Functions::get_option( 'rtcl_general_settings' );
            $sectionLabel = ! empty( $generalSettings['cldirectory_food_list_section_label'] ) ? $generalSettings['cldirectory_food_list_section_label'] : '';
        ?>
        <div class="additional-input-wrap">
            <div class="rtcl-post-section-title">
                <h3><i class="rtcl-icon rtcl-icon-food"></i><?php echo esc_html( $sectionLabel ); ?>:</h3>
            </div>
            <div class="rn-recipe-wrapper">
                <div class="additional-input-wrap group-bottom">
                    <div class="form-group row">
                        <div class="col-md-12">
                            <div class="rn-recipe-wrapper">
                                <div class="rn-recipe-wrap">
                                <?php
                                $recipes = get_post_meta( $post_id, "cldirectory_food_list", true );

                                if (!empty($recipes)) {
                                    foreach ($recipes as $key => $recipe) {
                                    
                                    ?>
                                    <div class="rn-recipe-item">
                                        <span class="rn-remove"><i class="fa fa-times"
                                        aria-hidden="true"></i></span>
                                        <div class="rn-recipe-title">
                                        <input type="text"
                                        name="cldirectory_food_list[<?php echo absint($key); ?>][gtitle]"
                                        class="form-control"
                                        value="<?php echo isset($recipe['gtitle']) ? esc_attr($recipe['gtitle']) : '' ?>"
                                        placeholder="Food Group Title">
                                        </div>
                                        <div class="rn-ingredient-wrap">
                                        <?php if (!empty($recipe['food_list'])) {
                                            foreach ($recipe['food_list'] as $ikey => $food_list) {
                                                $imgId = '';
                                                if (isset($food_list['attachment_id'])) {
                                                $imgId = $food_list['attachment_id'];
                                                }
                                            ?>
                                            <div class="rn-ingredient-item">
                                                <div class="rn-ingredient-fields">
                                                <input type="text"
                                                placeholder="Food Name"
                                                class="form-control"
                                                value="<?php echo isset($food_list['title']) ? esc_attr($food_list['title']) : '' ?>"
                                                name="cldirectory_food_list[<?php echo absint($key); ?>][food_list][<?php echo absint($ikey); ?>][title]">

                                                <input type="text"
                                                placeholder="Food Price"
                                                class="form-control"
                                                value="<?php echo isset($food_list['foodprice']) ? esc_attr($food_list['foodprice']) : '' ?>"
                                                name="cldirectory_food_list[<?php echo absint($key); ?>][food_list][<?php echo absint($ikey); ?>][foodprice]">

                                                <textarea placeholder="Description"
                                                class="form-control"
                                                name="cldirectory_food_list[<?php echo absint($key); ?>][food_list][<?php echo absint($ikey); ?>][description]"><?php echo isset($food_list['description']) ? esc_html($food_list['description']) : '' ?></textarea>
                                                </div>
                                                
                                                <div class="food-image-wrap">
                                                <?php
                                                $has_attachment = ! empty( $imgId ) && !empty(wp_get_attachment_image( $imgId ));
                                                if ($has_attachment){ ?>
                                                    <div class="food-image">

                                                    <input 
                                                        name="cldirectory_food_list[<?php echo absint($key); ?>][food_list][<?php echo absint($ikey); ?>][attachment_id]"
                                                        type="hidden" value="<?php echo esc_attr( $imgId ? $imgId : '' ); ?>">

                                                        <?php echo wp_get_attachment_image( $imgId, 'full' ); ?>

                                                    <div class="remove-food-image">
                                                        <a href="#" data-index="<?php echo absint($key); ?>" data-post_id="<?php echo esc_attr( $post_id ); ?>" data-attachment_id="<?php echo esc_attr( $imgId ); ?>"><?php esc_html_e( 'Remove Image', 'cldirectory' ); ?></a>
                                                    </div>

                                                    </div>
                                                <?php } ?>
                                                <div class="food-input-wrapper <?php echo esc_attr( $has_attachment ? 'd-none' : '' ); ?>">
                                                    <input name="cldirectory_food_images[<?php echo absint($key); ?>][food_list][<?php echo absint($ikey); ?>]" class="cldirectory-food-image" type="file"/>
                                                </div>
                                                </div>
                                                <span class="rn-remove"><i class="fa fa-times" aria-hidden="true"></i></span>
                                            </div>
                                            <?php
                                            }
                                        } ?>
                                        </div>
                                        <div class="rn-ingredient-actions">
                                        <a href="javascript:void()"
                                        class="btn-upload add-ingredient btn-sm btn-primary"><?php esc_html_e('Add New Food', 'cldirectory'); ?></a>
                                        </div>
                                    </div>
                                    <?php
                                    }
                                    }
                                ?>
                                </div>
                                <div class="rn-recipe-actions">
                                <a href="javascript:void()"
                                class="btn-upload add-recipe btn-sm btn-primary"><?php esc_html_e('Add New Foods', 'cldirectory'); ?></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>        
        <?php }
    ?>
</div>