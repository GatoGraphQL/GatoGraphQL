<?php

declare(strict_types=1);

namespace PoP\ComponentModel\MutationResolvers;

use Exception;

abstract class AbstractTaggedMutationResolver extends AbstractMutationResolver
{
    /**
     * @return array<string, MutationResolverInterface> Array of inputFieldName => MutationResolver
     */
    protected abstract function getMutationResolvers(): array;
    
    /**
     * The tagged input object can receive only 1 input field at a time.
     * Retrieve it, or throw an Exception if this is not respected
     *
     * @param array<string,mixed> $formData
     * @return string
     * @throws Exception If either there is none or more than 1 inputFieldNames being used
     */
    protected function getCurrentInputFieldName(array $formData): string
    {
        $formDataSize = count($formData);
        if ($formDataSize !== 1) {
            throw new Exception(
                sprintf(
                    $this->getTranslationAPI()->__('Only and exactly 1 input field must be provided to the TaggedMutationResolver, but %s were provided', 'component-model'),
                    $formDataSize
                )
            );
        }
        reset($formData);
        $inputFieldName = key($formData);
        if (!is_string($inputFieldName)) {
            throw new Exception(
                sprintf(
                    $this->getTranslationAPI()->__('The input field provided to the TaggedMutationResolver must be of type string, but \'%s\' was provided', 'component-model'),
                    $inputFieldName
                )
            );
        }
        return $inputFieldName;
    }

    /**
     * @param array<string,mixed> $formData
     * @return array<string,mixed>
     * @throws Exception If there is not MutationResolver for the input field
     */
    protected function getInputFieldMutationResolver(string $inputFieldName): MutationResolverInterface
    {
        $inputFieldMutationResolver = $this->getMutationResolvers()[$inputFieldName] ?? null;
        if ($inputFieldMutationResolver === null) {
            throw new Exception(
                sprintf(
                    $this->getTranslationAPI()->__('There is not MutationResolver for input field with name \'%s\'', 'component-model'),
                    $inputFieldName
                )
            );
        }
        return $inputFieldMutationResolver;
    }

    /**
     * @param array<string,mixed> $formData
     * @return array<string,mixed>
     * @throws Exception If the form data for the input field is not present in the array
     */
    protected function getInputFieldFormData(string $inputFieldName, array $formData): array
    {
        $inputFieldFormData = $formData[$inputFieldName] ?? null;
        if ($inputFieldFormData === null) {
            throw new Exception(
                sprintf(
                    $this->getTranslationAPI()->__('There is not form data for input field with name \'%s\'', 'component-model'),
                    $inputFieldName
                )
            );
        }
        return $inputFieldFormData;
    }

    final public function executeMutation(array $formData): mixed
    {
        $inputFieldName = $this->getCurrentInputFieldName($formData);
        $inputFieldMutationResolver = $this->getInputFieldMutationResolver($inputFieldName);
        $inputFieldFormData = $this->getInputFieldFormData($inputFieldName, $formData);
        return $inputFieldMutationResolver->executeMutation($inputFieldFormData);
    }
    final public function validateErrors(array $formData): array
    {
        $inputFieldName = $this->getCurrentInputFieldName($formData);
        $inputFieldMutationResolver = $this->getInputFieldMutationResolver($inputFieldName);
        $inputFieldFormData = $this->getInputFieldFormData($inputFieldName, $formData);
        return $inputFieldMutationResolver->validateErrors($inputFieldFormData);
    }
    final public function validateWarnings(array $formData): array
    {
        $inputFieldName = $this->getCurrentInputFieldName($formData);
        $inputFieldMutationResolver = $this->getInputFieldMutationResolver($inputFieldName);
        $inputFieldFormData = $this->getInputFieldFormData($inputFieldName, $formData);
        return $inputFieldMutationResolver->validateWarnings($inputFieldFormData);
    }
}
