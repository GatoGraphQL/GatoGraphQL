<?php

declare(strict_types=1);

namespace PoPCMSSchema\Settings\TypeAPIs;

use PoPCMSSchema\Settings\Exception\OptionNotAllowedException;

interface SettingsTypeAPIInterface
{
    /**
     * @param array<string,mixed> $options
     * @throws OptionNotAllowedException When the option does not exist, or is not in the allowlist
     */
    public function getOption(string $name, array $options = []): mixed;
    /**
     * List of all the option names stored in the DB.
     *
     * @return string[]
     */
    public function getOptionNames(): array;
    public function validateIsOptionAllowed(string $key): bool;
    /**
     * @return string[]
     */
    public function getAllowOrDenyOptionEntries(): array;
    public function getAllowOrDenyOptionBehavior(): string;
}
