<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Spec\Parser\Ast;

interface FieldInterface extends AstInterface, LocatableInterface, WithDirectivesInterface
{
    public function getName(): string;

    public function getAlias(): ?string;

    /**
     * @return Argument[]
     */
    public function getArguments(): array;

    public function getArgument(string $name): ?Argument;

    public function setParent(RelationalField|Fragment|InlineFragment|OperationInterface $parent): void;
    
    public function getParent(): RelationalField|Fragment|InlineFragment|OperationInterface;
}
