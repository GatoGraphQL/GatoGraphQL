<?php

declare(strict_types=1);

namespace PoPWPSchema\CustomPosts\Overrides\TypeResolvers\EnumType;

use PoPSchema\CustomPosts\TypeResolvers\EnumType\CustomPostOrderByEnumTypeResolver as UpstreamCustomPostOrderByEnumTypeResolver;
use PoPWPSchema\CustomPosts\Constants\CustomPostOrderBy;

/**
 * The "order by" parameters are defined here:
 *
 * @see https://developer.wordpress.org/reference/classes/wp_query/#order-orderby-parameters
 */
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
                CustomPostOrderBy::NONE,
                CustomPostOrderBy::COMMENT_COUNT,
                CustomPostOrderBy::RANDOM,
                CustomPostOrderBy::MODIFIED_DATE,
                CustomPostOrderBy::RELEVANCE,
                CustomPostOrderBy::TYPE,
                CustomPostOrderBy::PARENT,
                CustomPostOrderBy::MENU_ORDER,
                // CustomPostOrderBy::POST__IN,
                // CustomPostOrderBy::POST_PARENT__IN,
            ]
        );
    }

    public function getEnumValueDescription(string $enumValue): ?string
    {
        return match ($enumValue) {
            CustomPostOrderBy::NONE => $this->getTranslationAPI()->__('No order', 'schema-commons'),
            CustomPostOrderBy::COMMENT_COUNT => $this->getTranslationAPI()->__('Order by number of comments', 'schema-commons'),
            CustomPostOrderBy::RANDOM => $this->getTranslationAPI()->__('Order by a random number', 'schema-commons'),
            CustomPostOrderBy::MODIFIED_DATE => $this->getTranslationAPI()->__('Order by last modified date', 'schema-commons'),
            CustomPostOrderBy::RELEVANCE => $this->getTranslationAPI()->__('Order by relevance', 'schema-commons'),
            CustomPostOrderBy::TYPE => $this->getTranslationAPI()->__('Order by type', 'schema-commons'),
            CustomPostOrderBy::PARENT => $this->getTranslationAPI()->__('Order by post/page parent id', 'schema-commons'),
            CustomPostOrderBy::MENU_ORDER => $this->getTranslationAPI()->__('Order by menu order', 'schema-commons'),
            // CustomPostOrderBy::POST__IN => $this->getTranslationAPI()->__('Preserve post ID order given in the post__in array', 'schema-commons'),
            // CustomPostOrderBy::POST_PARENT__IN => $this->getTranslationAPI()->__('Preserve post parent order given in the ‘post_parent__in’ array', 'schema-commons'),
            default => parent::getEnumValueDescription($enumValue),
        };
    }
}
