<?php

declare(strict_types=1);

namespace PoPCMSSchema\Settings\TypeAPIs;

use PoP\Root\App;
use PoP\Root\Services\BasicServiceTrait;
use PoPCMSSchema\Settings\Module;
use PoPCMSSchema\Settings\ModuleConfiguration;
use PoPCMSSchema\Settings\Exception\OptionNotAllowedException;
use PoPSchema\SchemaCommons\Services\AllowOrDenySettingsServiceInterface;

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
     * If the allow/denylist validation fails, and passing option "assert-is-option-allowed",
     * then throw an exception.
     *
     * @param array<string,mixed> $options
     * @throws OptionNotAllowedException When the option name is not in the allowlist. Enabled by passing option "assert-is-option-allowed"
     */
    final public function getOption(string $name, array $options = []): mixed
    {
        if ($options['assert-is-option-allowed'] ?? null) {
            $this->assertIsOptionAllowed($name);
        }
        return $this->doGetOption($name);
    }

    /**
     * @return string[]
     */
    public function getAllowOrDenyOptionEntries(): array
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        return $moduleConfiguration->getSettingsEntries();
    }
    public function getAllowOrDenyOptionBehavior(): string
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        return $moduleConfiguration->getSettingsBehavior();
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
     * @throws OptionNotAllowedException
     */
    final protected function assertIsOptionAllowed(string $name): void
    {
        if (!$this->validateIsOptionAllowed($name)) {
            throw new OptionNotAllowedException(
                sprintf(
                    $this->__('There is no option with name \'%s\'', 'settings'),
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
