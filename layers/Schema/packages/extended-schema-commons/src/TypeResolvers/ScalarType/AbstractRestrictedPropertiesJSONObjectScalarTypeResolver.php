<?php

declare(strict_types=1);

namespace PoPSchema\ExtendedSchemaCommons\TypeResolvers\ScalarType;

use PoP\ComponentModel\Feedback\ObjectTypeFieldResolutionFeedbackStore;
use PoP\Engine\TypeResolvers\ScalarType\JSONObjectScalarTypeResolver;
use PoP\GraphQLParser\Spec\Parser\Ast\AstInterface;
use stdClass;

abstract class AbstractRestrictedPropertiesJSONObjectScalarTypeResolver extends JSONObjectScalarTypeResolver
{
    public function getTypeDescription(): ?string
    {
        return sprintf(
            $this->__('Custom scalar representing a JSON Object with restricted properties: \'%s\'', 'extended-schema-commons'),
            implode(
                $this->__('\', \'', 'extended-schema-commons'),
                $this->getRestrictedProperties()
            )
        );
    }

    /**
     * @return string[]
     */
    abstract protected function getRestrictedProperties(): array;

    public function getSpecifiedByURL(): ?string
    {
        return null;
    }
}
