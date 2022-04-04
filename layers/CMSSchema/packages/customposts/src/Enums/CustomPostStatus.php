<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPosts\Enums;

class CustomPostStatus
{
    /**
     * Using 'publish' instead of 'published' to make it work directly with WordPress,
     * used by the GraphQL API for WordPress.
     *
     * Otherwise, this plugin should override fields 'status' and 'isStatus',
     * to transform from one to the other value and back,
     * between 'publish' and a potential 'published' (generic value for a CMS).
     */
    public final const PUBLISH = 'publish';
    public final const PENDING = 'pending';
    public final const DRAFT = 'draft';
    public final const TRASH = 'trash';
}
