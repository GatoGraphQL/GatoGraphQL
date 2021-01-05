<?php

declare(strict_types=1);

namespace PoP\AccessControl\Hooks;

use PoP\Engine\Hooks\AbstractCMSBootHookSet;
use PoP\ComponentModel\TypeResolvers\HookHelpers;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\ComponentModel\FieldResolvers\FieldResolverInterface;
use PoP\ComponentModel\DirectiveResolvers\DirectiveResolverInterface;

abstract class AbstractAccessControlForDirectivesHookSet extends AbstractCMSBootHookSet
{
    public function cmsBoot(): void
    {
        if (!$this->enabled()) {
            return;
        }
        // If no directiveNames defined, apply to all of them
        if (
            $directiveNames = array_map(
                function ($directiveResolverClass) {
                    return $directiveResolverClass::getDirectiveName();
                },
                $this->getDirectiveResolverClasses()
            )
        ) {
            foreach ($directiveNames as $directiveName) {
                $this->hooksAPI->addFilter(
                    HookHelpers::getHookNameToFilterDirective($directiveName),
                    array($this, 'maybeFilterDirectiveName'),
                    10,
                    4
                );
            }
        } else {
            $this->hooksAPI->addFilter(
                HookHelpers::getHookNameToFilterDirective(),
                array($this, 'maybeFilterDirectiveName'),
                10,
                4
            );
        }
    }

    /**
     * Return true if the directives must be disabled
     *
     * @return boolean
     */
    protected function enabled(): bool
    {
        return true;
    }

    public function maybeFilterDirectiveName(bool $include, TypeResolverInterface $typeResolver, DirectiveResolverInterface $directiveResolver, string $directiveName): bool
    {
        // Because there may be several hooks chained, if any of them has already rejected the field, then already return that response
        if (!$include) {
            return false;
        }
        // Check if to remove the directive
        return !$this->removeDirective($typeResolver, $directiveResolver, $directiveName);
    }
    /**
     * Affected directives
     *
     * @param TypeResolverInterface $typeResolver
     * @param FieldResolverInterface $directiveResolver
     * @param string $directiveName
     * @return boolean
     */
    abstract protected function getDirectiveResolverClasses(): array;
    /**
     * Decide if to remove the directiveNames
     *
     * @param TypeResolverInterface $typeResolver
     * @param FieldResolverInterface $directiveResolver
     * @param string $directiveName
     * @return boolean
     */
    protected function removeDirective(TypeResolverInterface $typeResolver, DirectiveResolverInterface $directiveResolver, string $directiveName): bool
    {
        return true;
    }
}
