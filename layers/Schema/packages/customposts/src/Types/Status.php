<?php

declare(strict_types=1);

namespace PoPSchema\CustomPosts\Types;

class Status
{
    /**
     * Use 'publish' instead of 'published' to make it work directly with WordPress,
     * used by the GraphQL API for WordPress.
     * Otherwise, this plugin should override fields 'status' and 'isStatus',
     * to transform from one to the other value and back,
     * since users expect 'publish', not 'published'.
     *
     * This change goes against the total separation of application and CMS,
     * but is needed to simplify matters. When GraphQL by PoP is implemented for
     * a second CMS in addition to WordPress, this can be reviewed
     *
     * @todo Maybe change constant PUBLISHED from 'publish' to 'published'
     */
    // public const PUBLISHED = 'published';
    public const PUBLISHED = 'publish';
    public const PENDING = 'pending';
    public const DRAFT = 'draft';
    public const TRASH = 'trash';
}
