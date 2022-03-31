<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Spec\Parser\Ast;

use PoP\GraphQLParser\Spec\Parser\Location;

class RelationalField extends AbstractAst implements FieldInterface, WithFieldsOrFragmentBondsInterface
{
    use WithArgumentsTrait;
    use WithDirectivesTrait;
    use WithFieldsOrFragmentBondsTrait;

    protected RelationalField|Fragment|InlineFragment|OperationInterface $parent;

    /**
     * @param Argument[] $arguments
     * @param FieldInterface[]|FragmentBondInterface[] $fieldsOrFragmentBonds
     * @param Directive[] $directives
     */
    public function __construct(
        protected string $name,
        protected ?string $alias,
        array $arguments,
        array $fieldsOrFragmentBonds,
        array $directives,
        Location $location,
    ) {
        parent::__construct($location);
        $this->setFieldsOrFragmentBonds($fieldsOrFragmentBonds);
        $this->setArguments($arguments);
        $this->setDirectives($directives);
    }

    public function asQueryString(): string
    {
        // Generate the string for arguments
        $strFieldArguments = '';
        if ($this->arguments !== []) {
            $strArguments = [];
            foreach ($this->arguments as $argument) {
                $strArguments[] = $argument->asQueryString();
            }
            $strFieldArguments = sprintf(
                '(%s)',
                implode(', ', $strArguments)
            );
        }

        // Generate the string for directives
        $strFieldDirectives = '';
        if ($this->directives !== []) {
            $strDirectives = [];
            foreach ($this->directives as $directive) {
                $strDirectives[] = $directive->asQueryString();
            }
            $strFieldDirectives = sprintf(
                ' %s',
                implode(' ', $strDirectives)
            );
        }

        // Generate the string for the body of the operation
        $strFieldFieldsOrFragmentBonds = '';
        if ($this->fieldsOrFragmentBonds !== []) {
            $strFieldsOrFragmentBonds = [];
            foreach ($this->fieldsOrFragmentBonds as $fieldsOrFragmentBond) {
                $strFieldsOrFragmentBonds[] = $fieldsOrFragmentBond->asQueryString();
            }
            $strFieldFieldsOrFragmentBonds = sprintf(
                ' %s ',
                implode(' ', $strFieldsOrFragmentBonds)
            );
        }
        return sprintf(
            '%s%s%s%s {%s}',
            $this->alias !== null ? sprintf('%s: ', $this->alias) : '',
            $this->name,
            $strFieldArguments,
            $strFieldDirectives,
            $strFieldFieldsOrFragmentBonds,
        );
    }

    public function setParent(RelationalField|Fragment|InlineFragment|OperationInterface $parent): void
    {
        $this->parent = $parent;
    }

    public function getParent(): RelationalField|Fragment|InlineFragment|OperationInterface
    {
        return $this->parent;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getAlias(): ?string
    {
        return $this->alias;
    }
}
