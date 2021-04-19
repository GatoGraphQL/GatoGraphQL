<?php

declare(strict_types=1);

namespace PoPSchema\PostTagsWP\Hooks;

use PoPSchema\CustomPostTagsWP\Hooks\AbstractCustomPostTagQueryHookSet;

class PostTagQueryHookSet extends AbstractCustomPostTagQueryHookSet
{
    protected function getTagTaxonomy(): string
    {
        return 'post_tag';
    }
}
