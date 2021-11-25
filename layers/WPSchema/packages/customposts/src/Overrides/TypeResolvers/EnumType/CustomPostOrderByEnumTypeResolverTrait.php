<?php

declare(strict_types=1);

namespace PoPWPSchema\CustomPosts\Overrides\TypeResolvers\EnumType;

use PoPWPSchema\CustomPosts\Constants\CustomPostOrderBy;

/**
 * The "order by" parameters are defined here:
 *
 * @see https://developer.wordpress.org/reference/classes/wp_query/#order-orderby-parameters
 */
trait CustomPostOrderByEnumTypeResolverTrait
{
    /**
     * @return string[]
     */
    public function getAdditionalCustomPostEnumValues(): array
    {
        return [
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
        ];
    }

    public function getAdditionalCustomPostEnumValueDescription(string $enumValue): ?string
    {
        return match ($enumValue) {
            CustomPostOrderBy::NONE => $this->getTranslationAPI()->__('Skip ordering', 'customposts'),
            CustomPostOrderBy::COMMENT_COUNT => $this->getTranslationAPI()->__('Order by number of comments', 'customposts'),
            CustomPostOrderBy::RANDOM => $this->getTranslationAPI()->__('Order by a random number', 'customposts'),
            CustomPostOrderBy::MODIFIED_DATE => $this->getTranslationAPI()->__('Order by last modified date', 'customposts'),
            CustomPostOrderBy::RELEVANCE => $this->getTranslationAPI()->__('Order by relevance', 'customposts'),
            CustomPostOrderBy::TYPE => $this->getTranslationAPI()->__('Order by type', 'customposts'),
            CustomPostOrderBy::PARENT => $this->getTranslationAPI()->__('Order by custom post parent id', 'customposts'),
            CustomPostOrderBy::MENU_ORDER => $this->getTranslationAPI()->__('Order by menu order', 'customposts'),
            // CustomPostOrderBy::POST__IN => $this->getTranslationAPI()->__('Preserve post ID order given in the post__in array', 'customposts'),
            // CustomPostOrderBy::POST_PARENT__IN => $this->getTranslationAPI()->__('Preserve post parent order given in the ‘post_parent__in’ array', 'customposts'),
            default => null,
        };
    }
}
