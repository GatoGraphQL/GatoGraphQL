<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/
 
class GD_DataLoad_FieldProcessor_Posts_Utils {

	public static function get_link_excerpt($post) {

		// The $post->post_content is already the URL
		return sprintf(
			'<em>%2$s</em><a href="%1$s">%1$s</a>',
			$post->post_content,
			__('Source: ', 'pop-coreprocessors')
		);
	}

	// public static function get_link_content($post) {

	// 	$source = self::get_link_excerpt($post);

	// 	$url = $post->post_content;
	// 	$parse = parse_url($url);
	// 	$host = $parse['host'];
	// 	$nonembeddable_message = '';
	// 	$content = sprintf(
	// 		'<p>%s</p>', 
	// 		$source
	// 	);

	// 	// Check if the source is embeddable (eg: Facebook is not)
	// 	$nonembeddable = apply_filters('GD_DataLoad_FieldProcessor_Posts_Utils:link_content:nonembeddable_hosts', array());
	// 	// if (in_array($host, $nonembeddable)) {

	// 	// 	$nonembeddable_message = sprintf(
	// 	// 		__('This website does not allow to be embedded, so please click on the <a href="%s" target="_blank">source</a> to open the page in a new tab.', 'poptheme-wassup'),
	// 	// 		$url
	// 	// 	);
	// 	// }
	// 	// // Also check if the current website uses SSL but the embedded one does not
	// 	// if (is_ssl() && $parse['scheme'] != 'https') {

	// 	// 	$nonembeddable_message = sprintf(
	// 	// 		__('For security reasons, the browser can only embed pages using HTTPS, so please click on the <a href="%s" target="_blank">source</a> to open the page in a new tab.', 'poptheme-wassup'),
	// 	// 		$url
	// 	// 	);
	// 	// }
	// 	if (in_array($host, $nonembeddable) || (is_ssl() && $parse['scheme'] != 'https')) {

	// 		$nonembeddable_message = sprintf(
	// 			__('This webpage cannot be embedded, please <a href="%s" target="_blank">click here</a> to open it in a new tab.', 'poptheme-wassup'),
	// 			$url
	// 		);
	// 	}

	// 	if ($nonembeddable_message) {

	// 		$content .= sprintf(
	// 			'<div class="alert alert-warning"><div class="media"><div class="pull-left">%s</div><div class="media-body">%s</div></div></div>', 
	// 			'<i class="fa fa-2x fa-fw fa-link"></i>',
	// 			$nonembeddable_message
	// 		);
	// 	}
	// 	else {

	// 		// iframe wrapper: setting up width and height in code to fix the iOS Safari problem: https://stackoverflow.com/questions/16937070/iframe-size-with-css-on-ios
	// 		$content .= sprintf(
	// 			'<div class="iframe-wrapper content-iframe-wrapper" style="width: %2$s; height: %3$spx;"><iframe src="%1$s" width="%2$s" height="%3$s" frameborder="0"></iframe></div>',
	// 			$post->post_content,
	// 			'100%',
	// 			'500'
	// 		);
	// 	}

	// 	return $content;
	// }

	public static function get_link_content($post, $show = false) {

		$source = self::get_link_excerpt($post);

		$url = $post->post_content;
		$parse = parse_url($url);
		$host = $parse['host'];
		$nonembeddable_message = '';
		$content = sprintf(
			'<p>%s</p>', 
			$source
		);

		$collapse = $iframe = '';
		$messages = array();

		// Check if the source is embeddable (eg: Facebook is not)
		// $embeddable = apply_filters('GD_DataLoad_FieldProcessor_Posts_Utils:link_content:embeddable_hosts', array());
		// if (in_array($host, $embeddable) && (!is_ssl() || (is_ssl() && $parse['scheme'] == 'https'))) {
		$nonembeddable = apply_filters('GD_DataLoad_FieldProcessor_Posts_Utils:link_content:nonembeddable_hosts', array());
		if (!in_array($host, $nonembeddable) && (!is_ssl() || (is_ssl() && $parse['scheme'] == 'https'))) {

			// iframe wrapper: setting up width and height in code to fix the iOS Safari problem: https://stackoverflow.com/questions/16937070/iframe-size-with-css-on-ios
			$iframe = sprintf(
				'<div class="iframe-wrapper content-iframe-wrapper" style="width: %2$s; height: %3$spx;"><iframe src="%1$s" width="%2$s" height="%3$s" frameborder="0"></iframe></div>',
				$post->post_content,
				'100%',
				'400'
			);
			
			// If not $show, add a button to Load the frame (eg: feed). If not, show the frame directly (eg: single link)
			if (!$show) {

				$collapse_id = $post->post_type.$post->ID.'-'.time();
				$messages[] = sprintf(
					'<a href="%s" class="btn btn-primary" data-toggle="collapse"><i class="fa fa-fw fa-link"></i>%s</a>',
					'#'.$collapse_id,
					__('Load link', 'pop-coreprocessors')
				);

				$script = sprintf(
					'<script type="text/javascript">jQuery("%1$s").one("show.bs.collapse", function() { jQuery(this).html("%2$s"); });</script>',
					'#'.$collapse_id,
					str_replace('"', '\"', $iframe)
				);
				$collapse = sprintf(
					'<div id="%s" class="linkcollapse-iframe collapse"></div>',
					$collapse_id
				);
				$collapse .= $script;
			}
		}

		$messages[] = sprintf(
			'<a href="%s" class="btn btn-default" target="_blank"><i class="fa fa-fw fa-external-link"></i>%s</a>',
			$url,
			__('Open link in new tab', 'pop-coreprocessors')
		);

		$content .= sprintf(
			'<p class="btn-group">%s</p>',
			implode('', $messages)
		);
		if ($show) {

			// Add directly the frame
			$content .= $iframe;
		}
		else {

			// Add the collapse with the frame
			$content .= $collapse;
		}

		return $content;
	}
}