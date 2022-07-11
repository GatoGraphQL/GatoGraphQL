<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Spec\Parser\Ast;

use PoP\GraphQLParser\Spec\Parser\Location;

class Directive extends AbstractAst implements WithNameInterface, WithArgumentsInterface
{
    use WithArgumentsTrait;

    /**
     * @param Argument[] $arguments
     */
    public function __construct(
        protected readonly string $name,
        array $arguments,
        Location $location,
    ) {
        parent::__construct($location);
        $this->setArguments($arguments);
    }

    protected function doAsQueryString(): string
    {
        $strDirectiveArguments = '';
        if ($this->arguments !== []) {
            $strArguments = [];
            foreach ($this->arguments as $argument) {
                $strArguments[] = $argument->asQueryString();
            }
            $strDirectiveArguments = sprintf(
                '(%s)',
                implode(', ', $strArguments)
            );
        }
        return sprintf(
            '@%s%s',
            $this->name,
            $strDirectiveArguments
        );
    }

    public function getName(): string
    {
        return $this->name;
    }
}
