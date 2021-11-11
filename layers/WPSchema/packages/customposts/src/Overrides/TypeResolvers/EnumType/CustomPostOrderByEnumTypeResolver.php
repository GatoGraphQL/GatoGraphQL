<?php

declare(strict_types=1);

namespace PoPWPSchema\CustomPosts\Overrides\TypeResolvers\EnumType;

use PoPSchema\CustomPosts\TypeResolvers\EnumType\CustomPostOrderByEnumTypeResolver as UpstreamCustomPostOrderByEnumTypeResolver;
use PoPWPSchema\Posts\Constants\CustomPostOrderBy;

class CustomPostOrderByEnumTypeResolver extends UpstreamCustomPostOrderByEnumTypeResolver
{
    /**
     * @return string[]
     */
    public function getEnumValues(): array
    {
        return array_merge(
            parent::getEnumValues(),
            [
                CustomPostOrderBy::COMMENT_COUNT,
                CustomPostOrderBy::RANDOM,
                CustomPostOrderBy::MODIFIED_DATE,
                CustomPostOrderBy::RELEVANCE,
            ]
        );
    }

    public function getEnumValueDescription(string $enumValue): ?string
    {
        return match ($enumValue) {
            CustomPostOrderBy::COMMENT_COUNT => $this->getTranslationAPI()->__('Order by number of comments', 'schema-commons'),
            CustomPostOrderBy::RANDOM => $this->getTranslationAPI()->__('Order by a random number', 'schema-commons'),
            CustomPostOrderBy::MODIFIED_DATE => $this->getTranslationAPI()->__('Order by last modified date', 'schema-commons'),
            CustomPostOrderBy::RELEVANCE => $this->getTranslationAPI()->__('Order by relevance', 'schema-commons'),
            default => parent::getEnumValueDescription($enumValue),
        };
    }
}
