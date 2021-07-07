<?php

declare(strict_types=1);

namespace PoP\AccessControl\Hooks;

use PoP\Hooks\HooksAPIInterface;
use PoP\Engine\Hooks\AbstractCMSBootHookSet;
use PoP\Translation\TranslationAPIInterface;
use PoP\ComponentModel\TypeResolvers\HookHelpers;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\AccessControl\Services\AccessControlManagerInterface;
use PoP\ComponentModel\FieldResolvers\FieldResolverInterface;
use PoP\ComponentModel\DirectiveResolvers\DirectiveResolverInterface;

abstract class AbstractAccessControlForDirectivesHookSet extends AbstractCMSBootHookSet
{
    public function __construct(
        HooksAPIInterface $hooksAPI,
        TranslationAPIInterface $translationAPI,
        InstanceManagerInterface $instanceManager,
        protected AccessControlManagerInterface $accessControlManager
    ) {
        parent::__construct(
            $hooksAPI,
            $translationAPI,
            $instanceManager,
        );
    }

    public function cmsBoot(): void
    {
        if (!$this->enabled()) {
            return;
        }
        // If no directiveNames defined, apply to all of them
        if (
            $directiveNames = array_map(
                function ($directiveResolver) {
                    return $directiveResolver->getDirectiveName();
                },
                $this->getDirectiveResolvers()
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
     */
    abstract protected function getDirectiveResolverClasses(): array;
    /**
     * Affected directives
     * @return DirectiveResolverInterface[]
     */
    protected function getDirectiveResolvers(): array
    {
        return array_map(
            fn (string $directiveResolverClass) => $this->instanceManager->getInstance($directiveResolverClass),
            $this->getDirectiveResolverClasses()
        );
    }
    /**
     * Decide if to remove the directiveNames
     */
    protected function removeDirective(TypeResolverInterface $typeResolver, DirectiveResolverInterface $directiveResolver, string $directiveName): bool
    {
        return true;
    }
}
