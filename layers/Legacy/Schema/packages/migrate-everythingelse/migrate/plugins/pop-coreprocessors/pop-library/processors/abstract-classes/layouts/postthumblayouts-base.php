<?php
use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;

abstract class PoP_Module_Processor_PostThumbLayoutsBase extends PoPEngine_QueryDataComponentProcessorBase
{
    public function getTemplateResource(array $componentVariation, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_LAYOUT_POSTTHUMB];
    }

    public function getSubComponentVariations(array $componentVariation): array
    {
        $ret = parent::getSubComponentVariations($componentVariation);

        if ($thumb_extras = $this->getExtraThumbLayoutSubmodules($componentVariation)) {
            $ret = array_merge(
                $ret,
                $thumb_extras
            );
        }

        return $ret;
    }

    public function getExtraThumbLayoutSubmodules(array $componentVariation)
    {

        // Add the MultiLayout item always, since the layouts will also be referenced by the MultLayout
        // If not on the MultiLayout page (eg: All Content) this will be hidden using css
        return array(
            [PoP_Module_Processor_PostAdditionalLayouts::class, PoP_Module_Processor_PostAdditionalLayouts::MODULE_LAYOUT_POSTADDITIONAL_MULTILAYOUT_LABEL]
        );
    }

    /**
     * @todo Migrate from string to LeafModuleField
     *
     * @return \PoP\ComponentModel\GraphQLEngine\Model\ComponentModelSpec\LeafModuleField[]
     */
    public function getDataFields(array $componentVariation, array &$props): array
    {
        $ret = parent::getDataFields($componentVariation, $props);

        $ret[] = $this->getThumbField($componentVariation, $props);
        $ret[] = $this->getUrlField($componentVariation);

        return $ret;
    }

    public function getUrlField(array $componentVariation)
    {
        return 'url';
    }

    public function getLinktarget(array $componentVariation, array &$props)
    {
        return '';
    }

    public function getThumbField(array $componentVariation, array &$props)
    {
        return FieldQueryInterpreterFacade::getInstance()->getField(
            $this->getThumbFieldName($componentVariation, $props),
            $this->getThumbFieldArgs($componentVariation, $props),
            $this->getThumbFieldAlias($componentVariation, $props)
        );
    }

    protected function getThumbFieldName(array $componentVariation, array &$props)
    {
        return 'thumb';
    }

    protected function getThumbFieldArgs(array $componentVariation, array &$props)
    {
        return ['size' => 'thumb-sm'];
    }

    protected function getThumbFieldAlias(array $componentVariation, array &$props)
    {
        return 'thumb';
    }

    public function getThumbImgClass(array $componentVariation)
    {
        return '';
    }

    public function getThumbLinkClass(array $componentVariation)
    {
        return '';
    }

    public function getImmutableConfiguration(array $componentVariation, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($componentVariation, $props);

        $ret['url-field'] = $this->getUrlField($componentVariation);
        $ret['thumb'] = array(
            'name' => FieldQueryInterpreterFacade::getInstance()->getTargetObjectTypeUniqueFieldOutputKeys(
                $this->getProp($componentVariation, $props, 'succeeding-typeResolver'),
                $this->getThumbField($componentVariation, $props))
        );
        if ($target = $this->getLinktarget($componentVariation, $props)) {
            $ret['link-target'] = $target;
        }
        $ret[GD_JS_CLASSES]['img'] = $this->getProp($componentVariation, $props, 'img-class');
        $ret[GD_JS_CLASSES]['thumb-extras'] = 'thumb-extras';
        if ($link_class = $this->getThumbLinkClass($componentVariation)) {
            $ret[GD_JS_CLASSES]['link'] = $link_class;
        }

        if ($thumb_extras = $this->getExtraThumbLayoutSubmodules($componentVariation)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['thumb-extras'] = array_map(
                [\PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance(), 'getModuleOutputName'],
                $thumb_extras
            );
        }

        return $ret;
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        $this->appendProp($componentVariation, $props, 'img-class', $this->getThumbImgClass($componentVariation));
        parent::initModelProps($componentVariation, $props);
    }
}
