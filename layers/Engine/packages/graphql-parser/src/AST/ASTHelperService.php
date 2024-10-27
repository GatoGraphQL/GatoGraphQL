<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\AST;

use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\Fragment;
use PoP\GraphQLParser\Spec\Parser\Ast\FragmentBondInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\FragmentReference;
use PoP\GraphQLParser\Spec\Parser\Ast\InlineFragment;
use PoP\GraphQLParser\Spec\Parser\Ast\LeafField;
use PoP\GraphQLParser\Spec\Parser\Ast\RelationalField;
use PoP\Root\Services\BasicServiceTrait;

class ASTHelperService implements ASTHelperServiceInterface
{
    use BasicServiceTrait;

    private ?ASTNodeDuplicatorServiceInterface $astNodeDuplicatorService = null;

    final protected function getASTNodeDuplicatorService(): ASTNodeDuplicatorServiceInterface
    {
        if ($this->astNodeDuplicatorService === null) {
            /** @var ASTNodeDuplicatorServiceInterface */
            $astNodeDuplicatorService = $this->instanceManager->getInstance(ASTNodeDuplicatorServiceInterface::class);
            $this->astNodeDuplicatorService = $astNodeDuplicatorService;
        }
        return $this->astNodeDuplicatorService;
    }

    /**
     * @param array<FieldInterface|FragmentBondInterface> $fieldsOrFragmentBonds
     * @param Fragment[] $fragments
     * @return FieldInterface[]
     */
    public function getAllFieldsFromFieldsOrFragmentBonds(
        array $fieldsOrFragmentBonds,
        array $fragments,
    ): array {
        /** @var FieldInterface[] */
        $fields = [];
        foreach ($fieldsOrFragmentBonds as $fieldOrFragmentBond) {
            if ($fieldOrFragmentBond instanceof FragmentReference) {
                /** @var FragmentReference */
                $fragmentReference = $fieldOrFragmentBond;
                $fragment = $this->getASTNodeDuplicatorService()->getExclusiveFragment($fragmentReference, $fragments);
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
    public function isFieldEquivalentToField(
        FieldInterface $thisField,
        FieldInterface $oppositeField,
        array $fragments
    ): bool {
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
