<?php

declare(strict_types=1);

namespace PoPWPSchema\Blocks\TypeResolvers\EnumType;

use PoPWPSchema\Blocks\Enums\BlockTypeAttributeFieldType;
use PoP\ComponentModel\TypeResolvers\EnumType\AbstractEnumTypeResolver;

class BlockTypeAttributeFieldTypeEnumTypeResolver extends AbstractEnumTypeResolver
{
    public function getTypeName(): string
    {
        return 'BlockTypeAttributeFieldTypeEnum';
    }

    public function getTypeDescription(): ?string
    {
        return $this->__('JSON-Schema "type" of a block attribute', 'blocks');
    }

    /**
     * @return string[]
     */
    public function getEnumValues(): array
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
