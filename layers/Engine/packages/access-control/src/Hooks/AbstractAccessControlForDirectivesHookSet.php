<?php

declare(strict_types=1);

namespace PoP\AccessControl\Hooks;

use PoP\Root\App;
use PoP\AccessControl\Services\AccessControlManagerInterface;
use PoP\ComponentModel\DirectiveResolvers\FieldDirectiveResolverInterface;
use PoP\ComponentModel\TypeResolvers\HookHelpers;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\Root\Hooks\AbstractAfterAppBootHookSet;

abstract class AbstractAccessControlForDirectivesHookSet extends AbstractAfterAppBootHookSet
{
    private ?AccessControlManagerInterface $accessControlManager = null;

    final protected function getAccessControlManager(): AccessControlManagerInterface
    {
        if ($this->accessControlManager === null) {
            /** @var AccessControlManagerInterface */
            $accessControlManager = $this->instanceManager->getInstance(AccessControlManagerInterface::class);
            $this->accessControlManager = $accessControlManager;
        }
        return $this->accessControlManager;
    }

    public function cmsBoot(): void
    {
        if (!$this->enabled()) {
            return;
        }
        // If no directiveNames defined, apply to all of them
        if (
            $directiveNames = array_map(
                fn (FieldDirectiveResolverInterface $directiveResolver) => $directiveResolver->getDirectiveName(),
                $this->getFieldDirectiveResolvers()
            )
        ) {
            /** @var string[] $directiveNames */
            foreach ($directiveNames as $directiveName) {
                App::addFilter(
                    HookHelpers::getHookNameToFilterDirective($directiveName),
                    $this->maybeFilterDirectiveName(...),
                    10,
                    4
                );
            }
        } else {
            App::addFilter(
                HookHelpers::getHookNameToFilterDirective(),
                $this->maybeFilterDirectiveName(...),
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

    public function maybeFilterDirectiveName(bool $include, RelationalTypeResolverInterface $relationalTypeResolver, FieldDirectiveResolverInterface $directiveResolver, string $directiveName): bool
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
     * @return FieldDirectiveResolverInterface[]
     */
    abstract protected function getFieldDirectiveResolvers(): array;

    /**
     * Decide if to remove the directiveNames
     */
    protected function removeDirective(RelationalTypeResolverInterface $relationalTypeResolver, FieldDirectiveResolverInterface $directiveResolver, string $directiveName): bool
    {
        return true;
    }
}
