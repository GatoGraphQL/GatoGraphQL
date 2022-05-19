<?php
class PoP_ServerSide_JSRuntimeManager
{
    
    //-------------------------------------------------
    // INTERNAL variables
    //-------------------------------------------------
    private $full_session_ids;
    private $last_session_ids;
    private $blockURL;
    private $pageSectionURL;

    public function __construct()
    {
        PoP_ServerSide_LibrariesFactory::setJsruntimeInstance($this);

        // Initialize internal variables
        $this->full_session_ids = array();
        $this->last_session_ids = array();
        // $this->pageSectionURL = 'general';
    }

    //-------------------------------------------------
    // Added functions to the PHP, not available in the JS
    //-------------------------------------------------
    public function getSessionIds($session)
    {
        $session_key = $session.'_session_ids';
        return $this->$session_key;
    }

    //-------------------------------------------------
    // PUBLIC but NOT EXPOSED functions
    //-------------------------------------------------

    public function setPageSectionURL($url)
    {
        $this->pageSectionURL = $url;
    }

    public function setBlockURL($domain, $url)
    {
        $this->blockURL = $url;
        $this->setPageSectionURL($url);
    }

    public function initBlockVarPaths(&$blockVars, $url, $domain, $pssId, $targetId, $componentName, $group = null)
    {
        $group = $group ?? GD_JSMETHOD_GROUP_MAIN;

        if (!$blockVars[$url]) {
            $blockVars[$url] = array();
        }
        if (!$blockVars[$url][$domain]) {
            $blockVars[$url][$domain] = array();
        }
        if (!$blockVars[$url][$domain][$pssId]) {
            $blockVars[$url][$domain][$pssId] = array();
        }
        if (!$blockVars[$url][$domain][$pssId][$targetId]) {
            $blockVars[$url][$domain][$pssId][$targetId] = array();
        }
        if (!$blockVars[$url][$domain][$pssId][$targetId][$componentName]) {
            $blockVars[$url][$domain][$pssId][$targetId][$componentName] = array();
        }
        if (!$blockVars[$url][$domain][$pssId][$targetId][$componentName][$group]) {
            $blockVars[$url][$domain][$pssId][$targetId][$componentName][$group] = array();
        }
    }

    public function initVars($url, $domain, $pssId, $targetId, $componentName, $group)
    {
        $this->initBlockVarPaths($this->full_session_ids, $url, $domain, $pssId, $targetId, $componentName, $group);
        $this->initBlockVarPaths($this->last_session_ids, $url, $domain, $pssId, $targetId, $componentName, $group);
    }

    public function addGroup($id, $group)
    {
        if ($group) {
            $id .= POP_CONSTANT_ID_SEPARATOR.$group;
        }
        
        return $id;
    }

    public function addPageSectionId($domain, $pssId, $componentName, $id, $group = null)
    {
        return $this->addModule($domain, $pssId, $pssId, $componentName, $id, $group, $true, $true);
    }

    public function addModule($domain, $pssId, $targetId, $componentName, $id, $group = null, $fixed = null, $isIdUnique = null, $ignorePSRuntimeId = null)
    {
    
        // If the ID is not unique, then we gotta make it unique, getting the POP_ENGINEWEBPLATFORM_CONSTANT_UNIQUE_ID from the topLevel feedback
        $popManager = PoP_ServerSide_LibrariesFactory::getPopmanagerInstance();
        if (!$isIdUnique) {
            $id .= $popManager->getUniqueId($domain);
        }

        // Add a counter id at the end so that no two ids will be the same (only needed for elements other than pageSection and blocks)
        if (!$fixed) {
            $id .= POP_CONSTANT_ID_SEPARATOR.counterNext();
        }

        // Add the group at the end, so that we can recreate the ID in the back-end calling function PoP_WebPlatform_ServerUtils::getFrontendId
        $id = $this->addGroup($id, $group);

        // Add under both pageSection and block, unless the targetId is the pssId, then no need for the block (eg: pagesection-tabpane.tmpl for the group="interceptor" link)
        // ignorePSRuntimeId: to not add the runtime ID for the pageSection when re-drawing data inside a block (the IDs will be generated again, but no need to add them to the pageSection side, since pageSection js methods will not be executed again)
        if (!$ignorePSRuntimeId) {
            $this->addTargetId($this->pageSectionURL, $domain, $pssId, $pssId, $componentName, $group, $id);
        }
        if ($pssId != $targetId) {
            $this->addTargetId($this->blockURL, $domain, $pssId, $targetId, $componentName, $group, $id);
        }
        return $id;
    }

    public function addTargetId($url, $domain, $pssId, $targetId, $componentName, $group, $id)
    {
        $group = $group ?? GD_JSMETHOD_GROUP_MAIN;

        $this->initVars($url, $domain, $pssId, $targetId, $componentName, $group);
        $this->full_session_ids[$url][$domain][$pssId][$targetId][$componentName][$group][] = $id;
        $this->last_session_ids[$url][$domain][$pssId][$targetId][$componentName][$group][] = $id;

        return $id;
    }

    public function getLastGeneratedId($domain, $pssId, $targetId, $componentName, $group = null)
    {
        $group = $group ?? GD_JSMETHOD_GROUP_MAIN;

        // Is it a pageSection? or a block?
        $url = ($pssId == $targetId) ? $this->pageSectionURL : $this->blockURL;
        $ids = $this->full_session_ids[$url][$domain][$pssId][$targetId][$componentName][$group];
        return $ids[count($ids)-1];
    }
}

/**
 * Initialization
 */
new PoP_ServerSide_JSRuntimeManager();
