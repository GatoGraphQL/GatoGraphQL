<?php

declare(strict_types=1);

namespace PoPCMSSchema\PostTagsWP\Hooks;

use PoPCMSSchema\CustomPostTagsWP\Hooks\AbstractCustomPostTagQueryHookSet;

class PostTagQueryHookSet extends AbstractCustomPostTagQueryHookSet
{
    protected function getTagTaxonomy(): string
    {
        return 'post_tag';
    }
}
