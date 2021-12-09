<?php

declare(strict_types=1);

namespace PoPSchema\Settings\TypeAPIs;

use InvalidArgumentException;
use PoP\ComponentModel\Services\BasicServiceTrait;
use PoPSchema\SchemaCommons\Services\AllowOrDenySettingsServiceInterface;
use PoPSchema\Settings\ComponentConfiguration;

abstract class AbstractSettingsTypeAPI implements SettingsTypeAPIInterface
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
     * @throws InvalidArgumentException When the option does not exist, or is not in the allowlist
     */
    final public function getOption(string $name): mixed
    {
        $this->assertIsEntryAllowed($name);
        return $this->doGetOption($name);
    }

    /**
     * @return string[]
     */
    public function getAllowOrDenyOptionEntries(): array
    {
        return ComponentConfiguration::getSettingsEntries();
    }
    public function getAllowOrDenyOptionBehavior(): string
    {
        return ComponentConfiguration::getSettingsBehavior();
    }

    final public function validateIsEntryAllowed(string $key): bool
    {
        return $this->getAllowOrDenySettingsService()->isEntryAllowed(
            $key,
            $this->getAllowOrDenyOptionEntries(),
            $this->getAllowOrDenyOptionBehavior()
        );
    }

    /**
     * If the allow/denylist validation fails, throw an exception.
     *
     * @throws InvalidArgumentException
     */
    final protected function assertIsEntryAllowed(string $name): void
    {
        if (!$this->validateIsEntryAllowed($name)) {
            throw new InvalidArgumentException(
                sprintf(
                    $this->getTranslationAPI()->__('There is no option with name \'%s\'', 'settings'),
                    $name
                )
            );
        }
    }

    /**
     * If the name is non-existent, return `null`.
     * Otherwise, return the value.
     */
    abstract protected function doGetOption(string $name): mixed;
}
