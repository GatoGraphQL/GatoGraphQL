<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Spec\Parser\Ast;

use PoP\GraphQLParser\ExtendedSpec\Parser\Ast\MetaDirective;
use PoP\GraphQLParser\Spec\Parser\Location;

abstract class AbstractField extends AbstractAst implements FieldInterface, WithNameInterface, WithArgumentsInterface
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
        // Generate the string for arguments
        $strFieldArguments = '';
        if ($this->getArguments() !== []) {
            $strArguments = [];
            foreach ($this->getArguments() as $argument) {
                $strArguments[] = $argument->asASTNodeString();
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

        /**
         * @todo Temporarily calling ->asQueryString, must work with AST directly!
         */
        $strFieldDirectives = $this->getDirectivesQueryString($this->getDirectives());
        return sprintf(
            '%s%s%s%s',
            $this->getAlias() !== null ? sprintf('%s: ', $this->getAlias()) : '',
            $this->getName(),
            $strFieldArguments,
            $strFieldDirectives,
        );
        /** @phpstan-ignore-next-line */
        return sprintf(
            '%s%s%s',
            $this->getAlias() !== null ? sprintf('%s: ', $this->getAlias()) : '',
            $this->getName(),
            $strFieldArguments,
        );
    }

    /**
     * @todo Temporarily calling ->asQueryString, must work with AST directly!
     * @todo Completely remove this function!!!!
     * @param Directive[] $directives
     */
    private function getDirectivesQueryString(array $directives): string
    {
        $strFieldDirectives = '';
        if ($directives !== []) {
            $directiveQueryStrings = [];
            foreach ($directives as $directive) {
                // Remove the initial "@"
                $directiveQueryString = substr($directive->asQueryString(), 1);
                if ($directive instanceof MetaDirective) {
                    /** @var MetaDirective */
                    $metaDirective = $directive;
                    $directiveQueryString .= $this->getDirectivesQueryString($metaDirective->getNestedDirectives());
                }
                $directiveQueryStrings[] = $directiveQueryString;
            }
            $strFieldDirectives .= '<' . implode(', ', $directiveQueryStrings) . '>';
        }
        return $strFieldDirectives;
    }
}
