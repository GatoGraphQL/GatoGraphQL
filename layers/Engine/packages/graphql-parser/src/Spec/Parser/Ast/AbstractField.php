<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Spec\Parser\Ast;

use PoP\GraphQLParser\Spec\Parser\Location;
use PoP\GraphQLParser\ASTNodes\ASTNodesFactory;

abstract class AbstractField extends AbstractAst implements FieldInterface
{
    use WithArgumentsTrait;
    use WithDirectivesTrait;

    protected ?string $uniqueID = null;

    /**
     * @param Argument[] $arguments
     * @param Directive[] $directives
     */
    public function __construct(
        protected readonly string $name,
        protected readonly ?string $alias,
        array $arguments,
        array $directives,
        Location $location,
    ) {
        parent::__construct($location);
        $this->setArguments($arguments);
        $this->setDirectives($directives);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getAlias(): ?string
    {
        return $this->alias;
    }

    public function getOutputKey(): string
    {
        return $this->getAlias() ?? $this->getName();
    }

    /**
     * Take the Location into consideration to indicate
     * that 2 AST elements are the same.
     *
     * In this query:
     *
     *   { queryType { name } mutationType { name } }
     *
     * fields `name` are different fields (even if under the same type).
     */
    public function __toString(): string
    {
        return $this->getUniqueID();
    }

    /**
     * Print the field as string, and attach the location as a GraphQL comment
     */
    final public function getUniqueID(): string
    {
        if ($this->uniqueID === null) {
            $location = $this->getLocation();
            $locationComment = ' # Location: ' . $location->getLine() . 'x' . $location->getColumn();
            $this->uniqueID = $this->asFieldOutputQueryString() . $locationComment;
            if ($location === ASTNodesFactory::getNonSpecificLocation()) {
                $this->uniqueID .= ' #Hash: ' . spl_object_hash($this);
            }
        }
        return $this->uniqueID;
    }

    protected function doAsQueryString(): string
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

    protected function doAsASTNodeString(): string
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
            '%s%s%s%s',
            $this->getAlias() !== null ? sprintf('%s: ', $this->getAlias()) : '',
            $this->getName(),
            $strFieldArguments,
            $strFieldDirectives,
        );
    }

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
     * Indicate if a field equals another one based on its properties,
     * not on its object hash ID.
     *
     * Watch out: `{ title: title }` is equivalent to `{ title }`
     */
    public function isEquivalentTo(FieldInterface $field): bool
    {
        $thisQueryString = $this->asQueryString();
        $againstQueryString = $field->asQueryString();

        /**
         * If the alias is the same as the name then remove it,
         * as to have `{ title: title }` equivalent to `{ title }`
         */
        if ($this->getName() === $this->getAlias()) {
            $thisQueryString = substr(
                $thisQueryString, 
                strlen(
                    sprintf('%s: ', $this->getAlias())
                )
            );
        }
        if ($field->getName() === $field->getAlias()) {
            $againstQueryString = substr(
                $againstQueryString, 
                strlen(
                    sprintf('%s: ', $field->getAlias())
                )
            );
        }
        return $thisQueryString === $againstQueryString;
    }
}
