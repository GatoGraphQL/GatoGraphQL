<?php

declare(strict_types=1);

namespace PoPSchema\UserRolesAccessControl\Hooks;

use PoP\ComponentModel\State\ApplicationState;
use PoP\AccessControl\Hooks\AbstractConfigurableAccessControlForDirectivesInPrivateSchemaHookSet;

abstract class AbstractMaybeDisableDirectivesIfLoggedInUserDoesNotHaveItemPrivateSchemaHookSet extends AbstractConfigurableAccessControlForDirectivesInPrivateSchemaHookSet
{
    /**
     * @var string[]|null
     */
    protected ?array $directiveResolverClasses;

    protected function enabled(): bool
    {
        return parent::enabled() && !empty($this->getDirectiveResolverClasses());
    }

    /**
     * Indicate if the user has the item, to be implemented
     *
     * @param mixed[] $items
     */
    abstract protected function doesCurrentUserHaveAnyItem(array $items): bool;

    /**
     * Remove directiveName "translate" if the user is not logged in
     */
    protected function getDirectiveResolverClasses(): array
    {
        if (is_null($this->directiveResolverClasses)) {
            $entries = $this->getEntries();
            // If the user is not logged in, then it's all directives
            $vars = ApplicationState::getVars();
            if (!$vars['global-userstate']['is-user-logged-in']) {
                $this->directiveResolverClasses = array_values(array_unique(array_map(
                    function ($entry) {
                        return $entry[0];
                    },
                    $entries
                )));
            } else {
                // For each entry, validate if the current user has any of those items (roles/capabilities). If not, the directive must be removed
                $this->directiveResolverClasses = [];
                foreach ($entries as $entry) {
                    $directiveResolverClass = $entry[0];
                    $items = $entry[1] ?? [];
                    if (!$this->doesCurrentUserHaveAnyItem($items)) {
                        $this->directiveResolverClasses[] = $directiveResolverClass;
                    }
                }
            }
        }
        return $this->directiveResolverClasses;
    }
}
