<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_BLOCKGROUP_HOME_TOP', PoP_ServerUtils::get_template_definition('blockgroup-home-top'));
define ('GD_TEMPLATE_BLOCKGROUP_HOME_WELCOME', PoP_ServerUtils::get_template_definition('blockgroup-home-welcome'));
define ('GD_TEMPLATE_BLOCKGROUP_HOME_COMPACTWELCOME', PoP_ServerUtils::get_template_definition('blockgroup-home-compactwelcome'));
define ('GD_TEMPLATE_BLOCKGROUP_HOME_INSTITUTIONALWELCOME', PoP_ServerUtils::get_template_definition('blockgroup-home-institutionalwelcome'));
define ('GD_TEMPLATE_BLOCKGROUP_HOME_WELCOMEBLOG', PoP_ServerUtils::get_template_definition('blockgroup-home-welcomeblog'));
define ('GD_TEMPLATE_BLOCKGROUP_HOME_WIDGETAREA', PoP_ServerUtils::get_template_definition('blockgroup-home-widgetarea'));
define ('GD_TEMPLATE_BLOCKGROUP_HOME_WELCOMEACCOUNT', PoP_ServerUtils::get_template_definition('blockgroup-home-welcomeaccount'));
define ('GD_TEMPLATE_BLOCKGROUP_HOME_BLOGNEWSLETTER', PoP_ServerUtils::get_template_definition('blockgroup-home-blognewsletter'));

define ('GD_TEMPLATE_BLOCKGROUP_AUTHOR_TOP', PoP_ServerUtils::get_template_definition('blockgroup-author-top'));
define ('GD_TEMPLATE_BLOCKGROUP_AUTHOR_DESCRIPTION', PoP_ServerUtils::get_template_definition('blockgroup-author-description'));
define ('GD_TEMPLATE_BLOCKGROUP_AUTHOR_WIDGETAREA', PoP_ServerUtils::get_template_definition('blockgroup-author-widgetarea'));

define ('GD_TEMPLATE_BLOCKGROUP_TAG_WIDGETAREA', PoP_ServerUtils::get_template_definition('blockgroup-tag-widgetarea'));

define ('GD_TEMPLATE_BLOCKGROUP_WHOWEARE', PoP_ServerUtils::get_template_definition('blockgroup-whoweare'));
define ('GD_TEMPLATE_BLOCKGROUP_OURSPONSORS', PoP_ServerUtils::get_template_definition('blockgroup-oursponsors'));

define ('POP_HOOK_BLOCKGROUP_TOPWIDGETS_INCOLUMNS', 'blockgroup-topwidgets-incolumns');

class GD_Template_Processor_CustomBlockGroups extends GD_Template_Processor_ListBlockGroupsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_BLOCKGROUP_HOME_TOP,
			GD_TEMPLATE_BLOCKGROUP_HOME_WELCOME,
			GD_TEMPLATE_BLOCKGROUP_HOME_COMPACTWELCOME,
			GD_TEMPLATE_BLOCKGROUP_HOME_INSTITUTIONALWELCOME,
			GD_TEMPLATE_BLOCKGROUP_HOME_WELCOMEBLOG,
			GD_TEMPLATE_BLOCKGROUP_HOME_WIDGETAREA,
			GD_TEMPLATE_BLOCKGROUP_HOME_WELCOMEACCOUNT,
			GD_TEMPLATE_BLOCKGROUP_HOME_BLOGNEWSLETTER,
			GD_TEMPLATE_BLOCKGROUP_AUTHOR_TOP,
			GD_TEMPLATE_BLOCKGROUP_AUTHOR_DESCRIPTION,
			GD_TEMPLATE_BLOCKGROUP_AUTHOR_WIDGETAREA,
			GD_TEMPLATE_BLOCKGROUP_TAG_WIDGETAREA,
			GD_TEMPLATE_BLOCKGROUP_WHOWEARE,
			GD_TEMPLATE_BLOCKGROUP_OURSPONSORS,
		);
	}

	protected function get_description_bottom($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCKGROUP_HOME_WELCOME:
			case GD_TEMPLATE_BLOCKGROUP_HOME_COMPACTWELCOME:
			case GD_TEMPLATE_BLOCKGROUP_HOME_INSTITUTIONALWELCOME:
			case GD_TEMPLATE_BLOCKGROUP_HOME_WELCOMEBLOG:
			case GD_TEMPLATE_BLOCKGROUP_AUTHOR_DESCRIPTION:

				$welcometitle = PoPTheme_Wassup_Utils::get_welcome_title();
				$titles = array(
					GD_TEMPLATE_BLOCKGROUP_HOME_WELCOME => $welcometitle,
					GD_TEMPLATE_BLOCKGROUP_HOME_COMPACTWELCOME => $welcometitle,
					GD_TEMPLATE_BLOCKGROUP_HOME_INSTITUTIONALWELCOME => $welcometitle,
					GD_TEMPLATE_BLOCKGROUP_HOME_WELCOMEBLOG => $welcometitle,
					GD_TEMPLATE_BLOCKGROUP_AUTHOR_DESCRIPTION => '<i class="fa fa-fw fa-info"></i>'.__('Show description', 'poptheme-wassup'),
				);
				$markups = array(
					GD_TEMPLATE_BLOCKGROUP_HOME_WELCOME => 'h2',
					GD_TEMPLATE_BLOCKGROUP_HOME_COMPACTWELCOME => 'h4',
					GD_TEMPLATE_BLOCKGROUP_HOME_INSTITUTIONALWELCOME => 'h2',
					GD_TEMPLATE_BLOCKGROUP_HOME_WELCOMEBLOG => 'h2',
					GD_TEMPLATE_BLOCKGROUP_AUTHOR_DESCRIPTION => 'h4',
				);
				$classes = array(
					GD_TEMPLATE_BLOCKGROUP_HOME_WELCOME => '',
					GD_TEMPLATE_BLOCKGROUP_HOME_COMPACTWELCOME => '',
					GD_TEMPLATE_BLOCKGROUP_HOME_INSTITUTIONALWELCOME => '',
					GD_TEMPLATE_BLOCKGROUP_HOME_WELCOMEBLOG => '',
					GD_TEMPLATE_BLOCKGROUP_AUTHOR_DESCRIPTION => 'btn btn-default btn-block btn-lg',
				);

				$target = '#'.$this->get_frontend_id($template_id, $atts).'>.blocksection-extensions';

				$welcometitle = sprintf(
					'<a data-toggle="collapse" href="%s" aria-expanded="false" class="%s">%s</a>',
					$target,
					$classes[$template_id],
					$titles[$template_id].' <i class="fa fa-angle-down"></i>'
				);

				// $welcomeblocks = array(
				// 	GD_TEMPLATE_BLOCKGROUP_HOME_WELCOME,
				// 	GD_TEMPLATE_BLOCKGROUP_HOME_COMPACTWELCOME,
				// 	GD_TEMPLATE_BLOCKGROUP_HOME_INSTITUTIONALWELCOME,
				// 	GD_TEMPLATE_BLOCKGROUP_HOME_WELCOMEBLOG,
				// );
				// if (in_array($template_id, $welcomeblocks)) {
				
				// 	// Allow qTrans to add the language links
				// 	$welcometitle = apply_filters(
				// 		'GD_Template_Processor_CustomBlockGroups:homewelcometitle',
				// 		$welcometitle,
				// 		$template_id
				// 	);
				// }
				$frontend_id = $this->get_frontend_id($template_id, $atts);
				$welcome = sprintf(
					'<%1$s id="%2$s" class="top-expand text-center">%3$s</%1$s>',
					$markups[$template_id],
					$frontend_id.'-expand',
					$welcometitle
				);
				// $close = '';
				// if (in_array($template_id, $welcomeblocks)) {
				// 	$target = '#'.$frontend_id.'>.blocksection-extensions';
				// 	$close = sprintf(
				// 		'<h3 id="%s" class="bottom-collapse"><a data-toggle="collapse" href="%s" aria-expanded="false" title="%s" class="close">%s</a></h3>',
				// 		$frontend_id.'-collapsebottom',
				// 		$target,
				// 		__('Close', 'poptheme-wassup'),
				// 		'<i class="fa fa-close"></i>'.__('Close', 'poptheme-wassup')
				// 	);
				// }

				return $welcome;/*.$close;*/
		}
		
		return parent::get_description_bottom($template_id, $atts);
	}

	protected function get_ordered_blockgroup_blockunits($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCKGROUP_HOME_WELCOME:
			case GD_TEMPLATE_BLOCKGROUP_HOME_INSTITUTIONALWELCOME:
			// case GD_TEMPLATE_BLOCKGROUP_HOME_COMPACTWELCOME:

				return array_merge(
					$this->get_blockgroup_blocks($template_id),
					$this->get_blockgroup_blockgroups($template_id)
				);
		}
	
		return parent::get_ordered_blockgroup_blockunits($template_id);
	}

	function get_blockgroup_blocks($template_id) {

		$ret = parent::get_blockgroup_blocks($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_BLOCKGROUP_HOME_WELCOME:

				$ret[] = GD_TEMPLATE_BLOCK_HOMEWELCOME;
				$ret[] = GD_TEMPLATE_BLOCK_USERLOGGEDINWELCOME;
				break;

			case GD_TEMPLATE_BLOCKGROUP_HOME_COMPACTWELCOME:

				$ret[] = GD_TEMPLATE_BLOCK_HOMEWELCOME;
				// $ret[] = GD_TEMPLATE_BLOCK_NEWSLETTER;
				break;

			case GD_TEMPLATE_BLOCKGROUP_HOME_INSTITUTIONALWELCOME:

				$ret[] = GD_TEMPLATE_BLOCK_HOMEWELCOME;
				break;

			case GD_TEMPLATE_BLOCKGROUP_HOME_WELCOMEBLOG:

				$ret[] = GD_TEMPLATE_BLOCK_HOMEWELCOME;
				$ret[] = GD_TEMPLATE_BLOCK_BLOG_CAROUSEL;
				break;

			case GD_TEMPLATE_BLOCKGROUP_HOME_WELCOMEACCOUNT:

				$ret[] = GD_TEMPLATE_BLOCK_MENU_HOME_USERNOTLOGGEDIN;
				$ret[] = GD_TEMPLATE_BLOCK_NEWSLETTER;
				break;

			case GD_TEMPLATE_BLOCKGROUP_HOME_BLOGNEWSLETTER:

				$ret[] = GD_TEMPLATE_BLOCK_BLOG_CAROUSEL;
				$ret[] = GD_TEMPLATE_BLOCK_NEWSLETTER;
				break;

			case GD_TEMPLATE_BLOCKGROUP_AUTHOR_DESCRIPTION:

				$ret[] = GD_TEMPLATE_BLOCK_AUTHOR_CONTENT;
				break;
				
			case GD_TEMPLATE_BLOCKGROUP_HOME_WIDGETAREA:

				// $ret[] = GD_TEMPLATE_BLOCK_EVENTS_CAROUSEL;
				// $ret[] = GD_TEMPLATE_BLOCK_USERS_CAROUSEL;
				// Add the blocks
				if ($blocks = apply_filters('GD_Template_Processor_CustomBlockGroups:blocks:home_widgetarea', array(), $template_id)) {
					
					$ret = array_merge(
						$ret,
						$blocks
					);
				}
				break;
				
			case GD_TEMPLATE_BLOCKGROUP_AUTHOR_WIDGETAREA:

				// $blocks = array();

				// // Add the members only for communities
				// global $author;
				// if (gd_ure_is_community($author)) {
				
				// 	$blocks[] = GD_TEMPLATE_BLOCK_AUTHORMEMBERS_CAROUSEL;
				// }

				// $blocks[] = GD_TEMPLATE_BLOCK_AUTHOREVENTS_CAROUSEL;

				// Add the blocks
				if ($blocks = apply_filters('GD_Template_Processor_CustomBlockGroups:blocks:author_widgetarea', array(), $template_id)) {
					
					$ret = array_merge(
						$ret,
						$blocks
					);
				}
				break;
				
			case GD_TEMPLATE_BLOCKGROUP_TAG_WIDGETAREA:

				$blocks = array();
				$blocks[] = GD_TEMPLATE_BLOCK_TAG_CONTENT;

				// Allow to add the Featured Carousel
				if ($blocks = apply_filters('GD_Template_Processor_CustomBlockGroups:blocks:tag_widgetarea', $blocks, $template_id)) {
					
					$ret = array_merge(
						$ret,
						$blocks
					);
				}
				break;

			case GD_TEMPLATE_BLOCKGROUP_WHOWEARE:

				// Allow to override with custom blocks
				$ret = array_merge(
					$ret,
					apply_filters(
						'GD_Template_Processor_CustomBlockGroups:blocks:whoweare',
						array(
							GD_TEMPLATE_BLOCK_WHOWEARE_SCROLL_DETAILS
						)
					)
				);
				break;

			case GD_TEMPLATE_BLOCKGROUP_OURSPONSORS:

				$ret[] = GD_TEMPLATE_BLOCK_OURSPONSORSINTRO;
				$ret[] = GD_TEMPLATE_BLOCK_OURSPONSORS_SCROLL;
				$ret[] = GD_TEMPLATE_BLOCK_OURSUPPORTERS_SCROLL;
				break;
		}

		return $ret;
	}

	function get_blockgroup_blockgroups($template_id) {

		$ret = parent::get_blockgroup_blockgroups($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_BLOCKGROUP_HOME_WELCOME:
			// case GD_TEMPLATE_BLOCKGROUP_HOME_COMPACTWELCOME:

				$ret[] = GD_TEMPLATE_BLOCKGROUP_HOME_WELCOMEACCOUNT;
				break;

			case GD_TEMPLATE_BLOCKGROUP_HOME_INSTITUTIONALWELCOME:

				$ret[] = GD_TEMPLATE_BLOCKGROUP_HOME_BLOGNEWSLETTER;
				break;

			case GD_TEMPLATE_BLOCKGROUP_HOME_TOP:

				$ret[] = GD_TEMPLATE_BLOCKGROUP_HOME_COMPACTWELCOME; //GD_TEMPLATE_BLOCKGROUP_HOME_WELCOME;

				// Allow MESYM to override this
				if ($widgetarea = apply_filters(
					'GD_Template_Processor_CustomBlockGroups:hometop:blockgroups:widget',
					GD_TEMPLATE_BLOCKGROUP_HOME_WIDGETAREA
				)) {
					$ret[] = $widgetarea;
				}
				break;

			case GD_TEMPLATE_BLOCKGROUP_AUTHOR_TOP:

				$ret[] = GD_TEMPLATE_BLOCKGROUP_AUTHOR_DESCRIPTION;
					
				// Allow MESYM to override this
				if ($widgetarea = apply_filters(
					'GD_Template_Processor_CustomBlockGroups:authortop:blockgroups:widget',
					GD_TEMPLATE_BLOCKGROUP_AUTHOR_WIDGETAREA
				)) {
					$ret[] = $widgetarea;
				}
				break;
		}

		return $ret;
	}

	function get_block_jsmethod($template_id, $atts) {

		$ret = parent::get_block_jsmethod($template_id, $atts);

		switch ($template_id) {

			case GD_TEMPLATE_BLOCKGROUP_HOME_WELCOME:
			case GD_TEMPLATE_BLOCKGROUP_HOME_COMPACTWELCOME:
			case GD_TEMPLATE_BLOCKGROUP_HOME_INSTITUTIONALWELCOME:
			case GD_TEMPLATE_BLOCKGROUP_HOME_WELCOMEBLOG:

				// It will add class "in" through .js if there is no cookie
				$this->add_jsmethod($ret, 'cookieToggleClass');
				break;
		}

		return $ret;
	}

	protected function get_blocksections_classes($template_id) {

		$ret = parent::get_blocksections_classes($template_id);

		switch ($template_id) {

			// case GD_TEMPLATE_BLOCKGROUP_HOME_WIDGETAREA:
			// case GD_TEMPLATE_BLOCKGROUP_AUTHOR_WIDGETAREA:

			// 	// Allow the ThemeStyle Expansive to make it into 2 columns
			// 	if (apply_filters(POP_HOOK_BLOCKGROUP_TOPWIDGETS_INCOLUMNS, false)) {
			// 		$ret['blocksection-extensions'] = 'row';
			// 	}
			// 	break;

			case GD_TEMPLATE_BLOCKGROUP_HOME_WELCOMEACCOUNT:
			case GD_TEMPLATE_BLOCKGROUP_HOME_BLOGNEWSLETTER:

				$ret['blocksection-extensions'] = 'row';
				break;

			case GD_TEMPLATE_BLOCKGROUP_HOME_WELCOME:
			case GD_TEMPLATE_BLOCKGROUP_HOME_COMPACTWELCOME:
			case GD_TEMPLATE_BLOCKGROUP_HOME_INSTITUTIONALWELCOME:
			case GD_TEMPLATE_BLOCKGROUP_HOME_WELCOMEBLOG:

				// It will add class "in" through .js if there is no cookie
				$ret['blocksection-extensions'] = 'collapse';
				break;

			case GD_TEMPLATE_BLOCKGROUP_AUTHOR_DESCRIPTION:

				$ret['blocksection-extensions'] = 'collapse in row row-item';
				break;

			// case GD_TEMPLATE_BLOCKGROUP_WHOWEARE:
			// case GD_TEMPLATE_BLOCKGROUP_OURSPONSORS:

			// 	$ret['controlgroup-top'] = 'top pull-right';
			// 	break;
		}

		return $ret;
	}

	function init_atts($template_id, &$atts) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCKGROUP_AUTHOR_DESCRIPTION:

				$this->append_att($template_id, $atts, 'class', 'blockgroup-author-description');
				break;

			case GD_TEMPLATE_BLOCKGROUP_HOME_TOP:

				$this->append_att($template_id, $atts, 'class', 'blockgroup-hometop');
				break;

			case GD_TEMPLATE_BLOCKGROUP_AUTHOR_TOP:

				$this->append_att($template_id, $atts, 'class', 'blockgroup-authortop');
				break;

			case GD_TEMPLATE_BLOCKGROUP_HOME_WELCOMEACCOUNT:

				// Do not show if the user is logged in
				$this->append_att($template_id, $atts, 'class', 'visible-notloggedin');

				// Give it some formatting
				$this->append_att($template_id, $atts, 'class', 'well well-sm');
				break;

			// case GD_TEMPLATE_BLOCKGROUP_HOME_BLOGNEWSLETTER:

			// 	$this->append_att($template_id, $atts, 'class', 'well well-sm');
			// 	break;

			case GD_TEMPLATE_BLOCKGROUP_HOME_WELCOME:
			case GD_TEMPLATE_BLOCKGROUP_HOME_COMPACTWELCOME:
			case GD_TEMPLATE_BLOCKGROUP_HOME_INSTITUTIONALWELCOME:
			case GD_TEMPLATE_BLOCKGROUP_HOME_WELCOMEBLOG:
			
				$this->append_att($template_id, $atts, 'class', 'blockgroup-home-welcome');
		
				// Set the params for the cookie: show the welcome message, until the user clicks on "Close"
				$frontend_id = $this->get_frontend_id($template_id, $atts);
				$target = '#'.$frontend_id.'>.blocksection-extensions';
				$deletecookiebtn = '#'.$frontend_id.'-expand>a';
				$setcookiebtn = '#'.$frontend_id.'-collapse>a';/*, #'.$frontend_id.'-collapsebottom>a';*/
				$this->merge_att($template_id, $atts, 'params', array(
					'data-cookieid' => $template_id,
					'data-cookietarget' => $target,
					'data-cookieclass' => 'in',
					'data-deletecookiebtn' => $deletecookiebtn,
					'data-setcookiebtn' => $setcookiebtn,
				));
				break;
		}
			
		return parent::init_atts($template_id, $atts);
	}

	function init_atts_blockgroup_block($blockgroup, $blockgroup_block, &$blockgroup_block_atts, $blockgroup_atts) {

		switch ($blockgroup) {

			case GD_TEMPLATE_BLOCKGROUP_HOME_WELCOME:
			case GD_TEMPLATE_BLOCKGROUP_HOME_COMPACTWELCOME:
			case GD_TEMPLATE_BLOCKGROUP_HOME_INSTITUTIONALWELCOME:
			case GD_TEMPLATE_BLOCKGROUP_HOME_WELCOMEBLOG:
			case GD_TEMPLATE_BLOCKGROUP_AUTHOR_DESCRIPTION:

				$is_home = ($blockgroup == GD_TEMPLATE_BLOCKGROUP_HOME_WELCOME || $blockgroup == GD_TEMPLATE_BLOCKGROUP_HOME_COMPACTWELCOME || $blockgroup == GD_TEMPLATE_BLOCKGROUP_HOME_INSTITUTIONALWELCOME || $blockgroup == GD_TEMPLATE_BLOCKGROUP_HOME_WELCOMEBLOG);

				if (
					($is_home && $blockgroup_block == GD_TEMPLATE_BLOCK_HOMEWELCOME) 
					||
					($blockgroup == GD_TEMPLATE_BLOCKGROUP_AUTHOR_DESCRIPTION)
					) {
					$target = '#'.$this->get_frontend_id($blockgroup, $blockgroup_atts).'>.blocksection-extensions';
					$description = sprintf(
						'<span id="%s" class="top-collapse"><a data-toggle="collapse" href="%s" aria-expanded="false" class="close" title="%s">%s</a></span>',
						$this->get_frontend_id($blockgroup, $blockgroup_atts).'-collapse',
						$target,
						__('Close', 'poptheme-wassup'),
						'<i class="fa fa-close"></i>'
					);

					if ($is_home) {
						
						// Allow qTrans to add the language links
						$description = apply_filters(
							'GD_Template_Processor_CustomBlockGroups:homewelcometitle',
							$description,
							$blockgroup
						);
					}
					$this->append_att($blockgroup_block, $blockgroup_block_atts, 'description', $description);	
				}
				break;

			case GD_TEMPLATE_BLOCKGROUP_HOME_WIDGETAREA:
			case GD_TEMPLATE_BLOCKGROUP_AUTHOR_WIDGETAREA:
			case GD_TEMPLATE_BLOCKGROUP_TAG_WIDGETAREA:

				// External Injection
				$blockgroup_block_atts = apply_filters('GD_Template_Processor_CustomBlockGroups:blocks:atts', $blockgroup_block_atts, $blockgroup_block, $blockgroup, $blockgroup_atts, $this);
				break;

			// case GD_TEMPLATE_BLOCKGROUP_HOME_WIDGETAREA:

			// 	if ($blockgroup_block == GD_TEMPLATE_BLOCK_USERS_CAROUSEL) {

			// 		// Make it lazy-load
			// 		$this->add_att($blockgroup_block, $blockgroup_block_atts, 'content-loaded', false);
			// 	}
			// 	break;
		
			// case GD_TEMPLATE_BLOCKGROUP_AUTHOR_WIDGETAREA:

			// 	// The 2 blocks inside: don't show if they are empty and showing them in rows
			// 	if (!apply_filters(POP_HOOK_BLOCKGROUP_TOPWIDGETS_INCOLUMNS, false)) {
					
			// 		$this->add_att($blockgroup_block, $blockgroup_block_atts, 'hidden-if-empty', true);
			// 	}
			// 	break;

			// case GD_TEMPLATE_BLOCKGROUP_TAG_WIDGETAREA:

			// 	$this->add_att($blockgroup_block, $blockgroup_block_atts, 'hidden-if-empty', true);
			// 	break;
		
			case GD_TEMPLATE_BLOCKGROUP_HOME_WELCOMEACCOUNT:

				$classes = array(
					GD_TEMPLATE_BLOCK_MENU_HOME_USERNOTLOGGEDIN => 'col-md-6',
					GD_TEMPLATE_BLOCK_NEWSLETTER => 'col-md-6',
				);
				$class = $classes[$blockgroup_block];
				$this->append_att($blockgroup_block, $blockgroup_block_atts, 'class', $class);

				if ($blockgroup_block == GD_TEMPLATE_BLOCK_MENU_HOME_USERNOTLOGGEDIN) {

					$title = sprintf(
						'<h3>%s</h3>',
						__('Log in or Register', 'poptheme-wassup')
						// sprintf(
						// 	__('Log in/Register in %s', 'poptheme-wassup'),
						// 	get_bloginfo('name')
						// )
					);
					$this->add_att($blockgroup_block, $blockgroup_block_atts, 'description', $title);
				}
				break;
		
			case GD_TEMPLATE_BLOCKGROUP_HOME_BLOGNEWSLETTER:

				$classes = array(
					GD_TEMPLATE_BLOCK_BLOG_CAROUSEL => 'col-md-8',
					GD_TEMPLATE_BLOCK_NEWSLETTER => 'col-md-4',
				);
				$class = $classes[$blockgroup_block];
				$this->append_att($blockgroup_block, $blockgroup_block_atts, 'class', $class);
				break;
		}

		switch ($blockgroup) {

			case GD_TEMPLATE_BLOCKGROUP_HOME_WELCOME:
			case GD_TEMPLATE_BLOCKGROUP_HOME_COMPACTWELCOME:
			case GD_TEMPLATE_BLOCKGROUP_HOME_INSTITUTIONALWELCOME:
			case GD_TEMPLATE_BLOCKGROUP_HOME_WELCOMEBLOG:

				if ($blockgroup_block == GD_TEMPLATE_BLOCK_HOMEWELCOME) {
					
					$this->append_att($blockgroup_block, $blockgroup_block_atts, 'class', 'jumbotron text-center');
				}
				break;
		}

		switch ($blockgroup) {

			// case GD_TEMPLATE_BLOCKGROUP_HOME_WIDGETAREA:

			// 	// Allow the ThemeStyle Expansive to make it into 2 columns
			// 	if (apply_filters(POP_HOOK_BLOCKGROUP_TOPWIDGETS_INCOLUMNS, false)) {
				
			// 		$classes = array(
			// 			GD_TEMPLATE_BLOCK_EVENTS_CAROUSEL => 'col-sm-8',
			// 			GD_TEMPLATE_BLOCK_USERS_CAROUSEL => 'col-sm-4',
			// 		);
			// 		$this->append_att($blockgroup_block, $blockgroup_block_atts, 'class', $classes[$blockgroup_block]);
			// 	}
			// 	break;

			// case GD_TEMPLATE_BLOCKGROUP_AUTHOR_WIDGETAREA:

			// 	// Allow the ThemeStyle Expansive to make it into 2 columns
			// 	if (apply_filters(POP_HOOK_BLOCKGROUP_TOPWIDGETS_INCOLUMNS, false)) {
				
			// 		// Only if it is a community there will be 2 columns: events and members. Otherwise, only events.
			// 		global $author;
			// 		$classes = array(
			// 			GD_TEMPLATE_BLOCK_AUTHOREVENTS_CAROUSEL => gd_ure_is_community($author) ? 'col-sm-8 col-sm-pull-4' : 'col-sm-12',
			// 			GD_TEMPLATE_BLOCK_AUTHORMEMBERS_CAROUSEL => 'col-sm-4 col-sm-push-8',
			// 		);
			// 		$this->append_att($blockgroup_block, $blockgroup_block_atts, 'class', $classes[$blockgroup_block]);
			// 	}
			// 	break;

			case GD_TEMPLATE_BLOCKGROUP_HOME_WELCOME:
			// case GD_TEMPLATE_BLOCKGROUP_HOME_COMPACTWELCOME:
			case GD_TEMPLATE_BLOCKGROUP_HOME_BLOGNEWSLETTER:

				if (
					($blockgroup == GD_TEMPLATE_BLOCKGROUP_HOME_WELCOME && $blockgroup_block == GD_TEMPLATE_BLOCK_USERLOGGEDINWELCOME) ||
					((/*$blockgroup == GD_TEMPLATE_BLOCKGROUP_HOME_COMPACTWELCOME || */$blockgroup == GD_TEMPLATE_BLOCKGROUP_HOME_BLOGNEWSLETTER) && $blockgroup_block == GD_TEMPLATE_BLOCK_NEWSLETTER)
					) {

					$this->append_att($blockgroup_block, $blockgroup_block_atts, 'class', 'well well-sm');
				}
				break;

			case GD_TEMPLATE_BLOCKGROUP_AUTHOR_DESCRIPTION:

				// No title on the Description block
				$this->add_att($blockgroup_block, $blockgroup_block_atts, 'title', '');
				$this->append_att($blockgroup_block, $blockgroup_block_atts, 'class', 'col-xs-12');
				break;
		}

		return parent::init_atts_blockgroup_block($blockgroup, $blockgroup_block, $blockgroup_block_atts, $blockgroup_atts);
	}

	// protected function get_controlgroup_top($template_id) {

	// 	switch ($template_id) {

	// 		case GD_TEMPLATE_BLOCKGROUP_WHOWEARE:
	// 		case GD_TEMPLATE_BLOCKGROUP_OURSPONSORS:

	// 			return GD_TEMPLATE_CONTROLGROUP_BODYITEM;
	// 	}

	// 	return parent::get_controlgroup_top($template_id);
	// }
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_CustomBlockGroups();
