<?php
use PoP\ComponentModel\ComponentProcessors\FormComponentComponentProcessorInterface;
use PoPCMSSchema\Media\Facades\MediaTypeAPIFacade;

abstract class PoP_Module_Processor_FeaturedImageFormComponentsBase extends PoPEngine_QueryDataComponentProcessorBase implements FormComponentComponentProcessorInterface
{
    use FormComponentModuleDelegatorTrait;

    public function getTemplateResource(array $componentVariation, array &$props): ?array
    {
        // Hack: re-use multiple.tmpl
        return [PoP_BaseCollectionWebPlatform_TemplateResourceLoaderProcessor::class, PoP_BaseCollectionWebPlatform_TemplateResourceLoaderProcessor::RESOURCE_MULTIPLE];
    }

    public function getFormcomponentModule(array $componentVariation)
    {
        return $this->getFeaturedimageinnerSubmodule($componentVariation);
    }

    abstract public function getFeaturedimageinnerSubmodule(array $componentVariation): ?array;

    public function getSubComponentVariations(array $componentVariation): array
    {
        return array(
            $this->getFeaturedimageinnerSubmodule($componentVariation),
        );
    }

    public function getImageSize(array $componentVariation, array &$props)
    {
        return 'thumb-md';
    }

    public function getImmutableConfiguration(array $componentVariation, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($componentVariation, $props);

        // Hack: re-use multiple.tmpl
        $featuredimageinner = $this->getFeaturedimageinnerSubmodule($componentVariation);
        $ret[GD_JS_SUBMODULEOUTPUTNAMES]['elements'] = [
            \PoP\ComponentModel\Facades\Modules\ModuleHelpersFacade::getInstance()->getModuleOutputName($featuredimageinner),
        ];

        return $ret;
    }

    public function getDefaultImage(array $componentVariation, array &$props)
    {
        if (defined('POP_CONTENTCREATION_IMAGE_DEFAULTFEATUREDIMAGE') && POP_CONTENTCREATION_IMAGE_DEFAULTFEATUREDIMAGE) {
            return POP_CONTENTCREATION_IMAGE_DEFAULTFEATUREDIMAGE;
        }

        return null;
    }

    public function initWebPlatformModelProps(array $componentVariation, array &$props)
    {
        $featuredimageinner = $this->getFeaturedimageinnerSubmodule($componentVariation);

        // Set the "pop-merge" class to be able to redraw the inner layout
        $this->appendProp($componentVariation, $props, 'class', PoP_WebPlatformEngine_Module_Utils::getMergeClass($featuredimageinner));

        parent::initWebPlatformModelProps($componentVariation, $props);
    }

    public function initRequestProps(array $componentVariation, array &$props): void
    {
        $this->metaFormcomponentInitModuleRequestProps($componentVariation, $props);
        parent::initRequestProps($componentVariation, $props);
    }

    public function initModelProps(array $componentVariation, array &$props): void
    {
        $featuredimageinner = $this->getFeaturedimageinnerSubmodule($componentVariation);

        // Needed for the JS function
        $this->appendProp($componentVariation, $props, 'class', 'pop-featuredimage');

        // // The featuredimageinner module will need to be rendered dynamically on runtime
        // $this->setProp($featuredimageinner, $props, 'module-path', true);
        $this->setProp($featuredimageinner, $props, 'dynamic-module', true);

        // Set the needed params
        $img_size = $this->getImageSize($componentVariation, $props);
        $this->mergeProp(
            $componentVariation,
            $props,
            'params',
            array(
                'data-merge-module' => $featuredimageinner,
                'data-img-size' => $img_size,
            )
        );

        if ($defaultimg = $this->getDefaultImage($componentVariation, $props)) {
            $mediaTypeAPI = MediaTypeAPIFacade::getInstance();
            $defaultfeatured = $mediaTypeAPI->getImageProperties($defaultimg, $img_size);
            $this->setProp($featuredimageinner, $props, 'default-img', $defaultfeatured);
        }

        parent::initModelProps($componentVariation, $props);
    }
}
