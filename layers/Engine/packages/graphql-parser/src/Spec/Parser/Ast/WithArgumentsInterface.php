<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Spec\Parser\Ast;

interface WithArgumentsInterface
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
     * @internal Method used by the Engine to add default arguments. Don't call otherwise!
     */
    public function addArgument(Argument $argument): void;

    /**
     * @return array<string,mixed>
     */
    public function getKeyValueArguments(): array;
}
