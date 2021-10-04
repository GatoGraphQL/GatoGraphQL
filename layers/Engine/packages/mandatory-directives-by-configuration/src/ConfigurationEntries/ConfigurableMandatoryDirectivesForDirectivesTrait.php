<?php

declare(strict_types=1);

namespace PoP\MandatoryDirectivesByConfiguration\ConfigurationEntries;

use PoP\ComponentModel\DirectiveResolvers\DirectiveResolverInterface;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use Symfony\Contracts\Service\Attribute\Required;

trait ConfigurableMandatoryDirectivesForDirectivesTrait
{
    protected InstanceManagerInterface $instanceManager;

    #[Required]
    public function autowireConfigurableMandatoryDirectivesForDirectivesTrait(
        InstanceManagerInterface $instanceManager,
    ): void {
        $this->instanceManager = $instanceManager;
    }

    /**
     * Configuration entries
     */
    abstract protected function getConfigurationEntries(): array;

    /**
     * Configuration entries
     */
    final protected function getEntries(): array
    {
        $entryList = $this->getConfigurationEntries();
        $requiredEntryValue = $this->getRequiredEntryValue();
        return $this->getMatchingEntries(
            $entryList,
            $requiredEntryValue
        );
    }

    /**
     * The value in the 2nd element from the entry
     */
    protected function getRequiredEntryValue(): ?string
    {
        return null;
    }

    /**
     * Affected directives
     *
     * @return DirectiveResolverInterface[]
     */
    final protected function getDirectiveResolvers(): array
    {
        return array_map(
            fn (string $directiveResolverClass) => $this->instanceManager->getInstance($directiveResolverClass),
            $this->getDirectiveResolverClasses()
        );
    }

    /**
     * Remove directiveName "translate" if the user is not logged in
     */
    protected function getDirectiveResolverClasses(): array
    {
        // Obtain all entries for the current combination of typeResolver/fieldName
        return array_values(array_unique(array_map(
            fn (array $entry) => $entry[0],
            $this->getEntries()
        )));
    }

    /**
     * Filter all the entries from the list which apply to the passed typeResolver and fieldName
     */
    final protected function getMatchingEntries(array $entryList, ?string $value): array
    {
        if ($value) {
            return array_filter(
                $entryList,
                fn (array $entry) => ($entry[1] ?? null) == $value
            );
        }
        return $entryList;
    }
}
