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
     * @param array<string,mixed> $options
     * @throws InvalidArgumentException When the option does not exist, or is not in the allowlist
     */
    final public function getOption(string $name, array $options = []): mixed
    {
        if ($options['assert-is-entry-allowed'] ?? null) {
            $this->assertIsOptionAllowed($name);
        }
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

    final public function validateIsOptionAllowed(string $name): bool
    {
        return $this->getAllowOrDenySettingsService()->isEntryAllowed(
            $name,
            $this->getAllowOrDenyOptionEntries(),
            $this->getAllowOrDenyOptionBehavior()
        );
    }

    /**
     * If the allow/denylist validation fails, throw an exception.
     *
     * @throws InvalidArgumentException
     */
    final protected function assertIsOptionAllowed(string $name): void
    {
        if (!$this->validateIsOptionAllowed($name)) {
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
