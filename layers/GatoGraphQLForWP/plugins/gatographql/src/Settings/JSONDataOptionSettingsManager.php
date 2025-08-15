<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\Settings;

use GatoGraphQL\GatoGraphQL\Facades\Settings\OptionNamespacerFacade;

use function delete_option;
use function get_option;
use function update_option;

class JSONDataOptionSettingsManager implements JSONDataOptionSettingsManagerInterface
{
    private ?OptionNamespacerInterface $optionNamespacer = null;

    final protected function getOptionNamespacer(): OptionNamespacerInterface
    {
        return $this->optionNamespacer ??= OptionNamespacerFacade::getInstance();
    }

    /**
     * @param mixed[]|null $defaultValue
     * @return mixed[]|null
     */
    public function getJSONData(string $name, ?array $defaultValue = null): ?array
    {
        $jsonData = get_option($this->namespaceOption(Options::JSON_DATA), []);
        if (!array_key_exists($name, $jsonData)) {
            return $defaultValue;
        }
        if (!is_array($jsonData[$name])) {
            return $defaultValue;
        }
        /** @var array<string,mixed[]> $jsonData */
        return $jsonData[$name];
    }

    protected function namespaceOption(string $option): string
    {
        return $this->getOptionNamespacer()->namespaceOption($option);
    }

    /**
     * @param mixed[] $data
     */
    public function storeJSONData(string $name, array $data): void
    {
        $this->storeJSONDataMultiple([$name => $data]);
    }

    /**
     * @param array<string,mixed[]> $nameData Key: name, Value: data
     */
    public function storeJSONDataMultiple(array $nameData): void
    {
        $option = $this->namespaceOption(Options::JSON_DATA);

        /**
         * Get the current JSON data from the DB
         * @var array<string,mixed[]>
         */
        $jsonData = get_option($option, []);

        /**
         * Override with the provided values
         */
        $jsonData = array_merge(
            $jsonData,
            $nameData
        );
        update_option($option, $jsonData);
    }

    /**
     * @param string[] $names
     */
    public function removeJSONData(array $names): void
    {
        $option = $this->namespaceOption(Options::JSON_DATA);

        /**
         * Remove only the provided keys
         *
         * @var array<string,mixed[]>
         */
        $jsonData = get_option($option, []);
        foreach ($names as $name) {
            unset($jsonData[$name]);
        }

        /**
         * If there were no other keys, can safely delete the option
         */
        if ($jsonData === []) {
            delete_option($option);
            return;
        }

        update_option($option, $jsonData);
    }
}
