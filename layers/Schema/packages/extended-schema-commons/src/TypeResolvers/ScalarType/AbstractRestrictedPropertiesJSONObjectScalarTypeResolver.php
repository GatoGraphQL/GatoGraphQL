<?php

declare(strict_types=1);

namespace PoPSchema\ExtendedSchemaCommons\TypeResolvers\ScalarType;

use PoP\Engine\TypeResolvers\ScalarType\JSONObjectScalarTypeResolver;

abstract class AbstractRestrictedPropertiesJSONObjectScalarTypeResolver extends JSONObjectScalarTypeResolver
{
    public function getTypeDescription(): ?string
    {
        return sprintf(
            $this->__('Custom scalar representing a JSON Object with restricted properties: \'%s\'', 'extended-schema-commons'),
            implode(
                $this->__('\', \'', 'extended-schema-commons'),
                []
            )
        );
    }

    public function getSpecifiedByURL(): ?string
    {
        return null;
    }
}
