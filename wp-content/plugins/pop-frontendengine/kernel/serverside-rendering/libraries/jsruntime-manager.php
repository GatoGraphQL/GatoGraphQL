<?php
class PoP_ServerSide_JSRuntimeManager {
	
	//-------------------------------------------------
	// INTERNAL variables
	//-------------------------------------------------
	private $full_session_ids;
	private $last_session_ids;
	private $blockURL;
	private $pageSectionURL;

	function __construct() {

		PoP_ServerSide_Libraries_Factory::set_jsruntime_instance($this);

		// Initialize internal variables
		$this->full_session_ids = array();
		$this->last_session_ids = array();
		$this->pageSectionURL = 'general';
	}

	//-------------------------------------------------
	// Added functions to the PHP, not available in the JS
	//-------------------------------------------------
	function getSessionIds($session) {

		$session_key = $session.'_session_ids';
		return $this->$session_key;
	}

	//-------------------------------------------------
	// PUBLIC but NOT EXPOSED functions
	//-------------------------------------------------

	function setBlockURL($url) {

		$this->blockURL = $url;
	}

	// function initPageSectionVarPaths(&$vars, $pssId, $template, $group = null) {
	
	// 	if ($vars[$pssId]) {
	// 		$vars[$pssId] = array();
	// 	}
	// 	if ($vars[$pssId][$template]) {
	// 		$vars[$pssId][$template] = array();
	// 	}
	// 	if ($vars[$pssId][$template][$group]) {
	// 		$vars[$pssId][$template][$group] = array();
	// 	}
	// }

	function initBlockVarPaths(&$vars, $url, $domain, $pssId, $targetId, $template, $group = null) {
	
		$group = $group ?? GD_JSMETHOD_GROUP_MAIN;

		if (!$vars[$url]) {
			$vars[$url] = array();
		}
		if (!$vars[$url][$domain]) {
			$vars[$url][$domain] = array();
		}
		if (!$vars[$url][$domain][$pssId]) {
			$vars[$url][$domain][$pssId] = array();
		}
		if (!$vars[$url][$domain][$pssId][$targetId]) {
			$vars[$url][$domain][$pssId][$targetId] = array();
		}
		if (!$vars[$url][$domain][$pssId][$targetId][$template]) {
			$vars[$url][$domain][$pssId][$targetId][$template] = array();
		}
		if (!$vars[$url][$domain][$pssId][$targetId][$template][$group]) {
			$vars[$url][$domain][$pssId][$targetId][$template][$group] = array();
		}
	}

	function initVars($url, $domain, $pssId, $targetId, $template, $group) {
	
		$this->initBlockVarPaths($this->full_session_ids, $url, $domain, $pssId, $targetId, $template, $group);
		$this->initBlockVarPaths($this->last_session_ids, $url, $domain, $pssId, $targetId, $template, $group);
	}

	function addGroup($id, $group) {
	
		if ($group) {
			$id .= POP_CONSTANT_ID_SEPARATOR.$group;
		}
		
		return $id;
	}

	function addPageSectionId($domain, $pssId, $template, $id, $group = null) {

		return $this->addTemplateId($domain, $pssId, $pssId, $template, $id, $group, $true, $true);
	}

	function addTemplateId($domain, $pssId, $targetId, $template, $id, $group = null, $fixed = null, $isIdUnique = null, $ignorePSRuntimeId = null) {
	
		// If the ID is not unique, then we gotta make it unique, getting the POP_FRONTENDENGINE_CONSTANT_UNIQUE_ID from the topLevel feedback
		$popManager = PoP_ServerSide_Libraries_Factory::get_popmanager_instance();
		if (!$isIdUnique) {
			$id .= $popManager->getUniqueId($domain);
		}

		// Add a counter id at the end so that no two ids will be the same (only needed for elements other than pageSection and blocks)
		if (!$fixed) {
			$id .= POP_CONSTANT_ID_SEPARATOR.counterNext();
		}

		// Add the group at the end, so that we can recreate the ID in the back-end calling function GD_TemplateManager_Utils::get_frontend_id
		$id = $this->addGroup($id, $group);

		// Add under both pageSection and block, unless the targetId is the pssId, then no need for the block (eg: pagesection-tabpane.tmpl for the group="interceptor" link)
		// ignorePSRuntimeId: to not add the runtime ID for the pageSection when re-drawing data inside a block (the IDs will be generated again, but no need to add them to the pageSection side, since pageSection js methods will not be executed again)
		if (!$ignorePSRuntimeId) {
			$this->addTargetId($this->pageSectionURL, $domain, $pssId, $pssId, $template, $group, $id);
		}
		if ($pssId != $targetId) {
			$this->addTargetId($this->blockURL, $domain, $pssId, $targetId, $template, $group, $id);
		}
		return $id;
	}

	function addTargetId($url, $domain, $pssId, $targetId, $template, $group, $id) {

		$group = $group ?? GD_JSMETHOD_GROUP_MAIN;

		$this->initVars($url, $domain, $pssId, $targetId, $template, $group);
		$this->full_session_ids[$url][$domain][$pssId][$targetId][$template][$group][] = $id;
		$this->last_session_ids[$url][$domain][$pssId][$targetId][$template][$group][] = $id;

		return $id;
	}

	function getLastGeneratedId($domain, $pssId, $targetId, $template, $group = null) {
	
		$group = $group ?? GD_JSMETHOD_GROUP_MAIN;

		// Is it a pageSection? or a block?
		$url = ($pssId == $targetId) ? $this->pageSectionURL : $this->blockURL;
		$ids = $this->full_session_ids[$url][$domain][$pssId][$targetId][$template][$group];
		return $ids[count($ids)-1];
	}

	// ------------------------------------------------------
	// Comment Leo: Not needed in PHP => Commented out
	// ------------------------------------------------------
	// function getPageSectionSessionIds($pageSection, $session) {

	// 	// The URL is a static placeholder, since the pageSection is always initialized immediately
	// 	$url = $this->pageSectionURL;

	// 	return $this->getTargetSessionIds($url, $pageSection, $pageSection, $session);
	// }

	// ------------------------------------------------------
	// Comment Leo: Not needed in PHP => Commented out
	// ------------------------------------------------------
	// function getBlockSessionIds($pageSection, $block, $session) {
	
	// 	// Get the URL from the toplevel-url, since the block may be initialized later on (eg: in a Carousel or TabPane)
	// 	$url = $this->getBlockURL($pageSection, $block);

	// 	return $this->getTargetSessionIds($url, $pageSection, $block, $session);
	// }

	// ------------------------------------------------------
	// Comment Leo: Not needed in PHP => Commented out
	// ------------------------------------------------------
	// function getTargetSessionIds($url, $pageSection, $target, $session) {
	
	// 	$popManager = PoP_ServerSide_Libraries_Factory::get_popmanager_instance();
	// 	$pssId = $popManager->getSettingsId($pageSection);
	// 	$targetId = $popManager->getSettingsId($target);

	// 	// session can be 'last' or 'full'. 'last' is the default since it's the more used one (for appending newDOMs, we need just the newly added ids)
	// 	$session = $session ?? 'last';
	// 	$session_key = $session.'_session_ids';
	// 	if ($this->$session_key[$url] && $this->$session_key[$url][$pssId]) {

	// 		return $this->$session_key[$url][$pssId][$targetId];
	// 	}
		
	// 	return null;
	// }

	// ------------------------------------------------------
	// Comment Leo: Not needed in PHP => Commented out
	// ------------------------------------------------------
	// function deletePageSectionLastSessionIds($pageSection) {

	// 	var pssId = popManager.getSettingsId(pageSection);
	// 	var url = $this->pageSectionURL;
		
	// 	delete $this->last_session_ids[$url][$pssId][$pssId];
	// 	if ($.isEmptyObject($this->last_session_ids[$url][$pssId])) {

	// 		delete $this->last_session_ids[$url][$pssId];
	// 		if ($.isEmptyObject($this->last_session_ids[$url])) {

	// 			delete $this->last_session_ids[$url];
	// 		}
	// 	}
	// }

	// ------------------------------------------------------
	// Comment Leo: Not needed in PHP => Commented out
	// ------------------------------------------------------
	// function deleteBlockLastSessionIds($pageSection, $block) {

	// 	var pssId = popManager.getSettingsId(pageSection);
	// 	var targetId = popManager.getSettingsId(block);
		
	// 	// Get the URL from the toplevel-url, since the block may be initialized later on (eg: in a Carousel or TabPane)
	// 	var url = $this->getBlockURL(pageSection, block);
	// 	if ($this->last_session_ids[$url] && $this->last_session_ids[$url][$pssId] && $this->last_session_ids[$url][$pssId][$targetId]) {
	
	// 		delete $this->last_session_ids[$url][$pssId][$targetId];
	// 		if ($.isEmptyObject($this->last_session_ids[$url][$pssId])) {
	
	// 			delete $this->last_session_ids[$url][$pssId];
	// 			if ($.isEmptyObject($this->last_session_ids[$url])) {
	
	// 				delete $this->last_session_ids[$url];
	// 			}
	// 		}
	// 	}
	// }

	// ------------------------------------------------------
	// Comment Leo: Not needed in PHP => Commented out
	// ------------------------------------------------------
	// function getBlockURL($pageSection, $block) {
		
	// 	return block.data('toplevel-url');
	// }
}

/**---------------------------------------------------------------------------------------------------------------
 * Initialization
 * ---------------------------------------------------------------------------------------------------------------*/
new PoP_ServerSide_JSRuntimeManager();