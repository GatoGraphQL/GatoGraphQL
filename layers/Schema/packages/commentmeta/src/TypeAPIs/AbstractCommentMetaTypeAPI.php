<?php

declare(strict_types=1);

namespace PoPSchema\CommentMeta\TypeAPIs;

use PoPSchema\CommentMeta\ComponentConfiguration;
use PoPSchema\SchemaCommons\Services\AllowOrDenySettingsServiceInterface;
use Symfony\Contracts\Service\Attribute\Required;

abstract class AbstractCommentMetaTypeAPI implements CommentMetaTypeAPIInterface
{
    protected AllowOrDenySettingsServiceInterface $allowOrDenySettingsService;

    #[Required]
    final public function autowireAbstractCommentMetaTypeAPI(AllowOrDenySettingsServiceInterface $allowOrDenySettingsService): void
    {
        $this->allowOrDenySettingsService = $allowOrDenySettingsService;
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
