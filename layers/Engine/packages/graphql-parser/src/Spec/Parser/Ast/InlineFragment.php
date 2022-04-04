<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Spec\Parser\Ast;

use PoP\GraphQLParser\Spec\Parser\Location;

class InlineFragment extends AbstractAst implements FragmentBondInterface, WithDirectivesInterface, WithFieldsOrFragmentBondsInterface
{
    use WithDirectivesTrait;
    use WithFieldsOrFragmentBondsTrait;

    protected RelationalField|Fragment|InlineFragment|OperationInterface $parent;

    /**
     * @param FieldInterface[]|FragmentBondInterface[] $fieldsOrFragmentBonds
     * @param Directive[] $directives
     */
    public function __construct(
        protected readonly string $typeName,
        array $fieldsOrFragmentBonds,
        array $directives,
        Location $location,
    ) {
        parent::__construct($location);
        $this->setDirectives($directives);
        $this->setFieldsOrFragmentBonds($fieldsOrFragmentBonds);
    }

    public function asQueryString(): string
    {
        // Generate the string for directives
        $strInlineFragmentDirectives = '';
        if ($this->directives !== []) {
            $strDirectives = [];
            foreach ($this->directives as $directive) {
                $strDirectives[] = $directive->asQueryString();
            }
            $strInlineFragmentDirectives = sprintf(
                ' %s',
                implode(' ', $strDirectives)
            );
        }

        // Generate the string for the body of the fragment
        $strInlineFragmentFieldsOrFragmentBonds = '';
        if ($this->fieldsOrFragmentBonds !== []) {
            $strFieldsOrFragmentBonds = [];
            foreach ($this->fieldsOrFragmentBonds as $fieldsOrFragmentBond) {
                $strFieldsOrFragmentBonds[] = $fieldsOrFragmentBond->asQueryString();
            }
            $strInlineFragmentFieldsOrFragmentBonds = sprintf(
                ' %s ',
                implode(' ', $strFieldsOrFragmentBonds)
            );
        }
        return sprintf(
            '...on %s%s {%s}',
            $this->typeName,
            $strInlineFragmentDirectives,
            $strInlineFragmentFieldsOrFragmentBonds,
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

    public function getTypeName(): string
    {
        return $this->typeName;
    }
}
