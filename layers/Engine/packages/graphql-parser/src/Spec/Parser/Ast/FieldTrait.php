<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Spec\Parser\Ast;

use PoP\GraphQLParser\Spec\Parser\Location;

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

    /**
     * Print the field as string, and attach the location as a GraphQL comment
     */
    public function getUniqueID(): string
    {
        $location = $this->getLocation();
        $locationComment = ' # Location: ' . $location->getLine() . 'x' . $location->getColumn();
        return $this->asFieldOutputQueryString() . $locationComment;
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
    
    abstract public function getLocation(): Location;

    /**
     * @return Directive[]
     */
    abstract public function getDirectives(): array;

    /**
     * @return Argument[]
     */
    abstract public function getArguments(): array;

    /**
     * Take the Location into consideration to indicate
     * that 2 Fields are the same
     */
    public function __toString(): string
    {
        return $this->getUniqueID();
    }
}
