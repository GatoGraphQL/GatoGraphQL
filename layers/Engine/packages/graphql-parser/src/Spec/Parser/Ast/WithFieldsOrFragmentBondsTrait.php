<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Spec\Parser\Ast;

trait WithFieldsOrFragmentBondsTrait
{
    /** @var array<FieldInterface|FragmentBondInterface> */
    protected array $fieldsOrFragmentBonds;

    /**
     * @return array<FieldInterface|FragmentBondInterface>
     */
    public function getFieldsOrFragmentBonds(): array
    {
        return $this->fieldsOrFragmentBonds;
    }

    /**
     * @param array<FieldInterface|FragmentBondInterface> $fieldsOrFragmentBonds
     */
    private function setFieldsOrFragmentBonds(array $fieldsOrFragmentBonds): void
    {
        $this->fieldsOrFragmentBonds = $fieldsOrFragmentBonds;
    }
}
