<?php

declare(strict_types=1);

namespace PoPSchema\CustomPostMutations\MutationResolvers;

class MutationInputProperties
{
    public const ID = 'id';
    public const TITLE = 'title';
    public const CONTENT = 'content';
    public const STATUS = 'status';
    // @TODO: Migrate when package "Categories" is completed
    // public const CATEGORIES = 'categories';
}
