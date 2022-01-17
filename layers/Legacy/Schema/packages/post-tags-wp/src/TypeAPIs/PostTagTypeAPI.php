<?php

declare(strict_types=1);

namespace EverythingElse\PoPCMSSchema\PostTagsWP\TypeAPIs;

use PoPCMSSchema\PostTags\TypeAPIs\PostTagTypeAPIInterface;
use PoPCMSSchema\TagsWP\TypeAPIs\AbstractTagTypeAPI;

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
