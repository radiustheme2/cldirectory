<?php  
/**
 * Review summary summary layout one template
 * @author      RadiusTheme
 * @package     review-schema/templates/review
 * @version     1.0.0
 * 
 * @var use Rtrs\Helpers\Functions 
 * 
 */  

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

use Rtrs\Modules\Review\Helpers\ReviewFns;

?>
<div class="cldirectory-accordion-item">
    <div class="accordion-header" id="clproperty_listing_rts_summary_heading">
        <h2 class="mb-0">
            <button class="btn" data-bs-toggle="collapse" data-bs-target="#clproperty_listing_rts_summary" aria-expanded="true" aria-controls="clproperty_listing_rts_summary">
            <?php esc_html_e( 'Rating Average', 'cldirectory' ); ?>
            </button>
        </h2>
    </div>
    <div id="clproperty_listing_rts_summary" class="collapse accordion-collapse show" aria-labelledby="clproperty_listing_rts_summary_heading">    
        <div class="rtrs-summary cldirectory-accordion-content"> 
            <?php if ( $avg_rating = ReviewFns::getAvgRatings( get_the_ID() ) ) { ?>
            <div class="rtrs-summary-box">
                <div class="rtrs-rating-box">
                    <div class="rtrs-rating-number">
                        <span class="rtrs-rating"><?php echo esc_html( $avg_rating ); ?></span>
                        <span class="rtrs-rating-out"><?php esc_html_e("Out Of 5.0","cldirectory"); ?></span>
                    </div>
                    <div class="rtrs-rating-icon">
                        <?php echo wp_kses_post( ReviewFns::review_stars( $avg_rating ) ); ?>
                        <div class="rtrs-rating-text"> 
                            <?php 
                                printf(
                                    // translators: %s: total number of ratings.
                                    esc_html( _n( 'Based on %s rating', 'Based on %s ratings', $total_rating, 'cldirectory' ) ), 
                                    esc_html( $total_rating ) 
                                ); 
                            ?> 
                        </div>
                    </div>
                </div>
            </div>
            <?php } 
            
            if ( isset( $p_meta['recommendation'] ) && $p_meta['recommendation'][0] == '1' ) {
            ?>
            <div class="rtrs-summary-box">
                <div class="rtrs-rating-box">
                    <div class="rtrs-recomnded-icon">
                        <i class="rtrs-thumbs-up"></i>
                    </div>
                    <div class="rtrs-recomnded-content">
                        <span class="rtrs-recomnded-number"> 
                            <?php 
                                $total_recommended = ReviewFns::getTotalRecommendation( get_the_ID() );
                                printf(
                                    // translators: %s: number of users who recommended.
                                    wp_kses( _n( '<span>%s</span>User', '<span>%s</span>Users', $total_recommended, 'cldirectory' ), array( 'span' => array() ) ), 
                                    esc_html( $total_recommended ) 
                                ); 
                            ?> 
                        </span>
                        <p class="rtrs-recomnded-text"><?php echo esc_html_e( 'Recommended this item', 'cldirectory' ); ?></p>
                    </div>
                </div>
            </div> 
            <?php 
            } //end recommendation

            if ( isset( $p_meta['criteria'] ) && $p_meta['criteria'][0] == 'multi' ) {
            ?> 
            <div class="rtrs-summary-box rating-list">
                <div class="rtrs-progress-wrap">
                    <?php foreach( ReviewFns::getCriteriaAvgRatings( get_the_ID() ) as $value ) {
                        if ( !$value['avg'] ) continue;
                        ?>  
                        <div class="rtrs-progress">
                            <div><label><?php echo esc_html($value['title']); ?></label></div>
                            <div>
                                <progress class="rtrs-progress-bar service-preogress" value="<?php echo esc_html($value['avg']*20); ?>" max="100"></progress>
                                <span class="progress-percent"><?php echo esc_html($value['avg']); ?></span>
                            </div>
                        </div> 
                    <?php } ?>  
                </div>
            </div> 
            <?php } ?>
        </div>
    </div>
</div>