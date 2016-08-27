<?php
	/**
	 * API connectivity issues (CloudFlare's firewall) handler for handling different
	 * scenarios selected by the user after connectivity issue is detected, by sending
	 * AJAX call to the server in order to make the actual actions.
	 *
	 * @package     Freemius
	 * @copyright   Copyright (c) 2015, Freemius, Inc.
	 * @license     http://opensource.org/licenses/gpl-2.0.php GNU Public License
	 * @since       1.0.9
	 */

	if ( ! defined( 'ABSPATH' ) ) {
		exit;
	}
?>
<script type="text/javascript">
	jQuery(document).ready(function ($) {
		$('#fs_firewall_issue_options a.fs-resolve').click(function () {
			var
				error_type = $(this).attr('data-type'),
				notice = $(this).parents('.fs-notice'),
				slug = notice.attr('data-slug');

			var data = {
				action    : slug + '_resolve_firewall_issues',
				slug      : slug,
				error_type: error_type
			};

			if ('squid' === error_type) {
				data.hosting_company = prompt('What is the name or URL of your hosting company?');
				if (null == data.hosting_company)
					return false;

				if ('' === data.hosting_company) {
					alert('We won\'t be able to help without knowing your hosting company.');
					return false;
				}
			}

			$(this).css({'cursor': 'wait'});

			// since 2.8 ajaxurl is always defined in the admin header and points to admin-ajax.php
			$.post(ajaxurl, data, function (response) {
				if (1 == response) {
					// Refresh page on success.
					location.reload();
				}
			});
		});
	});
</script>