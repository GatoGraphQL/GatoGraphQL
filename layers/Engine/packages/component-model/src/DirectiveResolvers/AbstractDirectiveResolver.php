<?php

declare(strict_types=1);

namespace PoP\ComponentModel\DirectiveResolvers;

use PoP\ComponentModel\Directives\DirectiveKinds;
use PoP\GraphQLParser\ASTNodes\ASTNodesFactory;
use PoP\GraphQLParser\Spec\Parser\Ast\Directive;
use PoP\Root\Services\BasicServiceTrait;

/**
 * Top most ancestor class on the hierarchy of the
 * Directive Resolver classes.
 *
 * This hierarchy, in theory, allows the creation of
 * resolvers for all types of directives defined by the
 * GraphQL spec:
 *
 * - QueryOperationDirectiveResolver
 * - MutationOperationDirectiveResolver
 * - SubscriptionOperationDirectiveResolver
 *
 * However, in practice, only FieldDirectives are supported
 * by the GraphQL server, via the directive pipeline.
 *
 * It is through FieldDirectives that functionality for
 * Operation Directives is also supported.
 *
 * @see AbstractFieldDirectiveResolver
 */
abstract class AbstractDirectiveResolver implements DirectiveResolverInterface
{
    use BasicServiceTrait;

    protected Directive $directive;

    /**
     * The directiveResolvers are instantiated through the service container,
     * but NOT for the directivePipeline, since there each directiveResolver
     * will require the actual $directive to process.
     *
     * By default, the directive is directly the directive name.
     * This is what is used when instantiating the directive through the container.
     */
    public function __construct()
    {
        $this->directive = new Directive(
            $this->getDirectiveName(),
            [],
            ASTNodesFactory::getNonSpecificLocation()
        );
    }

    /**
     * Invoked when creating the non-shared directive instance
     * to resolve a directive in the pipeline
     */
    final public function setDirective(Directive $directive): void
    {
        $this->directive = $directive;
    }

    public function getDirective(): Directive
    {
        return $this->directive;
    }

    /**
     * Directives can be either of type "Schema" or "Query" and,
     * depending on one case or the other, might be exposed to the user.
     * By default, use the Query type
     */
    public function getDirectiveKind(): string
    {
        return DirectiveKinds::QUERY;
    }

    /**
     * ModuleConfiguration values cannot be accessed in `isServiceEnabled`,
     * because the DirectiveResolver services are initialized on
     * the "boot" event, and by then the `SchemaConfigurationExecuter`
     * services, to set-up configuration hooks, have not been initialized yet.
     * Then, the GraphQL custom endpoint will not have its Schema Configuration
     * applied.
     *
     * That's why it is done in this method instead.
     *
     * @see BootAttachExtensionCompilerPass.php
     */
    public function isDirectiveEnabled(): bool
    {
        return true;
    }

    /**
     * By default, a directive can be executed only one time for "Schema" and "System"
     * type directives (eg: <translate(en,es),translate(es,en)>),
     * and many times for the other types, "Query", "Scripting" and "Indexing"
     */
    public function isRepeatable(): bool
    {
        return !($this->getDirectiveKind() === DirectiveKinds::SYSTEM || $this->getDirectiveKind() === DirectiveKinds::SCHEMA);
    }
}
