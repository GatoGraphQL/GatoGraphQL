<?php

declare(strict_types=1);

namespace PoPBackbone\GraphQLParser\Parser\Ast;

use PoPBackbone\GraphQLParser\Parser\Location;

abstract class AbstractOperation extends AbstractAst implements OperationInterface
{
    use AstDirectivesTrait;

    /**
     * @param Variable[] $variables
     * @param Directive[] $directives
     */
    public function __construct(
        protected string $name,
        protected array $variables,
        array $directives,
        Location $location,
    ) {
        parent::__construct($location);
        $this->setDirectives($directives);
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return Variable[]
     */
    public function getVariables(): array
    {
        return $this->variables;
    }
}
