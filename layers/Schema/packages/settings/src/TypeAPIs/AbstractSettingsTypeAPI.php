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
        /**
         * Check if the allow/denylist validation fails
         * Compare for full match or regex
         */
        $entries = ComponentConfiguration::getSettingsEntries();
        $behavior = ComponentConfiguration::getSettingsBehavior();
        $this->assertIsEntryAllowed($entries, $behavior, $name);
        return $this->doGetOption($name);
    }

    /**
     * If the allow/denylist validation fails, throw an exception.
     * If the key is allowed but non-existent, return `null`.
     * Otherwise, return the value.
     *
     * @throws InvalidArgumentException
     */
    final protected function assertIsEntryAllowed(array $entries, string $behavior, string $name): bool|InvalidArgumentException
    {
        if (!$this->getAllowOrDenySettingsService()->isEntryAllowed($name, $entries, $behavior)) {
            return throw new InvalidArgumentException(
                sprintf(
                    $this->getTranslationAPI()->__('There is no option with name \'%s\'', 'settings'),
                    $name
                )
            );
        }
        return true;
    }

    /**
     * If the name is non-existent, return `null`.
     * Otherwise, return the value.
     */
    abstract protected function doGetOption(string $name): mixed;
}
