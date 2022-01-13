<?php

declare(strict_types=1);

namespace PoP\AccessControl\Hooks;

use PoP\Root\App;
use PoP\AccessControl\Services\AccessControlManagerInterface;
use PoP\ComponentModel\DirectiveResolvers\DirectiveResolverInterface;
use PoP\ComponentModel\TypeResolvers\HookHelpers;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\Engine\Hooks\AbstractCMSBootHookSet;

abstract class AbstractAccessControlForDirectivesHookSet extends AbstractCMSBootHookSet
{
    private ?AccessControlManagerInterface $accessControlManager = null;

    final public function setAccessControlManager(AccessControlManagerInterface $accessControlManager): void
    {
        $this->accessControlManager = $accessControlManager;
    }
    final protected function getAccessControlManager(): AccessControlManagerInterface
    {
        return $this->accessControlManager ??= $this->instanceManager->getInstance(AccessControlManagerInterface::class);
    }

    public function cmsBoot(): void
    {
        if (!$this->enabled()) {
            return;
        }
        // If no directiveNames defined, apply to all of them
        if (
            $directiveNames = array_map(
                fn (DirectiveResolverInterface $directiveResolver) => $directiveResolver->getDirectiveName(),
                $this->getDirectiveResolvers()
            )
        ) {
            /** @var string[] $directiveNames */
            foreach ($directiveNames as $directiveName) {
                App::addFilter(
                    HookHelpers::getHookNameToFilterDirective($directiveName),
                    array($this, 'maybeFilterDirectiveName'),
                    10,
                    4
                );
            }
        } else {
            App::addFilter(
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

    public function maybeFilterDirectiveName(bool $include, RelationalTypeResolverInterface $relationalTypeResolver, DirectiveResolverInterface $directiveResolver, string $directiveName): bool
    {
        // Because there may be several hooks chained, if any of them has already rejected the field, then already return that response
        if (!$include) {
            return false;
        }
        // Check if to remove the directive
        return !$this->removeDirective($relationalTypeResolver, $directiveResolver, $directiveName);
    }
    /**
     * Affected directives
     *
     * @return DirectiveResolverInterface[]
     */
    abstract protected function getDirectiveResolvers(): array;

    /**
     * Decide if to remove the directiveNames
     */
    protected function removeDirective(RelationalTypeResolverInterface $relationalTypeResolver, DirectiveResolverInterface $directiveResolver, string $directiveName): bool
    {
        return true;
    }
}
