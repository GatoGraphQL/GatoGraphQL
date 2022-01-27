<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostTagMutations\TypeAPIs;

interface CustomPostTagTypeMutationAPIInterface
{
    /**
     * @param string[] $tags
     */
    public function setTags(int | string $postID, array $tags, bool $append = false): void;
}
