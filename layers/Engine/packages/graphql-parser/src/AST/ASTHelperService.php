<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\AST;

use PoP\GraphQLParser\Enums\FragmentInclusionDirectiveName;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\Fragment;
use PoP\GraphQLParser\Spec\Parser\Ast\FragmentBondInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\FragmentReference;
use PoP\GraphQLParser\Spec\Parser\Ast\InlineFragment;
use PoP\GraphQLParser\Spec\Parser\Ast\LeafField;
use PoP\GraphQLParser\Spec\Parser\Ast\RelationalField;
use PoP\Root\Services\AbstractBasicService;

class ASTHelperService extends AbstractBasicService implements ASTHelperServiceInterface
{
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

    /**
     * The value of argument `if` is either a Boolean or an operation
     * variable (dynamic variables are not supported in fragments),
     * so it is known before resolving the query.
     *
     * @see https://spec.graphql.org/September2025/#sec-Field-Collection
     */
    public function isFragmentBondIncluded(FragmentReference|InlineFragment $fragmentBond): bool
    {
        foreach ($fragmentBond->getDirectives() as $directive) {
            $directiveName = FragmentInclusionDirectiveName::tryFrom($directive->getName());
            if ($directiveName === null) {
                continue;
            }
            $condition = $directive->getArgumentValue('if') === true;
            if ($directiveName === FragmentInclusionDirectiveName::Skip && $condition) {
                return false;
            }
            if ($directiveName === FragmentInclusionDirectiveName::Include && !$condition) {
                return false;
            }
        }
        return true;
    }
}
