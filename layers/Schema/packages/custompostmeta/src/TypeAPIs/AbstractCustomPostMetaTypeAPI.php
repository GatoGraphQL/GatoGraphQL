<?php

declare(strict_types=1);

namespace PoPSchema\CustomPostMeta\TypeAPIs;

use PoPSchema\CustomPostMeta\ComponentConfiguration;
use PoPSchema\CustomPostMeta\TypeAPIs\CustomPostMetaTypeAPIInterface;
use PoPSchema\SchemaCommons\Facades\Services\AllowOrDenySettingsServiceFacade;
use PoPSchema\SchemaCommons\Services\AllowOrDenySettingsServiceInterface;

abstract class AbstractCustomPostMetaTypeAPI implements CustomPostMetaTypeAPIInterface
{
    public function __construct(
        protected AllowOrDenySettingsServiceInterface $allowOrDenySettingsService,
    ) {
    }

    final public function getCustomPostMeta(string | int $customPostID, string $key, bool $single = false): mixed
    {
        /**
         * Check if the allow/denylist validation fails
         * Compare for full match or regex
         */
        $entries = ComponentConfiguration::getCustomPostMetaEntries();
        $behavior = ComponentConfiguration::getCustomPostMetaBehavior();
        if (!$this->allowOrDenySettingsService->isEntryAllowed($key, $entries, $behavior)) {
            return null;
        }
        return $this->doGetCustomPostMeta($customPostID, $key, $single);
    }

    abstract protected function doGetCustomPostMeta(string | int $customPostID, string $key, bool $single = false): mixed;
}
