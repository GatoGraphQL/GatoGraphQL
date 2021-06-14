<?php

declare(strict_types=1);

namespace PoPSitesWassup\FormMutations\MutationResolverBridges;

use PoP\ComponentModel\ErrorHandling\Error;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\ComponentModel\MutationResolverBridges\AbstractComponentMutationResolverBridge;
use PoP\ComponentModel\QueryInputOutputHandlers\ResponseConstants;

abstract class AbstractFormComponentMutationResolverBridge extends AbstractComponentMutationResolverBridge
{
    /**
     * @return array<string, mixed>|null
     */
    public function execute(array &$data_properties): ?array
    {
        if ($this->onlyExecuteWhenDoingPost() && 'POST' !== $_SERVER['REQUEST_METHOD']) {
            return null;
        }

        // Before submitting the form, validate the captcha (otherwise, the form is submitted independently of the result of this validation)
        if (\PoP_Forms_ConfigurationUtils::captchaEnabled()) {
            $captcha_validation = $this->validateCaptcha($data_properties);
            if (GeneralUtils::isError($captcha_validation)) {
                return $this->getCaptchaError($captcha_validation);
            }
        }

        // return $this->executeForm($data_properties);
        return parent::execute($data_properties);
    }

    protected function validateCaptcha($data_properties)
    {
        // Check if Captcha validation is needed
        if ($data_properties[GD_DATALOAD_QUERYHANDLERPROPERTY_FORM_VALIDATECAPTCHA]) {
            $captcha = $this->moduleProcessorManager->getProcessor([\PoP_Module_Processor_CaptchaFormInputs::class, \PoP_Module_Processor_CaptchaFormInputs::MODULE_FORMINPUT_CAPTCHA])->getValue([PoP_Module_Processor_CaptchaFormInputs::class, PoP_Module_Processor_CaptchaFormInputs::MODULE_FORMINPUT_CAPTCHA]);
            return \GD_Captcha::validate($captcha);
        }

        return true;
    }

    protected function getCaptchaError(Error $captcha_error)
    {
        return array(
            ResponseConstants::ERRORSTRINGS => array($captcha_error->getMessageOrCode())
        );
    }
}
