<?php

declare(strict_types=1);

namespace PoPCMSSchema\SchemaCommons\MutationResolvers;

use PoPCMSSchema\SchemaCommons\Constants\MutationInputProperties;
use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\ComponentModel\MutationResolvers\AbstractMutationResolver;
use PoP\ComponentModel\MutationResolvers\MutationResolverInterface;
use PoP\ComponentModel\QueryResolution\FieldDataAccessorInterface;
use PoP\ComponentModel\QueryResolution\InputObjectListItemSubpropertyFieldDataAccessor;
use PoP\Root\Exception\AbstractException;

abstract class AbstractBulkOperationDecoratorMutationResolver extends AbstractMutationResolver
{
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
        /** @var mixed[] */
        $inputs = $fieldDataAccessor->getValue(MutationInputProperties::INPUTS);
        foreach ($inputs as $position => $input) {
            /** @var InputObjectListItemSubpropertyFieldDataAccessor */
            $inputObjectListItemSubpropertyFieldDataAccessor = $inputObjectListItemSubpropertyFieldDataAccessors[$position];
            $results[] = $decoratedOperationMutationResolver->executeMutation(
                $inputObjectListItemSubpropertyFieldDataAccessor,
                $objectTypeFieldResolutionFeedbackStore
            );
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
