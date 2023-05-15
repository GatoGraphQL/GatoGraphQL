<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\ConditionalOnContext\PROPluginInformation\ModuleResolvers;

use GatoGraphQL\GatoGraphQL\ContentProcessors\MarkdownContentParserInterface;
use GatoGraphQL\GatoGraphQL\ModuleResolvers\AbstractModuleResolver;
use GatoGraphQL\GatoGraphQL\ModuleResolvers\SchemaConfigurationFunctionalityModuleResolver;
use GatoGraphQL\GatoGraphQL\ModuleResolvers\SchemaTypeModuleResolverTrait;
use GatoGraphQL\GatoGraphQL\Plugin;

class MutationSchemaTypeModuleResolver extends AbstractModuleResolver implements PROPseudoModuleResolverInterface
{
    use ModuleResolverTrait;
    use SchemaTypeModuleResolverTrait {
        SchemaTypeModuleResolverTrait::getPriority as getUpstreamPriority;
    }

    public final const EMAIL_SENDER = Plugin::NAMESPACE . '\email-sender';

    private ?MarkdownContentParserInterface $markdownContentParser = null;

    final public function setMarkdownContentParser(MarkdownContentParserInterface $markdownContentParser): void
    {
        $this->markdownContentParser = $markdownContentParser;
    }
    final protected function getMarkdownContentParser(): MarkdownContentParserInterface
    {
        /** @var MarkdownContentParserInterface */
        return $this->markdownContentParser ??= $this->instanceManager->getInstance(MarkdownContentParserInterface::class);
    }

    /**
     * @return string[]
     */
    public function getModulesToResolve(): array
    {
        return [
            self::EMAIL_SENDER,
        ];
    }

    /**
     * The priority to display the modules from this resolver in the Modules page.
     * The higher the number, the earlier it shows
     */
    public function getPriority(): int
    {
        return $this->getUpstreamPriority() - 7;
    }

    /**
     * @return array<string[]> List of entries that must be satisfied, each entry is an array where at least 1 module must be satisfied
     */
    public function getDependedModuleLists(string $module): array
    {
        switch ($module) {
            case self::EMAIL_SENDER:
                return [
                    [
                        SchemaConfigurationFunctionalityModuleResolver::MUTATIONS,
                    ],
                ];
        }
        return parent::getDependedModuleLists($module);
    }

    public function getName(string $module): string
    {
        return match ($module) {
            self::EMAIL_SENDER => \__('Email Sender', 'gato-graphql'),
            default => $module,
        };
    }

    public function getDescription(string $module): string
    {
        return match ($module) {
            self::EMAIL_SENDER => \__('Send emails via global mutation <code>_sendEmail</code>', 'gato-graphql'),
            default => parent::getDescription($module),
        };
    }
}
