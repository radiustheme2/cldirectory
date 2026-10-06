<?php
/**
 * Template Name: Author Archive Page
 *
 * @author  RadiusTheme
 * @since   1.0
 * @version 1.0
 */

namespace radiustheme\CLDirectory;

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Rtcl\Helpers\Functions;
use WP_User;
use WP_User_Query;

get_header();


$general_settings    = Functions::get_option( 'rtcl_general_settings' );


$roles = empty($general_settings['cldirectory_top_author_roles']) ? ['Subscriber']: $general_settings['cldirectory_top_author_roles'];


$number = $general_settings['cldirectory_top_author_per_page'];

$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;

$offset = ($paged - 1) * $number;

$users_query = new WP_User_Query(array
    (
        'number' => $number,
        'offset'=>$offset,
        'role__in'=>$roles,
        'meta_key' => '_rtcl_ads',
        'orderby' => 'meta_value',
        'order' => 'DESC',
    )
);

$total_users = $users_query->total_users;

$users=$users_query->get_results();


$total_pages = intval($total_users / $number) + 1;
?>


<?php if($users){ ?>
    <div id="primary" class="top-authors-wrapper">
            <div class="container">
                <div class="row">
                    <div  id="sticky_sidebar" class="col-lg-3 sidebar-widget">
                        <?php dynamic_sidebar('sidebar'); ?>
                    </div>
                    <div class="col-lg-9">
                        <?php foreach ( $users as $user ) {
                            $pp_id    = absint( get_user_meta( $user->ID, '_rtcl_pp_id', true ) );
                            $args=array(
                                'post_type'=>'rtcl_listing',
                                'author'=>$user->ID
                            ); 
                            $posts=get_posts($args);

                            $category=[];
                            if($posts){
                                foreach ($posts as $post) { 
                                    $post_id=$post->ID;
                                    $term=get_the_terms($post_id,'rtcl_category'); 
                                    $category[]= $term[0]->term_id;
                                }
                                $count_categories=array_count_values($category);
                            }
                            ?>
                            <div class="author">
                                <div class="author-thumb-wrapper">
                                    <div class="author-thumb">
                                        <a href="<?php echo esc_url(get_author_posts_url($user->ID)); ?>">
                                            <?php echo wp_kses_post($pp_id ? wp_get_attachment_image( $pp_id, [
                                                100,
                                                100
                                            ] ) : get_avatar( $user->ID,'100' )); ?>
                                        </a>
                                    </div>
                                    <div class="author-content">
                                        <h2 class="author-name"> <a href="<?php echo esc_url(get_author_posts_url($user->ID)); ?>"><?php echo esc_html($user->display_name); ?></a></h2>
                                        <div class="author-category">
                                            <?php 
                                                foreach ($count_categories as $cat_id => $count_cat) {
                                                    $cat=get_term_by('id',$cat_id,'rtcl_category');
                                                    //$cat_link=get_term_link($cat_id,'rtcl_category');
                                                    $listing=$count_cat >1 ? __('Listings','cldirectory'):__('Listing','cldirectory');
                                                    ?>
                                                    <div>
                                                        <span class="cat-name"><?php echo esc_html($cat->name); ?></span>
                                                        <span class="listing-count"><?php echo esc_html('('.$count_cat." ".$listing.')'); ?></span>
                                                    </div>
                                                <?php }
                                            ?>
                                        </div>
                                        <?php if($user->user_description){ ?>
                                            <p><?php echo esc_html($user->user_description); ?></p>
                                        <?php } ?>
                                    </div>
                                    <div class="action-btn">
                                        <a href="<?php echo esc_url(get_author_posts_url($user->ID)); ?>"><?php esc_html_e('See All Listings','cldirectory'); ?></a>
                                    </div>
                                </div>
                            </div>
                        <?php } ?>
                    <?php 
                        echo '<div id="pagination" class="clearfix">';
                        $current_page = max(1, get_query_var('paged'));

                        echo wp_kses_post( paginate_links( array(
                            'base'      => get_pagenum_link(1) . '%_%',
                            'format'    => 'page/%#%/',
                            'current'   => $current_page,
                            'total'     => $total_pages,
                            'prev_next' => true,
                            'prev_text'=>'<i class="angle-left-cl-icon"></i>',
                            'next_text'=>'<i class="angle-right-cl-icon"></i>',
                            'show_all'  => true,
                            'type'      => 'plain',
                        ) ) );
                    
                        echo '</div>';
                    ?>
                    </div>
                </div>
            </div>
    </div>

<?php } ?>


<?php 


?>
<?php get_footer(); 
?>