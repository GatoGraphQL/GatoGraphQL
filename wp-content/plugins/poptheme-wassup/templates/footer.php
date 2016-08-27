		<?php 

		include POPTHEME_WASSUP_TEMPLATES.'/hover.php';
		
		// Include the Theme Footer
		$vars = GD_TemplateManager_Utils::get_vars();
		include $vars['theme-path'].'/footer.php';

		// IMPORTANT: do not move the position of Operational pageSection
		// Because we have styles that place it directly after the pagesection-group (eg: .pagesection-group.active-top+.operational)
		?>
		<div class="pagesection-group-after">
			<?php
			include POPTHEME_WASSUP_TEMPLATES.'/operational.php';
			?>
		</div>
		<?php
		
		// Include the 'Modals' panel
		include POPTHEME_WASSUP_TEMPLATES.'/modals.php';

		// Include the 'Quickview' panel
		include POPTHEME_WASSUP_TEMPLATES.'/quickview.php';

		// Container
		include POPTHEME_WASSUP_TEMPLATES.'/container.php';
		?>
	</div><!--#wrapper-->

	<?php 
	// Here it will call $gd_templatemanager->output();
	wp_footer(); 

	/** Allow to include other templates. Eg: PhotoSwipe*/ 
	$extra_templates = apply_filters('pop_footer:templates', array());
	foreach ($extra_templates as $template) {

		include $template;		
	}
	?>
</body>
</html>