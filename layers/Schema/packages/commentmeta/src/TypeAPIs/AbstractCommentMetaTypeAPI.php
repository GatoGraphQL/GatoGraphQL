<?php

declare(strict_types=1);

namespace PoPSchema\CommentMeta\TypeAPIs;

use PoPSchema\CommentMeta\ComponentConfiguration;
use PoPSchema\CommentMeta\TypeAPIs\CommentMetaTypeAPIInterface;
use PoPSchema\SchemaCommons\Services\AllowOrDenySettingsServiceInterface;

abstract class AbstractCommentMetaTypeAPI implements CommentMetaTypeAPIInterface
{
    public function __construct(
        protected AllowOrDenySettingsServiceInterface $allowOrDenySettingsService,
    ) {
    }

    final public function getCommentMeta(string | int $commentID, string $key, bool $single = false): mixed
    {
        /**
         * Check if the allow/denylist validation fails
         * Compare for full match or regex
         */
        $entries = ComponentConfiguration::getCommentMetaEntries();
        $behavior = ComponentConfiguration::getCommentMetaBehavior();
        if (!$this->allowOrDenySettingsService->isEntryAllowed($key, $entries, $behavior)) {
            return null;
        }
        return $this->doGetCommentMeta($commentID, $key, $single);
    }

    abstract protected function doGetCommentMeta(string | int $commentID, string $key, bool $single = false): mixed;
}
