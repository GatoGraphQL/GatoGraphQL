<?php

declare(strict_types=1);

namespace PoPSchema\Meta\TypeAPIs;

use InvalidArgumentException;
use PoP\ComponentModel\Services\BasicServiceTrait;
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

    /**
     * If the allow/denylist validation fails, throw an exception.
     * If the key is allowed but non-existent, return `null`.
     * Otherwise, return the value.
     *
     * @param string[] $entries
     */
    final public function validateIsEntryAllowed(string $key): bool
    {
        return $this->getAllowOrDenySettingsService()->isEntryAllowed(
            $key,
            $this->getAllowOrDenyMetaEntries(),
            $this->getAllowOrDenyMetaBehavior()
        );
    }

    /**
     * If the allow/denylist validation fails, throw an exception.
     * If the key is allowed but non-existent, return `null`.
     * Otherwise, return the value.
     *
     * @param string[] $entries
     *
     * @throws InvalidArgumentException
     */
    final protected function assertIsEntryAllowed(string $key): void
    {
        if (!$this->validateIsEntryAllowed($key)) {
            throw new InvalidArgumentException(
                sprintf(
                    $this->getTranslationAPI()->__('There is no meta with key \'%s\'', 'commentmeta'),
                    $key
                )
            );
        }
    }
}
