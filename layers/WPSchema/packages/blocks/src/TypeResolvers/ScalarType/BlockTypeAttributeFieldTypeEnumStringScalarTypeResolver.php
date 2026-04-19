<?php

declare(strict_types=1);

namespace PoPWPSchema\Blocks\TypeResolvers\ScalarType;

use PoPSchema\SchemaCommons\TypeResolvers\ScalarType\AbstractEnumStringScalarTypeResolver;
use PoPWPSchema\Blocks\Enums\BlockTypeAttributeFieldType;

class BlockTypeAttributeFieldTypeEnumStringScalarTypeResolver extends AbstractEnumStringScalarTypeResolver
{
    public function getTypeName(): string
    {
        return 'BlockTypeAttributeFieldTypeEnumString';
    }

    public function getEnumStringTypeDescription(): ?string
    {
        return $this->__('JSON-Schema "type" of a block attribute. Includes `null`, so it can\'t be a GraphQL Enum (which disallows that name).', 'blocks');
    }

    /**
     * @return string[]
     */
    public function getPossibleValues(): array
    {
        return [
            BlockTypeAttributeFieldType::STRING,
            BlockTypeAttributeFieldType::INTEGER,
            BlockTypeAttributeFieldType::NUMBER,
            BlockTypeAttributeFieldType::BOOLEAN,
            BlockTypeAttributeFieldType::ARRAY,
            BlockTypeAttributeFieldType::OBJECT,
            BlockTypeAttributeFieldType::NULL,
        ];
    }
}
