<?php

declare(strict_types=1);

namespace EverythingElse\PoPCMSSchema\Tags\TypeAPIs;

use PoPCMSSchema\Tags\TypeAPIs\TagTypeAPIInterface as UpstreamTagTypeAPIInterface;

interface TagTypeAPIInterface extends UpstreamTagTypeAPIInterface
{
    public function getTagBase(): string;
    /**
     * @param string[] $tags
     */
    public function setPostTags(string|int $customPostID, array $tags, bool $append = false): void;
}
