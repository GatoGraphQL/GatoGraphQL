<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Spec\Parser\Ast;

use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\Fragment;
use PoP\GraphQLParser\Spec\Parser\Ast\FragmentBondInterface;
use PoP\GraphQLParser\Spec\Parser\Location;

class RelationalField extends AbstractField implements WithFieldsOrFragmentBondsInterface
{
    use WithFieldsOrFragmentBondsTrait;

    /**
     * @param Argument[] $arguments
     * @param array<FieldInterface|FragmentBondInterface> $fieldsOrFragmentBonds
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

    protected function doAsASTNodeString(): string
    {
        return sprintf(
            '%s { ... }',
            parent::doAsASTNodeString(),
        );
    }

    /**
     * Additionally validate that the contained fields
     * are all equivalent.
     *
     * @param Fragment[] $fragments
     */
    public function isEquivalentTo(RelationalField $relationalField, array $fragments): bool
    {
        if (!$this->doIsEquivalentTo($relationalField)) {
            return false;
        }
        
        $thisFields = $this->getAllFieldsFromFieldsOrFragmentBonds($this->getFieldsOrFragmentBonds(), $fragments);
        $againstFields = $this->getAllFieldsFromFieldsOrFragmentBonds($relationalField->getFieldsOrFragmentBonds(), $fragments);
        /**
         * The two relational fields are equivalent if all contained
         * fields have an equivalent on the opposite set
         *
         * Eg: these 2 fields are equivalent:
         *
         *   ```
         *   {
         *     posts {
         *       id
         *       title
         *     }
         *     
         *     posts {
         *       title
         *       id
         *       title:title()
         *     }
         *   }
         *   ```
         */
        foreach ($thisFields as $thisField) {
            $equivalentFieldsInOppositeSet = array_filter(
                $againstFields,
                fn (FieldInterface $oppositeField) => $this->isFieldEquivalentToField($thisField, $oppositeField, $fragments)
            );
            if ($equivalentFieldsInOppositeSet === []) {
                return false;
            }
        }
        foreach ($againstFields as $againstField) {
            $equivalentFieldsInOppositeSet = array_filter(
                $thisFields,
                fn (FieldInterface $oppositeField) => $this->isFieldEquivalentToField($againstField, $oppositeField, $fragments)
            );
            if ($equivalentFieldsInOppositeSet === []) {
                return false;
            }
        }

        return true;
    }
    
    /**
     * @param array<FieldInterface|FragmentBondInterface> $fieldsOrFragmentBonds
     * @param Fragment[] $fragments
     * @return FieldInterface[]
     */
    protected function getAllFieldsFromFieldsOrFragmentBonds(
        array $fieldsOrFragmentBonds,
        array $fragments,
    ): array {
        /** @var FieldInterface[] */
        $fields = [];
        foreach ($fieldsOrFragmentBonds as $fieldOrFragmentBond) {
            if ($fieldOrFragmentBond instanceof FragmentReference) {
                /** @var FragmentReference */
                $fragmentReference = $fieldOrFragmentBond;
                $fragment = $this->getFragment($fragmentReference->getName(), $fragments);
                if ($fragment === null) {
                    continue;
                }
                $allFieldsFromFieldsOrFragmentBonds = $this->getAllFieldsFromFieldsOrFragmentBonds(
                    $fragment->getFieldsOrFragmentBonds(),
                    $fragments,
                );
                $fields = array_merge(
                    $fields,
                    $allFieldsFromFieldsOrFragmentBonds
                );
                continue;
            }
            if ($fieldOrFragmentBond instanceof InlineFragment) {
                /** @var InlineFragment */
                $inlineFragment = $fieldOrFragmentBond;
                $allFieldsFromFieldsOrFragmentBonds = $this->getAllFieldsFromFieldsOrFragmentBonds(
                    $inlineFragment->getFieldsOrFragmentBonds(),
                    $fragments,
                );
                $fields = array_merge(
                    $fields,
                    $allFieldsFromFieldsOrFragmentBonds
                );
                continue;
            }
            /** @var FieldInterface */
            $field = $fieldOrFragmentBond;
            $fields[] = $field;
        }
        return $fields;
    }

    /**
     * @param Fragment[] $fragments
     */
    protected function getFragment(
        string $fragmentName,
        array $fragments,
    ): ?Fragment {
        foreach ($fragments as $fragment) {
            if ($fragment->getName() === $fragmentName) {
                return $fragment;
            }
        }
        return null;
    }

    /**
     * @param Fragment[] $fragments
     */
    protected function isFieldEquivalentToField(Fieldinterface $thisField, FieldInterface $oppositeField, array $fragments): bool
    {
        if (get_class($thisField) !== get_class($oppositeField)) {
            return false;
        }
        if ($thisField instanceof LeafField) {
            /** @var LeafField */
            $thisLeafField = $thisField;
            /** @var LeafField */
            $againstLeafField = $oppositeField;
            return $thisLeafField->isEquivalentTo($againstLeafField);
        }
        /** @var RelationalField */
        $thisRelationalField = $thisField;
        /** @var RelationalField */
        $againstRelationalField = $oppositeField;
        return $thisRelationalField->isEquivalentTo($againstRelationalField, $fragments);
    }
}
