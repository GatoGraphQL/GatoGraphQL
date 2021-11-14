<?php

declare(strict_types=1);

namespace PoP\ComponentModel\MutationResolvers;

use Exception;
use stdClass;

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
     * @return string
     * @throws Exception If either there is none or more than 1 inputFieldNames being used
     */
    protected function getCurrentInputFieldName(stdClass $taggedInputObjectFormData): string
    {
        $taggedInputObjectFormDataSize = count((array)$taggedInputObjectFormData);
        if ($taggedInputObjectFormDataSize !== 1) {
            throw new Exception(
                sprintf(
                    $this->getTranslationAPI()->__('Only and exactly 1 input field must be provided to the TaggedMutationResolver, but %s were provided', 'component-model'),
                    $taggedInputObjectFormDataSize
                )
            );
        }
        // Retrieve the first (and only) element key
        return key($taggedInputObjectFormData);
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
                    $this->getTranslationAPI()->__('There is no MutationResolver for input field with name \'%s\'', 'component-model'),
                    $inputFieldName
                )
            );
        }
        return $inputFieldMutationResolver;
    }

    /**
     * @return array<string,mixed>|stdClass
     * @throws Exception If the form data for the input field is not present in the array
     */
    protected function getInputFieldFormData(string $inputFieldName, stdClass $taggedInputObjectFormData): array|stdClass
    {
        $inputFieldFormData = $taggedInputObjectFormData->$inputFieldName ?? null;
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

    abstract protected function getTaggedInputObjectFormData(array $formData): stdClass;

    final public function executeMutation(array $formData): mixed
    {
        $taggedInputObjectFormData = $this->getTaggedInputObjectFormData($formData);
        $inputFieldName = $this->getCurrentInputFieldName($taggedInputObjectFormData);
        $inputFieldMutationResolver = $this->getInputFieldMutationResolver($inputFieldName);
        $inputFieldFormData = $this->getInputFieldFormData($inputFieldName, $taggedInputObjectFormData);
        return $inputFieldMutationResolver->executeMutation((array)$inputFieldFormData);
    }
    final public function validateErrors(array $formData): array
    {
        $taggedInputObjectFormData = $this->getTaggedInputObjectFormData($formData);
        $inputFieldName = $this->getCurrentInputFieldName($taggedInputObjectFormData);
        $inputFieldMutationResolver = $this->getInputFieldMutationResolver($inputFieldName);
        $inputFieldFormData = $this->getInputFieldFormData($inputFieldName, $taggedInputObjectFormData);
        return $inputFieldMutationResolver->validateErrors((array)$inputFieldFormData);
    }
    final public function validateWarnings(array $formData): array
    {
        $taggedInputObjectFormData = $this->getTaggedInputObjectFormData($formData);
        $inputFieldName = $this->getCurrentInputFieldName($taggedInputObjectFormData);
        $inputFieldMutationResolver = $this->getInputFieldMutationResolver($inputFieldName);
        $inputFieldFormData = $this->getInputFieldFormData($inputFieldName, $taggedInputObjectFormData);
        return $inputFieldMutationResolver->validateWarnings((array)$inputFieldFormData);
    }
}
