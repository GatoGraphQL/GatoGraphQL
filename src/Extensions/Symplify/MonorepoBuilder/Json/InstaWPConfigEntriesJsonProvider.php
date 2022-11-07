<?php

declare(strict_types=1);

namespace PoP\PoP\Extensions\Symplify\MonorepoBuilder\Json;

use PoP\PoP\Extensions\Symplify\MonorepoBuilder\ValueObject\Option;
use Symplify\PackageBuilder\Parameter\ParameterProvider;
use Symplify\SymplifyKernel\Exception\ShouldNotHappenException;

final class InstaWPConfigEntriesJsonProvider
{
    /**
     * @var array<string,string>
     */
    private array $instaWPConfigEntries = [];

    public function __construct(
        ParameterProvider $parameterProvider,
    ) {
        $this->instaWPConfigEntries = $parameterProvider->provideArrayParameter(Option::INSTAWP_CONFIG_ENTRIES);
    }

    /**
     * @return array<array<string,string>>
     */
    public function provideInstaWPConfigEntries(): array
    {
        /**
         * Validate that all required entries have been provided
         */
        $requiredEntries = [
            'templateSlug',
            'repoID',
        ];
        $instaWPConfigEntries = [];
        $sourceInstaWPConfigEntries = $this->instaWPConfigEntries;
        foreach ($sourceInstaWPConfigEntries as $entryConfig) {
            $unprovidedEntries = array_diff(
                $requiredEntries,
                array_keys((array) $entryConfig)
            );
            if ($unprovidedEntries !== []) {
                throw new ShouldNotHappenException(sprintf(
                    "The following entries must be provided in the InstaWP config: '%s'",
                    implode("', '", $unprovidedEntries)
                ));
            }
            $instaWPConfigEntries[] = $entryConfig;
        }

        return $instaWPConfigEntries;
    }
}
