<?php

declare(strict_types=1);

namespace PoPCMSSchema\Comments\TypeResolvers\EnumType;

use PoP\ComponentModel\TypeResolvers\EnumType\AbstractEnumTypeResolver;
use PoPCMSSchema\Comments\Constants\CommentTypes;

class CommentTypeEnumTypeResolver extends AbstractEnumTypeResolver
{
    public function getTypeName(): string
    {
        return 'CommentTypeEnum';
    }

    /**
     * @return string[]
     */
    public function getEnumValues(): array
    {
        return [
            CommentTypes::COMMENT,
            CommentTypes::TRACKBACK,
            CommentTypes::PINGBACK,
        ];
    }
}
