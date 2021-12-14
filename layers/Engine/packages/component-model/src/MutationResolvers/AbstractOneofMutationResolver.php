<?php

declare(strict_types=1);

namespace PoP\ComponentModel\MutationResolvers;

use Exception;
use stdClass;

abstract class AbstractOneofMutationResolver extends AbstractMutationResolver
{
    /** @var array<string, MutationResolverInterface>|null */
    private ?array $consolidatedInputFieldNameMutationResolversCache = null;

    /**
     * The MutationResolvers contained in the OneofMutationResolver,
     * organized by inputFieldName
     *
     * @return array<string, MutationResolverInterface> Array of inputFieldName => MutationResolver
     */
    abstract protected function getInputFieldNameMutationResolvers(): array;

    /**
     * Consolidation of the mutation resolver for each input field. Call this function to read the data
     * instead of the individual functions, since it applies hooks to override/extend.
     */
    final public function getConsolidatedInputFieldNameMutationResolvers(): array
    {
        if ($this->consolidatedInputFieldNameMutationResolversCache !== null) {
            return $this->consolidatedInputFieldNameMutationResolversCache;
        }
        $this->consolidatedInputFieldNameMutationResolversCache = $this->getHooksAPI()->applyFilters(
            HookNames::INPUT_FIELD_NAME_MUTATION_RESOLVERS,
            $this->getInputFieldNameMutationResolvers(),
            $this,
        );
        return $this->consolidatedInputFieldNameMutationResolversCache;
    }

    /**
     * The oneof input object can receive only 1 input field at a time.
     * Retrieve it, or throw an Exception if this is not respected
     *
     * @return string
     * @throws Exception If either there is none or more than 1 inputFieldNames being used
     */
    protected function getCurrentInputFieldName(stdClass $oneofInputObjectFormData): string
    {
        $oneofInputObjectFormDataSize = count((array)$oneofInputObjectFormData);
        if ($oneofInputObjectFormDataSize !== 1) {
            throw new Exception(
                sprintf(
                    $this->getTranslationAPI()->__('Only and exactly 1 input field must be provided to the OneofMutationResolver, but %s were provided', 'component-model'),
                    $oneofInputObjectFormDataSize
                )
            );
        }
        // Retrieve the first (and only) element key
        return (string)key((array)$oneofInputObjectFormData);
    }

    /**
     * @throws Exception If there is not MutationResolver for the input field
     */
    protected function getInputFieldMutationResolver(string $inputFieldName): MutationResolverInterface
    {
        $inputFieldMutationResolver = $this->getConsolidatedInputFieldNameMutationResolvers()[$inputFieldName] ?? null;
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
    protected function getInputFieldFormData(string $inputFieldName, stdClass $oneofInputObjectFormData): array|stdClass
    {
        $inputFieldFormData = $oneofInputObjectFormData->$inputFieldName ?? null;
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

    /**
     * Assume there's only one argument in the field,
     * for this OneofMutationResolver.
     * If that's not the case, this function must be overriden,
     * to avoid throwing an Exception
     *
     * @return stdClass The current input field's form data
     * @throws Exception If more than 1 argument is passed to the field executing the OneofMutation
     */
    protected function getOneofInputObjectFormData(array $formData): stdClass
    {
        $formDataSize = count($formData);
        if ($formDataSize !== 1) {
            throw new Exception(
                sprintf(
                    $this->getTranslationAPI()->__('The OneofMutationResolver expects only 1 argument is passed to the field executing the mutation, but %s were provided: \'%s\'', 'component-model'),
                    $formDataSize,
                    implode('\'%s\'', array_keys($formData))
                )
            );
        }
        $fieldArgName = key($formData);
        return $formData[$fieldArgName];
    }

    final public function executeMutation(array $formData): mixed
    {
        [$inputFieldMutationResolver, $inputFieldFormData] = $this->getInputFieldMutationResolverAndFormData($formData);
        return $inputFieldMutationResolver->executeMutation((array)$inputFieldFormData);
    }
    final public function validateErrors(array $formData): array
    {
        [$inputFieldMutationResolver, $inputFieldFormData] = $this->getInputFieldMutationResolverAndFormData($formData);
        return $inputFieldMutationResolver->validateErrors((array)$inputFieldFormData);
    }
    final public function validateWarnings(array $formData): array
    {
        [$inputFieldMutationResolver, $inputFieldFormData] = $this->getInputFieldMutationResolverAndFormData($formData);
        return $inputFieldMutationResolver->validateWarnings((array)$inputFieldFormData);
    }
    /**
     * @param array<string,mixed> $formData
     * @return mixed[] An array of 2 items: the current input field's mutation resolver, and the current input field's form data
     */
    final protected function getInputFieldMutationResolverAndFormData(array $formData): array
    {
        $oneofInputObjectFormData = $this->getOneofInputObjectFormData($formData);
        $inputFieldName = $this->getCurrentInputFieldName($oneofInputObjectFormData);
        $inputFieldMutationResolver = $this->getInputFieldMutationResolver($inputFieldName);
        $inputFieldFormData = $this->getInputFieldFormData($inputFieldName, $oneofInputObjectFormData);
        return [$inputFieldMutationResolver, $inputFieldFormData];
    }
}
