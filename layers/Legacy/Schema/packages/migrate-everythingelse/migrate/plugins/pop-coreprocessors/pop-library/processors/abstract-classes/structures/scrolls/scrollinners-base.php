<?php

define('POP_HOOK_SCROLLINNER_THUMBNAIL_GRID', 'scrollinner-thumbnail-grid');

abstract class PoP_Module_Processor_ScrollInnersBase extends PoP_Module_Processor_StructureInnersBase
{
    public function getTemplateResource(array $module, array &$props): ?array
    {
        return [PoP_CoreProcessors_TemplateResourceLoaderProcessor::class, PoP_CoreProcessors_TemplateResourceLoaderProcessor::RESOURCE_SCROLL_INNER];
    }

    public function getLayoutGrid(array $module, array &$props)
    {
        return array(
            'row-items' => 1,
            'class' => 'col-sm-12'
        );
    }

    public function getImmutableConfiguration(array $module, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($module, $props);

        $ret['layout-grid'] = $this->getProp($module, $props, 'layout-grid');

        // Comment Leo 03/07/2017: move the 'row' class out of the .tmpl, so it can be converted to style for the automatedemails
        $ret[GD_JS_CLASSES]['layoutgrid-wrapper'] = 'row';

        return $ret;
    }

    public function initModelProps(array $module, array &$props): void
    {
        $this->setProp($module, $props, 'layout-grid', $this->getLayoutGrid($module, $props));

        // Needed for the automated emails
        $this->appendProp($module, $props, 'class', 'scroll-elem');

        // Comment Leo 03/07/2017: if the layout-grid has a class, add it to the module class
        // This is done so that the name of the class can be converted to style, for the automatedemails
        if ($layout_grid = $this->getProp($module, $props, 'layout-grid')) {
            if ($class = $layout_grid['class']) {
                $this->appendProp($module, $props, 'class', $class);
            }
        }

        parent::initModelProps($module, $props);
    }
}
