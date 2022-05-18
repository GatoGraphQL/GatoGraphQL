<?php
define('GD_CONSTANT_AUTHORPOSITION_ABOVETHUMB', 'abovethumb');
define('GD_CONSTANT_AUTHORPOSITION_ABOVETITLE', 'abovetitle');
define('GD_CONSTANT_AUTHORPOSITION_BELOWCONTENT', 'belowcontent');

use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;
use PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\RelationalModuleField;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

abstract class PoP_Module_Processor_PreviewPostLayoutsBase extends PoP_Module_Processor_PreviewObjectLayoutsBase
{
    public function getTemplateResource(array $componentVariation, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_LAYOUT_PREVIEWPOST];
    }

    public function getSubComponentVariations(array $componentVariation): array
    {
        $ret = parent::getSubComponentVariations($componentVariation);

        if ($belowthumb_componentVariations = $this->getBelowthumbLayoutSubmodules($componentVariation)) {
            $ret = array_merge(
                $ret,
                $belowthumb_componentVariations
            );
        }
        if ($abovecontent_componentVariations = $this->getAbovecontentSubmodules($componentVariation)) {
            $ret = array_merge(
                $ret,
                $abovecontent_componentVariations
            );
        }
        if ($belowcontent_componentVariations = $this->getBelowcontentSubmodules($componentVariation)) {
            $ret = array_merge(
                $ret,
                $belowcontent_componentVariations
            );
        }
        if ($top_componentVariations = $this->getTopSubmodules($componentVariation)) {
            $ret = array_merge(
                $ret,
                $top_componentVariations
            );
        }
        if ($bottom_componentVariations = $this->getPreviewpostBottomSubmodules($componentVariation)) {
            $ret = array_merge(
                $ret,
                $bottom_componentVariations
            );
        }
        if ($beforecontent_componentVariations = $this->getBeforecontentSubmodules($componentVariation)) {
            $ret = array_merge(
                $ret,
                $beforecontent_componentVariations
            );
        }
        if ($aftercontent_componentVariations = $this->getAftercontentSubmodules($componentVariation)) {
            $ret = array_merge(
                $ret,
                $aftercontent_componentVariations
            );
        }
        if ($post_thumb = $this->getPostThumbSubmodule($componentVariation)) {
            $ret[] = $post_thumb;
        }
        if ($content_componentVariation = $this->getContentSubmodule($componentVariation)) {
            $ret[] = $content_componentVariation;
        }

        return $ret;
    }

    /**
     * @return RelationalModuleField[]
     */
    public function getRelationalSubmodules(array $componentVariation): array
    {
        $ret = parent::getRelationalSubmodules($componentVariation);

        $componentVariations = [];

        // Show author or not: if position defined
        if ($author_componentVariation = $this->getAuthorModule($componentVariation)) {
            $componentVariations[] = $author_componentVariation;
        }

        // Show author avatar: only if no thumb module defined, and author avatar is defined
        if (!$this->getPostThumbSubmodule($componentVariation)) {
            if ($author_avatar = $this->getAuthorAvatarModule($componentVariation)) {
                $componentVariations[] = $author_avatar;
            }
        }

        if ($componentVariations) {
            $ret[] = new RelationalModuleField(
                'authors',
                $componentVariations
            );
        }

        return $ret;
    }

    /**
     * @todo Migrate from string to LeafModuleField
     *
     * @return \PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\LeafModuleField[]
     */
    public function getDataFields(array $componentVariation, array &$props): array
    {
        $ret = parent::getDataFields($componentVariation, $props);

        $ret[] = 'customPostType';
        $ret[] = 'catSlugs';
        if ($this->showPosttitle($componentVariation)) {
            $ret[] = 'title';
        }
        if ($this->showDate($componentVariation)) {
            $ret[] = 'datetime';
        }

        return $ret;
    }

    public function showPosttitle(array $componentVariation)
    {
        return true;
    }
    public function showDate(array $componentVariation)
    {
        return false;
    }

    public function getContentSubmodule(array $componentVariation)
    {
        return null;
    }
    public function getPostThumbSubmodule(array $componentVariation)
    {
        return null;
    }
    public function getAuthorModule(array $componentVariation)
    {
        return [PoP_Module_Processor_MultipleUserLayouts::class, PoP_Module_Processor_MultipleUserLayouts::MODULE_LAYOUT_MULTIPLEUSER_CONTEXTUALPOSTAUTHOR];
    }
    public function getAuthorAvatarModule(array $componentVariation)
    {
        return null;
    }
    public function getTitleBeforeauthors(array $componentVariation, array &$props)
    {
        return array();
    }
    public function getTitleAfterauthors(array $componentVariation, array &$props)
    {
        return array();
    }
    public function authorPositions(array $componentVariation)
    {
        return array(
            GD_CONSTANT_AUTHORPOSITION_ABOVETHUMB
        );
    }
    // function layoutextraPosition(array $componentVariation, array &$props) {

    //     return GD_CONSTANT_LAYOUTEXTRAPOSITION_BELOWTHUMB;
    // }
    public function getAuthorsSeparator(array $componentVariation, array &$props)
    {
        return GD_CONSTANT_AUTHORS_SEPARATOR;
    }

    public function getBelowthumbLayoutSubmodules(array $componentVariation)
    {
        return array();
    }
    public function getAbovecontentSubmodules(array $componentVariation)
    {
        $ret = array();

        return $ret;
    }
    public function getBelowcontentSubmodules(array $componentVariation)
    {
        return array();
    }
    public function getBottomSubmodules(array $componentVariation)
    {
        return array();
    }
    public function getTopSubmodules(array $componentVariation)
    {
        return array();
    }
    public function getAftercontentSubmodules(array $componentVariation)
    {
        return array();
    }
    public function getBeforecontentSubmodules(array $componentVariation)
    {
        return array();
    }
    public function getPreviewpostBottomSubmodules(array $componentVariation)
    {

        // Allow 3rd parties to modify the modules. Eg: for the TPP website we re-use the MESYM Theme but we modify some of its elements, eg: adding the "What do you think about TPP?" modules in the fullview templates
        return \PoP\Root\App::applyFilters('PoP_Module_Processor_PreviewPostLayoutsBase:bottom_componentVariations', $this->getBottomSubmodules($componentVariation), $componentVariation);
    }

    public function getImmutableConfiguration(array $componentVariation, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($componentVariation, $props);

        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        if ($belowthumb_componentVariations = $this->getBelowthumbLayoutSubmodules($componentVariation)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['belowthumb'] = array_map(
                [\PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance(), 'getModuleOutputName'],
                $belowthumb_componentVariations
            );
        }
        if ($abovecontent_componentVariations = $this->getAbovecontentSubmodules($componentVariation)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['abovecontent'] = array_map(
                [\PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance(), 'getModuleOutputName'],
                $abovecontent_componentVariations
            );
        }
        if ($belowcontent_componentVariations = $this->getBelowcontentSubmodules($componentVariation)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['belowcontent'] = array_map(
                [\PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance(), 'getModuleOutputName'],
                $belowcontent_componentVariations
            );
        }
        if ($top_componentVariations = $this->getTopSubmodules($componentVariation)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['top'] = array_map(
                [\PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance(), 'getModuleOutputName'],
                $top_componentVariations
            );
        }
        if ($bottom_componentVariations = $this->getPreviewpostBottomSubmodules($componentVariation)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['bottom'] = array_map(
                [\PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance(), 'getModuleOutputName'],
                $bottom_componentVariations
            );
        }
        if ($beforecontent_componentVariations = $this->getBeforecontentSubmodules($componentVariation)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['beforecontent'] = array_map(
                [\PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance(), 'getModuleOutputName'],
                $beforecontent_componentVariations
            );
        }
        if ($aftercontent_componentVariations = $this->getAftercontentSubmodules($componentVariation)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['aftercontent'] = array_map(
                [\PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance(), 'getModuleOutputName'],
                $aftercontent_componentVariations
            );
        }
        if ($author_componentVariation = $this->getAuthorModule($componentVariation)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['authors'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($author_componentVariation);
            $ret['authors-position'] = $this->authorPositions($componentVariation);
            $ret['authors-sep'] = $this->getAuthorsSeparator($componentVariation, $props);
            if ($title_beforeauthors = $this->getTitleBeforeauthors($componentVariation, $props)) {
                $ret[GD_JS_TITLES]['beforeauthors'] = $title_beforeauthors;
            }
            if ($title_afterauthors = $this->getTitleAfterauthors($componentVariation, $props)) {
                $ret[GD_JS_TITLES]['afterauthors'] = $title_afterauthors;
            }
        }

        if ($this->showPosttitle($componentVariation)) {
            $ret['show-posttitle'] = true;
        }
        if ($this->showDate($componentVariation)) {
            $ret['show-date'] = true;
            $ret[GD_JS_TITLES]['date'] = TranslationAPIFacade::getInstance()->__('Go to permalink', 'pop-coreprocessors');
            $ret[GD_JS_CLASSES]['date'] = 'close close-sm';
        }

        if ($content_componentVariation = $this->getContentSubmodule($componentVariation)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['content'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($content_componentVariation);
        }

        if ($post_thumb = $this->getPostThumbSubmodule($componentVariation)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['postthumb'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($post_thumb);
        } else {
            if ($author_avatar = $this->getAuthorAvatarModule($componentVariation)) {
                $ret[GD_JS_SUBMODULEOUTPUTNAMES]['author-avatar'] = \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($author_avatar);
            }
        }

        return $ret;
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        if ($author_componentVariation = $this->getAuthorModule($componentVariation)) {
            $this->appendProp($author_componentVariation, $props, 'class', 'preview-author');
        }

        parent::initModelProps($componentVariation, $props);
    }
}
