<?php

declare(strict_types=1);

namespace PoPSitesWassup\FormMutations\MutationResolverBridges;

use PoP_Forms_ConfigurationUtils;
use PoP_Module_Processor_CaptchaFormInputs;
use GD_Captcha;
use PoP\ComponentModel\ComponentProcessors\DataloadQueryArgsFilterInputComponentProcessorInterface;
use PoP\ComponentModel\MutationResolverBridges\AbstractComponentMutationResolverBridge;
use PoP\ComponentModel\QueryInputOutputHandlers\ResponseConstants;
use PoP\Root\App;
use PoP\Root\Exception\GenericClientException;

abstract class AbstractFormComponentMutationResolverBridge extends AbstractComponentMutationResolverBridge
{
    /**
     * @return array<string,mixed>|null
     * @param array<string,mixed> $data_properties
     */
    public function executeMutation(array &$data_properties): ?array
    {
        if ($this->onlyExecuteWhenDoingPost() && 'POST' !== App::server('REQUEST_METHOD')) {
            return null;
        }

        // Before submitting the form, validate the captcha (otherwise, the form is submitted independently of the result of this validation)
        if (PoP_Forms_ConfigurationUtils::captchaEnabled()) {
            try {
                $this->validateCaptcha($data_properties);
            } catch (GenericClientException $e) {
                return array(
                    ResponseConstants::ERRORSTRINGS => array($e->getMessage())
                );
            }
        }

        // return $this->executeForm($data_properties);
        return parent::executeMutation($data_properties);
    }

    /**
     * @throws GenericClientException
     */
    protected function validateCaptcha($data_properties)
    {
        // Check if Captcha validation is needed
        if ($data_properties[GD_DATALOAD_QUERYHANDLERPROPERTY_FORM_VALIDATECAPTCHA]) {
            /** @var DataloadQueryArgsFilterInputComponentProcessorInterface */
            $processor = $this->getComponentProcessorManager()->getComponentProcessor([PoP_Module_Processor_CaptchaFormInputs::class, PoP_Module_Processor_CaptchaFormInputs::COMPONENT_FORMINPUT_CAPTCHA]);
            $captcha = $processor->getValue([PoP_Module_Processor_CaptchaFormInputs::class, PoP_Module_Processor_CaptchaFormInputs::COMPONENT_FORMINPUT_CAPTCHA]);
            GD_Captcha::assertIsValid($captcha);
        }
    }
}
