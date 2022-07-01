<?php

declare(strict_types=1);

namespace PoP\ComponentModel\MutationResolverBridges;

use Exception;
use PoP\ComponentModel\App;
use PoP\ComponentModel\ComponentProcessors\ComponentProcessorManagerInterface;
use PoP\ComponentModel\ComponentProcessors\DataloadingConstants;
use PoP\ComponentModel\Module;
use PoP\ComponentModel\ModuleConfiguration;
use PoP\ComponentModel\Mutation\MutationDataProvider;
use PoP\ComponentModel\Mutation\MutationDataProviderInterface;
use PoP\ComponentModel\MutationResolvers\ErrorTypes;
use PoP\ComponentModel\QueryInputOutputHandlers\ResponseConstants;
use PoP\Root\Exception\AbstractClientException;
use PoP\Root\Feedback\FeedbackItemResolution;
use PoP\Root\Services\BasicServiceTrait;

abstract class AbstractComponentMutationResolverBridge implements ComponentMutationResolverBridgeInterface
{
    use BasicServiceTrait;

    private ?ComponentProcessorManagerInterface $componentProcessorManager = null;

    final public function setComponentProcessorManager(ComponentProcessorManagerInterface $componentProcessorManager): void
    {
        $this->componentProcessorManager = $componentProcessorManager;
    }
    final protected function getComponentProcessorManager(): ComponentProcessorManagerInterface
    {
        return $this->componentProcessorManager ??= $this->instanceManager->getInstance(ComponentProcessorManagerInterface::class);
    }

    public function getSuccessString(string | int $resultID): ?string
    {
        return null;
    }

    /**
     * @return string[]
     */
    public function getSuccessStrings(string | int $resultID): array
    {
        $success_string = $this->getSuccessString($resultID);
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
    public function executeMutation(array &$data_properties): ?array
    {
        if ($this->onlyExecuteWhenDoingPost() && 'POST' !== App::server('REQUEST_METHOD')) {
            return null;
        }
        $mutationResolver = $this->getMutationResolver();
        $mutationDataProvider = $this->getMutationDataProvider();
        $this->fillMutationDataProvider($mutationDataProvider);
        $mutationResponse = [];
        // Validate errors
        $errorType = $mutationResolver->getErrorType();
        $errorTypeKeys = [
            ErrorTypes::DESCRIPTIONS => ResponseConstants::ERRORSTRINGS,
            ErrorTypes::CODES => ResponseConstants::ERRORCODES,
        ];
        $errorTypeKey = $errorTypeKeys[$errorType];
        if ($errors = $mutationResolver->validateErrors($mutationDataProvider)) {
            // @todo Migrate from string to FeedbackItemProvider
            $mutationResponse[$errorTypeKey] = array_map(
                fn (FeedbackItemResolution $feedbackItemResolution) => $feedbackItemResolution->getMessage(),
                $errors
            );
            if ($this->skipDataloadIfError()) {
                // Bring no results
                $data_properties[DataloadingConstants::SKIPDATALOAD] = true;
            }
            return $mutationResponse;
        }
        if ($warnings = $mutationResolver->validateWarnings($mutationDataProvider)) {
            $warningTypeKeys = [
                ErrorTypes::DESCRIPTIONS => ResponseConstants::WARNINGSTRINGS,
                ErrorTypes::CODES => ResponseConstants::WARNINGCODES,
            ];
            $warningTypeKey = $warningTypeKeys[$errorType];
            // @todo Migrate from string to FeedbackItemProvider
            $mutationResponse[$warningTypeKey] = array_map(
                fn (FeedbackItemResolution $feedbackItemResolution) => $feedbackItemResolution->getMessage(),
                $warnings
            );
        }

        $errorMessage = null;
        $resultID = null;
        try {
            $resultID = $mutationResolver->executeMutation($mutationDataProvider);
        } catch (AbstractClientException $e) {
            $errorMessage = $e->getMessage();
            $errorTypeKey = ResponseConstants::ERRORSTRINGS;
        } catch (Exception $e) {
            /** @var ModuleConfiguration */
            $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
            if ($moduleConfiguration->logExceptionErrorMessagesAndTraces()) {
                // @todo: Implement for Log
            }
            $errorMessage = $moduleConfiguration->sendExceptionErrorMessages()
                ? $e->getMessage()
                : $this->__('Resolving the mutation produced an exception, please contact the admin', 'component-model');
            $errorTypeKey = ResponseConstants::ERRORSTRINGS;
        }
        if ($errorMessage !== null) {
            if ($this->skipDataloadIfError()) {
                // Bring no results
                $data_properties[DataloadingConstants::SKIPDATALOAD] = true;
            }
            $mutationResponse[$errorTypeKey] = [$errorMessage];
            return $mutationResponse;
        }

        $this->modifyDataProperties($data_properties, $resultID);

        // Save the result for some component to incorporate it into the query args
        App::getMutationResolutionStore()->setResult($this, $resultID);

        $mutationResponse[ResponseConstants::SUCCESS] = true;
        if ($success_strings = $this->getSuccessStrings($resultID)) {
            $mutationResponse[ResponseConstants::SUCCESSSTRINGS] = $success_strings;
        }
        return $mutationResponse;
    }

    protected function getMutationDataProvider(): MutationDataProviderInterface
    {
        return new MutationDataProvider();
    }

    protected function modifyDataProperties(array &$data_properties, string | int $resultID): void
    {
    }
}
