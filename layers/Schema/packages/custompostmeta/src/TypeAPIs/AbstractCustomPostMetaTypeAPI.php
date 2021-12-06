<?php

declare(strict_types=1);

namespace PoPSchema\CustomPostMeta\TypeAPIs;

use InvalidArgumentException;
use PoPSchema\Meta\TypeAPIs\AbstractMetaTypeAPI;
use PoPSchema\CustomPostMeta\ComponentConfiguration;

abstract class AbstractCustomPostMetaTypeAPI extends AbstractMetaTypeAPI implements CustomPostMetaTypeAPIInterface
{
    /**
     * If the allow/denylist validation fails, throw an exception
     *
     * @throws InvalidArgumentException
     */
    final public function getCustomPostMeta(string | int $customPostID, string $key, bool $single = false): mixed
    {
        /**
         * Check if the allow/denylist validation fails
         * Compare for full match or regex
         */
        $entries = ComponentConfiguration::getCustomPostMetaEntries();
        $behavior = ComponentConfiguration::getCustomPostMetaBehavior();
        $this->assertIsEntryAllowed($entries, $behavior, $key);
        return $this->doGetCustomPostMeta($customPostID, $key, $single);
    }

    abstract protected function doGetCustomPostMeta(string | int $customPostID, string $key, bool $single = false): mixed;
}
