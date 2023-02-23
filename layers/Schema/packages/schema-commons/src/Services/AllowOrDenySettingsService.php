<?php

declare(strict_types=1);

namespace PoPSchema\SchemaCommons\Services;

use PoPSchema\SchemaCommons\Constants\Behaviors;

class AllowOrDenySettingsService implements AllowOrDenySettingsServiceInterface
{
    /**
     * Check if the allow/denylist validation fails
     * Compare for full match or regex
     *
     * @param string[] $entries
     */
    public function isEntryAllowed(string $name, array $entries, string $behavior): bool
    {
        if ($entries === []) {
            return $behavior === Behaviors::DENY;
        }
        $matchResults = array_filter(array_map(
            function (string $termOrRegex) use ($name): bool {
                // Remove whitespaces at either end of the string
                $termOrRegex = trim($termOrRegex);

                // Check if it is a regex expression
                if (
                    (str_starts_with($termOrRegex, '/') && str_ends_with($termOrRegex, '/'))
                    || (str_starts_with($termOrRegex, '#') && str_ends_with($termOrRegex, '#'))
                ) {
                    return preg_match($termOrRegex, $name) === 1;
                }
                // Check it's a full match
                return $termOrRegex === $name;
            },
            $entries
        ));
        if (
            ($behavior === Behaviors::ALLOW && count($matchResults) === 0)
            || ($behavior === Behaviors::DENY && count($matchResults) > 0)
        ) {
            return false;
        }
        return true;
    }
}
