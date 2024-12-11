<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Spec\Parser\Ast;

use PoP\GraphQLParser\Spec\Parser\Ast\Directive;
use PoP\GraphQLParser\Spec\Parser\Location;

abstract class AbstractField extends AbstractAst implements FieldInterface
{
    use WithArgumentsTrait;
    use WithDirectivesTrait;

    protected ?string $uniqueID = null;
    protected ?string $fieldOutputQueryString = null;

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
            $objectHash = ' #Hash: ' . spl_object_hash($this);
            /**
             * Watch out! Use ->getName() instead of ->asFieldOutputQueryString()
             * as the latter may be a very expensive operation (eg: when printing
             * a very big JSON)
             */
            $this->uniqueID = $this->getName() . $locationComment . $objectHash;
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

    final public function asFieldOutputQueryString(): string
    {
        if ($this->fieldOutputQueryString === null) {
            $this->fieldOutputQueryString = $this->doAsFieldOutputQueryString();
        }
        return $this->fieldOutputQueryString;
    }

    protected function doAsFieldOutputQueryString(): string
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
     *
     * @see https://spec.graphql.org/draft/#sec-Field-Selection-Merging
     */
    protected function doIsEquivalentTo(AbstractField $field): bool
    {
        if ($this->getName() !== $field->getName()) {
            return false;
        }

        if ($this->getAlias() !== $field->getAlias()) {
            /**
             * If the alias is the same as the name then can skip,
             * as to have `{ title: title }` equivalent to `{ title }`
             */
            if (
                !(($this->getName() === $this->getAlias() && $field->getAlias() === null)
                || ($field->getName() === $field->getAlias() && $this->getAlias() === null)
                )
            ) {
                return false;
            }
        }

        /**
         * Compare arguments
         */
        $thisArguments = $this->getArguments();
        $againstArguments = $field->getArguments();
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
         *     dateStr(format: "d/m", gmt: true)
         *     dateStr(gmt: true, format: "d/m")
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
            if (!$thisArgument->isEquivalentTo($againstArgument)) {
                return false;
            }
        }

        /**
         * The order of the directives does matter.
         * These 2 fields are not equivalent:
         *
         *   ```
         *   {
         *     id @strUpperCase @titleCase
         *     id @titleCase @strUpperCase
         *   }
         *   ```
         */
        $thisDirectives = $this->getDirectives();
        $againstDirectives = $field->getDirectives();
        $directiveCount = count($thisDirectives);
        if ($directiveCount !== count($againstDirectives)) {
            return false;
        }
        for ($i = 0; $i < $directiveCount; $i++) {
            $thisDirective = $thisDirectives[$i];
            $againstDirective = $againstDirectives[$i];
            if (!$thisDirective->isEquivalentTo($againstDirective)) {
                return false;
            }
        }

        return true;
    }
}
