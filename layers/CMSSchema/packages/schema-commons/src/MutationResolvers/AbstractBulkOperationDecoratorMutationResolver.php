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
     * @return string
     * @throws AbstractException In case of error
     */
    public function executeMutation(
        FieldDataAccessorInterface $fieldDataAccessor,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): mixed {
        $decoratedOperationMutationResolver = $this->getDecoratedOperationMutationResolver();
        $results = [];
        /** @var mixed[] */
        $inputs = $fieldDataAccessor->getValue(MutationInputProperties::INPUTS);
        foreach ($inputs as $position => $input) {
            $results[] = $decoratedOperationMutationResolver->executeMutation(
                new InputObjectListItemSubpropertyFieldDataAccessor(
                    $fieldDataAccessor->getField(),
                    MutationInputProperties::INPUTS,
                    $position,
                    $fieldDataAccessor->getFieldArgs(),
                ),
                $objectTypeFieldResolutionFeedbackStore
            );
        }
        return $results;
    }

    abstract protected function getDecoratedOperationMutationResolver(): MutationResolverInterface;
}
