<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Spec\Parser\Ast;

use PoP\GraphQLParser\Spec\Parser\Location;

class RelationalField extends AbstractField implements WithFieldsOrFragmentBondsInterface
{
    use WithFieldsOrFragmentBondsTrait;

    /**
     * @param Argument[] $arguments
     * @param FieldInterface[]|FragmentBondInterface[] $fieldsOrFragmentBonds
     * @param Directive[] $directives
     */
    public function __construct(
        string $name,
        ?string $alias,
        array $arguments,
        array $fieldsOrFragmentBonds,
        array $directives,
        Location $location,
    ) {
        parent::__construct($name, $alias, $arguments, $directives, $location);
        $this->setFieldsOrFragmentBonds($fieldsOrFragmentBonds);
    }

    protected function doAsQueryString(): string
    {
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
            '%s {%s}',
            parent::doAsQueryString(),
            $strFieldFieldsOrFragmentBonds,
        );
    }
}
