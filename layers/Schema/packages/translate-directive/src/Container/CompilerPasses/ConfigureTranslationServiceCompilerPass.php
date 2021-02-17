<?php

declare(strict_types=1);

namespace PoPSchema\TranslateDirective\Container\CompilerPasses;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use PoPSchema\TranslateDirective\Environment;
use PoPSchema\TranslateDirective\Translation\TranslationServiceInterface;

class ConfigureTranslationServiceCompilerPass implements CompilerPassInterface
{
    /**
     * GraphQL persisted query for Introspection query
     */
    public function process(ContainerBuilder $containerBuilder): void
    {
        // If there is a default translation provider, inject it into the service
        if ($defaultTranslationProvider = Environment::getDefaultTranslationProvider()) {
            $translationServiceDefinition = $containerBuilder->getDefinition(TranslationServiceInterface::class);
            $translationServiceDefinition->addMethodCall(
                'setDefaultProvider',
                [
                    $defaultTranslationProvider
                ]
            );
        }
    }
}
