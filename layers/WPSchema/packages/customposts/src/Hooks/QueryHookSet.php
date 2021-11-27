<?php

declare(strict_types=1);

namespace PoPWPSchema\CustomPosts\Hooks;

use PoP\Hooks\AbstractHookSet;
use PoPSchema\CustomPostsWP\TypeAPIs\AbstractCustomPostTypeAPI;
use PoPWPSchema\CustomPosts\Constants\CustomPostOrderBy;

class QueryHookSet extends AbstractHookSet
{
    protected function init(): void
    {
        $this->getHooksAPI()->addFilter(
            AbstractCustomPostTypeAPI::HOOK_ORDERBY_QUERY_ARG_VALUE,
            [$this, 'getOrderByQueryArgValue']
        );
        $this->getHooksAPI()->addFilter(
            AbstractCustomPostTypeAPI::HOOK_QUERY,
            [$this, 'convertCustomPostsQuery'],
            10,
            2
        );
    }

    public function convertCustomPostsQuery($query, array $options): array
    {
        // `null` is an accepted value
        if (array_key_exists('has-password', $query)) {
            $query['has_password'] = $query['has-password'];
            unset($query['has-password']);
        }
        if (isset($query['ignore-sticky'])) {
            $query['ignore_sticky_posts'] = $query['ignore-sticky'];
            unset($query['ignore-sticky']);
        }
        if (isset($query['exclude-sticky'])) {
            // Add the sticky posts to whichever post was already set to be excluded
            $query['post__not_in'] = array_merge(
                $query['post__not_in'] ?? [],
                \get_option('sticky_posts', [])
            );
            unset($query['exclude-sticky']);
        }
        return $query;
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
