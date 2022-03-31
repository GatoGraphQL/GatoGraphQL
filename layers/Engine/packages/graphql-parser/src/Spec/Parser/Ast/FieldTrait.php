<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Spec\Parser\Ast;

trait FieldTrait
{
    public function asFieldOutputQueryString(): string
    {
        // Generate the string for arguments
        $strFieldArguments = '';
        if ($this->getArguments() !== []) {
            $strArguments = [];
            foreach ($this->getArguments() as $argument) {
                $strArguments[] = $argument->asQueryString();
            }
            $strFieldArguments = sprintf(
                '(%s)',
                implode(', ', $strArguments)
            );
        }

        return sprintf(
            '%s%s%s',
            $this->getAlias() !== null ? sprintf('%s: ', $this->getAlias()) : '',
            $this->getName(),
            $strFieldArguments,
        );
    }

    public function asQueryString(): string
    {
        // Generate the string for directives
        $strFieldDirectives = '';
        if ($this->getDirectives() !== []) {
            $strDirectives = [];
            foreach ($this->getDirectives() as $directive) {
                $strDirectives[] = $directive->asQueryString();
            }
            $strFieldDirectives = sprintf(
                ' %s',
                implode(' ', $strDirectives)
            );
        }
        
        return sprintf(
            '%s%s',
            $this->asFieldOutputQueryString(),
            $strFieldDirectives,
        );
    }

    abstract public function getName(): string;

    abstract public function getAlias(): ?string;

    /**
     * @return Directive[]
     */
    abstract public function getDirectives(): array;

    /**
     * @return Argument[]
     */
    abstract public function getArguments(): array;
}
