<?php

declare(strict_types=1);

namespace GatoGraphQL\GatoGraphQL\ConditionalOnContext\PROPluginInformation\ModuleResolvers;

use GatoGraphQL\GatoGraphQL\Plugin;
use GatoGraphQL\GatoGraphQL\ContentProcessors\MarkdownContentParserInterface;
use GatoGraphQL\GatoGraphQL\ModuleResolvers\AbstractModuleResolver;
use GatoGraphQL\GatoGraphQL\ModuleResolvers\SchemaTypeModuleResolverTrait;

class SchemaTypeModuleResolver extends AbstractModuleResolver implements PROPseudoModuleResolverInterface
{
    use ModuleResolverTrait;
    use SchemaTypeModuleResolverTrait {
        SchemaTypeModuleResolverTrait::getPriority as getUpstreamPriority;
    }

    public final const INSPECT_HTTP_REQUEST_FIELDS = Plugin::NAMESPACE . '\inspect-http-request-fields';
    public final const SEND_HTTP_REQUEST_FIELDS = Plugin::NAMESPACE . '\send-http-request-fields';
    public final const ENVIRONMENT_FIELDS = Plugin::NAMESPACE . '\environment-fields';
    public final const FUNCTION_FIELDS = Plugin::NAMESPACE . '\function-fields';
    public final const HELPER_FIELDS = Plugin::NAMESPACE . '\helper-fields';

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
            self::INSPECT_HTTP_REQUEST_FIELDS,
            self::SEND_HTTP_REQUEST_FIELDS,
            self::ENVIRONMENT_FIELDS,
            self::FUNCTION_FIELDS,
            self::HELPER_FIELDS,
        ];
    }

    /**
     * The priority to display the modules from this resolver in the Modules page.
     * The higher the number, the earlier it shows
     */
    public function getPriority(): int
    {
        return $this->getUpstreamPriority() - 5;
    }

    public function getName(string $module): string
    {
        return match ($module) {
            self::INSPECT_HTTP_REQUEST_FIELDS => \__('Inspect HTTP Request Fields', 'gato-graphql'),
            self::SEND_HTTP_REQUEST_FIELDS => \__('Send HTTP Request Fields', 'gato-graphql'),
            self::ENVIRONMENT_FIELDS => \__('Environment Fields', 'gato-graphql'),
            self::FUNCTION_FIELDS => \__('Function Fields', 'gato-graphql'),
            self::HELPER_FIELDS => \__('Helper Fields', 'gato-graphql'),
            default => $module,
        };
    }

    public function getDescription(string $module): string
    {
        return match ($module) {
            self::INSPECT_HTTP_REQUEST_FIELDS => \__('Addition of fields to retrieve the current HTTP request data', 'gato-graphql'),
            self::SEND_HTTP_REQUEST_FIELDS => \__('Addition of fields to execute HTTP requests against a webserver and fetch their response', 'gato-graphql'),
            self::ENVIRONMENT_FIELDS => \__('Query the value from an environment variable or PHP constant', 'gato-graphql'),
            self::FUNCTION_FIELDS => \__('Manipulate the field output using standard programming language functions (provided via special fields)', 'gato-graphql'),
            self::HELPER_FIELDS => \__('Collection of fields providing useful functionality concerning URLs, Date formatting, etc', 'gato-graphql'),
            default => parent::getDescription($module),
        };
    }
}
