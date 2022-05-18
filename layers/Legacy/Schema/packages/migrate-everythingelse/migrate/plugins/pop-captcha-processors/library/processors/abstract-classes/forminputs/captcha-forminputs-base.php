<?php

use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\Engine\ComponentProcessors\FormMultipleInputComponentProcessorTrait;

abstract class PoP_Module_Processor_CaptchaFormInputsBase extends PoP_Module_Processor_FormInputsBase
{
    use FormMultipleInputComponentProcessorTrait;

    public function getTemplateResource(array $component, array &$props): ?array
    {
        return [PoP_CaptchaWebPlatform_TemplateResourceLoaderProcessor::class, PoP_CaptchaWebPlatform_TemplateResourceLoaderProcessor::RESOURCE_FORMINPUT_CAPTCHA];
    }

    public function initModelProps(array $component, array &$props): void
    {

        // Use the label as placeholder
        $this->setProp($component, $props, 'placeholder', $this->getLabel($component, $props));
        parent::initModelProps($component, $props);
    }

    public function getInputSubnames(array $component): array
    {
        return array('input', 'encoded', 'random');
    }

    public function getMutableonrequestConfiguration(array $component, array &$props): array
    {
        $ret = parent::getMutableonrequestConfiguration($component, $props);

        // Generate a random number for this captcha
        $captcha = GeneralUtils::generateRandomString(4, false, '0123456789');

        // Also generate a random string to add more security
        $random = GeneralUtils::generateRandomString();

        // Calculate the encoded string
        $encoded = PoP_CaptchaEncodeDecode::encode($captcha, $random);

        // Save the captcha image URL
        $ret['captcha-imgsrc'] = GD_Captcha::getImageSrc($encoded, $random);

        // Save the values to recreate/validate the captcha
        $ret['encoded'] = $encoded;
        $ret['random'] = $random;

        return $ret;
    }

    public function getImmutableConfiguration(array $component, array &$props): array
    {
        $ret = parent::getImmutableConfiguration($component, $props);

        if ($placeholder = $this->getProp($component, $props, 'placeholder')) {
            $ret['placeholder'] = $placeholder;
        }

        if ($wrapper_class = $this->getProp($component, $props, 'wrapper-class')) {
            $ret[GD_JS_CLASSES]['wrapper'] = $wrapper_class;
        }

        return $ret;
    }
}
