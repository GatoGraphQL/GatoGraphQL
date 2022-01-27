<?php

declare(strict_types=1);

namespace PoPCMSSchema\Settings\TypeAPIs;

use InvalidArgumentException;

interface SettingsTypeAPIInterface
{
    /**
     * @param array<string,mixed> $options
     * @throws InvalidArgumentException When the option does not exist, or is not in the allowlist
     */
    public function getOption(string $name, array $options = []): mixed;
    public function validateIsOptionAllowed(string $key): bool;
    /**
     * @return string[]
     */
    public function getAllowOrDenyOptionEntries(): array;
    public function getAllowOrDenyOptionBehavior(): string;
}
