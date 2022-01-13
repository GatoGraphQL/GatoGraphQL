<?php

declare(strict_types=1);

namespace PoPWPSchema\CustomPosts\Hooks;

use PoP\Root\Hooks\AbstractHookSet;
use PoPSchema\CustomPostsWP\TypeAPIs\AbstractCustomPostTypeAPI;
use PoPWPSchema\CustomPosts\Constants\CustomPostOrderBy;

class CustomPostOrderByQueryHookSet extends AbstractHookSet
{
    protected function init(): void
    {
        $this->getHooksAPI()->addFilter(
            AbstractCustomPostTypeAPI::HOOK_ORDERBY_QUERY_ARG_VALUE,
            [$this, 'getOrderByQueryArgValue']
        );
    }

    public function getOrderByQueryArgValue(string $orderBy): string
    {
        return match ($orderBy) {
            CustomPostOrderBy::NONE => 'none',
            CustomPostOrderBy::COMMENT_COUNT => 'comment_count',
            CustomPostOrderBy::RANDOM => 'rand',
            CustomPostOrderBy::MODIFIED_DATE => 'modified',
            CustomPostOrderBy::RELEVANCE => 'relevance',
            CustomPostOrderBy::TYPE => 'type',
            CustomPostOrderBy::PARENT => 'parent',
            CustomPostOrderBy::MENU_ORDER => 'menu_order',
            // CustomPostOrderBy::POST__IN => 'post__in',
            // CustomPostOrderBy::POST_PARENT__IN => 'post_parent__in',
            default => $orderBy,
        };
    }
}
