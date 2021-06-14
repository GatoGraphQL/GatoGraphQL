<?php

declare(strict_types=1);

namespace PoP\ComponentModel\MutationResolverBridges;

use PoP\Hooks\HooksAPIInterface;
use PoP\ComponentModel\Misc\GeneralUtils;
use PoP\ComponentModel\ErrorHandling\Error;
use PoP\Translation\TranslationAPIInterface;
use PoP\ComponentModel\MutationResolvers\ErrorTypes;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\ComponentModel\ModuleProcessors\DataloadingConstants;
use PoP\ComponentModel\QueryInputOutputHandlers\ResponseConstants;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoP\ComponentModel\MutationResolution\MutationResolutionManagerInterface;
use PoP\ComponentModel\MutationResolverBridges\ComponentMutationResolverBridgeInterface;

abstract class AbstractComponentMutationResolverBridge implements ComponentMutationResolverBridgeInterface
{ 
    public function __construct(
        protected HooksAPIInterface $hooksAPI,
        protected TranslationAPIInterface $translationAPI,
        protected InstanceManagerInterface $instanceManager,
        protected MutationResolutionManagerInterface $mutationResolutionManager,
    ) {
    }

    public function getSuccessString(string | int $result_id): ?string
    {
        return null;
    }

    /**
     * @return string[]
     */
    public function getSuccessStrings(string | int $result_id): array
    {
        $success_string = $this->getSuccessString($result_id);
        return $success_string !== null ? [$success_string] : [];
    }

    protected function onlyExecuteWhenDoingPost(): bool
    {
        return true;
    }

    protected function skipDataloadIfError(): bool
    {
        return false;
    }

    /**
     * @return array<string, mixed>|null
     */
    public function execute(array &$data_properties): ?array
    {
        if ($this->onlyExecuteWhenDoingPost() && 'POST' !== $_SERVER['REQUEST_METHOD']) {
            return null;
        }
        $mutationResolverClass = $this->getMutationResolverClass();
        /** @var MutationResolverInterface */
        $mutationResolver = $this->instanceManager->getInstance($mutationResolverClass);
        $form_data = $this->getFormData();
        $return = [];
        // Validate errors
        $errorType = $mutationResolver->getErrorType();
        $errorTypeKeys = [
            ErrorTypes::DESCRIPTIONS => ResponseConstants::ERRORSTRINGS,
            ErrorTypes::CODES => ResponseConstants::ERRORCODES,
        ];
        $errorTypeKey = $errorTypeKeys[$errorType];
        if ($errors = $mutationResolver->validateErrors($form_data)) {
            $return[$errorTypeKey] = $errors;
            if ($this->skipDataloadIfError()) {
                // Bring no results
                $data_properties[DataloadingConstants::SKIPDATALOAD] = true;
            }
            return $return;
        }
        if ($warnings = $mutationResolver->validateWarnings($form_data)) {
            $warningTypeKeys = [
                ErrorTypes::DESCRIPTIONS => ResponseConstants::WARNINGSTRINGS,
                ErrorTypes::CODES => ResponseConstants::WARNINGCODES,
            ];
            $warningTypeKey = $warningTypeKeys[$errorType];
            $return[$warningTypeKey] = $warnings;
        }
        $result_id = $mutationResolver->execute($form_data);
        if (GeneralUtils::isError($result_id)) {
            /** @var Error */
            $error = $result_id;
            $errors = [];
            if ($errorTypeKey == ErrorTypes::DESCRIPTIONS) {
                $errors[] = $error->getMessageOrCode();
            } elseif ($errorTypeKey == ErrorTypes::CODES) {
                $errors[] = $error->getCode();
            }
            $return[$errorTypeKey] = $errors;
            if ($this->skipDataloadIfError()) {
                // Bring no results
                $data_properties[DataloadingConstants::SKIPDATALOAD] = true;
            }
            return $return;
        }
        $this->modifyDataProperties($data_properties, $result_id);

        // Save the result for some module to incorporate it into the query args
        $this->mutationResolutionManager->setResult(get_called_class(), $result_id);

        $return[ResponseConstants::SUCCESS] = true;
        if ($success_strings = $this->getSuccessStrings($result_id)) {
            $return[ResponseConstants::SUCCESSSTRINGS] = $success_strings;
        }
        return $return;
    }

    protected function modifyDataProperties(array &$data_properties, string | int $result_id): void
    {
    }
}
