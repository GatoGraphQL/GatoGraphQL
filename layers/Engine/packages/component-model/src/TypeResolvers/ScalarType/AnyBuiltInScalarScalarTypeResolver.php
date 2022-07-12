<?php

declare(strict_types=1);

namespace PoP\ComponentModel\TypeResolvers\ScalarType;

use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\GraphQLParser\Spec\Parser\Ast\AstInterface;
use stdClass;

class AnyBuiltInScalarScalarTypeResolver extends AbstractScalarTypeResolver
{
    public function getTypeName(): string
    {
        return 'AnyBuiltInScalar';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('Wildcard type representing any of GraphQL\'s built-in types (String, Int, Boolean, Float or ID)', 'engine');
    }

    /**
     * Accept anything and everything, other than arrays and objects
     */
    public function coerceValue(
        string|int|float|bool|stdClass $inputValue,
        AstInterface $astNode,
        ObjectTypeFieldResolutionFeedbackStore $objectTypeFieldResolutionFeedbackStore,
    ): string|int|float|bool|object|null {
        $separateObjectTypeFieldResolutionFeedbackStore = new ObjectTypeFieldResolutionFeedbackStore();
        $this->validateIsNotStdClass($inputValue, $astNode, $separateObjectTypeFieldResolutionFeedbackStore);
        $objectTypeFieldResolutionFeedbackStore->incorporate($separateObjectTypeFieldResolutionFeedbackStore);
        if ($separateObjectTypeFieldResolutionFeedbackStore->getErrors() !== []) {
            return null;
        }
        return $inputValue;
    }
}
