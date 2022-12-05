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
use SplObjectStorage;

class ASTNodeDuplicatorService implements ASTNodeDuplicatorServiceInterface
{
    use BasicServiceTrait;

    /**
     * @var SplObjectStorage<FragmentReference,Fragment>
     */
    protected SplObjectStorage $fragmentReferenceFragments;
    
    public function __construct()
    {
        /**
         * @var SplObjectStorage<FragmentReference,Fragment>
         */
        $fragmentReferenceFragments = new SplObjectStorage();
        $this->fragmentReferenceFragments = $fragmentReferenceFragments;
    }

    /**
     * Retrieve a specific and dynamic Fragment for that FragmentReference
     *
     * @param Fragment[] $fragments
     */
    public function getFragment(
        FragmentReference $fragmentReference,
        array $fragments,
    ): ?Fragment {
        if ($this->fragmentReferenceFragments->contains($fragmentReference)) {
            return $this->fragmentReferenceFragments[$fragmentReference];
        }
        foreach ($fragments as $fragment) {
            if ($fragment->getName() !== $fragmentReference->getName()) {
                continue;
            }
            $this->fragmentReferenceFragments[$fragmentReference] = $this->recursivelyCloneFragment(
                $fragment,
                $fragments
            );
            return $this->fragmentReferenceFragments[$fragmentReference];
        }
        return null;
    }

    /**
     * @param Fragment[] $fragments
     */
    protected function recursivelyCloneFragment(
        Fragment $fragment,
        array $fragments,
    ): Fragment {
        return new Fragment(
            $fragment->getName(),
            $fragment->getModel(),
            $fragment->getDirectives(),
            $this->recursivelyCloneFieldOrFragmentBonds(
                $fragment->getFieldsOrFragmentBonds($fragments),
            ),
            $fragment->getLocation(),
        );
    }
    
    /**
     * @param array<FieldInterface|FragmentBondInterface> $fieldsOrFragmentBonds
     * @return array<FieldInterface|FragmentBondInterface>
     */
    protected function recursivelyCloneFieldOrFragmentBonds(
        array $fieldsOrFragmentBonds,
    ): array {
        $clonedFieldsOrFragmentBonds = [];
        foreach ($fieldsOrFragmentBonds as $fieldOrFragmentBond) {
            if ($fieldOrFragmentBond instanceof FragmentReference) {
                /** @var FragmentReference */
                $fragmentReference = $fieldOrFragmentBond;
                $clonedFragmentReference = $this->cloneFragmentReference($fragmentReference);
                $clonedFieldsOrFragmentBonds[] = $clonedFragmentReference;
                continue;
            }
            if ($fieldOrFragmentBond instanceof InlineFragment) {
                /** @var InlineFragment */
                $inlineFragment = $fieldOrFragmentBond;
                $clonedFieldsOrFragmentBonds = array_merge(
                    $clonedFieldsOrFragmentBonds,
                    $this->recursivelyCloneFieldOrFragmentBonds(
                        $inlineFragment->getFieldsOrFragmentBonds(),
                    )
                );
                continue;
            }
            if ($fieldOrFragmentBond instanceof RelationalField) {
                /** @var RelationalField */
                $relationalField = $fieldOrFragmentBond;
                $clonedRelationalField = $this->cloneRelationalField($relationalField);
                $clonedFieldsOrFragmentBonds[] = $clonedRelationalField;
                continue;
            }
            /** @var LeafField */
            $leafField = $fieldOrFragmentBond;
            $clonedLeafField = $this->cloneLeafField($leafField);
            $clonedFieldsOrFragmentBonds[] = $clonedLeafField;
        }
        return $clonedFieldsOrFragmentBonds;
    }

    protected function cloneFragmentReference(
        FragmentReference $fragmentReference,
    ): FragmentReference {
        return new FragmentReference(
            $fragmentReference->getName(),
            $fragmentReference->getLocation(),
        );
    }

    protected function cloneRelationalField(
        RelationalField $relationalField,
    ): RelationalField {
        return new RelationalField(
            $relationalField->getName(),
            $relationalField->getAlias(),
            $relationalField->getArguments(),
            $this->recursivelyCloneFieldOrFragmentBonds(
                $relationalField->getFieldsOrFragmentBonds(),
            ),
            $relationalField->getDirectives(),
            $relationalField->getLocation(),
        );
    }

    protected function cloneLeafField(
        LeafField $field,
    ): LeafField {
        return new LeafField(
            $field->getName(),
            $field->getAlias(),
            $field->getArguments(),
            $field->getDirectives(),
            $field->getLocation(),
        );
    }
}
