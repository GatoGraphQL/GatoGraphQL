<?php

declare(strict_types=1);

namespace PoP\ComponentModel\DirectiveResolvers;

use PoP\GraphQLParser\Spec\Parser\Ast\Directive;

interface DirectiveResolverInterface
{
    public function getDirectiveName(): string;

    public function getDirective(): Directive;

    /**
     * Set the Directive to be resolved by the DirectiveResolver.
     */
    public function setDirective(
        Directive $directive,
    ): void;

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
    public function isDirectiveEnabled(): bool;

    /**
     * Directives can be either of type "Schema" or "Query" and,
     * depending on one case or the other, might be exposed to the user.
     * By default, use the Query type
     */
    public function getDirectiveKind(): string;

    /**
     * @return string[]
     */
    public function getDirectiveLocations(): array;

    /**
     * Indicates if the directive can be added several times to the pipeline, or only once
     */
    public function isRepeatable(): bool;
}
