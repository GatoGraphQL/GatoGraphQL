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

    public function getTransient(string $name, ?string $defaultValue = null): ?string
    {
        /** @var array<string,string> */
        $timestamps = get_option($this->namespaceOption(Options::TRANSIENTS), []);
        if (!array_key_exists($name, $timestamps)) {
            return $defaultValue;
        }
        return $timestamps[$name];
    }

    protected function namespaceOption(string $option): string
    {
        return $this->getOptionNamespacer()->namespaceOption($option);
    }

    public function storeTransient(string $name, string $timestamp): void
    {
        $this->storeTransients([$name => $timestamp]);
    }

    /**
     * @param array<string,string> $nameTransients Key: name, Value: timestamp
     */
    public function storeTransients(array $nameTransients): void
    {
        $option = $this->namespaceOption(Options::TRANSIENTS);

        /**
         * Get the current timestamps from the DB
         * @var array<string,string>
         */
        $timestamps = get_option($option, []);

        /**
         * Override with the provided values
         */
        $timestamps = array_merge(
            $timestamps,
            $nameTransients
        );
        update_option($option, $timestamps);
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
         * @var array<string,string>
         */
        $timestamps = get_option($option, []);
        foreach ($names as $name) {
            unset($timestamps[$name]);
        }

        /**
         * If there were no other keys, can safely delete the option
         */
        if ($timestamps === []) {
            delete_option($option);
            return;
        }

        update_option($option, $timestamps);
    }
}
