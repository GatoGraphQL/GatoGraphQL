<?php

declare(strict_types=1);

namespace PoPSchema\Posts\TypeResolvers\EnumType;

use PoP\ComponentModel\TypeResolvers\EnumType\AbstractEnumTypeResolver;
use PoPSchema\Posts\Constants\PostOrderBy;

class PostOrderByEnumTypeResolver extends AbstractEnumTypeResolver
{
    public function getTypeName(): string
    {
        return 'PostOrderBy';
    }

    /**
     * @return string[]
     */
    public function getEnumValues(): array
    {
        return [
            PostOrderBy::TITLE,
            PostOrderBy::DATE,
            PostOrderBy::NUMBER_COMMENTS,
        ];
    }

    public function getEnumValueDescription(string $enumValue): ?string
    {
        return match ($enumValue) {
            PostOrderBy::TITLE => $this->getTranslationAPI()->__('Order by title', 'schema-commons'),
            PostOrderBy::DATE => $this->getTranslationAPI()->__('Order by date', 'schema-commons'),
            PostOrderBy::NUMBER_COMMENTS => $this->getTranslationAPI()->__('Order by number of comments', 'schema-commons'),
            default => parent::getEnumValueDescription($enumValue),
        };
    }
}
