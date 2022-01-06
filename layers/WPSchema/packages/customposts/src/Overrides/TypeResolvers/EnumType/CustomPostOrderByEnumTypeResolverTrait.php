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
            CustomPostOrderBy::NONE => $this->__('Skip ordering', 'customposts'),
            CustomPostOrderBy::COMMENT_COUNT => $this->__('Order by number of comments', 'customposts'),
            CustomPostOrderBy::RANDOM => $this->__('Order by a random number', 'customposts'),
            CustomPostOrderBy::MODIFIED_DATE => $this->__('Order by last modified date', 'customposts'),
            CustomPostOrderBy::RELEVANCE => $this->__('Order by relevance', 'customposts'),
            CustomPostOrderBy::TYPE => $this->__('Order by type', 'customposts'),
            CustomPostOrderBy::PARENT => $this->__('Order by custom post parent id', 'customposts'),
            CustomPostOrderBy::MENU_ORDER => $this->__('Order by menu order', 'customposts'),
            // CustomPostOrderBy::POST__IN => $this->__('Preserve post ID order given in the post__in array', 'customposts'),
            // CustomPostOrderBy::POST_PARENT__IN => $this->__('Preserve post parent order given in the ‘post_parent__in’ array', 'customposts'),
            default => null,
        };
    }
}
