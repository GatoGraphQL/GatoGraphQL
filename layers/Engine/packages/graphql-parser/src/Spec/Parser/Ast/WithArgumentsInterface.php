<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Spec\Parser\Ast;

interface WithArgumentsInterface extends AstInterface
{
    public function hasArguments(): bool;

    public function hasArgument(string $name): bool;

    /**
     * @return Argument[]
     */
    public function getArguments(): array;

    public function getArgument(string $name): ?Argument;

    public function getArgumentValue(string $name): mixed;

    /**
     * @return array<string,mixed>
     */
    public function getArgumentKeyValues(): array;

    public function hasArgumentReferencingPromise(): bool;
}
