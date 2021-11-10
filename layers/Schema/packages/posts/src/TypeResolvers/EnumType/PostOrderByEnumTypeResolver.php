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
            PostOrderBy::ID,
            PostOrderBy::TITLE,
            PostOrderBy::DATE,
            PostOrderBy::COMMENT_COUNT,
            PostOrderBy::RAND,
            PostOrderBy::MODIFIED_DATE,
            PostOrderBy::RELEVANCE,
        ];
    }

    public function getEnumValueDescription(string $enumValue): ?string
    {
        return match ($enumValue) {
            PostOrderBy::ID => $this->getTranslationAPI()->__('Order by id', 'schema-commons'),
            PostOrderBy::TITLE => $this->getTranslationAPI()->__('Order by title', 'schema-commons'),
            PostOrderBy::DATE => $this->getTranslationAPI()->__('Order by date', 'schema-commons'),
            PostOrderBy::COMMENT_COUNT => $this->getTranslationAPI()->__('Order by number of comments', 'schema-commons'),
            PostOrderBy::RAND => $this->getTranslationAPI()->__('Order by a random number', 'schema-commons'),
            PostOrderBy::MODIFIED_DATE => $this->getTranslationAPI()->__('Order by last modified date', 'schema-commons'),
            PostOrderBy::RELEVANCE => $this->getTranslationAPI()->__('Order by relevance', 'schema-commons'),
            default => parent::getEnumValueDescription($enumValue),
        };
    }
}
