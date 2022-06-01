<?php
use PoP\ComponentModel\ComponentProcessors\FormComponentComponentProcessorInterface;
use PoPCMSSchema\Media\Facades\MediaTypeAPIFacade;

abstract class PoP_Module_Processor_FeaturedImageFormComponentsBase extends PoPEngine_QueryDataComponentProcessorBase implements FormComponentComponentProcessorInterface
{
    use FormComponentModuleDelegatorTrait;

    public function getTemplateResource(\PoP\ComponentModel\Component\Component $component, array &$props): ?array
    {
        // Hack: re-use multiple.tmpl
        return [PoP_BaseCollectionWebPlatform_TemplateResourceLoaderProcessor::class, PoP_BaseCollectionWebPlatform_TemplateResourceLoaderProcessor::RESOURCE_MULTIPLE];
    }

    public function getFormcomponentComponent(\PoP\ComponentModel\Component\Component $component)
    {
        return $this->getFeaturedimageinnerSubcomponent($component);
    }

    abstract public function getFeaturedimageinnerSubcomponent(\PoP\ComponentModel\Component\Component $component): ?\PoP\ComponentModel\Component\Component;

    /**
     * @return \PoP\ComponentModel\Component\Component[]
     */
    public function getSubcomponents(\PoP\ComponentModel\Component\Component $component): array
    {
        return array(
            $this->getFeaturedimageinnerSubcomponent($component),
        );
    }

    public function getImageSize(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        return 'thumb-md';
    }

    public function getImmutableConfiguration(\PoP\ComponentModel\Component\Component $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        // Hack: re-use multiple.tmpl
        $featuredimageinner = $this->getFeaturedimageinnerSubcomponent($component);
        $ret[GD_JS_SUBCOMPONENTOUTPUTNAMES]['elements'] = [
            \PoP\ComponentModel\Facades\ComponentHelpers\ComponentHelpersFacade::getInstance()->getComponentOutputName($featuredimageinner),
        ];

        return $ret;
    }

    public function getDefaultImage(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        if (defined('POP_CONTENTCREATION_IMAGE_DEFAULTFEATUREDIMAGE') && POP_CONTENTCREATION_IMAGE_DEFAULTFEATUREDIMAGE) {
            return POP_CONTENTCREATION_IMAGE_DEFAULTFEATUREDIMAGE;
        }

        return null;
    }

    public function initWebPlatformModelProps(\PoP\ComponentModel\Component\Component $component, array &$props)
    {
        $featuredimageinner = $this->getFeaturedimageinnerSubcomponent($component);

        // Set the "pop-merge" class to be able to redraw the inner layout
        $this->appendProp($component, $props, 'class', PoP_WebPlatformEngine_Module_Utils::getMergeClass($featuredimageinner));

        parent::initWebPlatformModelProps($component, $props);
    }

    public function initRequestProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        $this->metaFormcomponentInitModuleRequestProps($component, $props);
        parent::initRequestProps($component, $props);
    }

    public function initModelProps(\PoP\ComponentModel\Component\Component $component, array &$props): void
    {
        $featuredimageinner = $this->getFeaturedimageinnerSubcomponent($component);

        // Needed for the JS function
        $this->appendProp($component, $props, 'class', 'pop-featuredimage');

        // // The featuredimageinner module will need to be rendered dynamically on runtime
        // $this->setProp($featuredimageinner, $props, 'module-path', true);
        $this->setProp($featuredimageinner, $props, 'dynamic-component', true);

        // Set the needed params
        $img_size = $this->getImageSize($component, $props);
        $this->mergeProp(
            $component,
            $props,
            'params',
            array(
                'data-merge-component' => $featuredimageinner,
                'data-img-size' => $img_size,
            )
        );

        if ($defaultimg = $this->getDefaultImage($component, $props)) {
            $mediaTypeAPI = MediaTypeAPIFacade::getInstance();
            $defaultfeatured = $mediaTypeAPI->getImageProperties($defaultimg, $img_size);
            $this->setProp($featuredimageinner, $props, 'default-img', $defaultfeatured);
        }

        parent::initModelProps($component, $props);
    }
}
