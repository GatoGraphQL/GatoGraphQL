<?php
use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;

abstract class PoP_Module_Processor_PostThumbLayoutsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $component, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_LAYOUT_POSTTHUMB];
    }

    public function getSubcomponents(array $component): array
    {
        $ret = parent::getSubcomponents($component);

        if ($thumb_extras = $this->getExtraThumbLayoutSubmodules($component)) {
            $ret = array_merge(
                $ret,
                $thumb_extras
            );
        }

        return $ret;
    }

    public function getExtraThumbLayoutSubmodules(array $component)
    {

        // Add the MultiLayout item always, since the layouts will also be referenced by the MultLayout
        // If not on the MultiLayout page (eg: All Content) this will be hidden using css
        return array(
            [PoP_Module_Processor_PostAdditionalLayouts::class, PoP_Module_Processor_PostAdditionalLayouts::COMPONENT_LAYOUT_POSTADDITIONAL_MULTILAYOUT_LABEL]
        );
    }

    /**
     * @todo Migrate from string to LeafModuleField
     *
     * @return \PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\LeafModuleField[]
     */
    public function getDataFields(array $component, array &$props): array
    {
        $ret = parent::getDataFields($component, $props);

        $ret[] = $this->getThumbField($component, $props);
        $ret[] = $this->getUrlField($component);

        return $ret;
    }

    public function getUrlField(array $component)
    {
        return 'url';
    }

    public function getLinktarget(array $component, array &$props)
    {
        return '';
    }

    public function getThumbField(array $component, array &$props)
    {
        return FieldQueryInterpreterFacade::getInstance()->getField(
            $this->getThumbFieldName($component, $props),
            $this->getThumbFieldArgs($component, $props),
            $this->getThumbFieldAlias($component, $props)
        );
    }

    protected function getThumbFieldName(array $component, array &$props)
    {
        return 'thumb';
    }

    protected function getThumbFieldArgs(array $component, array &$props)
    {
        return ['size' => 'thumb-sm'];
    }

    protected function getThumbFieldAlias(array $component, array &$props)
    {
        return 'thumb';
    }

    public function getThumbImgClass(array $component)
    {
        return '';
    }

    public function getThumbLinkClass(array $component)
    {
        return '';
    }

    public function getImmutableConfiguration(array $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        $ret['url-field'] = $this->getUrlField($component);
        $ret['thumb'] = array(
            'name' => FieldQueryInterpreterFacade::getInstance()->getTargetObjectTypeUniqueFieldOutputKeys(
                $this->getProp($component, $props, 'succeeding-typeResolver'),
                $this->getThumbField($component, $props))
        );
        if ($target = $this->getLinktarget($component, $props)) {
            $ret['link-target'] = $target;
        }
        $ret[GD_JS_CLASSES]['img'] = $this->getProp($component, $props, 'img-class');
        $ret[GD_JS_CLASSES]['thumb-extras'] = 'thumb-extras';
        if ($link_class = $this->getThumbLinkClass($component)) {
            $ret[GD_JS_CLASSES]['link'] = $link_class;
        }

        if ($thumb_extras = $this->getExtraThumbLayoutSubmodules($component)) {
            $ret[GD_JS_SUBCOMPONENTOUTPUTNAMES]['thumb-extras'] = array_map(
                [\PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance(), 'getModuleOutputName'],
                $thumb_extras
            );
        }

        return $ret;
    }

    public function initModelProps(array $component, array &$props): void
    {
        $this->appendProp($component, $props, 'img-class', $this->getThumbImgClass($component));
        parent::initModelProps($component, $props);
    }
}
