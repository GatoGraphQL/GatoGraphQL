<?php

declare(strict_types=1);

namespace PoPCMSSchema\SchemaCommons\MutationResolvers;

use PoPCMSSchema\SchemaCommons\Constants\MutationInputProperties;
use PoPSchema\SchemaCommons\Enums\OperationStatusEnum;
use PoPSchema\SchemaCommons\ObjectModels\ObjectMutationPayload;
use PoP\ComponentModel\Dictionaries\ObjectDictionaryInterface;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\MutationResolvers\AbstractMutationResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\QueryResolution\InputObjectListItemSubpropertyFieldDataAccessor;
use PoP\Root\Exception\AbstractException;

abstract class AbstractBulkOperationDecoratorMutationResolver extends AbstractMutationResolver
{
    private ?ObjectDictionaryInterface $objectDictionary = null;

    final public function setObjectDictionary(ObjectDictionaryInterface $objectDictionary): void
    {
        $this->objectDictionary = $objectDictionary;
    }
    final protected function getObjectDictionary(): ObjectDictionaryInterface
    {
        if ($this->objectDictionary === null) {
            /** @var ObjectDictionaryInterface */
            $objectDictionary = $this->instanceManager->getInstance(ObjectDictionaryInterface::class);
            $this->objectDictionary = $objectDictionary;
        }
        return $this->objectDictionary;
    }

    /**
     * @return InputObjectListItemSubpropertyFieldDataAccessor[]
     */
    protected function getInputObjectListItemSubpropertyFieldDataAccessors(
        FieldDataAccessorInterface $fieldDataAccessor,
    ): array {
        $inputObjectListItemSubpropertyFieldDataAccessors = [];
        /** @var mixed[] */
        $inputs = $fieldDataAccessor->getValue(MutationInputProperties::INPUTS);
        foreach ($inputs as $position => $input) {
            $inputObjectListItemSubpropertyFieldDataAccessors[] = new InputObjectListItemSubpropertyFieldDataAccessor(
                $fieldDataAccessor->getField(),
                MutationInputProperties::INPUTS,
                $position,
                $fieldDataAccessor->getFieldArgs(),
            );
        }
        return $inputObjectListItemSubpropertyFieldDataAccessors;
    }
    
    /**
     * @return string
     * @throws AbstractException In case of error
     */
    public function executeMutation(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): mixed {
        $decoratedOperationMutationResolver = $this->getDecoratedOperationMutationResolver();
        $inputObjectListItemSubpropertyFieldDataAccessors = $this->getInputObjectListItemSubpropertyFieldDataAccessors($fieldDataAccessor);
        $results = [];
        $objectDictionary = $this->getObjectDictionary();
        /** @var mixed[] */
        $inputs = $fieldDataAccessor->getValue(MutationInputProperties::INPUTS);
        /** @var bool */
        $stopExecutingMutationItemsOnFirstError = $fieldDataAccessor->getValue(MutationInputProperties::STOP_EXECUTING_MUTATION_ITEMS_ON_FIRST_ERROR);
        foreach ($inputs as $position => $input) {
            /** @var InputObjectListItemSubpropertyFieldDataAccessor */
            $inputObjectListItemSubpropertyFieldDataAccessor = $inputObjectListItemSubpropertyFieldDataAccessors[$position];
            $errorCount = $objectTypeFieldResolutionFeedbackStore->getErrorCount();
            $result = $decoratedOperationMutationResolver->executeMutation(
                $inputObjectListItemSubpropertyFieldDataAccessor,
                $objectTypeFieldResolutionFeedbackStore
            );
            $results[] = $result;
            if ($stopExecutingMutationItemsOnFirstError) {
                // Non-payloadable produced error => exit
                if ($objectTypeFieldResolutionFeedbackStore->getErrorCount() > $errorCount) {
                    break;
                }

                /**
                 * Check if a Payloadable Mutation Resolver produced error...
                 *
                 * Steps:
                 *
                 * - Get the ID of the result
                 * - If that ID belongs to ObjectMutationPayload, then it's payloadable
                 * - Check if that object has failure status
                 *
                 * ObjectMutationPayload is a TransientObject, its ID is an integer.
                 * 
                 * @see layers/Engine/packages/component-model/src/ObjectModels/AbstractTransientObject.php
                 */
                if (!is_integer($result)) {
                    continue;
                }
                /** @var int */
                $resultID = $result;
                if (!$objectDictionary->has(ObjectMutationPayload::class, $resultID)) {
                    continue;
                }
                /** @var ObjectMutationPayload */
                $objectMutationPayload = $objectDictionary->get(ObjectMutationPayload::class, $resultID);
                if ($objectMutationPayload->status === OperationStatusEnum::FAILURE) {
                    break;
                }
            }
        }
        return $results;
    }

    abstract protected function getDecoratedOperationMutationResolver(): MutationResolverInterface;

    public function validate(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): void {
        $decoratedOperationMutationResolver = $this->getDecoratedOperationMutationResolver();
        $inputObjectListItemSubpropertyFieldDataAccessors = $this->getInputObjectListItemSubpropertyFieldDataAccessors($fieldDataAccessor);
        /** @var mixed[] */
        $inputs = $fieldDataAccessor->getValue(MutationInputProperties::INPUTS);
        foreach ($inputs as $position => $input) {
            /** @var InputObjectListItemSubpropertyFieldDataAccessor */
            $inputObjectListItemSubpropertyFieldDataAccessor = $inputObjectListItemSubpropertyFieldDataAccessors[$position];
            $decoratedOperationMutationResolver->validate(
                $inputObjectListItemSubpropertyFieldDataAccessor,
                $objectTypeFieldResolutionFeedbackStore
            );
        }
    }
}
