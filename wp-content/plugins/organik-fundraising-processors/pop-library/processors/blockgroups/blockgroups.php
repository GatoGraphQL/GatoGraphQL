<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_ORGANIKFUNDRAISING_TEMPLATE_BLOCKGROUP_HOME', PoP_ServerUtils::get_template_definition('blockgroup-organikfundraising-home'));
define ('GD_ORGANIKFUNDRAISING_TEMPLATE_BLOCKGROUP_WEBSITEGOALS', PoP_ServerUtils::get_template_definition('blockgroup-organikfundraising-websitegoals'));
define ('GD_ORGANIKFUNDRAISING_TEMPLATE_BLOCKGROUP_CONTACT', PoP_ServerUtils::get_template_definition('blockgroup-organikfundraising-contact'));

class OrganikFundraising_Template_Processor_CustomBlockGroups extends GD_Template_Processor_ListBlockGroupsBase {

	function get_templates_to_process() {
	
		return array(
			GD_ORGANIKFUNDRAISING_TEMPLATE_BLOCKGROUP_HOME,
			GD_ORGANIKFUNDRAISING_TEMPLATE_BLOCKGROUP_WEBSITEGOALS,
			GD_ORGANIKFUNDRAISING_TEMPLATE_BLOCKGROUP_CONTACT,
		);
	}

	function get_blockgroup_blocks($template_id) {

		$ret = parent::get_blockgroup_blocks($template_id);

		switch ($template_id) {

			case GD_ORGANIKFUNDRAISING_TEMPLATE_BLOCKGROUP_HOME:

				$ret[] = GD_TEMPLATE_BLOCK_HOWMUCHWENEED;
				break;

			case GD_ORGANIKFUNDRAISING_TEMPLATE_BLOCKGROUP_WEBSITEGOALS:

				$blocks = array(
					GD_TEMPLATE_BLOCK_FARMS_SCROLLMAP,
					// GD_TEMPLATE_BLOCK_ASKTHEEXPERTS,
					GD_TEMPLATE_BLOCK_DISCUSSIONS_SCROLL_THUMBNAIL,
					GD_TEMPLATE_BLOCK_ANNOUNCEMENTS_SCROLL_THUMBNAIL,
					GD_TEMPLATE_BLOCK_STORIES_SCROLL_ADDONS,
					GD_TEMPLATE_BLOCK_EVENTSCALENDAR_CALENDAR,
				);

				// Allow to override with custom blocks
				$ret = array_merge(
					$ret,
					apply_filters(
						'GD_Template_Processor_CustomBlockGroups:blocks:websitegoals:features',
						$blocks
					)
				);
				break;

			case GD_ORGANIKFUNDRAISING_TEMPLATE_BLOCKGROUP_CONTACT:

				$ret[] = GD_TEMPLATE_BLOCK_CONTACTABOUTUS;
				$ret[] = GD_TEMPLATE_BLOCK_CONTACTUS;
				break;
		}

		return $ret;
	}

	function get_blockgroup_blockgroups($template_id) {

		$ret = parent::get_blockgroup_blockgroups($template_id);

		switch ($template_id) {

			case GD_ORGANIKFUNDRAISING_TEMPLATE_BLOCKGROUP_HOME:

				$ret[] = GD_ORGANIKFUNDRAISING_TEMPLATE_BLOCKGROUP_WEBSITEGOALS;
				$ret[] = GD_ORGANIKFUNDRAISING_TEMPLATE_BLOCKGROUP_CONTACT;
				break;
		}

		return $ret;
	}

	protected function get_ordered_blockgroup_blockunits($template_id) {

		switch ($template_id) {

			case GD_ORGANIKFUNDRAISING_TEMPLATE_BLOCKGROUP_HOME:

				return array(
					GD_ORGANIKFUNDRAISING_TEMPLATE_BLOCKGROUP_WEBSITEGOALS,
					GD_TEMPLATE_BLOCK_HOWMUCHWENEED,
					GD_ORGANIKFUNDRAISING_TEMPLATE_BLOCKGROUP_CONTACT,
				);
		}
	
		return parent::get_ordered_blockgroup_blockunits($template_id);
	}

	function get_title($template_id) {

		switch ($template_id) {

			case GD_ORGANIKFUNDRAISING_TEMPLATE_BLOCKGROUP_WEBSITEGOALS:

				return __('Features of Organik', 'organik-fundraising-processors');
		
			case GD_ORGANIKFUNDRAISING_TEMPLATE_BLOCKGROUP_CONTACT:

				return __('Contact us', 'organik-fundraising-processors');
		}
		
		return parent::get_title($template_id);
	}

	function init_atts($template_id, &$atts) {

		switch ($template_id) {
		
			case GD_ORGANIKFUNDRAISING_TEMPLATE_BLOCKGROUP_HOME:
		
				$this->append_att($template_id, $atts, 'class', 'blockgroup-websitegoals');
				break;
		}
			
		return parent::init_atts($template_id, $atts);
	}

	protected function get_blocksections_classes($template_id) {

		$ret = parent::get_blocksections_classes($template_id);

		switch ($template_id) {

			case GD_ORGANIKFUNDRAISING_TEMPLATE_BLOCKGROUP_CONTACT:

				$ret['blocksection-extensions'] = 'row';
				break;
		}

		return $ret;
	}

	function init_atts_blockgroup_block($blockgroup, $blockgroup_block, &$blockgroup_block_atts, $blockgroup_atts) {

		switch ($blockgroup) {

			case GD_ORGANIKFUNDRAISING_TEMPLATE_BLOCKGROUP_HOME:

				if ($blockgroup_block == GD_TEMPLATE_BLOCK_HOWMUCHWENEED) {

					$this->append_att($blockgroup_block, $blockgroup_block_atts, 'class', 'first-item');
				}
				break;
		
			case GD_ORGANIKFUNDRAISING_TEMPLATE_BLOCKGROUP_WEBSITEGOALS:

				$this->add_att($blockgroup_block, $blockgroup_block_atts, 'title-htmltag', 'h2');
				
				if ($blockgroup_block == GD_TEMPLATE_BLOCK_FARMS_SCROLLMAP) {

					// Make it lazy load
					// $this->add_att($blockgroup_block, $blockgroup_block_atts, 'content-loaded', false);
					
					// No theater mode, scrollable inside the dimensions of the map
					$this->add_att(GD_TEMPLATE_SCROLL_FARMS_MAP, $blockgroup_block_atts, 'theatermap', false);
					$this->add_att(GD_TEMPLATE_SCROLL_FARMS_MAP, $blockgroup_block_atts, 'scrollable-container', true);

					// Class for formatting
					$this->append_att($blockgroup_block, $blockgroup_block_atts, 'class', 'first-item');

					$title = __('Look for organic farms in Malaysia', 'organik-fundraising-processors');
					$description = sprintf(
						'<div class="well well-info"><p class="readable">%s</p><p class="readable">%s</p></div>',
						__('Organik will have a database of organic farms in Malaysia, including details on their location, what they produce and information about their farming practices. As this is a crowdsourced platform, users (farmers/members of the public) of the website can log in to add this information as well.', 'organik-fundraising-processors'),
						__('CETDEM will conduct farm visits to verify the details posted on the website, subject to the funding that we receive.', 'organik-fundraising-processors')
					);
					$this->add_att($blockgroup_block, $blockgroup_block_atts, 'description', $description);
					$this->add_att($blockgroup_block, $blockgroup_block_atts, 'title', $title);
				}
				elseif ($blockgroup_block == GD_TEMPLATE_BLOCK_DISCUSSIONS_SCROLL_THUMBNAIL) {

					// Make it lazy load
					// $this->add_att($blockgroup_block, $blockgroup_block_atts, 'content-loaded', false);

					$title = __('Read and write about issues on organic agriculture', 'organik-fundraising-processors');
					$description = sprintf(
						'<div class="well well-info"><p class="readable">%s</p></div>',
						__('For those who are concerned about issues on organic agriculture such as farming practices, you can read and write articles about the topic on Organik, such as below:', 'organik-fundraising-processors')
					);
					$this->add_att($blockgroup_block, $blockgroup_block_atts, 'description', $description);
					$this->add_att($blockgroup_block, $blockgroup_block_atts, 'title', $title);
				}
				elseif ($blockgroup_block == GD_TEMPLATE_BLOCK_ANNOUNCEMENTS_SCROLL_THUMBNAIL) {

					// Make it lazy load
					// $this->add_att($blockgroup_block, $blockgroup_block_atts, 'content-loaded', false);

					$title = __('Join the Malaysian community of organic enthusiasts', 'organik-fundraising-processors');
					$description = sprintf(
						'<div class="well well-info"><p class="readable">%s</p><ul class="readable"><li>%s</li><li>%s</li><li>%s</li><li>%s</li></ul></div>',
						__('Members of the public can register a (free) profile in Organik, and join the community of organic enthusiasts in Malaysia. As a registered user, you can do the following, and more, in the website: ', 'organik-fundraising-processors'),
						__('Upload pictures and share a blog post of your organic garden', 'organik-fundraising-processors'),
						__('Follow your favourite farms for updates', 'organik-fundraising-processors'),
						__('Recommend the posts that you like', 'organik-fundraising-processors'),
						__('Make announcements (e.g. to organise community composting, share seeds, etc.)', 'organik-fundraising-processors')
					);
					$this->add_att($blockgroup_block, $blockgroup_block_atts, 'description', $description);
					$this->add_att($blockgroup_block, $blockgroup_block_atts, 'title', $title);
				}
				elseif ($blockgroup_block == GD_TEMPLATE_BLOCK_STORIES_SCROLL_ADDONS) {

					// Make it lazy load
					// $this->add_att($blockgroup_block, $blockgroup_block_atts, 'content-loaded', false);

					$title = __('Ask Organik: ask questions about organic gardening and farming', 'organik-fundraising-processors');
					$description = sprintf(
						'<div class="well well-info"><p class="readable">%s</p></div>',
						__('Organik will have a section where you can ask questions on anything organic – whether it’s where to buy produce/products, how to plant vegetables, how to get rid of pests organically, and so on. CETDEM or members of the community can answer and have a discussion based on the question.', 'organik-fundraising-processors')
					);
					$this->add_att($blockgroup_block, $blockgroup_block_atts, 'description', $description);
					$this->add_att($blockgroup_block, $blockgroup_block_atts, 'title', $title);
				}
				elseif ($blockgroup_block == GD_TEMPLATE_BLOCK_EVENTSCALENDAR_CALENDAR) {

					$title = __('Check the Events Calendar on happenings in the organic sector', 'organik-fundraising-processors');
					$description = sprintf(
						'<div class="well well-info"><p class="readable">%s</p></div>',
						__('The Events Calendar will show you all the organic markets, public forums, workshops, and other happenings. Registered users of the website can post information of events that they know of.', 'organik-fundraising-processors')
					);
					$this->add_att($blockgroup_block, $blockgroup_block_atts, 'description', $description);
					$this->add_att($blockgroup_block, $blockgroup_block_atts, 'title', $title);
				}
				break;
		
			case GD_ORGANIKFUNDRAISING_TEMPLATE_BLOCKGROUP_CONTACT:

				$this->append_att($blockgroup_block, $blockgroup_block_atts, 'class', 'col-sm-6');
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
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new OrganikFundraising_Template_Processor_CustomBlockGroups();
