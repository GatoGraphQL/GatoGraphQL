<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_GETPOP_TEMPLATE_BLOCKGROUP_HOME', PoP_ServerUtils::get_template_definition('blockgroup-getpop-home'));
define ('GD_GETPOP_TEMPLATE_BLOCKGROUP_OVERVIEW', PoP_ServerUtils::get_template_definition('blockgroup-getpop-overview'));
define ('GD_GETPOP_TEMPLATE_BLOCKGROUP_WEBSITEFEATURES', PoP_ServerUtils::get_template_definition('blockgroup-getpop-websitefeatures'));
define ('GD_GETPOP_TEMPLATE_BLOCKGROUP_WEBSITEFRAMEWORK', PoP_ServerUtils::get_template_definition('blockgroup-getpop-websiteframework'));
define ('GD_GETPOP_TEMPLATE_BLOCKGROUP_DESIGNPRINCIPLES', PoP_ServerUtils::get_template_definition('blockgroup-getpop-designprinciples'));
define ('GD_GETPOP_TEMPLATE_BLOCKGROUP_CONTACT', PoP_ServerUtils::get_template_definition('blockgroup-getpop-contact'));

class GetPoP_Template_Processor_CustomBlockGroups extends GD_Template_Processor_ListBlockGroupsBase {

	function get_templates_to_process() {
	
		return array(
			GD_GETPOP_TEMPLATE_BLOCKGROUP_HOME,
			GD_GETPOP_TEMPLATE_BLOCKGROUP_OVERVIEW,
			GD_GETPOP_TEMPLATE_BLOCKGROUP_WEBSITEFEATURES,
			GD_GETPOP_TEMPLATE_BLOCKGROUP_WEBSITEFRAMEWORK,
			GD_GETPOP_TEMPLATE_BLOCKGROUP_DESIGNPRINCIPLES,
			GD_GETPOP_TEMPLATE_BLOCKGROUP_CONTACT,
		);
	}

	protected function get_ordered_blockgroup_blockunits($template_id) {

		switch ($template_id) {

			case GD_GETPOP_TEMPLATE_BLOCKGROUP_HOME:

				return array(
					GD_TEMPLATE_BLOCK_WHATISIT,
					// GD_TEMPLATE_BLOCK_DISCOVER,
					// GD_GETPOP_TEMPLATE_BLOCKGROUP_WEBSITEFRAMEWORK,
					// GD_GETPOP_TEMPLATE_BLOCKGROUP_DESIGNPRINCIPLES,
					// GD_TEMPLATE_BLOCK_WEBSITEFEATURES_UNDERTHEHOOD,
					GD_GETPOP_TEMPLATE_BLOCKGROUP_WEBSITEFEATURES,
					GD_GETPOP_TEMPLATE_BLOCKGROUP_CONTACT,
				);

			case GD_GETPOP_TEMPLATE_BLOCKGROUP_OVERVIEW:

				return array(
					GD_GETPOP_TEMPLATE_BLOCKGROUP_WEBSITEFRAMEWORK,
					GD_GETPOP_TEMPLATE_BLOCKGROUP_DESIGNPRINCIPLES,
					GD_TEMPLATE_BLOCK_WEBSITEFEATURES_UNDERTHEHOOD,
					GD_TEMPLATE_BLOCK_WEBSITEFEATURES_ADVANTAGES,
				);

			case GD_GETPOP_TEMPLATE_BLOCKGROUP_WEBSITEFEATURES:

				// Use the blocks as the baseline
				$blockunits = $this->get_blockgroup_blocks($template_id);
				
				// Add the blockgroups
				array_splice($blockunits, array_search(GD_TEMPLATE_BLOCK_WEBSITEFEATURES_PAGECONTAINERS, $blockunits), 0, array(GD_TEMPLATE_BLOCKGROUP_TABPANEL_WEBSITEFEATURES_FORMATS));

				// $blockunits[] = GD_TEMPLATE_BLOCKGROUP_CAROUSEL_WEBSITEFEATURES_MORE;
				return $blockunits;
		}
	
		return parent::get_ordered_blockgroup_blockunits($template_id);
	}

	function get_blockgroup_blocks($template_id) {

		$ret = parent::get_blockgroup_blocks($template_id);

		switch ($template_id) {

			case GD_GETPOP_TEMPLATE_BLOCKGROUP_DESIGNPRINCIPLES:

				$ret[] = GD_TEMPLATE_BLOCK_DESIGNPRINCIPLES_API;

				// If the external website domain was specified, then add the Events Calendar
				if (GETPOP_URL_EXTERNALWEBSITEDOMAIN) {

					$ret[] = GD_TEMPLATE_BLOCK_DESIGNPRINCIPLES_DESCENTRALIZATION;
					$ret[] = GD_TEMPLATE_BLOCK_EVENTSCALENDAR_CALENDAR;
					// $ret[] = GD_TEMPLATE_BLOCK_WEBSITEFRAMEWORK_GETPOST;
					$ret[] = GD_TEMPLATE_BLOCK_WEBPOSTS_SCROLL_LIST;
					// $ret[] = GD_TEMPLATE_BLOCK_WEBSITEFRAMEWORK_MICROSERVICES;
				}
				break;

			case GD_GETPOP_TEMPLATE_BLOCKGROUP_HOME:

				$ret[] = GD_TEMPLATE_BLOCK_WHATISIT;
				// $ret[] = GD_TEMPLATE_BLOCK_DISCOVER;
				// $ret[] = GD_TEMPLATE_BLOCK_WEBSITEFEATURES_UNDERTHEHOOD;
				break;

			case GD_GETPOP_TEMPLATE_BLOCKGROUP_OVERVIEW:

				$ret[] = GD_TEMPLATE_BLOCK_WEBSITEFEATURES_UNDERTHEHOOD;
				$ret[] = GD_TEMPLATE_BLOCK_WEBSITEFEATURES_ADVANTAGES;
				break;

			case GD_GETPOP_TEMPLATE_BLOCKGROUP_WEBSITEFEATURES:

				$blocks = array();

				// If the external website domain was specified, then add the Events Calendar
				if (GETPOP_URL_EXTERNALWEBSITEDOMAIN) {

					$blocks[] = GD_TEMPLATE_BLOCK_EVENTSCALENDAR_CALENDAR;
				}

				$blocks = array_merge(
					$blocks,
					array(
						GD_TEMPLATE_BLOCK_WEBSITEFEATURES_PAGECONTAINERS,
						GD_TEMPLATE_BLOCK_WEBSITEFEATURES_THEMEMODES,
						GD_TEMPLATE_BLOCK_DISCUSSION_CREATE,
						GD_TEMPLATE_BLOCK_WEBPOSTS_SCROLL_THUMBNAIL,
						GD_TEMPLATE_BLOCK_EVERYTHING_QUICKLINKS,
						GD_TEMPLATE_BLOCK_WEBSITEFEATURES_SOCIALNETWORK,
					)
				);

				// Allow to override with custom blocks
				$ret = array_merge(
					$ret,
					apply_filters(
						'GD_Template_Processor_CustomBlockGroups:blocks:websitefeatures:features',
						$blocks
					)
				);
				break;

			case GD_GETPOP_TEMPLATE_BLOCKGROUP_WEBSITEFRAMEWORK:

				// Comment Leo 28/10/2016: switching from the hardcoded block using a Code template, to another using a PostCode template
				$ret[] = GD_TEMPLATE_BLOCK_FRAMEWORK; //GD_TEMPLATE_BLOCK_WEBSITEFRAMEWORK;
				break;

			case GD_GETPOP_TEMPLATE_BLOCKGROUP_CONTACT:

				$ret[] = GD_TEMPLATE_BLOCK_CONTACTUS;
				$ret[] = GD_TEMPLATE_BLOCK_CONTACTABOUTUS;
				break;
		}

		return $ret;
	}

	function get_blockgroup_blockgroups($template_id) {

		$ret = parent::get_blockgroup_blockgroups($template_id);

		switch ($template_id) {

			case GD_GETPOP_TEMPLATE_BLOCKGROUP_HOME:

				// $ret[] = GD_GETPOP_TEMPLATE_BLOCKGROUP_WEBSITEFRAMEWORK;
				// $ret[] = GD_GETPOP_TEMPLATE_BLOCKGROUP_DESIGNPRINCIPLES;
				$ret[] = GD_GETPOP_TEMPLATE_BLOCKGROUP_WEBSITEFEATURES;
				$ret[] = GD_GETPOP_TEMPLATE_BLOCKGROUP_CONTACT;
				break;

			case GD_GETPOP_TEMPLATE_BLOCKGROUP_OVERVIEW:

				$ret[] = GD_GETPOP_TEMPLATE_BLOCKGROUP_WEBSITEFRAMEWORK;
				$ret[] = GD_GETPOP_TEMPLATE_BLOCKGROUP_DESIGNPRINCIPLES;
				break;

			// case GD_GETPOP_TEMPLATE_BLOCKGROUP_WEBSITEFRAMEWORK:
				
			// 	$ret[] = GD_GETPOP_TEMPLATE_BLOCKGROUP_WEBSITEFRAMEWORK_DECENTRALIZED;
			// 	break;

			case GD_GETPOP_TEMPLATE_BLOCKGROUP_WEBSITEFEATURES:

				$ret[] = GD_TEMPLATE_BLOCKGROUP_TABPANEL_WEBSITEFEATURES_FORMATS;
				// $ret[] = GD_TEMPLATE_BLOCKGROUP_CAROUSEL_WEBSITEFEATURES_MORE;
				break;
		}

		return $ret;
	}

	protected function get_controlgroup_top($template_id) {

		// Do not add for the quickview, since it is a modal and can't open a new modal (eg: Embed) on top
		$vars = GD_TemplateManager_Utils::get_vars();
		if (!$vars['fetching-json-quickview']) {

			switch ($template_id) {

				case GD_GETPOP_TEMPLATE_BLOCKGROUP_OVERVIEW:

					return GD_TEMPLATE_CONTROLGROUP_SHARE;
			}
		}

		return parent::get_controlgroup_top($template_id);
	}

	function get_title($template_id) {

		switch ($template_id) {

			case GD_GETPOP_TEMPLATE_BLOCKGROUP_OVERVIEW:

				return get_the_title(GETPOP_PROCESSORS_PAGE_DOCUMENTATION_OVERVIEW);

			case GD_GETPOP_TEMPLATE_BLOCKGROUP_WEBSITEFRAMEWORK:

				return __('The framework', 'getpop-processors');

			case GD_GETPOP_TEMPLATE_BLOCKGROUP_DESIGNPRINCIPLES:

				return __('Design principles', 'getpop-processors');

			case GD_GETPOP_TEMPLATE_BLOCKGROUP_WEBSITEFEATURES:

				// return __('Front-end features', 'getpop-processors');
				return __('Features', 'getpop-processors');
		
			case GD_GETPOP_TEMPLATE_BLOCKGROUP_CONTACT:

				return __('Get in touch with us', 'getpop-processors');
		}
		
		return parent::get_title($template_id);
	}

	protected function get_description($template_id, $atts) {

		switch ($template_id) {

			case GD_GETPOP_TEMPLATE_BLOCKGROUP_CONTACT:

				return sprintf(
					'<pre class="breakable">%s</pre>',
					__('Are you interested in the PoP software? Would you like to collaborate, develop code, write documentation? Would you like to sponsors/support us, or hire our services to develop your PoP website? Or do you have any cool idea we should know about? <strong>Let us know!</strong>', 'getpop-processors')
				);
		}
		
		return parent::get_description($template_id, $atts);
	}

	function init_atts($template_id, &$atts) {

		switch ($template_id) {
		
			case GD_GETPOP_TEMPLATE_BLOCKGROUP_HOME:
			case GD_GETPOP_TEMPLATE_BLOCKGROUP_OVERVIEW:
		
				$this->append_att($template_id, $atts, 'class', 'mainsections');
				break;
		}
			
		return parent::init_atts($template_id, $atts);
	}

	// protected function get_blocksections_classes($template_id) {

	// 	$ret = parent::get_blocksections_classes($template_id);

	// 	switch ($template_id) {

	// 		case GD_GETPOP_TEMPLATE_BLOCKGROUP_CONTACT:

	// 			$ret['blocksection-extensions'] = 'row';
	// 			break;
	// 	}

	// 	return $ret;
	// }

	function init_atts_blockgroup_block($blockgroup, $blockgroup_block, &$blockgroup_block_atts, $blockgroup_atts) {

		switch ($blockgroup) {

			case GD_GETPOP_TEMPLATE_BLOCKGROUP_WEBSITEFRAMEWORK:

				// if ($blockgroup_block == GD_TEMPLATE_BLOCK_WEBSITEFRAMEWORK) {
				if ($blockgroup_block == GD_TEMPLATE_BLOCK_FRAMEWORK) {

					$this->add_att($blockgroup_block, $blockgroup_block_atts, 'title', '');
				}
				break;

			case GD_GETPOP_TEMPLATE_BLOCKGROUP_DESIGNPRINCIPLES:

				if ($blockgroup_block == GD_TEMPLATE_BLOCK_DESIGNPRINCIPLES_API) {

					$this->append_att($blockgroup_block, $blockgroup_block_atts, 'class', 'first-item');
				}
				elseif ($blockgroup_block == GD_TEMPLATE_BLOCK_EVENTSCALENDAR_CALENDAR) {

					$this->add_att($blockgroup_block, $blockgroup_block_atts, 'dataloadsource-domain', GETPOP_URL_EXTERNALWEBSITEDOMAIN);
					
					$title = __('Example #1: decentralized calendar', 'getpop-processors');
					$description = sprintf(
						'<p class="bg-warning text-warning">%s</p>',
						sprintf(
							__('The data feeding the calendar below is not coming from <strong>%1$s</strong>, but from another website, <a href="%3$s" target="_blank">%4$s</a> (<strong>%2$s</strong>). Even though pointing to %2$s, clicking on a link inside the calendar opens the page locally, not in a new tab.', 'getpop-processors'),
							get_site_url(),
							GETPOP_URL_EXTERNALWEBSITEDOMAIN,
							str_replace(get_site_url(), GETPOP_URL_EXTERNALWEBSITEDOMAIN, get_permalink(POPTHEME_WASSUP_EM_PAGE_EVENTSCALENDAR)),
							GETPOP_URL_EXTERNALWEBSITENAME
						)
					);
					$description_bottom = sprintf(
						'<br><p class="text-warning"><em>%s</em></p><br/>',
						__('<strong>Coming soon:</strong> components will be able to load data from several sources concurrently. As such, this calendar will be able to fetch events, in real time, from many different websites.', 'getpop-processors')
					);
					$this->add_att($blockgroup_block, $blockgroup_block_atts, 'description-top', $description);
					$this->add_att($blockgroup_block, $blockgroup_block_atts, 'description-bottom', $description_bottom);
					$this->add_att($blockgroup_block, $blockgroup_block_atts, 'title-htmltag', 'h3');
					$this->add_att($blockgroup_block, $blockgroup_block_atts, 'title', $title);
				}
				elseif ($blockgroup_block == GD_TEMPLATE_BLOCK_WEBPOSTS_SCROLL_LIST) {

					$this->add_att($blockgroup_block, $blockgroup_block_atts, 'dataloadsource-domain', GETPOP_URL_EXTERNALWEBSITEDOMAIN);
					
					$title = __('Example #2: submitting a POST request', 'getpop-processors');
					$description = sprintf(
						'<div class="bg-warning text-warning"><p>%s</p><ol><li>%s</li><li>%s</li><li>%s</li></ol></div>',
						sprintf(
							__('The posts below are being fetched from <a href="%s" target="_blank">%s</a>. Users can create a new post in this other website, yet do it from here. Try it out:', 'getpop-processors'),
							GETPOP_URL_EXTERNALWEBSITEDOMAIN,
							GETPOP_URL_EXTERNALWEBSITENAME
						),
						sprintf(
							__('<a href="%s">Log in</a> on this another website', 'getpop-processors'),
							str_replace(get_site_url(), GETPOP_URL_EXTERNALWEBSITEDOMAIN, get_permalink(POP_WPAPI_PAGE_LOGIN))
						),
						sprintf(
							__('<a href="%s" target="%s">Click here</a> to create a post, add content, and then click on Submit.', 'getpop-processors'),
							str_replace(get_site_url(), GETPOP_URL_EXTERNALWEBSITEDOMAIN, get_permalink(POPTHEME_WASSUP_PAGE_ADDWEBPOST)),
							GD_URLPARAM_TARGET_ADDONS
						),
						__('Click on the <i class="fa fa-fw fa-refresh"></i>Refresh button above to see the new post.', 'getpop-processors')
					);
					$this->add_att($blockgroup_block, $blockgroup_block_atts, 'description-top', $description);
					$this->add_att($blockgroup_block, $blockgroup_block_atts, 'title', $title);
					$this->add_att($blockgroup_block, $blockgroup_block_atts, 'title-htmltag', 'h3');

					// Limit the number of results
					$this->add_att($blockgroup_block, $blockgroup_block_atts, 'limit', 2);

					// Do not show the Fetchmore button
					$this->add_att($blockgroup_block, $blockgroup_block_atts, 'show-fetchmore', false);
				}
				break;
		
			case GD_GETPOP_TEMPLATE_BLOCKGROUP_WEBSITEFEATURES:

				if ($blockgroup_block == GD_TEMPLATE_BLOCK_DISCUSSION_CREATE) {
				// if ($blockgroup_block == GD_TEMPLATE_BLOCK_OPINIONATEDVOTE_CREATEORUPDATE) {

					$title = __('User states: “Logged-in” and “Not yet logged-in”', 'getpop-processors');
					$description = sprintf(
						'<pre class="breakable">%s</pre><h3>%s</h3>',
						__('In a PoP website, there is no <em>“Please log in to start posting content”</em> message. The user can start writing a post or comment even before logging in. Upon clicking on <em>Submit</em>, if not yet logged in, the system will prompt the user to log in or register on a modal window.', 'getpop-processors'),
						sprintf(
							__('Example: %s', 'getpop-processors'),
							get_the_title(POPTHEME_WASSUP_SECTIONPROCESSORS_PAGE_ADDDISCUSSION)
						)
					);
					$this->add_att($blockgroup_block, $blockgroup_block_atts, 'description', $description);
					$this->add_att($blockgroup_block, $blockgroup_block_atts, 'title', $title);
					$this->add_att($blockgroup_block, $blockgroup_block_atts, 'title-htmltag', 'h2');

					// Make the form more visible
					$this->append_att(GD_TEMPLATE_FORM_DISCUSSION_CREATE, $blockgroup_block_atts, 'class', 'well well-warning');

					// // Make the Opinionated Vote horizontal
					// $this->add_att(GD_TEMPLATE_FORM_OPINIONATEDVOTE_UPDATE, $blockgroup_block_atts, 'horizontal', true);
				}
				elseif ($blockgroup_block == GD_TEMPLATE_BLOCK_EVERYTHING_QUICKLINKS) {

					$title = __('Search / Quick access input', 'getpop-processors');
					$description = sprintf(
						'<pre class="breakable">%s</pre>',
						__('The Search / Quick access input allows to search posts and users, or quickly access any post, user or tag.', 'getpop-processors')
					);
					$this->add_att($blockgroup_block, $blockgroup_block_atts, 'description', $description);
					$this->add_att($blockgroup_block, $blockgroup_block_atts, 'title', $title);
					$this->add_att($blockgroup_block, $blockgroup_block_atts, 'title-htmltag', 'h2');
				}
				elseif ($blockgroup_block == GD_TEMPLATE_BLOCK_WEBPOSTS_SCROLL_THUMBNAIL) {

					$title = __('Powerful filters', 'getpop-processors');
					$description = sprintf(
						'<pre class="breakable">%s</pre><h3>%s</h3>',
						__('The software allows the creation of highly customized filters, for all type of content (posts, users, comments, tags).', 'getpop-processors'),
						__('Example: filter posts', 'getpop-processors')
					);
					$this->add_att($blockgroup_block, $blockgroup_block_atts, 'description-top', $description);
					$this->add_att($blockgroup_block, $blockgroup_block_atts, 'title', $title);
					$this->add_att($blockgroup_block, $blockgroup_block_atts, 'title-htmltag', 'h2');

					// Initial Search values
					$this->add_att(GD_TEMPLATE_FILTERFORMCOMPONENT_HASHTAGS, $blockgroup_block_atts, 'selected', 'internet facebook');
					if ($authors = gd_fixedscroll_user_ids(GD_TEMPLATE_BLOCK_WHOWEARE_SCROLL_DETAILS)) {

						$this->add_att(GD_URE_TEMPLATE_FILTERFORMCOMPONENT_TYPEAHEAD_PROFILES, $blockgroup_block_atts, 'selected', $authors);
					}

					// Limit the number of results
					$this->add_att($blockgroup_block, $blockgroup_block_atts, 'limit', 2);

					// Load content (by default it's not loaded)
					$this->add_att($blockgroup_block, $blockgroup_block_atts, 'data-load', true);

					// But load it with lazy load, because the filter values otherwise are not set!
					$this->add_att($blockgroup_block, $blockgroup_block_atts, 'content-loaded', false);

					// Do not show the Fetchmore button
					$this->add_att($blockgroup_block, $blockgroup_block_atts, 'show-fetchmore', false);

					// Show the filter
					$this->add_att($blockgroup_block, $blockgroup_block_atts, 'show-filter', true);
					$this->add_att($blockgroup_block, $blockgroup_block_atts, 'filter-visible', true);
				}
				elseif ($blockgroup_block == GD_TEMPLATE_BLOCK_EVENTSCALENDAR_CALENDAR) {

					$this->add_att($blockgroup_block, $blockgroup_block_atts, 'dataloadsource-domain', GETPOP_URL_EXTERNALWEBSITEDOMAIN);
					
					$title = __('Decentralized', 'getpop-processors');
					$description = sprintf(
						'<p class="bg-warning text-warning">%s</p>',
						sprintf(
							__('The calendar below is natively displaying data from a different website (<a href="%1$s" target="_blank">%2$s</a>). Even though pointing to %3$s, clicking on a link inside the calendar opens the page locally, not in a new tab.', 'getpop-processors'),
							str_replace(get_site_url(), GETPOP_URL_EXTERNALWEBSITEDOMAIN, get_permalink(POPTHEME_WASSUP_EM_PAGE_EVENTSCALENDAR)),
							GETPOP_URL_EXTERNALWEBSITENAME,
							GETPOP_URL_EXTERNALWEBSITEDOMAIN
						)
					);
					$this->add_att($blockgroup_block, $blockgroup_block_atts, 'description-top', $description);
					$this->add_att($blockgroup_block, $blockgroup_block_atts, 'title-htmltag', 'h2');
					$this->add_att($blockgroup_block, $blockgroup_block_atts, 'title', $title);
					$this->append_att($blockgroup_block, $blockgroup_block_atts, 'class', 'first-item');
				}
				break;
		
			case GD_GETPOP_TEMPLATE_BLOCKGROUP_CONTACT:

				// $this->append_att($blockgroup_block, $blockgroup_block_atts, 'class', 'col-sm-6');
				$this->add_att($blockgroup_block, $blockgroup_block_atts, 'title', '');

				if ($blockgroup_block == GD_TEMPLATE_BLOCK_CONTACTUS) {

					$this->append_att(GD_TEMPLATE_FORM_CONTACTUS, $blockgroup_block_atts, 'class', 'well');
				}
				elseif ($blockgroup_block == GD_TEMPLATE_BLOCK_CONTACTABOUTUS) {

					$this->add_att($blockgroup_block, $blockgroup_block_atts, 'title', '');
					$this->append_att(GD_TEMPLATE_POSTCODE_CONTACTABOUTUS, $blockgroup_block_atts, 'class', 'well well-info');
				}
				break;
		}

		return parent::init_atts_blockgroup_block($blockgroup, $blockgroup_block, $blockgroup_block_atts, $blockgroup_atts);
	}

	// function init_atts_blockgroup_blockgroup($blockgroup, $blockgroup_blockgroup, &$blockgroup_blockgroup_atts, $blockgroup_atts) {

	// 	switch ($blockgroup) {

	// 		case GD_GETPOP_TEMPLATE_BLOCKGROUP_WEBSITEFEATURES:

	// 			if ($blockgroup_blockgroup == GD_TEMPLATE_BLOCKGROUP_TABPANEL_WEBSITEFEATURES_FORMATS) {

	// 				$this->append_att($blockgroup_blockgroup, $blockgroup_blockgroup_atts, 'class', 'first-item');
	// 			}
	// 			break;
	// 	}

	// 	return parent::init_atts_blockgroup_blockgroup($blockgroup, $blockgroup_blockgroup, $blockgroup_blockgroup_atts, $blockgroup_atts);
	// }
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GetPoP_Template_Processor_CustomBlockGroups();
