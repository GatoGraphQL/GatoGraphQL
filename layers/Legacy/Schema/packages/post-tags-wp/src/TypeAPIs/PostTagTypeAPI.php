<?php

declare(strict_types=1);

namespace EverythingElse\PoPSchema\PostTagsWP\TypeAPIs;

use PoPSchema\PostTags\TypeAPIs\PostTagTypeAPIInterface;
use PoPSchema\TagsWP\TypeAPIs\AbstractTagTypeAPI;

/**
 * Methods to interact with the Type, to be implemented by the underlying CMS
 */
class PostTagTypeAPI extends AbstractTagTypeAPI implements PostTagTypeAPIInterface
{
    protected function getTagBaseOption(): string
    {
        return 'tag_base';
    }
}
