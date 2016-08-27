<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Template Manager (Handlebars)
 *
 * ---------------------------------------------------------------------------------------------------------------*/

define ('GD_TEMPLATE_BLOCK_COMMENTS_SCROLL', PoP_ServerUtils::get_template_definition('block-comments-scroll'));
define ('GD_TEMPLATE_BLOCK_ADDCOMMENT', PoP_ServerUtils::get_template_definition('block-addcomment'));
define ('GD_TEMPLATE_BLOCKDATA_ADDCOMMENT', PoP_ServerUtils::get_template_definition('blockdata-addcomment'));
define ('GD_TEMPLATE_BLOCK_COMMENTSINGLE', PoP_ServerUtils::get_template_definition('block-commentsingle'));

class GD_Template_Processor_CommentsBlocks extends GD_Template_Processor_BlocksBase {

	function get_templates_to_process() {
	
		return array(
			GD_TEMPLATE_BLOCK_COMMENTS_SCROLL,
			GD_TEMPLATE_BLOCK_ADDCOMMENT,
			GD_TEMPLATE_BLOCKDATA_ADDCOMMENT,
			GD_TEMPLATE_BLOCK_COMMENTSINGLE,
		);
	}

	function get_filter_template($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_COMMENTS_SCROLL:
				
				return GD_TEMPLATE_FILTER_COMMENTS;
		}
		
		return parent::get_filter_template($template_id);
	}

	function integrate_execution_bag($template_id, $atts, &$data_settings, $execution_bag) {	

		switch ($template_id) {

			case GD_TEMPLATE_BLOCKDATA_ADDCOMMENT:

				// Integrate results brought by GD_TEMPLATE_ACTION_ADDCOMMENT
				foreach ($execution_bag as $pagesection => $pagesection_execution_bag) {
					foreach ($pagesection_execution_bag as $block => $block_execution_bag) {

						if ($block == GD_TEMPLATE_ACTION_ADDCOMMENT) {

							// It is the AddComment Execution Bag. Get the dataset
							if ($dataset = $block_execution_bag['dataset']) {
								$data_settings['dataload-atts']['include'] = $dataset;
								$data_settings[GD_DATALOAD_LOAD] = true;
							}
							break;
						}						
					}
				}
				break;
		}

		return parent::integrate_execution_bag($template_id, $atts, $data_settings, $execution_bag);
	}

	protected function get_controlgroup_top($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_COMMENTS_SCROLL:
				
				return GD_TEMPLATE_CONTROLGROUP_COMMENTS;
		}
		
		return parent::get_controlgroup_top($template_id);
	}

	protected function get_messagefeedback($template_id) {
	
		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_ADDCOMMENT:
			
				return GD_TEMPLATE_MESSAGEFEEDBACK_ADDCOMMENT;

			case GD_TEMPLATE_BLOCK_COMMENTS_SCROLL:
			
				return GD_TEMPLATE_MESSAGEFEEDBACK_COMMENTS;
		}

		return parent::get_messagefeedback($template_id);
	}

	protected function get_block_inner_templates($template_id) {

		$ret = parent::get_block_inner_templates($template_id);

		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_COMMENTS_SCROLL:

				$ret[] = GD_TEMPLATE_SCROLL_COMMENTS_LIST;
				break;

			case GD_TEMPLATE_BLOCK_ADDCOMMENT:

				$ret[] = GD_TEMPLATE_FORM_ADDCOMMENT;
				break;

			case GD_TEMPLATE_BLOCKDATA_ADDCOMMENT:

				$ret[] = GD_TEMPLATE_SCROLL_COMMENTS_ADD;
				break;

			case GD_TEMPLATE_BLOCK_COMMENTSINGLE:

				$ret[] = GD_TEMPLATE_CONTENT_COMMENTSINGLE;
				break;
		}
	
		return $ret;
	}

	function get_dataloader($template_id) {

		switch ($template_id) {

			case GD_TEMPLATE_BLOCK_COMMENTS_SCROLL:
			case GD_TEMPLATE_BLOCKDATA_ADDCOMMENT:

				return GD_DATALOADER_COMMENTLIST;

			case GD_TEMPLATE_BLOCK_COMMENTSINGLE:

				return GD_DATALOADER_EDITCOMMENT;
		}
		
		return parent::get_dataloader($template_id);
	}

	protected function get_iohandler($template_id) {

		switch ($template_id) {
					
			case GD_TEMPLATE_BLOCK_COMMENTS_SCROLL:

				return GD_DATALOAD_IOHANDLER_LIST_COMMENTS;
		}
		
		return parent::get_iohandler($template_id);
	}
	
	function init_atts($template_id, &$atts) {
	
		switch ($template_id) {
				
			case GD_TEMPLATE_BLOCK_COMMENTS_SCROLL:

				// Comments are not infinite scroll
				$this->add_att(GD_TEMPLATE_FETCHMORE, $atts, 'infinite', false);

				$this->append_att($template_id, $atts, 'class', 'block-comments');
				break;

			case GD_TEMPLATE_BLOCKDATA_ADDCOMMENT:

				$this->add_att($template_id, $atts, 'data-load', false);	
				$this->append_att($template_id, $atts, 'class', 'hidden');	
				break;

			case GD_TEMPLATE_BLOCK_ADDCOMMENT:

				// Do not show the labels in the form
				$this->append_att(GD_TEMPLATE_FORM_ADDCOMMENT, $atts, 'class', 'nolabel');

				// Change the 'Loading' message in the Status
				$this->add_att(GD_TEMPLATE_STATUS, $atts, 'loading-msg', __('Sending...', 'pop-coreprocessors'));	

				// Destroy blocks on success
				// $this->merge_block_jsmethod_att($template_id, $atts, array('destroyPageOnSuccess'));
				break;
		}
		
		return parent::init_atts($template_id, $atts);
	}
}


/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_Template_Processor_CommentsBlocks();