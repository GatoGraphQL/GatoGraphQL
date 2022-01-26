<?php
use PoP\ComponentModel\ComponentInfo as ComponentModelComponentInfo;
use PoP\ComponentModel\Facades\Engine\EngineFacade;
use PoP\ComponentModel\Facades\HelperServices\RequestHelperServiceFacade;
use PoP\ComponentModel\State\ApplicationState;
use PoPCMSSchema\SchemaCommons\Facades\CMS\CMSServiceFacade;

class PoP_ServerSideRendering
{
    private $enabled;
    private $template_paths;
    private $data;
    private $renderers;

    public function __construct()
    {

        // Add myself as the instance in the factory
        PoP_ServerSideRenderingFactory::setInstance($this);

        // Initialize variables
        $this->enabled = !PoP_SSR_ServerUtils::disableServerSideRendering();
        $this->template_paths = array();
        $this->renderers = array();
    }

    public function addTemplatePath($path, $templates = array())
    {
        foreach ($templates as $template) {
            $this->template_paths[$template] = $path;
        }
    }

    public function initJson()
    {
        if (!$this->enabled) {
            return;
        }

        // Obtain the JSON from the Engine
        if (!$this->data) {
            // The JSON is already encoded, as a String, so we must decode it to transformt it into an array
            $engine = EngineFacade::getInstance();
            $this->data = $engine->engineState->data;
        }
    }

    public function initPopmanager()
    {
        if (!$this->enabled) {
            return;
        }

        $cmsService = CMSServiceFacade::getInstance();
        $requestHelperService = RequestHelperServiceFacade::getInstance();
        $domain = $cmsService->getSiteURL();        
        $url = $requestHelperService->getCurrentURL();

        // Initialize the popManager, so it will get all its private values from $data
        $popManager = PoP_ServerSide_LibrariesFactory::getPopmanagerInstance();
        // Comment Leo: passing extra parameter $data in PHP
        $popManager->init($this->data);

        // Because $popManager modified the settings (eg: added the topLevelSettings, etc), then we gotta update the local $data object accordingly
        $this->data['combinedstatedata']['settings'] = $popManager->getCombinedStateData($domain, $url)['settings'];
    }

    public function getJson()
    {
        if (!$this->enabled) {
            return array();
        }

        // Obtain the JSON from the Engine
        if (!$this->data) {
            // Initialize the JSON
            $this->initJson();

            // Also initialize the popManager, so it will get all its private values from $data
            $this->initPopmanager();
        }

        return $this->data;
    }

    public function mergeJson($context)
    {
        $this->data = array_merge(
            $this->data,
            $context
        );
    }

    public function getJsonSettings()
    {
        if (!$this->enabled) {
            return array();
        }

        $data = $this->getJson();
        return $data['combinedstatedata']['settings'];
    }

    public function getJsonConfiguration()
    {
        if (!$this->enabled) {
            return array();
        }

        $settings = $this->getJsonSettings();
        return $settings['configuration'];
    }

    public function getJsonTemplates()
    {
        if (!$this->enabled) {
            return array();
        }

        $settings = $this->getJsonSettings();
        return $settings['templates'];
    }

    protected function getRenderer($filename)
    {

        // If the file has already been included, then return it
        if ($renderer = $this->renderers[$filename] ?? null) {
            return $renderer;
        }

        // Otherwise, include the file, and store it for later use
        $this->renderers[$filename] = include $filename;
        return $this->renderers[$filename];
    }

    public function getTemplateRenderer($template)
    {
        if (!$this->enabled) {
            return null;
        }

        if (!$path = $this->template_paths[$template]) {
            $requestHelperService = RequestHelperServiceFacade::getInstance();
            throw new Exception(
                sprintf(
                    'No path registered for $template \'%s\', for $module \'%s\' (%s)',
                    $template,
                    $module,
                    $requestHelperService->getRequestedFullURL()
                )
            );
        }

        return $this->getRenderer($path.'/'.$template.'.php');
    }

    public function renderTemplate($template, $configuration)
    {
        if (!$this->enabled) {
            return '';
        }

        $renderer = $this->getTemplateRenderer($template);

        // Render and return the html
        return $renderer($configuration);
    }

    public function renderModule(array $module, $configuration)
    {
        if (!$this->enabled) {
            return '';
        }

        if (!$module) {
            $requestHelperService = RequestHelperServiceFacade::getInstance();
            throw new Exception(
                sprintf(
                    '$module cannot be null (%s)',
                    $requestHelperService->getRequestedFullURL()
                )
            );
        }

        $templates = $this->getJsonTemplates();

        // If a template source is not defined, then it is the template itself (eg: the pageSection templates)
        $template = $templates[$module[1]] ?? $module;

        // Render and return the html
        return $this->renderTemplate($template, $configuration);
    }

    public function renderPagesection($pagesection_settings_id, $target = null)
    {
        return $this->renderTarget($pagesection_settings_id, null, $target);
    }

    public function renderBlock($pagesection_settings_id, $block, $target = null)
    {
        return $this->renderTarget($pagesection_settings_id, $block, $target);
    }

    public function renderTarget($pagesection_settings_id, $block = null, $target = null)
    {
        if (!$this->enabled) {
            return '';
        }

        // If the target was provided, then check that the current page has that target to render the html
        // Eg: addons pageSection must have target "addons", if not do nothing
        if (!is_null($target) && $target != \PoP\Root\App::getState('target')) {
            return '';
        }

        $requestHelperService = RequestHelperServiceFacade::getInstance();
        // The pageSection has its configuration right under key
        // $pagesection_settings_id of the global configuration
        $configuration = $this->getJsonConfiguration();
        if (!$pagesection_configuration = $configuration[$pagesection_settings_id]) {
            throw new Exception(
                sprintf(
                    'No configuration in context for $pagesection_settings_id \'%s\' (%s)',
                    $pagesection_settings_id,
                    $requestHelperService->getRequestedFullURL()
                )
            );
        }

        // Expand the JS Keys first, since the template key may be the compacted one
        $popManager = PoP_ServerSide_LibrariesFactory::getPopmanagerInstance();
        $popManager->expandJSKeys($pagesection_configuration);
        if (!$pagesection_module = $pagesection_configuration[GD_JS_MODULE]) {
            throw new Exception(
                sprintf(
                    'No template defined in context (%s)',
                    $requestHelperService->getRequestedFullURL()
                )
            );
        }

        // We can render a block instead of the pageSection
        // Needed for producing the html for the automated emails
        $renderModule = $pagesection_module;
        $render_context = $pagesection_configuration;
        if ($block) {
            $render_context = $render_context[ComponentModelComponentInfo::get('response-prop-submodules')][$block];
            $renderModule = $render_context[GD_JS_MODULE];
        }

        return $this->renderModule($renderModule, $render_context);
    }
}

/**
 * Initialization
 */
new PoP_ServerSideRendering();
