<?php
use PoP\ComponentModel\ModuleProcessors\FormComponentModuleProcessorInterface;
use PoPCMSSchema\Media\Facades\MediaTypeAPIFacade;

abstract class PoP_Module_Processor_FeaturedImageFormComponentsBase extends PoPEngine_QueryDataModuleProcessorBase implements FormComponentModuleProcessorInterface
{
    use FormComponentModuleDelegatorTrait;

    public function getTemplateResource(array $module, array &$props): ?array
    {
        // Hack: re-use multiple.tmpl
        return [PoP_BaseCollectionWebPlatform_TemplateResourceLoaderProcessor::class, PoP_BaseCollectionWebPlatform_TemplateResourceLoaderProcessor::RESOURCE_MULTIPLE];
    }

    public function getFormcomponentModule(array $module)
    {
        return $this->getFeaturedimageinnerSubmodule($module);
    }

    abstract public function getFeaturedimageinnerSubmodule(array $module): ?array;

    public function getSubmodules(array $module): array
    {
        return array(
            $this->getFeaturedimageinnerSubmodule($module),
        );
    }

    public function getImageSize(array $module, array &$props)
    {
        return 'thumb-md';
    }

    public function getImmutableConfiguration(array $module, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($module, $props);

        // Hack: re-use multiple.tmpl
        $featuredimageinner = $this->getFeaturedimageinnerSubmodule($module);
        $ret[GD_JS_SUBMODULEOUTPUTNAMES]['elements'] = [
            \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($featuredimageinner),
        ];

        return $ret;
    }

    public function getDefaultImage(array $module, array &$props)
    {
        if (defined('POP_CONTENTCREATION_IMAGE_DEFAULTFEATUREDIMAGE') && POP_CONTENTCREATION_IMAGE_DEFAULTFEATUREDIMAGE) {
            return POP_CONTENTCREATION_IMAGE_DEFAULTFEATUREDIMAGE;
        }

        return null;
    }

    public function initWebPlatformModelProps(array $module, array &$props)
    {
        $featuredimageinner = $this->getFeaturedimageinnerSubmodule($module);

        // Set the "pop-merge" class to be able to redraw the inner layout
        $this->appendProp($module, $props, 'class', PoP_WebPlatformEngine_Module_Utils::getMergeClass($featuredimageinner));

        parent::initWebPlatformModelProps($module, $props);
    }

    public function initRequestProps(array $module, array &$props): void
    {
        $this->metaFormcomponentInitModuleRequestProps($module, $props);
        parent::initRequestProps($module, $props);
    }

    public function initModelProps(array $module, array &$props): void
    {
        $featuredimageinner = $this->getFeaturedimageinnerSubmodule($module);

        // Needed for the JS function
        $this->appendProp($module, $props, 'class', 'pop-featuredimage');

        // // The featuredimageinner module will need to be rendered dynamically on runtime
        // $this->setProp($featuredimageinner, $props, 'module-path', true);
        $this->setProp($featuredimageinner, $props, 'dynamic-module', true);

        // Set the needed params
        $img_size = $this->getImageSize($module, $props);
        $this->mergeProp(
            $module,
            $props,
            'params',
            array(
                'data-merge-module' => $featuredimageinner,
                'data-img-size' => $img_size,
            )
        );

        if ($defaultimg = $this->getDefaultImage($module, $props)) {
            $mediaTypeAPI = MediaTypeAPIFacade::getInstance();
            $defaultfeatured = $mediaTypeAPI->getImageProperties($defaultimg, $img_size);
            $this->setProp($featuredimageinner, $props, 'default-img', $defaultfeatured);
        }

        parent::initModelProps($module, $props);
    }
}
