<?php

declare(strict_types=1);

namespace PoPCMSSchema\Meta\TypeAPIs;

use PoP\Root\Services\BasicServiceTrait;
use PoPCMSSchema\Meta\Exception\MetaKeyNotAllowedException;
use PoPSchema\SchemaCommons\Services\AllowOrDenySettingsServiceInterface;

abstract class AbstractMetaTypeAPI implements MetaTypeAPIInterface
{
    use BasicServiceTrait;

    private ?AllowOrDenySettingsServiceInterface $allowOrDenySettingsService = null;

    final public function setAllowOrDenySettingsService(AllowOrDenySettingsServiceInterface $allowOrDenySettingsService): void
    {
        $this->allowOrDenySettingsService = $allowOrDenySettingsService;
    }
    final protected function getAllowOrDenySettingsService(): AllowOrDenySettingsServiceInterface
    {
        if ($this->allowOrDenySettingsService === null) {
            /** @var AllowOrDenySettingsServiceInterface */
            $allowOrDenySettingsService = $this->instanceManager->getInstance(AllowOrDenySettingsServiceInterface::class);
            $this->allowOrDenySettingsService = $allowOrDenySettingsService;
        }
        return $this->allowOrDenySettingsService;
    }

    final public function validateIsMetaKeyAllowed(string $key): bool
    {
        return $this->getAllowOrDenySettingsService()->isEntryAllowed(
            $key,
            $this->getAllowOrDenyMetaEntries(),
            $this->getAllowOrDenyMetaBehavior()
        );
    }

    /**
     * If the allow/denylist validation fails, throw an exception.
     *
     * @throws MetaKeyNotAllowedException
     */
    final protected function assertIsMetaKeyAllowed(string $key): void
    {
        if (!$this->validateIsMetaKeyAllowed($key)) {
            throw new MetaKeyNotAllowedException(
                sprintf(
                    $this->__('There is no meta with key \'%s\'', 'commentmeta'),
                    $key
                )
            );
        }
    }
}
