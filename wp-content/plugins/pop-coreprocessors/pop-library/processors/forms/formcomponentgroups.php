<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_FORMCOMPONENTGROUP_EDITOR', PoP_ServerUtils::get_template_definition('formcomponenteditorgroup'));
define ('GD_TEMPLATE_FORMCOMPONENTGROUP_TEXTAREAEDITOR', PoP_ServerUtils::get_template_definition('formcomponent-textarea-editorgroup'));
define ('GD_TEMPLATE_FORMCOMPONENTGROUP_FEATUREDIMAGE', PoP_ServerUtils::get_template_definition('formcomponentgroup-featuredimage'));
define ('GD_TEMPLATE_FORMCOMPONENTGROUP_SELECTABLETYPEAHEAD_REFERENCES', PoP_ServerUtils::get_template_definition('formcomponentgroup-selectabletypeahead-references'));
define ('GD_TEMPLATE_FORMCOMPONENTGROUP_SELECTABLETYPEAHEAD_POSTAUTHORS', PoP_ServerUtils::get_template_definition('formcomponentgroup-selectabletypeahead-postauthors'));
define ('GD_TEMPLATE_FORMCOMPONENTGROUP_SELECTABLETYPEAHEAD_POSTCOAUTHORS', PoP_ServerUtils::get_template_definition('formcomponentgroup-selectabletypeahead-postcoauthors'));
define ('GD_TEMPLATE_FORMCOMPONENTGROUP_DATERANGETIMEPICKER', PoP_ServerUtils::get_template_definition('formcomponentgroup-daterangetimepicker'));
define ('GD_TEMPLATE_FORMCOMPONENTGROUP_CAPTCHA', PoP_ServerUtils::get_template_definition('formcomponentgroup-captcha'));
define ('GD_TEMPLATE_FORMCOMPONENTGROUP_SETTINGSFORMAT', PoP_ServerUtils::get_template_definition('formcomponentgroup-settingsformat'));
define ('GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_DATERANGEPICKER', PoP_ServerUtils::get_template_definition('filterformcomponentgroup-date'));
define ('GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_MODERATEDPOSTSTATUS', PoP_ServerUtils::get_template_definition('filterformcomponentgroup-moderatedpoststatus'));
define ('GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_UNMODERATEDPOSTSTATUS', PoP_ServerUtils::get_template_definition('filterformcomponentgroup-unmoderatedpoststatus'));
define ('GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_ORDERUSER', PoP_ServerUtils::get_template_definition('filterformcomponentgroup-order-user'));
define ('GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_ORDERPOST', PoP_ServerUtils::get_template_definition('filterformcomponentgroup-order-post'));
define ('GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_ORDERTAG', PoP_ServerUtils::get_template_definition('filterformcomponentgroup-order-tag'));
define ('GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_SEARCH', PoP_ServerUtils::get_template_definition('filterformcomponentgroup-searchfor'));
define ('GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_HASHTAGS', PoP_ServerUtils::get_template_definition('filterformcomponentgroup-hashtags'));
define ('GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_NAME', PoP_ServerUtils::get_template_definition('filterformcomponentgroup-nombre'));
define ('GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_TYPEAHEAD_PROFILES', PoP_ServerUtils::get_template_definition('filterformcomponentgroup-profiles'));
define ('GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_TYPEAHEAD_REFERENCES', PoP_ServerUtils::get_template_definition('filterformcomponentgroup-references'));
define ('GD_TEMPLATE_SUBMITBUTTONFORMGROUP_SEARCH', PoP_ServerUtils::get_template_definition('submitbuttonformgroup-search'));
define ('GD_TEMPLATE_FORMCOMPONENTGROUP_EMAILS', PoP_ServerUtils::get_template_definition('ure-formcomponentgroup-emails'));
define ('GD_TEMPLATE_FORMCOMPONENTGROUP_SENDERNAME', PoP_ServerUtils::get_template_definition('ure-formcomponentgroup-sendername'));
define ('GD_TEMPLATE_FORMCOMPONENTGROUP_ADDITIONALMESSAGE', PoP_ServerUtils::get_template_definition('ure-formcomponentgroup-additionalmessage'));

class GD_Template_Processor_FormComponentGroups extends GD_Template_Processor_FormComponentGroupsBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_FORMCOMPONENTGROUP_EDITOR,
			GD_TEMPLATE_FORMCOMPONENTGROUP_TEXTAREAEDITOR,
			GD_TEMPLATE_FORMCOMPONENTGROUP_FEATUREDIMAGE,
			GD_TEMPLATE_FORMCOMPONENTGROUP_SELECTABLETYPEAHEAD_REFERENCES,
			GD_TEMPLATE_FORMCOMPONENTGROUP_SELECTABLETYPEAHEAD_POSTAUTHORS,
			GD_TEMPLATE_FORMCOMPONENTGROUP_SELECTABLETYPEAHEAD_POSTCOAUTHORS,
			GD_TEMPLATE_FORMCOMPONENTGROUP_DATERANGETIMEPICKER,
			GD_TEMPLATE_FORMCOMPONENTGROUP_CAPTCHA,
			// GD_TEMPLATE_FORMCOMPONENTGROUP_FILEUPLOAD_PICTURE,
			GD_TEMPLATE_FORMCOMPONENTGROUP_SETTINGSFORMAT,
			GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_DATERANGEPICKER,
			GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_MODERATEDPOSTSTATUS,
			GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_UNMODERATEDPOSTSTATUS,
			GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_ORDERUSER,
			GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_ORDERPOST,
			GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_ORDERTAG,
			GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_SEARCH,
			GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_HASHTAGS,
			GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_NAME,
			GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_TYPEAHEAD_PROFILES,
			GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_TYPEAHEAD_REFERENCES,
			GD_TEMPLATE_SUBMITBUTTONFORMGROUP_SEARCH,
			GD_TEMPLATE_FORMCOMPONENTGROUP_EMAILS,
			GD_TEMPLATE_FORMCOMPONENTGROUP_SENDERNAME,
			GD_TEMPLATE_FORMCOMPONENTGROUP_ADDITIONALMESSAGE,
		);
	}

	function get_label_class($template_id) {

		$ret = parent::get_label_class($template_id);

		switch ($template_id) {
			
			case GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_DATERANGEPICKER:
			case GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_MODERATEDPOSTSTATUS:
			case GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_UNMODERATEDPOSTSTATUS:
			case GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_ORDERUSER:
			case GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_ORDERPOST:
			case GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_ORDERTAG:
			case GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_SEARCH:
			case GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_HASHTAGS:
			case GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_NAME:
			case GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_TYPEAHEAD_PROFILES:
			case GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_TYPEAHEAD_REFERENCES:

				$ret .= ' col-sm-2';
				break;
		}

		return $ret;
	}
	function get_formcontrol_class($template_id) {

		$ret = parent::get_formcontrol_class($template_id);

		switch ($template_id) {
			
			case GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_DATERANGEPICKER:
			case GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_MODERATEDPOSTSTATUS:
			case GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_UNMODERATEDPOSTSTATUS:
			case GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_ORDERUSER:
			case GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_ORDERPOST:
			case GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_ORDERTAG:
			case GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_SEARCH:
			case GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_HASHTAGS:
			case GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_NAME:
			case GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_TYPEAHEAD_PROFILES:
			case GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_TYPEAHEAD_REFERENCES:

				$ret .= ' col-sm-10';
				break;

			case GD_TEMPLATE_SUBMITBUTTONFORMGROUP_SEARCH:

				$ret .= ' col-sm-offset-2 col-sm-10';
				break;
		}

		return $ret;
	}

	function get_component($template_id) {

		$components = array(
			GD_TEMPLATE_FORMCOMPONENTGROUP_EDITOR => GD_TEMPLATE_FORMCOMPONENT_EDITOR,
			GD_TEMPLATE_FORMCOMPONENTGROUP_TEXTAREAEDITOR => GD_TEMPLATE_FORMCOMPONENT_TEXTAREAEDITOR,
			GD_TEMPLATE_FORMCOMPONENTGROUP_FEATUREDIMAGE => GD_TEMPLATE_FORMCOMPONENT_FEATUREDIMAGE,
			GD_TEMPLATE_FORMCOMPONENTGROUP_SELECTABLETYPEAHEAD_REFERENCES => GD_TEMPLATE_FORMCOMPONENT_SELECTABLETYPEAHEAD_REFERENCES,
			GD_TEMPLATE_FORMCOMPONENTGROUP_SELECTABLETYPEAHEAD_POSTAUTHORS => GD_TEMPLATE_FORMCOMPONENT_SELECTABLETYPEAHEAD_POSTAUTHORS,
			GD_TEMPLATE_FORMCOMPONENTGROUP_SELECTABLETYPEAHEAD_POSTCOAUTHORS => GD_TEMPLATE_FORMCOMPONENT_SELECTABLETYPEAHEAD_POSTCOAUTHORS,
			GD_TEMPLATE_FORMCOMPONENTGROUP_DATERANGETIMEPICKER => GD_TEMPLATE_FORMCOMPONENT_DATERANGETIMEPICKER,
			GD_TEMPLATE_FORMCOMPONENTGROUP_CAPTCHA => GD_TEMPLATE_FORMCOMPONENT_CAPTCHA,
			// GD_TEMPLATE_FORMCOMPONENTGROUP_FILEUPLOAD_PICTURE => GD_TEMPLATE_FORMCOMPONENT_FILEUPLOAD_PICTURE,
			GD_TEMPLATE_FORMCOMPONENTGROUP_SETTINGSFORMAT => GD_TEMPLATE_FORMCOMPONENT_SETTINGSFORMAT,
			GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_DATERANGEPICKER => GD_TEMPLATE_FILTERFORMCOMPONENT_DATERANGEPICKER,
			GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_MODERATEDPOSTSTATUS => GD_TEMPLATE_FILTERFORMCOMPONENT_MODERATEDPOSTSTATUS,
			GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_UNMODERATEDPOSTSTATUS => GD_TEMPLATE_FILTERFORMCOMPONENT_UNMODERATEDPOSTSTATUS,
			GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_ORDERUSER => GD_TEMPLATE_FILTERFORMCOMPONENT_ORDERUSER,
			GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_ORDERPOST => GD_TEMPLATE_FILTERFORMCOMPONENT_ORDERPOST,
			GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_ORDERTAG => GD_TEMPLATE_FILTERFORMCOMPONENT_ORDERTAG,
			GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_SEARCH => GD_TEMPLATE_FILTERFORMCOMPONENT_SEARCH,
			GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_HASHTAGS => GD_TEMPLATE_FILTERFORMCOMPONENT_HASHTAGS,
			GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_NAME => GD_TEMPLATE_FILTERFORMCOMPONENT_NAME,
			GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_TYPEAHEAD_PROFILES => GD_TEMPLATE_FILTERFORMCOMPONENT_TYPEAHEAD_PROFILES,
			GD_TEMPLATE_FILTERFORMCOMPONENTGROUP_TYPEAHEAD_REFERENCES => GD_TEMPLATE_FILTERFORMCOMPONENT_TYPEAHEAD_REFERENCES,
			GD_TEMPLATE_SUBMITBUTTONFORMGROUP_SEARCH => GD_TEMPLATE_SUBMITBUTTON_SEARCH,
			GD_TEMPLATE_FORMCOMPONENTGROUP_EMAILS => GD_TEMPLATE_FORMCOMPONENT_EMAILS,
			GD_TEMPLATE_FORMCOMPONENTGROUP_SENDERNAME => GD_TEMPLATE_FORMCOMPONENT_SENDERNAME,
			GD_TEMPLATE_FORMCOMPONENTGROUP_ADDITIONALMESSAGE => GD_TEMPLATE_FORMCOMPONENT_ADDITIONALMESSAGE,
		);

		if ($component = $components[$template_id]) {

			return $component;
		}
		
		return parent::get_component($template_id);
	}
	function use_component_configuration($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_FORMCOMPONENTGROUP_CAPTCHA:
			// case GD_TEMPLATE_FORMCOMPONENTGROUP_FILEUPLOAD_PICTURE:
			case GD_TEMPLATE_SUBMITBUTTONFORMGROUP_SEARCH:
				
				return false;
		}
		
		return parent::use_component_configuration($template_id);
	}

	function get_block_jsmethod($template_id, $atts) {

		$ret = parent::get_block_jsmethod($template_id, $atts);

		switch ($template_id) {
		
			case GD_TEMPLATE_FORMCOMPONENTGROUP_CAPTCHA:
			case GD_TEMPLATE_FORMCOMPONENTGROUP_SENDERNAME:

				$this->add_jsmethod($ret, 'addDomainClass');
				break;
		}
		
		return $ret;
	}
	function get_js_setting($template_id, $atts) {

		$ret = parent::get_js_setting($template_id, $atts);

		switch ($template_id) {
		
			case GD_TEMPLATE_FORMCOMPONENTGROUP_CAPTCHA:
			case GD_TEMPLATE_FORMCOMPONENTGROUP_SENDERNAME:

				// For function addDomainClass
				$ret['prefix'] = 'visible-notloggedin-';
				break;
		}

		return $ret;
	}

	function init_atts($template_id, &$atts) {

		switch ($template_id) {

			case GD_TEMPLATE_FORMCOMPONENTGROUP_CAPTCHA:
			case GD_TEMPLATE_FORMCOMPONENTGROUP_SENDERNAME:
				
				$this->append_att($template_id, $atts, 'class', 'visible-notloggedin');

				// If we don't use the loggedinuser-data, then show the inputs always
				if (!PoP_FormUtils::use_loggedinuser_data()) {
					$this->append_att($template_id, $atts, 'class', 'visible-always');
				}
				break;

			case GD_TEMPLATE_FORMCOMPONENTGROUP_SELECTABLETYPEAHEAD_REFERENCES:
			case GD_TEMPLATE_FORMCOMPONENTGROUP_SELECTABLETYPEAHEAD_POSTAUTHORS:
			case GD_TEMPLATE_FORMCOMPONENTGROUP_SELECTABLETYPEAHEAD_POSTCOAUTHORS:

				$placeholders = array(
					GD_TEMPLATE_FORMCOMPONENTGROUP_SELECTABLETYPEAHEAD_REFERENCES => __('Type post title...', 'pop-coreprocessors'),
					GD_TEMPLATE_FORMCOMPONENTGROUP_SELECTABLETYPEAHEAD_POSTAUTHORS => __('Type name...', 'pop-coreprocessors'),
					GD_TEMPLATE_FORMCOMPONENTGROUP_SELECTABLETYPEAHEAD_POSTCOAUTHORS => __('Type name...', 'pop-coreprocessors'),
				);
				$placeholder = $placeholders[$template_id];
				$component = $this->get_component($template_id);
				$this->add_att($component, $atts, 'placeholder', $placeholder);
				break;
		
			case GD_TEMPLATE_FORMCOMPONENTGROUP_EMAILS:
			
				$placeholder = __('Type emails here, separated by space or comma (" " or ","), or 1 email per line', 'pop-coreprocessors');
				$this->add_att(GD_TEMPLATE_FORMCOMPONENT_EMAILS, $atts, 'placeholder', $placeholder);
				break;
		}
		
		return parent::init_atts($template_id, $atts);
	}

	function get_info($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_FORMCOMPONENTGROUP_SELECTABLETYPEAHEAD_POSTAUTHORS:
			case GD_TEMPLATE_FORMCOMPONENTGROUP_SELECTABLETYPEAHEAD_POSTCOAUTHORS:

				return __('Co-authoring this post with other users? Select them all here, they will not only appear as co-owners in the webpage, but will also be able to edit this post.', 'pop-coreprocessors');
				
			case GD_TEMPLATE_FORMCOMPONENTGROUP_SELECTABLETYPEAHEAD_REFERENCES:

				return __('Please select all related content, so the reader can easily access this inter-related information.', 'pop-coreprocessors');
		}
		
		return parent::get_info($template_id, $atts);
	}

	function get_label($template_id, $atts) {

		switch ($template_id) {

			case GD_TEMPLATE_FORMCOMPONENTGROUP_SELECTABLETYPEAHEAD_POSTCOAUTHORS:

				return __('Co-authors', 'pop-coreprocessors');
		}
		
		return parent::get_label($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_FormComponentGroups();