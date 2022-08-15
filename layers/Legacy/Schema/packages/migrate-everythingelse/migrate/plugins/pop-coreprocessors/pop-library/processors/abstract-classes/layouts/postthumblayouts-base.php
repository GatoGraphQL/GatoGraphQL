<?php
use PoP\ConfigurationComponentModel\Facades\HelperServices\TypeResolverHelperServiceFacade;

abstract class PoP_Module_Processor_PostThumbLayoutsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(\PoP\ComponentModel\Component\Component $component, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_LAYOUT_POSTTHUMB];
    }

    /**
     * @return \PoP\ComponentModel\Component\Component[]
     */
    public function getSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        $ret = parent::getSubcomponents($component);

        if ($thumb_extras = $this->getExtraThumbLayoutSubcomponents($component)) {
            $ret = array_merge(
                $ret,
                $thumb_extras
            );
        }

        return $ret;
    }

    public function getExtraThumbLayoutSubcomponents(\PoP\ComponentModel\Component\Component $component)
    {

        // Add the MultiLayout item always, since the layouts will also be referenced by the MultLayout
        // If not on the MultiLayout page (eg: All Content) this will be hidden using css
        return array(
            [PoP_Module_Processor_PostAdditionalLayouts::class, PoP_Module_Processor_PostAdditionalLayouts::COMPONENT_LAYOUT_POSTADDITIONAL_MULTILAYOUT_LABEL]
        );
    }

    /**
     * @todo Migrate from string to LeafComponentFieldNode
     *
     * @return \PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\LeafComponentFieldNode[]
     */
    public function getLeafComponentFieldNodes(\PoP\ComponentModel\Component\Component $component, array &$props): array
    {
        $ret = parent::getLeafComponentFieldNodes($component, $props);

        $ret[] = $this->getThumbField($component, $props);
        $ret[] = $this->getUrlField($component);

        return $ret;
    }

    public function getUrlField(\PoP\ComponentModel\Component\Component $component)
    {
        return 'url';
    }

    public function getLinktarget(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        return '';
    }

    public function getThumbField(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        return /* @todo Re-do this code! Left undone */ new Field(
            $this->getThumbFieldName($component, $props),
            $this->getThumbFieldArgs($component, $props),
            $this->getThumbFieldAlias($component, $props)
        );
    }

    protected function getThumbFieldName(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        return 'thumb';
    }

    protected function getThumbFieldArgs(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        return ['size' => 'thumb-sm'];
    }

    protected function getThumbFieldAlias(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        return 'thumb';
    }

    public function getThumbImgClass(\PoP\ComponentModel\Component\Component $component)
    {
        return '';
    }

    public function getThumbLinkClass(\PoP\ComponentModel\Component\Component $component)
    {
        return '';
    }

    public function getImmutableConfiguration(\PoP\ComponentModel\Component\Component $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        $ret['url-field'] = $this->getUrlField($component);
        $ret['thumb'] = array(
            'name' => TypeResolverHelperServiceFacade::getInstance()->getTargetObjectTypeUniqueFieldOutputKeys(
                $this->getProp($component, $props, 'succeeding-typeResolver'),
                $this->getThumbField($component, $props) // @todo Fix: pass LeafField
            )
        );
        if ($target = $this->getLinktarget($component, $props)) {
            $ret['link-target'] = $target;
        }
        $ret[GD_JS_CLASSES]['img'] = $this->getProp($component, $props, 'img-class');
        $ret[GD_JS_CLASSES]['thumb-extras'] = 'thumb-extras';
        if ($link_class = $this->getThumbLinkClass($component)) {
            $ret[GD_JS_CLASSES]['link'] = $link_class;
        }

        if ($thumb_extras = $this->getExtraThumbLayoutSubcomponents($component)) {
            $ret[GD_JS_SUBCOMPONENTOUTPUTNAMES]['thumb-extras'] = array_map(
                \PoP\ComponentModel\Facades\ComponentHelpers\ComponentHelpersFacade::getInstance()->getComponentOutputName(...),
                $thumb_extras
            );
        }

        return $ret;
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        $this->appendProp($component, $props, 'img-class', $this->getThumbImgClass($component));
        parent::initModelProps($component, $props);
    }
}
