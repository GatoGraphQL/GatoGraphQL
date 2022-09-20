<?php

declare(strict_types=1);

namespace PoPCMSSchema\UserRolesAccessControl\Hooks;

use PoP\AccessControl\Hooks\AbstractConfigurableAccessControlForDirectivesInPrivateSchemaHookSet;
use PoP\ComponentModel\DirectiveResolvers\FieldDirectiveResolverInterface;
use PoP\Root\App;

abstract class AbstractMaybeDisableDirectivesIfLoggedInUserDoesNotHaveItemPrivateSchemaHookSet extends AbstractConfigurableAccessControlForDirectivesInPrivateSchemaHookSet
{
    /**
     * @var string[]|null
     */
    protected ?array $directiveResolverClasses = null;

    protected function enabled(): bool
    {
        return parent::enabled() && !empty($this->getFieldDirectiveResolverClasses());
    }

    /**
     * Indicate if the user has the item, to be implemented
     *
     * @param mixed[] $items
     */
    abstract protected function doesCurrentUserHaveAnyItem(array $items): bool;

    /**
     * Remove directiveName "translate" if the user is not logged in
     *
     * @return array<class-string<FieldDirectiveResolverInterface>>
     */
    protected function getFieldDirectiveResolverClasses(): array
    {
        if ($this->directiveResolverClasses === null) {
            $entries = $this->getEntries();
            // If the user is not logged in, then it's all directives
            if (!App::getState('is-user-logged-in')) {
                $this->directiveResolverClasses = array_values(array_unique(array_map(
                    fn (array $entry) => $entry[0],
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
        /** @var array<class-string<FieldDirectiveResolverInterface>> */
        return $this->directiveResolverClasses;
    }
}
