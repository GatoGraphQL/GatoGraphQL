<?php

declare(strict_types=1);

namespace PoPCMSSchema\MediaMutations\TypeResolvers\ScalarType;

use PoPSchema\SchemaCommons\TypeResolvers\ScalarType\AbstractEnumStringScalarTypeResolver;

class AllowedMimeTypeEnumStringScalarTypeResolver extends AbstractEnumStringScalarTypeResolver
{
    public function getTypeName(): string
    {
        return 'AllowedMimeTypeEnumString';
    }

    public function getTypeDescription(): string
    {
        return sprintf(
            $this->__('Allowed mime types for uploading files, with possible values: `"%s"`.', 'menus'),
            implode('"`, `"', $this->getConsolidatedPossibleValues())
        );
    }

    /**
     * @return string[]
     */
    public function getPossibleValues(): array
    {
        return array_values(\get_allowed_mime_types());
    }
}
