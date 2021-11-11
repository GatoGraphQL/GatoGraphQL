<?php

declare(strict_types=1);

namespace PoPWPSchema\Posts\Overrides\TypeResolvers\EnumType;

use PoPSchema\CustomPosts\TypeResolvers\EnumType\CustomPostOrderByEnumTypeResolver as UpstreamCustomPostOrderByEnumTypeResolver;
use PoPWPSchema\Posts\Constants\PostOrderBy;

class PostOrderByEnumTypeResolver extends UpstreamCustomPostOrderByEnumTypeResolver
{
    /**
     * @return string[]
     */
    public function getEnumValues(): array
    {
        return array_merge(
            parent::getEnumValues(),
            [
                PostOrderBy::COMMENT_COUNT,
                PostOrderBy::RANDOM,
                PostOrderBy::MODIFIED_DATE,
                PostOrderBy::RELEVANCE,
            ]
        );
    }

    public function getEnumValueDescription(string $enumValue): ?string
    {
        return match ($enumValue) {
            PostOrderBy::COMMENT_COUNT => $this->getTranslationAPI()->__('Order by number of comments', 'schema-commons'),
            PostOrderBy::RANDOM => $this->getTranslationAPI()->__('Order by a random number', 'schema-commons'),
            PostOrderBy::MODIFIED_DATE => $this->getTranslationAPI()->__('Order by last modified date', 'schema-commons'),
            PostOrderBy::RELEVANCE => $this->getTranslationAPI()->__('Order by relevance', 'schema-commons'),
            default => parent::getEnumValueDescription($enumValue),
        };
    }
}
