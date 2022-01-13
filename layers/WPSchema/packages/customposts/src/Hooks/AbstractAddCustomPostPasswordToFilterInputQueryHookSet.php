<?php

declare(strict_types=1);

namespace PoPWPSchema\CustomPosts\Hooks;

use PoP\Root\App;
use PoP\Root\Hooks\AbstractHookSet;

abstract class AbstractAddCustomPostPasswordToFilterInputQueryHookSet extends AbstractHookSet
{
    protected function init(): void
    {
        App::getHookManager()->addFilter(
            $this->getHookName(),
            [$this, 'convertCustomPostsQuery'],
            10,
            2
        );
    }

    abstract protected function getHookName(): string;

    /**
     * @see https://developer.wordpress.org/reference/classes/wp_query/#password-parameters
     */
    public function convertCustomPostsQuery($query, array $options): array
    {
        // `null` is an accepted value
        if (array_key_exists('has-password', $query)) {
            $query['has_password'] = $query['has-password'];
            unset($query['has-password']);
        }
        if (isset($query['password'])) {
            $query['post_password'] = $query['password'];
            unset($query['password']);
        }
        return $query;
    }
}
