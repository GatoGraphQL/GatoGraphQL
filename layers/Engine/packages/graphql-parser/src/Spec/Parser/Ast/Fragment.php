<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Spec\Parser\Ast;

use PoP\GraphQLParser\Spec\Parser\Location;

class Fragment extends AbstractAst implements WithDirectivesInterface, WithFieldsOrFragmentBondsInterface
{
    use WithDirectivesTrait;
    use WithFieldsOrFragmentBondsTrait;

    /**
     * @param Directive[] $directives
     * @param array<FieldInterface|FragmentBondInterface> $fieldsOrFragmentBonds
     */
    public function __construct(
        protected readonly string $name,
        protected readonly string $model,
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
        // Generate the string for directives
        $strFragmentDirectives = '';
        if ($this->directives !== []) {
            $strDirectives = [];
            foreach ($this->directives as $directive) {
                $strDirectives[] = $directive->asQueryString();
            }
            $strFragmentDirectives = sprintf(
                ' %s',
                implode(' ', $strDirectives)
            );
        }

        // Generate the string for the body of the fragment
        $strFragmentFieldsOrFragmentBonds = '';
        if ($this->fieldsOrFragmentBonds !== []) {
            $strFieldsOrFragmentBonds = [];
            foreach ($this->fieldsOrFragmentBonds as $fieldsOrFragmentBond) {
                $strFieldsOrFragmentBonds[] = $fieldsOrFragmentBond->asQueryString();
            }
            $strFragmentFieldsOrFragmentBonds = sprintf(
                ' %s ',
                implode(' ', $strFieldsOrFragmentBonds)
            );
        }
        return sprintf(
            'fragment %s on %s%s {%s}',
            $this->name,
            $this->model,
            $strFragmentDirectives,
            $strFragmentFieldsOrFragmentBonds,
        );
    }

    protected function doAsASTNodeString(): string
    {
        return sprintf(
            'fragment %s on %s { ... }',
            $this->name,
            $this->model,
        );
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getModel(): string
    {
        return $this->model;
    }
}
