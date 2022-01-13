<?php

declare(strict_types=1);

namespace PoP\ComponentModel\MutationResolvers;

use PoP\Root\App;
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
        $this->consolidatedInputFieldNameMutationResolversCache = App::applyFilters(
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
     * @throws Exception If either there is none or more than 1 inputFieldNames being used
     */
    protected function getCurrentInputFieldName(stdClass $oneofInputObjectFormData): string
    {
        $oneofInputObjectFormDataSize = count((array)$oneofInputObjectFormData);
        if ($oneofInputObjectFormDataSize !== 1) {
            throw new Exception(
                sprintf(
                    $this->__('Only and exactly 1 input field must be provided to the OneofMutationResolver, but %s were provided', 'component-model'),
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
                    $this->__('There is no MutationResolver for input field with name \'%s\'', 'component-model'),
                    $inputFieldName
                )
            );
        }
        return $inputFieldMutationResolver;
    }

    /**
     * @todo Review this commenting works for different oneof mutations
     * eg: http://graphql-by-pop-pro.lndo.site/graphiql/?query=mutation%20LoginUser%20%7B%0A%20%20loginUser(by%3A%20%7Bcredentials%3A%20%7BusernameOrEmail%3A%20%22admin%22%2C%20password%3A%20%22admin%22%7D%7D)%20%7B%0A%20%20%20%20id%0A%20%20%20%20name%0A%20%20%7D%0A%7D&operationName=LoginUser&variables=%7B%0A%20%20%22authorID%22%3A%203%0A%7D
     */
    // /**
    //  * @return array<string,mixed>|stdClass
    //  * @throws Exception If the form data for the input field is not present in the array
    //  */
    // protected function getInputFieldFormData(string $inputFieldName, stdClass $oneofInputObjectFormData): array|stdClass
    // {
    //     $inputFieldFormData = $oneofInputObjectFormData->$inputFieldName ?? null;
    //     if ($inputFieldFormData === null) {
    //         throw new Exception(
    //             sprintf(
    //                 $this->__('There is not form data for input field with name \'%s\'', 'component-model'),
    //                 $inputFieldName
    //             )
    //         );
    //     }
    //     return $inputFieldFormData;
    // }

    /**
     * Assume there's only one argument in the field,
     * for this OneofMutationResolver.
     * If that's not the case, this function must be overriden,
     * to avoid throwing an Exception
     *
     * @return string The current input field's name
     * @throws Exception If more than 1 argument is passed to the field executing the OneofMutation
     */
    protected function getOneofInputObjectFieldName(array $formData): string
    {
        $formDataSize = count($formData);
        if ($formDataSize !== 1) {
            throw new Exception(
                sprintf(
                    $this->__('The OneofMutationResolver expects only 1 argument is passed to the field executing the mutation, but %s were provided: \'%s\'', 'component-model'),
                    $formDataSize,
                    implode('\'%s\'', array_keys($formData))
                )
            );
        }
        return key($formData);
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
        $inputFieldName = $this->getOneofInputObjectFieldName($formData);
        /** @var stdClass */
        $oneofInputObjectFormData = $formData[$inputFieldName];
        $inputFieldMutationResolver = $this->getInputFieldMutationResolver($inputFieldName);
        /**
         * @todo Review this commenting works for different oneof mutations
         * eg: http://graphql-by-pop-pro.lndo.site/graphiql/?query=mutation%20LoginUser%20%7B%0A%20%20loginUser(by%3A%20%7Bcredentials%3A%20%7BusernameOrEmail%3A%20%22admin%22%2C%20password%3A%20%22admin%22%7D%7D)%20%7B%0A%20%20%20%20id%0A%20%20%20%20name%0A%20%20%7D%0A%7D&operationName=LoginUser&variables=%7B%0A%20%20%22authorID%22%3A%203%0A%7D
         */
        // $inputFieldFormData = $this->getInputFieldFormData($inputFieldName, $oneofInputObjectFormData);
        // return [$inputFieldMutationResolver, $inputFieldFormData];
        return [$inputFieldMutationResolver, $oneofInputObjectFormData];
    }
}
