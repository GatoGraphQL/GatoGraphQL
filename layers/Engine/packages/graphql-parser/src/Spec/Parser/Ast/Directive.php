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

    protected function doAsASTNodeString(): string
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

    /**
     * Indicate if a field equals another one based on its properties,
     * not on its object hash ID.
     */
    public function equalsTo(Directive $directive): bool
    {
        if ($this->getName() !== $directive->getName()) {
            return false;
        }

        /**
         * Compare arguments
         */
        $thisArguments = $this->getArguments();
        $againstArguments = $directive->getArguments();
        $argumentCount = count($thisArguments);
        if ($argumentCount !== count($againstArguments)) {
            return false;
        }

        /**
         * The order of the arguments does not matter.
         * These 2 fields are equivalent:
         * 
         *   ```
         *   {
         *     id @translate(from: "en", to: "es")
         *     id @translate(to: "es", from: "en")
         *   }
         *   ```
         * 
         * So first sort them as to compare apples to apples.
         */
        usort($thisArguments, fn (Argument $argument1, Argument $argument2): int => $argument1->getName() <=> $argument2->getName());
        usort($againstArguments, fn (Argument $argument1, Argument $argument2): int => $argument1->getName() <=> $argument2->getName());
        for ($i = 0; $i < $argumentCount; $i++) {
            $thisArgument = $thisArguments[$i];
            $againstArgument = $againstArguments[$i];
            if (!$thisArgument->equalsTo($againstArgument)) {
                return false;
            }
        }

        return true;
    }
}
