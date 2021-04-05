<?php
use PoP\Hooks\Facades\HooksAPIFacade;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\ComponentModel\State\ApplicationState;
use PoPSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPSchema\PostTags\Facades\PostTagTypeAPIFacade;
use PoPSchema\PostCategories\Facades\PostCategoryTypeAPIFacade;

class PoP_SPAResourceLoader_FileReproduction_Config extends \PoP\FileStore\File\AbstractRenderableFileFragment
{
    // public function getRenderer()
    // {
    //     global $pop_sparesourceloader_configfile_renderer;
    //     return $pop_sparesourceloader_configfile_renderer;
    // }

    public function getAssetsPath(): string
    {
        return POP_SPARESOURCELOADER_ASSETS_DIR.'/js/jobs/resourceloader-config.js';
    }

    /**
     * @return mixed[]
     */
    public function getConfiguration(): array
    {
        $configuration = parent::getConfiguration();
        $cmsengineapi = \PoP\Engine\FunctionAPIFactory::getInstance();
        $cmsusersapi = \PoPSchema\Users\FunctionAPIFactory::getInstance();
        $postTagTypeAPI = PostTagTypeAPIFacade::getInstance();
        $postCategoryTypeAPI = PostCategoryTypeAPIFacade::getInstance();
        $vars = ApplicationState::getVars();

        // Domain
        $configuration['$domain'] = $cmsengineapi->getSiteURL();
        // $configuration['$pathStartPos'] = strlen(GeneralUtils::maybeAddTrailingSlash($cmsengineapi->getHomeURL()));

        // Get the list of all categories, and then their paths
        $categories = $postCategoryTypeAPI->getCategories(['hide-empty' => false], ['return-type' => ReturnTypes::IDS]);
        $single_paths = array_map(array($postCategoryTypeAPI, 'getCategoryPath'), $categories);

        // Allow EM to add their own paths
        $single_paths = HooksAPIFacade::getInstance()->applyFilters(
            'PoP_SPAResourceLoader_FileReproduction_Config:configuration:category-paths',
            $single_paths
        );

        // Path slugs
        $configuration['$paths'] = array(
            'author' => $cmsusersapi->getAuthorBase().'/',
            'tag' => $postTagTypeAPI->getTagBase().'/',
            'single' => $single_paths,
        );

        global $pop_sparesourceloader_natureformatcombinationresources_configfile;
        $configFileURLPlaceholder =
            $pop_sparesourceloader_natureformatcombinationresources_configfile->getUrl()
            .'/'
            .$pop_sparesourceloader_natureformatcombinationresources_configfile->getVariableFilename('{0}', '{1}');
        $configFileURLPlaceholder = GeneralUtils::addQueryArgs([
            'ver' => $vars['version'],
        ], $configFileURLPlaceholder);
        $configuration['$configFileURLPlaceholder'] = $configFileURLPlaceholder;

        $configuration['$configTypes'] = array(
            POP_RESOURCELOADER_RESOURCETYPE_JS => array(),
            POP_RESOURCELOADER_RESOURCETYPE_CSS => array(),
        );

        return $configuration;
    }
}
