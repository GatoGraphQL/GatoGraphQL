<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Settings;

use GatoGraphQL\GatoGraphQL\Facades\Settings\OptionNamespacerFacade;

use function delete_option;
use function get_option;
use function update_option;

class TransientSettingsManager implements TransientSettingsManagerInterface
{
    private ?OptionNamespacerInterface $optionNamespacer = null;

    final protected function getOptionNamespacer(): OptionNamespacerInterface
    {
        return $this->optionNamespacer ??= OptionNamespacerFacade::getInstance();
    }

    public function getTransient(string $name, mixed $defaultValue = null): mixed
    {
        /** @var array<string,mixed> */
        $transients = get_option($this->namespaceOption(Options::TRANSIENTS), []);
        if (!array_key_exists($name, $transients)) {
            return $defaultValue;
        }
        return $transients[$name];
    }

    protected function namespaceOption(string $option): string
    {
        return $this->getOptionNamespacer()->namespaceOption($option);
    }

    public function storeTransient(string $name, mixed $transient): void
    {
        $this->storeTransients([$name => $transient]);
    }

    /**
     * @param array<string,mixed> $nameTransients Key: name, Value: transient data
     */
    public function storeTransients(array $nameTransients): void
    {
        $option = $this->namespaceOption(Options::TRANSIENTS);

        /**
         * Get the current transients from the DB
         * @var array<string,mixed>
         */
        $transients = get_option($option, []);

        /**
         * Override with the provided values
         */
        $transients = array_merge(
            $transients,
            $nameTransients
        );
        update_option($option, $transients);
    }

    /**
     * @param string[] $names
     */
    public function removeTransients(array $names): void
    {
        $option = $this->namespaceOption(Options::TRANSIENTS);

        /**
         * Remove only the provided keys
         *
         * @var array<string,mixed>
         */
        $transients = get_option($option, []);
        foreach ($names as $name) {
            unset($transients[$name]);
        }

        /**
         * If there were no other keys, can safely delete the option
         */
        if ($transients === []) {
            delete_option($option);
            return;
        }

        update_option($option, $transients);
    }
}
