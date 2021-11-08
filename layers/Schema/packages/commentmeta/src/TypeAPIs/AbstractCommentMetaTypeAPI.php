<?php

declare(strict_types=1);

namespace PoPSchema\CommentMeta\TypeAPIs;

use PoP\ComponentModel\Services\BasicServiceTrait;
use PoPSchema\CommentMeta\ComponentConfiguration;
use PoPSchema\SchemaCommons\Services\AllowOrDenySettingsServiceInterface;

abstract class AbstractCommentMetaTypeAPI implements CommentMetaTypeAPIInterface
{
    use BasicServiceTrait;

    private ?AllowOrDenySettingsServiceInterface $allowOrDenySettingsService = null;

    final public function setAllowOrDenySettingsService(AllowOrDenySettingsServiceInterface $allowOrDenySettingsService): void
    {
        $this->allowOrDenySettingsService = $allowOrDenySettingsService;
    }
    final protected function getAllowOrDenySettingsService(): AllowOrDenySettingsServiceInterface
    {
        return $this->allowOrDenySettingsService ??= $this->instanceManager->getInstance(AllowOrDenySettingsServiceInterface::class);
    }

    final public function getCommentMeta(string | int $commentID, string $key, bool $single = false): mixed
    {
        /**
         * Check if the allow/denylist validation fails
         * Compare for full match or regex
         */
        $entries = ComponentConfiguration::getCommentMetaEntries();
        $behavior = ComponentConfiguration::getCommentMetaBehavior();
        if (!$this->getAllowOrDenySettingsService()->isEntryAllowed($key, $entries, $behavior)) {
            return null;
        }
        return $this->doGetCommentMeta($commentID, $key, $single);
    }

    abstract protected function doGetCommentMeta(string | int $commentID, string $key, bool $single = false): mixed;
}
