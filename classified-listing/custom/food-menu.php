<?php
/**
 * This file is for showing listing header
 *
 * @version 1.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Rtcl\Helpers\Functions;
use radiustheme\CLDirectory\Listing_Functions;

global $listing;


$cats_ids = $listing->get_category_ids();

foreach ($cats_ids as $key => $value) {
	$cat_id = $value;
}


$cat = Listing_Functions::cldirectory_selected_category( $cat_id );
$cat = ($cat == 'restaurant' || $cat == 'restaurants' ) ? true : false;

if ( $cat == true && Listing_Functions::is_enable_restaurant_listing() ){
    $foodList = get_post_meta( $listing->get_id(), "cldirectory_food_list", true );
    $count=0;
    if(!empty($foodList)){
        foreach ($foodList as $food) {
            $count++; 
            $default_group_title=__('Food Menu','cldirectory');
            $group_title=$food['gtitle'] ? $food['gtitle']: $default_group_title;
            $food_list=$food['food_list'] ?? '';
            ?>
            <div class="cldirectory-accordion-item">
                <div class="accordion-header" id="clproperty_listing_food_menu_heading_<?php echo esc_attr($count); ?>">
                    <h2 class="mb-0">
                        <button class="btn" data-bs-toggle="collapse" data-bs-target="#clproperty_listing_food_menu_<?php echo esc_attr($count); ?>" aria-expanded="true" aria-controls="clproperty_listing_food_menu_<?php echo esc_attr($count); ?>">
                        <?php echo esc_html($group_title); ?>
                        </button>
                    </h2>
                </div>
                <div id="clproperty_listing_food_menu_<?php echo esc_attr($count); ?>" class="collapse accordion-collapse show" aria-labelledby="clproperty_listing_food_menu_heading_<?php echo esc_attr($count); ?>" >
                    <div class="cldirectory-accordion-content single-listing-food-menu">
                        <?php 
                            if(!empty($food_list)){
                                    foreach ($food_list as $key => $value) {
                                        $imgID = $title = $foodprice = $desc = '';
                                        if (isset($value['attachment_id'] )) {
                                            $imgID = $value['attachment_id'];
                                        }
                                        if (isset($value['title'] )) {
                                                $title = $value['title'];
                                        }
                                        if (isset($value['foodprice'] )) {
                                                $foodprice = $value['foodprice'];
                                        }
                                        if (isset($value['description'] )) {
                                                $desc = $value['description'];
                                        }
                                        $has_attachment = ! empty( $imgID ) && !empty(wp_get_attachment_image( $imgID ));
                                        ?>
                                        <figure>
                                            <?php if(!empty($has_attachment)){?>
                                                <a href="<?php echo esc_url( wp_get_attachment_image_url( $imgID, 'full' ) ); ?>" class="food-img" data-size="600x600">
                                                   <?php echo wp_kses_post(wp_get_attachment_image($imgID,'thumbnail')); ?>
                                                </a>
                                            <?php } ?>
                                            <?php if($title || $foodprice || $desc){ ?>
                                                <div class="food-info">
                                                    <?php 
                                                        if($title || $foodprice){ ?>
                                                        <div class="title-wrap">
                                                            <h3 class="food-name"><?php  echo esc_html($title); ?></h3>
                                                            <h4 class="food-price"><?php echo esc_html($foodprice);?></h4>
                                                        </div>
                                                        <?php }
                                                        if($desc){
                                                            echo '<p>' . wp_kses_post( $desc ) . '</p>';
                                                        }
                                                    ?>
                                                </div>
                                            <?php } ?>
                                        </figure>
                                   <?php }
                            }
                        ?>
                    </div>
                </div> 
            </div>  
        <?php }
    ?>
<?php }
}
