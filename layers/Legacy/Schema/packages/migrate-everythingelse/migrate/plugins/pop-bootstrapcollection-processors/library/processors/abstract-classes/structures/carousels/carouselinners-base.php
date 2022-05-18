<?php

abstract class PoP_Module_Processor_CarouselInnersBase extends PoP_Module_Processor_StructureInnersBase
{
    public function getTemplateResource(array $component, array &$props): ?array
    {
        return [PoP_BootstrapCollectionWebPlatform_TemplateResourceLoaderProcessor::class, PoP_BootstrapCollectionWebPlatform_TemplateResourceLoaderProcessor::RESOURCE_CAROUSEL_INNER];
    }

    public function getLayoutGrid(array $component, array &$props)
    {
        return array(
            'divider' => 1,
            'class' => '',
        );
    }

    public function getImmutableConfiguration(array $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        $ret['layout-grid'] = $this->getProp($component, $props, 'layout-grid');

        // Comment Leo 03/07/2017: move the 'row' class out of the .tmpl, so it can be converted to style for the automatedemails
        $ret[GD_JS_CLASSES]['layoutgrid-wrapper'] = 'row';

        // Comment Leo 20/10/2017: this approach below doesn't work, because the configuration is saved, so that when appending items
        // will also paint the newcomer item as 'active' (since it has @index = 0) so then the carousel will have 2 active items
        // because of this, I introduced helper iffirstload, so that it only works when first loading the website, otherwise it doesn't,
        // which is ok since JS will add the class anyway
        // // If we are loading the frame, then we can already add class 'active' to the first item
        // // Do it so that content is already visible immediately
        // // If not no need, since JS will add it already
        // // We need JS to execute the logic to add 'active' when filtering/appending items on the carousel,
        // // so that there will always be one with active status (JS knows when it's the case, back-end doesn't know)
        // // that's why we don't add 'active' always here
        // if (RequestUtils::loadingSite()) {
        //     $ret[GD_JS_CLASSES]['first-item'] = 'active';
        // }

        return $ret;
    }

    public function initModelProps(array $component, array &$props): void
    {
        $this->setProp($component, $props, 'layout-grid', $this->getLayoutGrid($component, $props));

        // Needed for the automated emails
        $this->appendProp($component, $props, 'class', 'carousel-elem');

        // Comment Leo 03/07/2017: if the layout-grid has a class, add it to the module class
        // This is done so that the name of the class can be converted to style, for the automatedemails
        if ($layout_grid = $this->getProp($component, $props, 'layout-grid')) {
            if ($class = $layout_grid['class']) {
                $this->appendProp($component, $props, 'class', $class);
            }
        }


        parent::initModelProps($component, $props);
    }
}
