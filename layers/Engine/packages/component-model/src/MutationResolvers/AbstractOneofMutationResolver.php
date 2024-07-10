<?php

declare(strict_types=1);

namespace PoP\ComponentModel\MutationResolvers;

use PoP\ComponentModel\Exception\QueryResolutionException;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedback;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\FeedbackItemProviders\MutationErrorFeedbackItemProvider;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\QueryResolution\InputObjectSubpropertyFieldDataAccessor;
use PoP\ComponentModel\QueryResolution\InputObjectSubpropertyFieldDataAccessorInterface;
use PoP\GraphQLParser\Exception\AbstractValueResolutionPromiseException;
use PoP\Root\App;
use PoP\Root\Exception\AbstractException;
use PoP\ComponentModel\Feedback\FeedbackItemResolution;
use stdClass;

abstract class AbstractOneofMutationResolver extends AbstractMutationResolver
{
    /** @var array<string,MutationResolverInterface>|null */
    private ?array $consolidatedInputFieldNameMutationResolversCache = null;

    /**
     * The MutationResolvers contained in the OneofMutationResolver,
     * organized by inputFieldName
     *
     * @return array<string,MutationResolverInterface> Array of inputFieldName => MutationResolver
     */
    abstract protected function getInputFieldNameMutationResolvers(): array;

    /**
     * Consolidation of the mutation resolver for each input field. Call this function to read the data
     * instead of the individual functions, since it applies hooks to override/extend.
     * @return array<string,MutationResolverInterface>
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
     * @throws QueryResolutionException If either there is none or more than 1 inputFieldNames being used
     */
    protected function getCurrentInputFieldName(stdClass $oneofInputObjectFormData): string
    {
        $oneofInputObjectFormDataSize = count((array)$oneofInputObjectFormData);
        if ($oneofInputObjectFormDataSize !== 1) {
            throw new QueryResolutionException(
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
     * @throws QueryResolutionException If there is not MutationResolver for the input field
     */
    protected function getInputFieldMutationResolver(string $inputFieldName): MutationResolverInterface
    {
        $inputFieldMutationResolver = $this->getConsolidatedInputFieldNameMutationResolvers()[$inputFieldName] ?? null;
        if ($inputFieldMutationResolver === null) {
            throw new QueryResolutionException(
                sprintf(
                    $this->__('There is no MutationResolver for input field with name \'%s\'', 'component-model'),
                    $inputFieldName
                )
            );
        }
        return $inputFieldMutationResolver;
    }

    /**
     * Assume there's only one argument in the field,
     * for this OneofMutationResolver.
     * If that's not the case, this function must be overridden,
     * to avoid throwing an Exception
     *
     * @throws QueryResolutionException If more than 1 argument is passed to the field executing the OneofMutation
     */
    protected function getOneofInputObjectPropertyName(FieldDataAccessorInterface $fieldDataAccessor): string
    {
        $propertyNames = $fieldDataAccessor->getProperties();
        $formDataSize = count($propertyNames);
        if ($formDataSize !== 1) {
            throw new QueryResolutionException(
                sprintf(
                    $this->__('The OneofMutationResolver expects only 1 argument is passed to the field executing the mutation, but %s were provided: \'%s\'', 'component-model'),
                    $formDataSize,
                    implode(
                        $this->__(', ', 'component-model'),
                        $propertyNames
                    )
                )
            );
        }
        return $propertyNames[0];
    }

    /**
     * @param InputObjectSubpropertyFieldDataAccessorInterface $fieldDataAccessor
     * @throws AbstractException In case of error
     */
    public function executeMutation(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): mixed {
        [$inputFieldMutationResolver, $fieldDataAccessor] = $this->getInputFieldMutationResolverAndOneOfFieldDataAccessor($fieldDataAccessor);
        /** @var MutationResolverInterface $inputFieldMutationResolver */
        return $inputFieldMutationResolver->executeMutation(
            $fieldDataAccessor,
            $objectTypeFieldResolutionFeedbackStore,
        );
    }

    /**
     * @param InputObjectSubpropertyFieldDataAccessorInterface $fieldDataAccessor
     * @throws AbstractValueResolutionPromiseException
     */
    public function validate(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        try {
            [$inputFieldMutationResolver, $fieldDataAccessor] = $this->getInputFieldMutationResolverAndOneOfFieldDataAccessor($fieldDataAccessor);
            /** @var MutationResolverInterface $inputFieldMutationResolver */
            $inputFieldMutationResolver->validate($fieldDataAccessor, $objectTypeFieldResolutionFeedbackStore);
        } catch (QueryResolutionException $e) {
            // Return the error message from the exception
            $objectTypeFieldResolutionFeedbackStore->addError(
                new ObjectTypeFieldResolutionFeedback(
                    new FeedbackItemResolution(
                        MutationErrorFeedbackItemProvider::class,
                        MutationErrorFeedbackItemProvider::E1,
                        [
                            $e->getMessage(),
                        ]
                    ),
                    $fieldDataAccessor->getField(),
                )
            );
        }
    }

    /**
     * @return mixed[] An array of 2 items: the current input field's mutation resolver, and the AST with the current input field's form data
     * @throws QueryResolutionException If there is not MutationResolver for the input field
     * @throws AbstractValueResolutionPromiseException
     */
    final protected function getInputFieldMutationResolverAndOneOfFieldDataAccessor(InputObjectSubpropertyFieldDataAccessorInterface $inputObjectFieldArgumentFieldDataAccessor): array
    {
        // Create a new Field, passing the corresponding Argument only
        $oneOfPropertyName = $this->getOneofInputObjectPropertyName($inputObjectFieldArgumentFieldDataAccessor);
        $inputFieldMutationResolver = $this->getInputFieldMutationResolver($oneOfPropertyName);
        $oneOfFieldDataAccessor = $this->getOneOfFieldDataAccessor(
            $inputObjectFieldArgumentFieldDataAccessor,
            $oneOfPropertyName
        );
        return [$inputFieldMutationResolver, $oneOfFieldDataAccessor];
    }

    /**
     * @throws AbstractValueResolutionPromiseException
     */
    final protected function getOneOfFieldDataAccessor(
        InputObjectSubpropertyFieldDataAccessorInterface $inputObjectFieldArgumentFieldDataAccessor,
        string $oneOfPropertyName,
    ): InputObjectSubpropertyFieldDataAccessorInterface {
        return new InputObjectSubpropertyFieldDataAccessor(
            $inputObjectFieldArgumentFieldDataAccessor->getField(),
            $oneOfPropertyName,
            $inputObjectFieldArgumentFieldDataAccessor->getFieldArgs()
        );
    }
}
