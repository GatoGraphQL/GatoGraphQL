<?php
define('GD_CONSTANT_AUTHORPOSITION_ABOVETHUMB', 'abovethumb');
define('GD_CONSTANT_AUTHORPOSITION_ABOVETITLE', 'abovetitle');
define('GD_CONSTANT_AUTHORPOSITION_BELOWCONTENT', 'belowcontent');

use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;
use PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\RelationalModuleField;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

abstract class PoP_Module_Processor_PreviewPostLayoutsBase extends PoP_Module_Processor_PreviewObjectLayoutsBase
{
    public function getTemplateResource(array $module, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_LAYOUT_PREVIEWPOST];
    }

    public function getSubComponentVariations(array $module): array
    {
        $ret = parent::getSubComponentVariations($module);

        if ($belowthumb_modules = $this->getBelowthumbLayoutSubmodules($module)) {
            $ret = array_merge(
                $ret,
                $belowthumb_modules
            );
        }
        if ($abovecontent_modules = $this->getAbovecontentSubmodules($module)) {
            $ret = array_merge(
                $ret,
                $abovecontent_modules
            );
        }
        if ($belowcontent_modules = $this->getBelowcontentSubmodules($module)) {
            $ret = array_merge(
                $ret,
                $belowcontent_modules
            );
        }
        if ($top_modules = $this->getTopSubmodules($module)) {
            $ret = array_merge(
                $ret,
                $top_modules
            );
        }
        if ($bottom_modules = $this->getPreviewpostBottomSubmodules($module)) {
            $ret = array_merge(
                $ret,
                $bottom_modules
            );
        }
        if ($beforecontent_modules = $this->getBeforecontentSubmodules($module)) {
            $ret = array_merge(
                $ret,
                $beforecontent_modules
            );
        }
        if ($aftercontent_modules = $this->getAftercontentSubmodules($module)) {
            $ret = array_merge(
                $ret,
                $aftercontent_modules
            );
        }
        if ($post_thumb = $this->getPostThumbSubmodule($module)) {
            $ret[] = $post_thumb;
        }
        if ($content_module = $this->getContentSubmodule($module)) {
            $ret[] = $content_module;
        }

        return $ret;
    }

    /**
     * @return RelationalModuleField[]
     */
    public function getRelationalSubmodules(array $module): array
    {
        $ret = parent::getRelationalSubmodules($module);

        $modules = [];

        // Show author or not: if position defined
        if ($author_module = $this->getAuthorModule($module)) {
            $modules[] = $author_module;
        }

        // Show author avatar: only if no thumb module defined, and author avatar is defined
        if (!$this->getPostThumbSubmodule($module)) {
            if ($author_avatar = $this->getAuthorAvatarModule($module)) {
                $modules[] = $author_avatar;
            }
        }

        if ($modules) {
            $ret[] = new RelationalModuleField(
                'authors',
                $modules
            );
        }

        return $ret;
    }

    /**
     * @todo Migrate from string to LeafModuleField
     *
     * @return \PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\LeafModuleField[]
     */
    public function getDataFields(array $module, array &$props): array
    {
        $ret = parent::getDataFields($module, $props);

        $ret[] = 'customPostType';
        $ret[] = 'catSlugs';
        if ($this->showPosttitle($module)) {
            $ret[] = 'title';
        }
        if ($this->showDate($module)) {
            $ret[] = 'datetime';
        }

        return $ret;
    }

    public function showPosttitle(array $module)
    {
        return true;
    }
    public function showDate(array $module)
    {
        return false;
    }

    public function getContentSubmodule(array $module)
    {
        return null;
    }
    public function getPostThumbSubmodule(array $module)
    {
        return null;
    }
    public function getAuthorModule(array $module)
    {
        return [PoP_Module_Processor_MultipleUserLayouts::class, PoP_Module_Processor_MultipleUserLayouts::MODULE_LAYOUT_MULTIPLEUSER_CONTEXTUALPOSTAUTHOR];
    }
    public function getAuthorAvatarModule(array $module)
    {
        return null;
    }
    public function getTitleBeforeauthors(array $module, array &$props)
    {
        return array();
    }
    public function getTitleAfterauthors(array $module, array &$props)
    {
        return array();
    }
    public function authorPositions(array $module)
    {
        return array(
            GD_CONSTANT_AUTHORPOSITION_ABOVETHUMB
        );
    }
    // function layoutextraPosition(array $module, array &$props) {

    //     return GD_CONSTANT_LAYOUTEXTRAPOSITION_BELOWTHUMB;
    // }
    public function getAuthorsSeparator(array $module, array &$props)
    {
        return GD_CONSTANT_AUTHORS_SEPARATOR;
    }

    public function getBelowthumbLayoutSubmodules(array $module)
    {
        return array();
    }
    public function getAbovecontentSubmodules(array $module)
    {
        $ret = array();

        return $ret;
    }
    public function getBelowcontentSubmodules(array $module)
    {
        return array();
    }
    public function getBottomSubmodules(array $module)
    {
        return array();
    }
    public function getTopSubmodules(array $module)
    {
        return array();
    }
    public function getAftercontentSubmodules(array $module)
    {
        return array();
    }
    public function getBeforecontentSubmodules(array $module)
    {
        return array();
    }
    public function getPreviewpostBottomSubmodules(array $module)
    {

        // Allow 3rd parties to modify the modules. Eg: for the TPP website we re-use the MESYM Theme but we modify some of its elements, eg: adding the "What do you think about TPP?" modules in the fullview templates
        return \PoP\Root\App::applyFilters('PoP_Module_Processor_PreviewPostLayoutsBase:bottom_modules', $this->getBottomSubmodules($module), $module);
    }

    public function getImmutableConfiguration(array $module, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($module, $props);

        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        if ($belowthumb_modules = $this->getBelowthumbLayoutSubmodules($module)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['belowthumb'] = array_map(
                [\PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance(), 'getModuleOutputName'],
                $belowthumb_modules
            );
        }
        if ($abovecontent_modules = $this->getAbovecontentSubmodules($module)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['abovecontent'] = array_map(
                [\PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance(), 'getModuleOutputName'],
                $abovecontent_modules
            );
        }
        if ($belowcontent_modules = $this->getBelowcontentSubmodules($module)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['belowcontent'] = array_map(
                [\PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance(), 'getModuleOutputName'],
                $belowcontent_modules
            );
        }
        if ($top_modules = $this->getTopSubmodules($module)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['top'] = array_map(
                [\PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance(), 'getModuleOutputName'],
                $top_modules
            );
        }
        if ($bottom_modules = $this->getPreviewpostBottomSubmodules($module)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['bottom'] = array_map(
                [\PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance(), 'getModuleOutputName'],
                $bottom_modules
            );
        }
        if ($beforecontent_modules = $this->getBeforecontentSubmodules($module)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['beforecontent'] = array_map(
                [\PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance(), 'getModuleOutputName'],
                $beforecontent_modules
            );
        }
        if ($aftercontent_modules = $this->getAftercontentSubmodules($module)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['aftercontent'] = array_map(
                [\PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance(), 'getModuleOutputName'],
                $aftercontent_modules
            );
        }
        if ($author_module = $this->getAuthorModule($module)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['authors'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($author_module);
            $ret['authors-position'] = $this->authorPositions($module);
            $ret['authors-sep'] = $this->getAuthorsSeparator($module, $props);
            if ($title_beforeauthors = $this->getTitleBeforeauthors($module, $props)) {
                $ret[GD_JS_TITLES]['beforeauthors'] = $title_beforeauthors;
            }
            if ($title_afterauthors = $this->getTitleAfterauthors($module, $props)) {
                $ret[GD_JS_TITLES]['afterauthors'] = $title_afterauthors;
            }
        }

        if ($this->showPosttitle($module)) {
            $ret['show-posttitle'] = true;
        }
        if ($this->showDate($module)) {
            $ret['show-date'] = true;
            $ret[GD_JS_TITLES]['date'] = TranslationAPIFacade::getInstance()->__('Go to permalink', 'pop-coreprocessors');
            $ret[GD_JS_CLASSES]['date'] = 'close close-sm';
        }

        if ($content_module = $this->getContentSubmodule($module)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['content'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($content_module);
        }

        if ($post_thumb = $this->getPostThumbSubmodule($module)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['postthumb'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($post_thumb);
        } else {
            if ($author_avatar = $this->getAuthorAvatarModule($module)) {
                $ret[GD_JS_SUBMODULEOUTPUTNAMES]['author-avatar'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($author_avatar);
            }
        }

        return $ret;
    }

    public function initModelProps(array $module, array &$props): void
    {
        if ($author_module = $this->getAuthorModule($module)) {
            $this->appendProp($author_module, $props, 'class', 'preview-author');
        }

        parent::initModelProps($module, $props);
    }
}
