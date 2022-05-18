<?php
define('GD_CONSTANT_AUTHORPOSITION_ABOVETHUMB', 'abovethumb');
define('GD_CONSTANT_AUTHORPOSITION_ABOVETITLE', 'abovetitle');
define('GD_CONSTANT_AUTHORPOSITION_BELOWCONTENT', 'belowcontent');

use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;
use PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\RelationalModuleField;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

abstract class PoP_Module_Processor_PreviewPostLayoutsBase extends PoP_Module_Processor_PreviewObjectLayoutsBase
{
    public function getTemplateResource(array $component, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_LAYOUT_PREVIEWPOST];
    }

    public function getSubComponents(array $component): array
    {
        $ret = parent::getSubComponents($component);

        if ($belowthumb_components = $this->getBelowthumbLayoutSubmodules($component)) {
            $ret = array_merge(
                $ret,
                $belowthumb_components
            );
        }
        if ($abovecontent_components = $this->getAbovecontentSubmodules($component)) {
            $ret = array_merge(
                $ret,
                $abovecontent_components
            );
        }
        if ($belowcontent_components = $this->getBelowcontentSubmodules($component)) {
            $ret = array_merge(
                $ret,
                $belowcontent_components
            );
        }
        if ($top_components = $this->getTopSubmodules($component)) {
            $ret = array_merge(
                $ret,
                $top_components
            );
        }
        if ($bottom_components = $this->getPreviewpostBottomSubmodules($component)) {
            $ret = array_merge(
                $ret,
                $bottom_components
            );
        }
        if ($beforecontent_components = $this->getBeforecontentSubmodules($component)) {
            $ret = array_merge(
                $ret,
                $beforecontent_components
            );
        }
        if ($aftercontent_components = $this->getAftercontentSubmodules($component)) {
            $ret = array_merge(
                $ret,
                $aftercontent_components
            );
        }
        if ($post_thumb = $this->getPostThumbSubmodule($component)) {
            $ret[] = $post_thumb;
        }
        if ($content_component = $this->getContentSubmodule($component)) {
            $ret[] = $content_component;
        }

        return $ret;
    }

    /**
     * @return RelationalModuleField[]
     */
    public function getRelationalSubmodules(array $component): array
    {
        $ret = parent::getRelationalSubmodules($component);

        $components = [];

        // Show author or not: if position defined
        if ($author_component = $this->getAuthorModule($component)) {
            $components[] = $author_component;
        }

        // Show author avatar: only if no thumb module defined, and author avatar is defined
        if (!$this->getPostThumbSubmodule($component)) {
            if ($author_avatar = $this->getAuthorAvatarModule($component)) {
                $components[] = $author_avatar;
            }
        }

        if ($components) {
            $ret[] = new RelationalModuleField(
                'authors',
                $components
            );
        }

        return $ret;
    }

    /**
     * @todo Migrate from string to LeafModuleField
     *
     * @return \PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\LeafModuleField[]
     */
    public function getDataFields(array $component, array &$props): array
    {
        $ret = parent::getDataFields($component, $props);

        $ret[] = 'customPostType';
        $ret[] = 'catSlugs';
        if ($this->showPosttitle($component)) {
            $ret[] = 'title';
        }
        if ($this->showDate($component)) {
            $ret[] = 'datetime';
        }

        return $ret;
    }

    public function showPosttitle(array $component)
    {
        return true;
    }
    public function showDate(array $component)
    {
        return false;
    }

    public function getContentSubmodule(array $component)
    {
        return null;
    }
    public function getPostThumbSubmodule(array $component)
    {
        return null;
    }
    public function getAuthorModule(array $component)
    {
        return [PoP_Module_Processor_MultipleUserLayouts::class, PoP_Module_Processor_MultipleUserLayouts::COMPONENT_LAYOUT_MULTIPLEUSER_CONTEXTUALPOSTAUTHOR];
    }
    public function getAuthorAvatarModule(array $component)
    {
        return null;
    }
    public function getTitleBeforeauthors(array $component, array &$props)
    {
        return array();
    }
    public function getTitleAfterauthors(array $component, array &$props)
    {
        return array();
    }
    public function authorPositions(array $component)
    {
        return array(
            GD_CONSTANT_AUTHORPOSITION_ABOVETHUMB
        );
    }
    // function layoutextraPosition(array $component, array &$props) {

    //     return GD_CONSTANT_LAYOUTEXTRAPOSITION_BELOWTHUMB;
    // }
    public function getAuthorsSeparator(array $component, array &$props)
    {
        return GD_CONSTANT_AUTHORS_SEPARATOR;
    }

    public function getBelowthumbLayoutSubmodules(array $component)
    {
        return array();
    }
    public function getAbovecontentSubmodules(array $component)
    {
        $ret = array();

        return $ret;
    }
    public function getBelowcontentSubmodules(array $component)
    {
        return array();
    }
    public function getBottomSubmodules(array $component)
    {
        return array();
    }
    public function getTopSubmodules(array $component)
    {
        return array();
    }
    public function getAftercontentSubmodules(array $component)
    {
        return array();
    }
    public function getBeforecontentSubmodules(array $component)
    {
        return array();
    }
    public function getPreviewpostBottomSubmodules(array $component)
    {

        // Allow 3rd parties to modify the modules. Eg: for the TPP website we re-use the MESYM Theme but we modify some of its elements, eg: adding the "What do you think about TPP?" modules in the fullview templates
        return \PoP\Root\App::applyFilters('PoP_Module_Processor_PreviewPostLayoutsBase:bottom_components', $this->getBottomSubmodules($component), $component);
    }

    public function getImmutableConfiguration(array $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        if ($belowthumb_components = $this->getBelowthumbLayoutSubmodules($component)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['belowthumb'] = array_map(
                [\PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance(), 'getModuleOutputName'],
                $belowthumb_components
            );
        }
        if ($abovecontent_components = $this->getAbovecontentSubmodules($component)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['abovecontent'] = array_map(
                [\PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance(), 'getModuleOutputName'],
                $abovecontent_components
            );
        }
        if ($belowcontent_components = $this->getBelowcontentSubmodules($component)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['belowcontent'] = array_map(
                [\PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance(), 'getModuleOutputName'],
                $belowcontent_components
            );
        }
        if ($top_components = $this->getTopSubmodules($component)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['top'] = array_map(
                [\PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance(), 'getModuleOutputName'],
                $top_components
            );
        }
        if ($bottom_components = $this->getPreviewpostBottomSubmodules($component)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['bottom'] = array_map(
                [\PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance(), 'getModuleOutputName'],
                $bottom_components
            );
        }
        if ($beforecontent_components = $this->getBeforecontentSubmodules($component)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['beforecontent'] = array_map(
                [\PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance(), 'getModuleOutputName'],
                $beforecontent_components
            );
        }
        if ($aftercontent_components = $this->getAftercontentSubmodules($component)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['aftercontent'] = array_map(
                [\PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance(), 'getModuleOutputName'],
                $aftercontent_components
            );
        }
        if ($author_component = $this->getAuthorModule($component)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['authors'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($author_component);
            $ret['authors-position'] = $this->authorPositions($component);
            $ret['authors-sep'] = $this->getAuthorsSeparator($component, $props);
            if ($title_beforeauthors = $this->getTitleBeforeauthors($component, $props)) {
                $ret[GD_JS_TITLES]['beforeauthors'] = $title_beforeauthors;
            }
            if ($title_afterauthors = $this->getTitleAfterauthors($component, $props)) {
                $ret[GD_JS_TITLES]['afterauthors'] = $title_afterauthors;
            }
        }

        if ($this->showPosttitle($component)) {
            $ret['show-posttitle'] = true;
        }
        if ($this->showDate($component)) {
            $ret['show-date'] = true;
            $ret[GD_JS_TITLES]['date'] = TranslationAPIFacade::getInstance()->__('Go to permalink', 'pop-coreprocessors');
            $ret[GD_JS_CLASSES]['date'] = 'close close-sm';
        }

        if ($content_component = $this->getContentSubmodule($component)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['content'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($content_component);
        }

        if ($post_thumb = $this->getPostThumbSubmodule($component)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['postthumb'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($post_thumb);
        } else {
            if ($author_avatar = $this->getAuthorAvatarModule($component)) {
                $ret[GD_JS_SUBMODULEOUTPUTNAMES]['author-avatar'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($author_avatar);
            }
        }

        return $ret;
    }

    public function initModelProps(array $component, array &$props): void
    {
        if ($author_component = $this->getAuthorModule($component)) {
            $this->appendProp($author_component, $props, 'class', 'preview-author');
        }

        parent::initModelProps($component, $props);
    }
}
