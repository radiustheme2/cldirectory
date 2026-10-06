
		
		</div>
		</div>  <!-- End wiper-wrapper -->
		<?php if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( $instance['slider_nav'] ) { ?>
			<!-- If we need navigation buttons -->
			<span class="rtcl-slider-btn button-left long-arrow-left-cl-icon"></span>
			<span class="rtcl-slider-btn button-right long-arrow-right-cl-icon"></span>
		
		<?php } ?>
		<?php if ( $instance['slider_dots'] ) { ?>
			<!-- If we need pagination -->
			<div class="rtcl-slider-pagination"></div>
		<?php } ?>
	</div>
</div>
