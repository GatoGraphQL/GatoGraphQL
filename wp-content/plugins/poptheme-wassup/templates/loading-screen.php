<div class="loadinglogo">
	<?php $gd_logo = gd_logo('large') ?>
	<?php $maxwidth = $gd_logo[1] ?>
	<p>
		<img id="loading-logo" class="img-responsive" src="<?php echo $gd_logo[0] ?>">
	</p>
	<p>
		<i class="fa fa-lg fa-spinner fa-spin"></i>
		<?php 
			printf(
				'<em><strong>%s</strong>, %s</em>', 
				apply_filters('gd_loading_msg', __('Loading pure awesomeness', 'poptheme-wassup')),
				__('please wait...', 'poptheme-wassup')
			); 
		?>
	</p>
</div>