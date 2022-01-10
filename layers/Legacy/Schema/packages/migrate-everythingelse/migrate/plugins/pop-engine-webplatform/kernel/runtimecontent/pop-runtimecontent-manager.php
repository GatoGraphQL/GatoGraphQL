<?php
use PoP\ComponentModel\Facades\Info\ApplicationInfoFacade;
use PoP\ComponentModel\State\ApplicationState;
use PoP\Hooks\Facades\HooksAPIFacade;

define('POP_CACHE_EXT_JS', '.js');

class PoP_Module_RuntimeContentManager
{
    use \PoP\ComponentModel\Cache\ReplaceCurrentExecutionDataWithPlaceholdersTrait, FileGeneratorManagerTrait;

    public function __construct()
    {
        $this->addDeleteFilesHooks();
    }
    
    protected function getDefaultExtension()
    {
        return POP_CACHE_EXT_JS;
    }

    protected function getFileBasedir()
    {
        // Add the version in the path, so it's easier to identify currently-needed files
        $vars = ApplicationState::getVars();
        return POP_RUNTIMECONTENT_DIR.'/'.ApplicationInfoFacade::getInstance()->getVersion();
    }

    protected function getFileBaseURL()
    {
        // Add the version in the path, so it's easier to identify currently-needed files
        // Allow to modify the domain, from Assets to Uploads CDN
        $vars = ApplicationState::getVars();
        return HooksAPIFacade::getInstance()->applyFilters(
            'PoP_Module_RuntimeContentManager:cache-baseurl',
            POP_RUNTIMECONTENT_URL.'/'.ApplicationInfoFacade::getInstance()->getVersion()
        );
    }

    public function getFileUrl($filename, $type, $ext = '')
    {
        if (!$ext) {
            $ext = $this->getDefaultExtension();
        }
        
        return $this->getFileBaseURL().'/'.$type.'/'.$filename.$ext;
    }

    public function getFileUrlByModelInstance($type, $ext = '')
    {
        $model_instance_id = \PoP\ComponentModel\Facades\ModelInstance\ModelInstanceFacade::getInstance()->getModelInstanceId();
        return $this->getFileUrl($model_instance_id, $type, $ext);
    }
}

/**
 * Initialization
 */
global $pop_module_runtimecontentmanager;
$pop_module_runtimecontentmanager = new PoP_Module_RuntimeContentManager();
