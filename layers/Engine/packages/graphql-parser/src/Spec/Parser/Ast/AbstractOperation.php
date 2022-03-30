<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Spec\Parser\Ast;

use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\Variable;
use PoP\GraphQLParser\Spec\Parser\Location;

abstract class AbstractOperation extends AbstractAst implements OperationInterface
{
    use WithDirectivesTrait;
    use WithFieldsOrFragmentBondsTrait;

    protected Document $parent;

    public function __construct(
        protected string $name,
        /** @var Variable[] */
        protected array $variables,
        /** @var Directive[] $directives */
        array $directives,
        /** @var FieldInterface[]|FragmentBondInterface[] */
        array $fieldsOrFragmentBonds,
        Location $location,
    ) {
        parent::__construct($location);
        $this->setDirectives($directives);
        $this->setFieldsOrFragmentBonds($fieldsOrFragmentBonds);
    }

    public function asQueryString(): string
    {
        // Generate the string for variables
        $strOperationVariables = '';
        if ($this->variables !== []) {
            $strVariables = [];
            foreach ($this->variables as $variable) {
                $strVariables[] = $variable->asQueryString();
            }
            $strOperationVariables = sprintf(
                '(%s)',
                implode(', ', $strVariables)
            );
        }

        // Generate the string for directives
        $strOperationDirectives = '';
        if ($this->directives !== []) {
            $strDirectives = [];
            foreach ($this->directives as $directive) {
                $strDirectives[] = $directive->asQueryString();
            }
            $strOperationDirectives = sprintf(
                ' %s',
                implode(' ', $strDirectives)
            );
        }

        // Generate the string for the body of the operation
        $strOperationFieldsOrFragmentBonds = '';
        if ($this->fieldsOrFragmentBonds !== []) {
            $strFieldsOrFragmentBonds = [];
            foreach ($this->fieldsOrFragmentBonds as $fieldsOrFragmentBond) {
                $strFieldsOrFragmentBonds[] = $fieldsOrFragmentBond->asQueryString();
            }
            $strOperationFieldsOrFragmentBonds = sprintf(
                ' %s ',
                implode(' ', $strFieldsOrFragmentBonds)
            );
        }
        $operationDefinition = sprintf(
            '%s%s%s',
            $this->name,
            $strOperationVariables,
            $strOperationDirectives,
        );
        return sprintf(
            '%s%s{%s}',
            $this->getOperationType(),
            /** @phpstan-ignore-next-line */
            $operationDefinition !== '' ? sprintf(' %s ', $operationDefinition) : ' ',
            $strOperationFieldsOrFragmentBonds,
        );
    }

    public function setParent(Document $parent): void
    {
        $this->parent = $parent;
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

    /**
     * @param Fragment[] $fragments
     */
    protected function getFragment(array $fragments, string $fragmentName): ?Fragment
    {
        foreach ($fragments as $fragment) {
            if ($fragment->getName() === $fragmentName) {
                return $fragment;
            }
        }

        return null;
    }
}
