<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('POP_HOOK_TOPLEVEL_FRAMEPAGESECTIONS', 'toplevel-framepagesections');
define ('POP_HOOK_CUSTOMTOPLEVELS_ADDPAGETABS', 'hook-customtoplevels-addpagetabs');

class GD_Template_Processor_CustomTopLevels extends GD_Template_Processor_TopLevelsBase {

	function get_templates_to_process() {

		return array(
			GD_TEMPLATE_TOPLEVEL_HOME,
			GD_TEMPLATE_TOPLEVEL_TAG,
			GD_TEMPLATE_TOPLEVEL_PAGE,
			GD_TEMPLATE_TOPLEVEL_SINGLE,
			GD_TEMPLATE_TOPLEVEL_AUTHOR,
			GD_TEMPLATE_TOPLEVEL_404
		);
	}

	private function get_jsondata_module($template_id) {

		$vars = GD_TemplateManager_Utils::get_vars();
		
		// Because many different templates share the same settings_id for both
		// `main` and `sideinfo`, for these cases we must return the corresponding
		// pagesection depending on the toplevel.
		$multiple = array(
			GD_TEMPLATEID_PAGESECTIONSETTINGSID_MAIN,
			GD_TEMPLATEID_PAGESECTIONSETTINGSID_SIDEINFO,
			GD_TEMPLATEID_PAGESECTIONSETTINGSID_QUICKVIEWMAIN,
			GD_TEMPLATEID_PAGESECTIONSETTINGSID_QUICKVIEWSIDEINFO,
			GD_TEMPLATEID_PAGESECTIONSETTINGSID_ADDONS,
			GD_TEMPLATEID_PAGESECTIONSETTINGSID_MODALS,
		);
		$pagesection = $vars['pagesection'];

		// When getting the typeahead data, the pagesection is not naturally added, so assume
		// by default that if no pagesection is present, the we are loading `main`
		if (!$pagesection) {
			$pagesection = GD_TEMPLATEID_PAGESECTIONSETTINGSID_MAIN;
		}
		if (in_array($pagesection, $multiple)) {
			
			$toplevel_pagesections = array();
			if ($pagesection == GD_TEMPLATEID_PAGESECTIONSETTINGSID_MAIN) {

				$toplevel_pagesections = array(
					GD_TEMPLATE_TOPLEVEL_HOME => GD_TEMPLATE_PAGESECTION_HOME,
					GD_TEMPLATE_TOPLEVEL_TAG => GD_TEMPLATE_PAGESECTION_TAG,
					GD_TEMPLATE_TOPLEVEL_PAGE => GD_TEMPLATE_PAGESECTION_PAGE,
					GD_TEMPLATE_TOPLEVEL_SINGLE => GD_TEMPLATE_PAGESECTION_SINGLE,
					GD_TEMPLATE_TOPLEVEL_AUTHOR => GD_TEMPLATE_PAGESECTION_AUTHOR,
					GD_TEMPLATE_TOPLEVEL_404 => GD_TEMPLATE_PAGESECTION_404,
				);
			}
			elseif ($pagesection == GD_TEMPLATEID_PAGESECTIONSETTINGSID_SIDEINFO) {

				$toplevel_pagesections = array(
					GD_TEMPLATE_TOPLEVEL_HOME => GD_TEMPLATE_PAGESECTION_SIDEINFO_HOME,//GD_TEMPLATE_PAGESECTION_SIDEINFO_EMPTY
					GD_TEMPLATE_TOPLEVEL_TAG => GD_TEMPLATE_PAGESECTION_SIDEINFO_TAG,//GD_TEMPLATE_PAGESECTION_SIDEINFO_EMPTY
					GD_TEMPLATE_TOPLEVEL_PAGE => GD_TEMPLATE_PAGESECTION_SIDEINFO_PAGE,
					GD_TEMPLATE_TOPLEVEL_SINGLE => GD_TEMPLATE_PAGESECTION_SIDEINFO_SINGLE,
					GD_TEMPLATE_TOPLEVEL_AUTHOR => GD_TEMPLATE_PAGESECTION_SIDEINFO_AUTHOR,
					GD_TEMPLATE_TOPLEVEL_404 => GD_TEMPLATE_PAGESECTION_SIDEINFO_EMPTY,
				);
			}
			elseif ($pagesection == GD_TEMPLATEID_PAGESECTIONSETTINGSID_QUICKVIEWMAIN) {

				$toplevel_pagesections = array(
					GD_TEMPLATE_TOPLEVEL_HOME => GD_TEMPLATE_PAGESECTION_QUICKVIEWHOME,
					GD_TEMPLATE_TOPLEVEL_TAG => GD_TEMPLATE_PAGESECTION_QUICKVIEWTAG,
					GD_TEMPLATE_TOPLEVEL_PAGE => GD_TEMPLATE_PAGESECTION_QUICKVIEWPAGE,
					GD_TEMPLATE_TOPLEVEL_SINGLE => GD_TEMPLATE_PAGESECTION_QUICKVIEWSINGLE,
					GD_TEMPLATE_TOPLEVEL_AUTHOR => GD_TEMPLATE_PAGESECTION_QUICKVIEWAUTHOR,
					GD_TEMPLATE_TOPLEVEL_404 => GD_TEMPLATE_PAGESECTION_QUICKVIEW404,
				);
			}
			elseif ($pagesection == GD_TEMPLATEID_PAGESECTIONSETTINGSID_QUICKVIEWSIDEINFO) {

				$toplevel_pagesections = array(
					GD_TEMPLATE_TOPLEVEL_HOME => GD_TEMPLATE_PAGESECTION_SIDEINFO_QUICKVIEWHOME,//GD_TEMPLATE_PAGESECTION_SIDEINFO_QUICKVIEWEMPTY,
					GD_TEMPLATE_TOPLEVEL_TAG => GD_TEMPLATE_PAGESECTION_SIDEINFO_QUICKVIEWTAG,//GD_TEMPLATE_PAGESECTION_SIDEINFO_QUICKVIEWEMPTY
					GD_TEMPLATE_TOPLEVEL_PAGE => GD_TEMPLATE_PAGESECTION_SIDEINFO_QUICKVIEWPAGE,
					GD_TEMPLATE_TOPLEVEL_SINGLE => GD_TEMPLATE_PAGESECTION_SIDEINFO_QUICKVIEWSINGLE,
					GD_TEMPLATE_TOPLEVEL_AUTHOR => GD_TEMPLATE_PAGESECTION_SIDEINFO_QUICKVIEWAUTHOR,
					GD_TEMPLATE_TOPLEVEL_404 => GD_TEMPLATE_PAGESECTION_SIDEINFO_QUICKVIEWEMPTY,
				);
			}
			elseif ($pagesection == GD_TEMPLATEID_PAGESECTIONSETTINGSID_ADDONS) {

				$toplevel_pagesections = array(
					GD_TEMPLATE_TOPLEVEL_HOME => GD_TEMPLATE_PAGESECTION_ADDONS_HOME,
					GD_TEMPLATE_TOPLEVEL_TAG => GD_TEMPLATE_PAGESECTION_ADDONS_TAG,
					GD_TEMPLATE_TOPLEVEL_PAGE => GD_TEMPLATE_PAGESECTION_ADDONS_PAGE,
					GD_TEMPLATE_TOPLEVEL_SINGLE => GD_TEMPLATE_PAGESECTION_ADDONS_SINGLE,
					GD_TEMPLATE_TOPLEVEL_AUTHOR => GD_TEMPLATE_PAGESECTION_ADDONS_AUTHOR,
					GD_TEMPLATE_TOPLEVEL_404 => GD_TEMPLATE_PAGESECTION_ADDONS_404,
				);
			}
			elseif ($pagesection == GD_TEMPLATEID_PAGESECTIONSETTINGSID_MODALS) {

				$toplevel_pagesections = array(
					GD_TEMPLATE_TOPLEVEL_HOME => GD_TEMPLATE_PAGESECTION_MODALS_HOME,
					GD_TEMPLATE_TOPLEVEL_TAG => GD_TEMPLATE_PAGESECTION_MODALS_TAG,
					GD_TEMPLATE_TOPLEVEL_PAGE => GD_TEMPLATE_PAGESECTION_MODALS_PAGE,
					GD_TEMPLATE_TOPLEVEL_SINGLE => GD_TEMPLATE_PAGESECTION_MODALS_SINGLE,
					GD_TEMPLATE_TOPLEVEL_AUTHOR => GD_TEMPLATE_PAGESECTION_MODALS_AUTHOR,
					GD_TEMPLATE_TOPLEVEL_404 => GD_TEMPLATE_PAGESECTION_MODALS_404,
				);
			}

			return $toplevel_pagesections[$template_id];
		}

		// Otherwise, the settings_id is already the name of the pagesection
		return $pagesection;
	}

	function get_modules($template_id) {

		$ret = parent::get_modules($template_id);

		$vars = GD_TemplateManager_Utils::get_vars();
		
		// If doing JSON for the block, just return that same pageSection it came from
		if ($vars['fetching-json-data']) {

			$ret[] = $this->get_jsondata_module($template_id);			
			return $ret;
		}

		// Always add the Operational pageSection
		$ret[] = GD_TEMPLATE_PAGESECTION_OPERATIONAL;
		$ret[] = GD_TEMPLATE_PAGESECTION_COMPONENTS;

		// Add pageSections respective to the provided target
		$target = $vars['target'];
		if ($target == GD_URLPARAM_TARGET_NAVIGATOR) {
			
			$ret[] = GD_TEMPLATE_PAGESECTION_NAVIGATOR;
		}
		elseif ($target == GD_URLPARAM_TARGET_ADDONS) {

			// IMPORTANT: Do ALWAYS place first the main pageSection (eg: GD_TEMPLATE_PAGESECTION_ADDONS_HOME)
			// and later the secondary ones (eg: GD_TEMPLATE_PAGESECTION_ADDONTABS_HOME), since the main one
			// will execute the `replicateTopLevel` js method, which must be called before calling `replicatePageSection` js method
			
			switch ($template_id) {

				case GD_TEMPLATE_TOPLEVEL_HOME:
				
					$ret[] = GD_TEMPLATE_PAGESECTION_ADDONS_HOME;
					$ret[] = GD_TEMPLATE_PAGESECTION_ADDONTABS_HOME;
					break;

				case GD_TEMPLATE_TOPLEVEL_TAG:
				
					$ret[] = GD_TEMPLATE_PAGESECTION_ADDONS_TAG;
					$ret[] = GD_TEMPLATE_PAGESECTION_ADDONTABS_TAG;
					break;

				case GD_TEMPLATE_TOPLEVEL_PAGE:

					$ret[] = GD_TEMPLATE_PAGESECTION_ADDONS_PAGE;
					$ret[] = GD_TEMPLATE_PAGESECTION_ADDONTABS_PAGE;
					break;

				case GD_TEMPLATE_TOPLEVEL_SINGLE:

					$ret[] = GD_TEMPLATE_PAGESECTION_ADDONS_SINGLE;
					$ret[] = GD_TEMPLATE_PAGESECTION_ADDONTABS_SINGLE;
					break;

				case GD_TEMPLATE_TOPLEVEL_AUTHOR:
					
					$ret[] = GD_TEMPLATE_PAGESECTION_ADDONS_AUTHOR;
					$ret[] = GD_TEMPLATE_PAGESECTION_ADDONTABS_AUTHOR;
					break;

				case GD_TEMPLATE_TOPLEVEL_404:

					$ret[] = GD_TEMPLATE_PAGESECTION_ADDONS_404;
					$ret[] = GD_TEMPLATE_PAGESECTION_ADDONTABS_404;
					break;
			}
		}
		elseif ($target == GD_URLPARAM_TARGET_MODALS) {
			
			switch ($template_id) {

				case GD_TEMPLATE_TOPLEVEL_HOME:
				
					$ret[] = GD_TEMPLATE_PAGESECTION_MODALS_HOME;
					break;

				case GD_TEMPLATE_TOPLEVEL_TAG:
				
					$ret[] = GD_TEMPLATE_PAGESECTION_MODALS_TAG;
					break;

				case GD_TEMPLATE_TOPLEVEL_PAGE:

					$ret[] = GD_TEMPLATE_PAGESECTION_MODALS_PAGE;
					break;

				case GD_TEMPLATE_TOPLEVEL_SINGLE:

					$ret[] = GD_TEMPLATE_PAGESECTION_MODALS_SINGLE;
					break;

				case GD_TEMPLATE_TOPLEVEL_AUTHOR:
					
					$ret[] = GD_TEMPLATE_PAGESECTION_MODALS_AUTHOR;
					break;

				case GD_TEMPLATE_TOPLEVEL_404:

					$ret[] = GD_TEMPLATE_PAGESECTION_MODALS_404;
					break;
			}
		}
		elseif ($target == GD_URLPARAM_TARGET_QUICKVIEW) {

			switch ($template_id) {

				case GD_TEMPLATE_TOPLEVEL_HOME:
				
					$ret[] = GD_TEMPLATE_PAGESECTION_QUICKVIEWHOME;
					$ret[] = GD_TEMPLATE_PAGESECTION_SIDEINFO_QUICKVIEWHOME;//GD_TEMPLATE_PAGESECTION_SIDEINFO_QUICKVIEWEMPTY;
					break;

				case GD_TEMPLATE_TOPLEVEL_TAG:
				
					$ret[] = GD_TEMPLATE_PAGESECTION_QUICKVIEWTAG;
					$ret[] = GD_TEMPLATE_PAGESECTION_SIDEINFO_QUICKVIEWTAG;//GD_TEMPLATE_PAGESECTION_SIDEINFO_QUICKVIEWEMPTY;
					break;

				case GD_TEMPLATE_TOPLEVEL_PAGE:

					$ret[] = GD_TEMPLATE_PAGESECTION_QUICKVIEWPAGE;
					$ret[] = GD_TEMPLATE_PAGESECTION_SIDEINFO_QUICKVIEWPAGE;
					break;

				case GD_TEMPLATE_TOPLEVEL_SINGLE:

					$ret[] = GD_TEMPLATE_PAGESECTION_QUICKVIEWSINGLE;
					$ret[] = GD_TEMPLATE_PAGESECTION_SIDEINFO_QUICKVIEWSINGLE;
					break;

				case GD_TEMPLATE_TOPLEVEL_AUTHOR:
					
					$ret[] = GD_TEMPLATE_PAGESECTION_QUICKVIEWAUTHOR;
					$ret[] = GD_TEMPLATE_PAGESECTION_SIDEINFO_QUICKVIEWAUTHOR;
					break;

				case GD_TEMPLATE_TOPLEVEL_404:

					$ret[] = GD_TEMPLATE_PAGESECTION_QUICKVIEW404;
					$ret[] = GD_TEMPLATE_PAGESECTION_SIDEINFO_QUICKVIEWEMPTY;
					break;
			}
		}
		
		// These ones will be embedded always, no need to transfer to all different thememode.php files
		if (GD_TemplateManager_Utils::loading_frame() || $target == GD_URLPARAM_TARGET_MAIN) {

			$add_pagetabs = apply_filters(POP_HOOK_CUSTOMTOPLEVELS_ADDPAGETABS, true);
			switch ($template_id) {

				case GD_TEMPLATE_TOPLEVEL_HOME:
				
					$ret[] = GD_TEMPLATE_PAGESECTION_HOME;
					$ret[] = GD_TEMPLATE_PAGESECTION_SIDEINFO_HOME;//GD_TEMPLATE_PAGESECTION_SIDEINFO_EMPTY;
					if ($add_pagetabs) {
						$ret[] = GD_TEMPLATE_PAGESECTION_PAGETABS_HOME;	
					}
					break;

				case GD_TEMPLATE_TOPLEVEL_TAG:
				
					$ret[] = GD_TEMPLATE_PAGESECTION_TAG;
					$ret[] = GD_TEMPLATE_PAGESECTION_SIDEINFO_TAG;//GD_TEMPLATE_PAGESECTION_SIDEINFO_EMPTY;
					if ($add_pagetabs) {
						$ret[] = GD_TEMPLATE_PAGESECTION_PAGETABS_TAG;	
					}
					break;

				case GD_TEMPLATE_TOPLEVEL_PAGE:

					$ret[] = GD_TEMPLATE_PAGESECTION_PAGE;
					$ret[] = GD_TEMPLATE_PAGESECTION_HOVER;
					$ret[] = GD_TEMPLATE_PAGESECTION_SIDEINFO_PAGE;
					if ($add_pagetabs) {
						$ret[] = GD_TEMPLATE_PAGESECTION_PAGETABS_PAGE;	
					}
					break;

				case GD_TEMPLATE_TOPLEVEL_SINGLE:

					$ret[] = GD_TEMPLATE_PAGESECTION_SINGLE;
					$ret[] = GD_TEMPLATE_PAGESECTION_SIDEINFO_SINGLE;
					if ($add_pagetabs) {
						$ret[] = GD_TEMPLATE_PAGESECTION_PAGETABS_SINGLE;	
					}
					break;

				case GD_TEMPLATE_TOPLEVEL_AUTHOR:
					
					$ret[] = GD_TEMPLATE_PAGESECTION_AUTHOR;
					$ret[] = GD_TEMPLATE_PAGESECTION_SIDEINFO_AUTHOR;
					if ($add_pagetabs) {
						$ret[] = GD_TEMPLATE_PAGESECTION_PAGETABS_AUTHOR;	
					}
					break;

				case GD_TEMPLATE_TOPLEVEL_404:

					$ret[] = GD_TEMPLATE_PAGESECTION_404;
					$ret[] = GD_TEMPLATE_PAGESECTION_SIDEINFO_EMPTY;
					if ($add_pagetabs) {
						$ret[] = GD_TEMPLATE_PAGESECTION_PAGETABS_404;	
					}
					break;
			}
		}
		// If requesting JSON Main Page Section then no need to embed the frame elements
		if (GD_TemplateManager_Utils::loading_frame()) {

			// The framepagesections must be defined in the selected PoP Theme
			$framepagesections = apply_filters(POP_HOOK_TOPLEVEL_FRAMEPAGESECTIONS, array(), $template_id);

			$ret = array_merge(
				$ret,
				$framepagesections
			);
		}

		$ret = array_unique($ret);

		return $ret;
	}

	protected function get_toplevel_iohandler() {

		$vars = GD_TemplateManager_Utils::get_vars();
		if (!$vars['fetching-json-data']) {

			return GD_CUSTOM_DATALOAD_IOHANDLER_TOPLEVEL;			
		}
		return parent::get_toplevel_iohandler();
	}

	protected function get_user_info($template_id, $atts) {
			
		$vars = GD_TemplateManager_Utils::get_vars();
		switch ($template_id) {
		
			case GD_TEMPLATE_TOPLEVEL_PAGE:

				$post = $vars['global-state']['post']/*global $post*/;
				switch ($post->ID) {

					case POP_WPAPI_PAGE_LOGIN:
					case POP_WPAPI_PAGE_LOGOUT:

						if (doing_post()) {
							return true;
						}
						break;
				}	
		}
		
		return parent::get_user_info($template_id, $atts);
	}

	function get_redirect_url($template_id) {
	
		$vars = GD_TemplateManager_Utils::get_vars();
		switch ($template_id) {
		
			case GD_TEMPLATE_TOPLEVEL_PAGE:

				$post = $vars['global-state']['post']/*global $post*/;
				switch ($post->ID) {

					case POP_WPAPI_PAGE_EDITPROFILE:
						if (gd_ure_is_organization($user_id)) {
							return get_permalink(POP_URE_POPPROCESSORS_PAGE_EDITPROFILEORGANIZATION);
						}
						elseif (gd_ure_is_individual($user_id)) {
							return get_permalink(POP_URE_POPPROCESSORS_PAGE_EDITPROFILEINDIVIDUAL);
						}
						break;
					case POP_COREPROCESSORS_PAGE_MYPROFILE:
						
						// If logged in, redirect to user Profile
						if ($vars['global-state']['is-user-logged-in']/*is_user_logged_in()*/) {
							return get_author_posts_url($vars['global-state']['current-user-id']/*get_current_user_id()*/);
						}
						break;
				}	
		}
		
		return parent::get_redirect_url($template_id);
	}
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_CustomTopLevels();
