<?php

declare(strict_types=1);

namespace GraphQLAPI\GraphQLAPI\ConditionalOnContext\PROPluginInformation\ModuleResolvers;

use GraphQLAPI\GraphQLAPI\Plugin;
use GraphQLAPI\GraphQLAPI\ContentProcessors\MarkdownContentParserInterface;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\AbstractModuleResolver;
use GraphQLAPI\GraphQLAPI\ModuleResolvers\SchemaTypeModuleResolverTrait;

class SchemaTypeModuleResolver extends AbstractModuleResolver implements PROPseudoModuleResolverInterface
{
    use ModuleResolverTrait;
    use SchemaTypeModuleResolverTrait {
        SchemaTypeModuleResolverTrait::getPriority as getUpstreamPriority;
    }

    public final const SEND_HTTP_REQUEST_FIELDS = Plugin::NAMESPACE . '\send-http-request-fields';
    public final const ENVIRONMENT_FIELDS = Plugin::NAMESPACE . '\environment-fields';
    public final const FUNCTION_FIELDS = Plugin::NAMESPACE . '\function-fields';

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
            self::SEND_HTTP_REQUEST_FIELDS,
            self::ENVIRONMENT_FIELDS,
            self::FUNCTION_FIELDS,
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
            self::SEND_HTTP_REQUEST_FIELDS => \__('Send HTTP Request Fields', 'graphql-api'),
            self::ENVIRONMENT_FIELDS => \__('Environment Fields', 'graphql-api'),
            self::FUNCTION_FIELDS => \__('Function Fields', 'graphql-api'),
            default => $module,
        };
    }

    public function getDescription(string $module): string
    {
        return match ($module) {
            self::SEND_HTTP_REQUEST_FIELDS => \__('Addition of fields to execute HTTP requests against a webserver and fetch their response', 'graphql-api'),
            self::ENVIRONMENT_FIELDS => \__('Query the value from an environment variable or PHP constant', 'graphql-api'),
            self::FUNCTION_FIELDS => \__('Manipulate the field output using standard programming language functions (provided via special fields)', 'graphql-api'),
            default => parent::getDescription($module),
        };
    }
}
