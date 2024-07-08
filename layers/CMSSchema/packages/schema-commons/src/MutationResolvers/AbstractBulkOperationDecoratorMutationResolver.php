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
    /** @param InputObjectListItemSubpropertyFieldDataAccessor[] */
    protected ?array $inputObjectListItemSubpropertyFieldDataAccessor = null;

    /**
     * @return InputObjectListItemSubpropertyFieldDataAccessor[]
     */
    protected function getInputObjectListItemSubpropertyFieldDataAccessors(
        FieldDataAccessorInterface $fieldDataAccessor,
    ): array {
        if ($this->inputObjectListItemSubpropertyFieldDataAccessor === null) {
            $this->inputObjectListItemSubpropertyFieldDataAccessor = [];
            /** @var mixed[] */
            $inputs = $fieldDataAccessor->getValue(MutationInputProperties::INPUTS);
            foreach ($inputs as $position => $input) {
                $this->inputObjectListItemSubpropertyFieldDataAccessor[] = new InputObjectListItemSubpropertyFieldDataAccessor(
                    $fieldDataAccessor->getField(),
                    MutationInputProperties::INPUTS,
                    $position,
                    $fieldDataAccessor->getFieldArgs(),
                );
            }
        }
        return $this->inputObjectListItemSubpropertyFieldDataAccessor;
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
            $results[] = $decoratedOperationMutationResolver->executeMutation(
                $inputObjectListItemSubpropertyFieldDataAccessors[$position],
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
            $decoratedOperationMutationResolver->validate(
                $inputObjectListItemSubpropertyFieldDataAccessors[$position],
                $objectTypeFieldResolutionFeedbackStore
            );
        }
    }
}
