<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Spec\Parser\Ast;

use PoP\GraphQLParser\Spec\Parser\Location;

abstract class AbstractOperation extends AbstractAst implements OperationInterface
{
    use WithDirectivesTrait;
    use WithFieldsOrFragmentBondsTrait;

    /**
     * @param Variable[] $variables
     * @param Directive[] $directives
     * @param array<FieldInterface|FragmentBondInterface> $fieldsOrFragmentBonds
     */
    public function __construct(
        protected readonly string $name,
        protected readonly array $variables,
        array $directives,
        array $fieldsOrFragmentBonds,
        Location $location,
    ) {
        parent::__construct($location);
        $this->setDirectives($directives);
        $this->setFieldsOrFragmentBonds($fieldsOrFragmentBonds);
    }

    protected function doAsQueryString(): string
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

    protected function doAsASTNodeString(): string
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

        $operationDefinition = sprintf(
            '%s%s',
            $this->name,
            $strOperationVariables,
        );
        return sprintf(
            '%s%s%s { ... }',
            $this->getOperationType(),
            /** @phpstan-ignore-next-line */
            $operationDefinition !== '' ? ' ' . $operationDefinition : '',
            $strOperationDirectives,
        );
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
