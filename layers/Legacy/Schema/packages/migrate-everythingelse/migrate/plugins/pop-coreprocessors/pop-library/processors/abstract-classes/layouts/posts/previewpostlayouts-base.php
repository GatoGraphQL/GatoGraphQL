<?php
define('GD_CONSTANT_AUTHORPOSITION_ABOVETHUMB', 'abovethumb');
define('GD_CONSTANT_AUTHORPOSITION_ABOVETITLE', 'abovetitle');
define('GD_CONSTANT_AUTHORPOSITION_BELOWCONTENT', 'belowcontent');

use PoP\ComponentModel\Facades\ComponentProcessors\ComponentProcessorManagerFacade;
use PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\RelationalComponentField;
use PoP\Root\Facades\Translation\TranslationAPIFacade;

abstract class PoP_Module_Processor_PreviewPostLayoutsBase extends PoP_Module_Processor_PreviewObjectLayoutsBase
{
    public function getTemplateResource(\PoP\ComponentModel\Component\Component $component, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_LAYOUT_PREVIEWPOST];
    }

    public function getSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getSubcomponents($component);

        if ($belowthumb_components = $this->getBelowthumbLayoutSubcomponents($component)) {
            $ret = array_merge(
                $ret,
                $belowthumb_components
            );
        }
        if ($abovecontent_components = $this->getAbovecontentSubcomponents($component)) {
            $ret = array_merge(
                $ret,
                $abovecontent_components
            );
        }
        if ($belowcontent_components = $this->getBelowcontentSubcomponents($component)) {
            $ret = array_merge(
                $ret,
                $belowcontent_components
            );
        }
        if ($top_components = $this->getTopSubcomponents($component)) {
            $ret = array_merge(
                $ret,
                $top_components
            );
        }
        if ($bottom_components = $this->getPreviewpostBottomSubcomponents($component)) {
            $ret = array_merge(
                $ret,
                $bottom_components
            );
        }
        if ($beforecontent_components = $this->getBeforecontentSubcomponents($component)) {
            $ret = array_merge(
                $ret,
                $beforecontent_components
            );
        }
        if ($aftercontent_components = $this->getAftercontentSubcomponents($component)) {
            $ret = array_merge(
                $ret,
                $aftercontent_components
            );
        }
        if ($post_thumb = $this->getPostThumbSubcomponent($component)) {
            $ret[] = $post_thumb;
        }
        if ($content_component = $this->getContentSubcomponent($component)) {
            $ret[] = $content_component;
        }

        return $ret;
    }

    /**
     * @return RelationalComponentField[]
     */
    public function getRelationalComponentFields(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getRelationalComponentFields($component);

        $components = [];

        // Show author or not: if position defined
        if ($author_component = $this->getAuthorComponent($component)) {
            $components[] = $author_component;
        }

        // Show author avatar: only if no thumb module defined, and author avatar is defined
        if (!$this->getPostThumbSubcomponent($component)) {
            if ($author_avatar = $this->getAuthorAvatarComponent($component)) {
                $components[] = $author_avatar;
            }
        }

        if ($components) {
            $ret[] = new RelationalComponentField(
                'authors',
                $components
            );
        }

        return $ret;
    }

    /**
     * @todo Migrate from string to LeafComponentField
     *
     * @return \PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\LeafComponentField[]
     */
    public function getLeafComponentFields(\PoP\ComponentModel\Component\Component $component, array &$props): array
    {
        $ret = parent::getLeafComponentFields($component, $props);

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

    public function showPosttitle(\PoP\ComponentModel\Component\Component $component)
    {
        return true;
    }
    public function showDate(\PoP\ComponentModel\Component\Component $component)
    {
        return false;
    }

    public function getContentSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        return null;
    }
    public function getPostThumbSubcomponent(\PoP\ComponentModel\Component\Component $component)
    {
        return null;
    }
    public function getAuthorComponent(\PoP\ComponentModel\Component\Component $component)
    {
        return [PoP_Module_Processor_MultipleUserLayouts::class, PoP_Module_Processor_MultipleUserLayouts::COMPONENT_LAYOUT_MULTIPLEUSER_CONTEXTUALPOSTAUTHOR];
    }
    public function getAuthorAvatarComponent(\PoP\ComponentModel\Component\Component $component)
    {
        return null;
    }
    public function getTitleBeforeauthors(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        return array();
    }
    public function getTitleAfterauthors(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        return array();
    }
    public function authorPositions(\PoP\ComponentModel\Component\Component $component)
    {
        return array(
            GD_CONSTANT_AUTHORPOSITION_ABOVETHUMB
        );
    }
    // function layoutextraPosition(\PoP\ComponentModel\Component\Component $component, array &$props) {

    //     return GD_CONSTANT_LAYOUTEXTRAPOSITION_BELOWTHUMB;
    // }
    public function getAuthorsSeparator(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        return GD_CONSTANT_AUTHORS_SEPARATOR;
    }

    public function getBelowthumbLayoutSubcomponents(\PoP\ComponentModel\Component\Component $component)
    {
        return array();
    }
    public function getAbovecontentSubcomponents(\PoP\ComponentModel\Component\Component $component)
    {
        $ret = array();

        return $ret;
    }
    public function getBelowcontentSubcomponents(\PoP\ComponentModel\Component\Component $component)
    {
        return array();
    }
    public function getBottomSubcomponents(\PoP\ComponentModel\Component\Component $component)
    {
        return array();
    }
    public function getTopSubcomponents(\PoP\ComponentModel\Component\Component $component)
    {
        return array();
    }
    public function getAftercontentSubcomponents(\PoP\ComponentModel\Component\Component $component)
    {
        return array();
    }
    public function getBeforecontentSubcomponents(\PoP\ComponentModel\Component\Component $component)
    {
        return array();
    }
    public function getPreviewpostBottomSubcomponents(\PoP\ComponentModel\Component\Component $component)
    {

        // Allow 3rd parties to modify the modules. Eg: for the TPP website we re-use the MESYM Theme but we modify some of its elements, eg: adding the "What do you think about TPP?" modules in the fullview templates
        return \PoP\Root\App::applyFilters('PoP_Module_Processor_PreviewPostLayoutsBase:bottom_components', $this->getBottomSubcomponents($component), $component);
    }

    public function getImmutableConfiguration(\PoP\ComponentModel\Component\Component $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        $componentprocessor_manager = ComponentProcessorManagerFacade::getInstance();

        if ($belowthumb_components = $this->getBelowthumbLayoutSubcomponents($component)) {
            $ret[GD_JS_SUBCOMPONENTOUTPUTNAMES]['belowthumb'] = array_map(
                \PoP\ComponentModel\Facades\Modules\ComponentHelpersFacade::getInstance()->getComponentOutputName(...),
                $belowthumb_components
            );
        }
        if ($abovecontent_components = $this->getAbovecontentSubcomponents($component)) {
            $ret[GD_JS_SUBCOMPONENTOUTPUTNAMES]['abovecontent'] = array_map(
                \PoP\ComponentModel\Facades\Modules\ComponentHelpersFacade::getInstance()->getComponentOutputName(...),
                $abovecontent_components
            );
        }
        if ($belowcontent_components = $this->getBelowcontentSubcomponents($component)) {
            $ret[GD_JS_SUBCOMPONENTOUTPUTNAMES]['belowcontent'] = array_map(
                \PoP\ComponentModel\Facades\Modules\ComponentHelpersFacade::getInstance()->getComponentOutputName(...),
                $belowcontent_components
            );
        }
        if ($top_components = $this->getTopSubcomponents($component)) {
            $ret[GD_JS_SUBCOMPONENTOUTPUTNAMES]['top'] = array_map(
                \PoP\ComponentModel\Facades\Modules\ComponentHelpersFacade::getInstance()->getComponentOutputName(...),
                $top_components
            );
        }
        if ($bottom_components = $this->getPreviewpostBottomSubcomponents($component)) {
            $ret[GD_JS_SUBCOMPONENTOUTPUTNAMES]['bottom'] = array_map(
                \PoP\ComponentModel\Facades\Modules\ComponentHelpersFacade::getInstance()->getComponentOutputName(...),
                $bottom_components
            );
        }
        if ($beforecontent_components = $this->getBeforecontentSubcomponents($component)) {
            $ret[GD_JS_SUBCOMPONENTOUTPUTNAMES]['beforecontent'] = array_map(
                \PoP\ComponentModel\Facades\Modules\ComponentHelpersFacade::getInstance()->getComponentOutputName(...),
                $beforecontent_components
            );
        }
        if ($aftercontent_components = $this->getAftercontentSubcomponents($component)) {
            $ret[GD_JS_SUBCOMPONENTOUTPUTNAMES]['aftercontent'] = array_map(
                \PoP\ComponentModel\Facades\Modules\ComponentHelpersFacade::getInstance()->getComponentOutputName(...),
                $aftercontent_components
            );
        }
        if ($author_component = $this->getAuthorComponent($component)) {
            $ret[GD_JS_SUBCOMPONENTOUTPUTNAMES]['authors'] = \PoP\ComponentModel\Facades\Modules\ComponentHelpersFacade::getInstance()->getComponentOutputName($author_component);
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

        if ($content_component = $this->getContentSubcomponent($component)) {
            $ret[GD_JS_SUBCOMPONENTOUTPUTNAMES]['content'] = \PoP\ComponentModel\Facades\Modules\ComponentHelpersFacade::getInstance()->getComponentOutputName($content_component);
        }

        if ($post_thumb = $this->getPostThumbSubcomponent($component)) {
            $ret[GD_JS_SUBCOMPONENTOUTPUTNAMES]['postthumb'] = \PoP\ComponentModel\Facades\Modules\ComponentHelpersFacade::getInstance()->getComponentOutputName($post_thumb);
        } else {
            if ($author_avatar = $this->getAuthorAvatarComponent($component)) {
                $ret[GD_JS_SUBCOMPONENTOUTPUTNAMES]['author-avatar'] = \PoP\ComponentModel\Facades\Modules\ComponentHelpersFacade::getInstance()->getComponentOutputName($author_avatar);
            }
        }

        return $ret;
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        if ($author_component = $this->getAuthorComponent($component)) {
            $this->appendProp($author_component, $props, 'class', 'preview-author');
        }

        parent::initModelProps($component, $props);
    }
}
