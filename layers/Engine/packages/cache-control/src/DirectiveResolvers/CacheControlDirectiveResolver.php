<?php

declare(strict_types=1);

namespace PoP\CacheControl\DirectiveResolvers;

use PoP\Root\App;
use PoP\CacheControl\Module;
use PoP\CacheControl\ModuleConfiguration;
use PoP\ComponentModel\Container\ServiceTags\MandatoryDirectiveServiceTagInterface;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;

final class CacheControlFieldDirectiveResolver extends AbstractCacheControlFieldDirectiveResolver implements MandatoryDirectiveServiceTagInterface
{
    /**
     * It must execute after everyone else!
     */
    public function getPriorityToAttachToClasses(): int
    {
        return 0;
    }

    /**
     * Do add this directive to the schema
     */
    public function skipExposingDirectiveInSchema(RelationalTypeResolverInterface $relationalTypeResolver): bool
    {
        return false;
    }

    /**
     * The default max-age is configured through an environment variable
     */
    public function getMaxAge(): ?int
    {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        return $moduleConfiguration->getDefaultCacheControlMaxAge();
    }
}
