<?php
use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;

abstract class PoP_Module_Processor_PostThumbLayoutsBase extends PoPEngine_QueryDataModuleProcessorBase
{
    public function getTemplateResource(array $module, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_LAYOUT_POSTTHUMB];
    }

    public function getSubmodules(array $module): array
    {
        $ret = parent::getSubmodules($module);

        if ($thumb_extras = $this->getExtraThumbLayoutSubmodules($module)) {
            $ret = array_merge(
                $ret,
                $thumb_extras
            );
        }

        return $ret;
    }

    public function getExtraThumbLayoutSubmodules(array $module)
    {

        // Add the MultiLayout item always, since the layouts will also be referenced by the MultLayout
        // If not on the MultiLayout page (eg: All Content) this will be hidden using css
        return array(
            [PoP_Module_Processor_PostAdditionalLayouts::class, PoP_Module_Processor_PostAdditionalLayouts::MODULE_LAYOUT_POSTADDITIONAL_MULTILAYOUT_LABEL]
        );
    }

    /**
     * @todo Migrate from string to LeafField
     *
     * @return \PoP\GraphQLParser\Spec\Parser\Ast\LeafField[]
     */
    public function getDataFields(array $module, array &$props): array
    {
        $ret = parent::getDataFields($module, $props);

        $ret[] = $this->getThumbField($module, $props);
        $ret[] = $this->getUrlField($module);

        return $ret;
    }

    public function getUrlField(array $module)
    {
        return 'url';
    }

    public function getLinktarget(array $module, array &$props)
    {
        return '';
    }

    public function getThumbField(array $module, array &$props)
    {
        return FieldQueryInterpreterFacade::getInstance()->getField(
            $this->getThumbFieldName($module, $props),
            $this->getThumbFieldArgs($module, $props),
            $this->getThumbFieldAlias($module, $props)
        );
    }

    protected function getThumbFieldName(array $module, array &$props)
    {
        return 'thumb';
    }

    protected function getThumbFieldArgs(array $module, array &$props)
    {
        return ['size' => 'thumb-sm'];
    }

    protected function getThumbFieldAlias(array $module, array &$props)
    {
        return 'thumb';
    }

    public function getThumbImgClass(array $module)
    {
        return '';
    }

    public function getThumbLinkClass(array $module)
    {
        return '';
    }

    public function getImmutableConfiguration(array $module, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($module, $props);

        $ret['url-field'] = $this->getUrlField($module);
        $ret['thumb'] = array(
            'name' => FieldQueryInterpreterFacade::getInstance()->getTargetObjectTypeUniqueFieldOutputKeys(
                $this->getProp($module, $props, 'succeeding-typeResolver'),
                $this->getThumbField($module, $props))
        );
        if ($target = $this->getLinktarget($module, $props)) {
            $ret['link-target'] = $target;
        }
        $ret[GD_JS_CLASSES]['img'] = $this->getProp($module, $props, 'img-class');
        $ret[GD_JS_CLASSES]['thumb-extras'] = 'thumb-extras';
        if ($link_class = $this->getThumbLinkClass($module)) {
            $ret[GD_JS_CLASSES]['link'] = $link_class;
        }

        if ($thumb_extras = $this->getExtraThumbLayoutSubmodules($module)) {
            $ret[GD_JS_SUBMODULEOUTPUTNAMES]['thumb-extras'] = array_map(
                [\PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance(), 'getModuleOutputName'],
                $thumb_extras
            );
        }

        return $ret;
    }

    public function initModelProps(array $module, array &$props): void
    {
        $this->appendProp($module, $props, 'img-class', $this->getThumbImgClass($module));
        parent::initModelProps($module, $props);
    }
}
