<?php
/**---------------------------------------------------------------------------------------------------------------
 *
 * Data Load Library
 *
 * ---------------------------------------------------------------------------------------------------------------*/

// BlockList because it serves both Carousel and InfiniteScroll
define ('GD_DATALOAD_IOHANDLER_LIST', 'list');

define ('GD_DATALOAD_IOHANDLER_LIST_STOPFETCHING', 'stop-fetching');
// define ('GD_DATALOAD_IOHANDLER_LIST_LAYOUTCONTAINER', 'layout-container');


class GD_DataLoad_IOHandler_List extends GD_DataLoad_IOHandler_Query {

    function get_name() {
    
		return GD_DATALOAD_IOHANDLER_LIST;
	}

	private function stop_loading($dataset, $vars_atts, $iohandler_atts) {
	
		$vars = $this->get_vars($vars_atts, $iohandler_atts);

		// Do not announce to stop loading when doing loadLatest
		if (GD_TemplateManager_Utils::loading_latest()) {

			return false;
		}

		// Keep loading? (If limit = 0 or -1, this will always return false => keep fetching!)
		// If limit = 0 or -1, then it brought already all the results, so stop fetching
		$limit = $vars[GD_URLPARAM_LIMIT];
		if ($iohandler_atts[GD_DATALOAD_IOHANDLER_LIST_STOPFETCHING] || $limit <= 0) {
			
			return true;
		}
		return $stop_loading = count($dataset) < $limit;
	}
	
	// function get_general_querystate($checkpoint, $dataset, $vars_atts, $iohandler_atts, $executed = null, $atts) {
	function get_domain_querystate($checkpoint, $dataset, $vars_atts, $iohandler_atts, $executed = null, $atts) {
	
		// $ret = parent::get_general_querystate($checkpoint, $dataset, $vars_atts, $iohandler_atts, $executed, $atts);
		$ret = parent::get_domain_querystate($checkpoint, $dataset, $vars_atts, $iohandler_atts, $executed, $atts);
		$vars = $this->get_vars($vars_atts, $iohandler_atts);

		// Needed to loadLatest, to know from what time to get results
		// Needed also if data-load or content-loaded == false.
		$ret[GD_URLPARAM_TIMESTAMP] = POP_CONSTANT_CURRENTTIMESTAMP;//current_time('timestamp');

		// If data not loaded, then "stop-fetching" as to not show the Load More button
		if ($iohandler_atts[GD_DATALOAD_LOAD] === false) {

			$ret[GD_URLPARAM_STOPFETCHING] = true;
			return $ret;
		}
		
		// If it is lazy load or Pop, no need to calculate paged / stop-fetching / etc
		if ($iohandler_atts[GD_DATALOAD_CONTENTLOADED] === false) {

			return $ret;
		}

		$paged = $vars[GD_URLPARAM_PAGED];
		$stop_loading = $this->stop_loading($dataset, $vars_atts, $iohandler_atts);
		
		$ret[GD_URLPARAM_STOPFETCHING] = $stop_loading;

		// When loading latest, we need to return the same $paged as we got, because it must not alter the params
		$nextpaged = (GD_TemplateManager_Utils::loading_latest()) ? $paged : $paged + 1;
		$ret[GD_DATALOAD_PARAMS][GD_URLPARAM_PAGED] = $stop_loading ? '' : $nextpaged;

		// Do not send this value back when doing loadLatest, or it will mess up the original structure loading
		// Doing 'unset' as to also take it out if an ancestor class (eg: GD_DataLoad_BlockIOHandler) has set it
		if (GD_TemplateManager_Utils::loading_latest()) {

			unset($ret[GD_URLPARAM_STOPFETCHING]);
		}
		
		return $ret;
	}

	function get_feedback($checkpoint, $dataset, $vars_atts, $iohandler_atts, $data_settings, $executed = null, $atts) {
	
		$ret = parent::get_feedback($checkpoint, $dataset, $vars_atts, $iohandler_atts, $data_settings, $executed, $atts);

		$vars = $this->get_vars($vars_atts, $iohandler_atts);

		if ($format = $iohandler_atts[GD_URLPARAM_FORMAT]) {

			$ret[GD_DATALOAD_PARAMS][GD_URLPARAM_FORMAT] = $format;
		}
		
		$limit = $vars[GD_URLPARAM_LIMIT];
		$ret[GD_DATALOAD_PARAMS][GD_URLPARAM_LIMIT] = $limit;

		// If it is lazy load or Pop, no need to calculate show-msg / paged / stop-fetching / etc
		if ($iohandler_atts[GD_DATALOAD_CONTENTLOADED] === false) {

			return $ret;
		}

		$paged = $vars[GD_URLPARAM_PAGED];
								
		// Show error message if no items
		if (empty($dataset)) {

			// Do not show the message when doing loadLatest
			$show_msgs = !(GD_TemplateManager_Utils::loading_latest());
			
			// If paged < 2 => There are no results at all
			$msgs = array();
			$msg = 
				($paged < 2) ? 
					array(
						'codes' => array('noresults'),
						'can-close' => true
					)
					:
					array(
						'codes' => array('nomore'),
						'can-close' => true
					)
			;							
			$msg['class'] = 'alert-warning';
			$msgs[] = $msg;
			$ret['msgs'] = $msgs;
			$ret['show-msgs'] = $show_msgs;
		}

		// stop-fetching is loaded twice: in the params and in the feedback. This is because we can't access the params from the .tmpl files
		// (the params object is created only when initializing JS => after rendering the html with Handlebars so it's not available by then)
		// and this value is needed in fetchmore.tmpl
		$stop_loading = $this->stop_loading($dataset, $vars_atts, $iohandler_atts);
		
		$ret[GD_URLPARAM_STOPFETCHING] = $stop_loading;

		// Add the Fetch more link to the crawlable data, for the Search Engine
		if (!$stop_loading && $data_settings['dataload-source']) {

			$ret[GD_URLPARAM_QUERYNEXTURL] = add_query_arg(GD_URLPARAM_PAGED, $paged+1, $data_settings['dataload-source']);
		}

		// Do not send this value back when doing loadLatest, or it will mess up the original structure loading
		// Doing 'unset' as to also take it out if an ancestor class (eg: GD_DataLoad_BlockIOHandler) has set it
		if (GD_TemplateManager_Utils::loading_latest()) {

			unset($ret[GD_URLPARAM_STOPFETCHING]);
		}

		return $ret;
	}

	function get_crawlable_data($feedback, $params, $data_settings) {

		$crawlable_data = parent::get_crawlable_data($feedback, $params, $data_settings);
	
		// Add the Fetch more link to the crawlable data, for the Search Engine
		// if (!$params[GD_DATALOAD_INTERNALPARAMS][GD_URLPARAM_STOPFETCHING]) {
		if (!$params[GD_URLPARAM_STOPFETCHING]) {

			$url = add_query_arg(GD_URLPARAM_PAGED, $params[GD_DATALOAD_PARAMS][GD_URLPARAM_PAGED], $data_settings['dataload-source']);
			$crawlable_data[] = sprintf('<a href="%s">'.__('Next', 'pop-engine').'</a>', $url);
		}

		return $crawlable_data;
	}

}
	
/**---------------------------------------------------------------------------------------------------------------
 * Initialize
 * ---------------------------------------------------------------------------------------------------------------*/
new GD_DataLoad_IOHandler_List();
