<?php

declare(strict_types=1);

namespace PoPSchema\Meta\TypeAPIs;

use InvalidArgumentException;
use PoP\Root\Services\BasicServiceTrait;
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
        return $this->allowOrDenySettingsService ??= $this->instanceManager->getInstance(AllowOrDenySettingsServiceInterface::class);
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
     * @throws InvalidArgumentException
     */
    final protected function assertIsMetaKeyAllowed(string $key): void
    {
        if (!$this->validateIsMetaKeyAllowed($key)) {
            throw new InvalidArgumentException(
                sprintf(
                    $this->__('There is no meta with key \'%s\'', 'commentmeta'),
                    $key
                )
            );
        }
    }
}
