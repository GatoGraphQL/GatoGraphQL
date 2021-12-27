<?php

declare(strict_types=1);

namespace PoPBackbone\GraphQLParser\Parser\Ast;

trait WithFieldsOrFragmentBondsTrait
{
    /** @var FieldInterface[]|FragmentBondInterface[] */
    protected array $fieldsOrFragmentBonds;    

    /**
     * @return FieldInterface[]|FragmentBondInterface[]
     */
    public function getFieldsOrFragmentBonds(): array
    {
        return $this->fieldsOrFragmentBonds;
    }
    
    /**
     * @param FieldInterface[]|FragmentBondInterface[] $fieldOrFragmentBonds
     */
    public function setFieldsOrFragmentBonds(array $fieldsOrFragmentBonds): void
    {
        $this->fieldsOrFragmentBonds = $fieldsOrFragmentBonds;
    }
}
