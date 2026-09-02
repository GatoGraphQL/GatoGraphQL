<?php

declare(strict_types=1);

namespace PoPCMSSchema\Meta\TypeAPIs;

use PoP\Root\Services\AbstractBasicService;
use PoPCMSSchema\Meta\Exception\MetaKeyNotAllowedException;
use PoPSchema\SchemaCommons\Constants\Behaviors;
use PoPSchema\SchemaCommons\Services\AllowOrDenySettingsServiceInterface;

abstract class AbstractMetaTypeAPI extends AbstractBasicService implements MetaTypeAPIInterface
{
    private ?AllowOrDenySettingsServiceInterface $allowOrDenySettingsService = null;

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

    final protected function isMetaKeyExplicitlyAllowed(string $key): bool
    {
        if ($this->getAllowOrDenyMetaBehavior() !== Behaviors::ALLOW) {
            return false;
        }
        foreach ($this->getAllowOrDenyMetaEntries() as $entry) {
            $entry = trim($entry);
            if (
                (str_starts_with($entry, '/') && str_ends_with($entry, '/'))
                || (str_starts_with($entry, '#') && str_ends_with($entry, '#'))
            ) {
                continue;
            }
            if ($entry === $key) {
                return true;
            }
        }
        return false;
    }

    public function isMetaKeyProtected(string $key): bool
    {
        return false;
    }

    public function isMetaKeyProtectedFromReading(string $key): bool
    {
        return false;
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
                    $this->__('There is no meta with key \'%s\'', 'gatographql'),
                    $key
                )
            );
        }
    }
}
