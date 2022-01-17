<?php

declare(strict_types=1);

namespace PoPCMSSchema\CustomPostMeta\TypeAPIs;

use PoP\Root\App;
use InvalidArgumentException;
use PoPCMSSchema\Meta\TypeAPIs\AbstractMetaTypeAPI;
use PoPCMSSchema\CustomPostMeta\Component;
use PoPCMSSchema\CustomPostMeta\ComponentConfiguration;

abstract class AbstractCustomPostMetaTypeAPI extends AbstractMetaTypeAPI implements CustomPostMetaTypeAPIInterface
{
    /**
     * If the allow/denylist validation fails, and passing option "assert-is-meta-key-allowed",
     * then throw an exception.
     * If the key is allowed but non-existent, return `null`.
     * Otherwise, return the value.
     *
     * @param array<string,mixed> $options
     * @throws InvalidArgumentException
     */
    final public function getCustomPostMeta(string | int $customPostID, string $key, bool $single = false, array $options = []): mixed
    {
        if ($options['assert-is-meta-key-allowed'] ?? null) {
            $this->assertIsMetaKeyAllowed($key);
        }
        return $this->doGetCustomPostMeta($customPostID, $key, $single);
    }

    /**
     * @return string[]
     */
    public function getAllowOrDenyMetaEntries(): array
    {
        /** @var ComponentConfiguration */
        $componentConfiguration = App::getComponent(Component::class)->getConfiguration();
        return $componentConfiguration->getCustomPostMetaEntries();
    }
    public function getAllowOrDenyMetaBehavior(): string
    {
        /** @var ComponentConfiguration */
        $componentConfiguration = App::getComponent(Component::class)->getConfiguration();
        return $componentConfiguration->getCustomPostMetaBehavior();
    }

    /**
     * If the key is non-existent, return `null`.
     * Otherwise, return the value.
     */
    abstract protected function doGetCustomPostMeta(string | int $customPostID, string $key, bool $single = false): mixed;
}
