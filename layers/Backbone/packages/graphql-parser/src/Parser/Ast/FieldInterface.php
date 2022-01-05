<?php

declare(strict_types=1);

namespace PoPBackbone\GraphQLParser\Parser\Ast;

interface FieldInterface extends LocatableInterface, WithDirectivesInterface
{
    public function getName(): string;

    public function getAlias(): ?string;

    /**
     * @return Argument[]
     */
    public function getArguments(): array;

    public function getArgument(string $name): ?Argument;
}
