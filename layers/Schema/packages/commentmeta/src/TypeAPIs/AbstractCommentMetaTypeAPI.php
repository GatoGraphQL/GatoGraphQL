<?php

declare(strict_types=1);

namespace PoPSchema\CommentMeta\TypeAPIs;

use InvalidArgumentException;
use PoPSchema\CommentMeta\ComponentConfiguration;
use PoPSchema\Meta\TypeAPIs\AbstractMetaTypeAPI;

abstract class AbstractCommentMetaTypeAPI extends AbstractMetaTypeAPI implements CommentMetaTypeAPIInterface
{
    /**
     * If the allow/denylist validation fails, throw an exception.
     * If the key is allowed but non-existent, return `null`.
     * Otherwise, return the value.
     *
     * @throws InvalidArgumentException
     */
    final public function getCommentMeta(string | int $commentID, string $key, bool $single = false): mixed
    {
        $this->assertIsEntryAllowed($key);
        return $this->doGetCommentMeta($commentID, $key, $single);
    }

    /**
     * @return string[]
     */
    public function getAllowOrDenyMetaEntries(): array
    {
        return ComponentConfiguration::getCommentMetaEntries();
    }
    public function getAllowOrDenyMetaBehavior(): string
    {
        return ComponentConfiguration::getCommentMetaBehavior();
    }

    /**
     * If the key is non-existent, return `null`.
     * Otherwise, return the value.
     */
    abstract protected function doGetCommentMeta(string | int $commentID, string $key, bool $single = false): mixed;
}
