<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\AST;

use PoP\GraphQLParser\ASTNodes\ASTNodesFactory;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\Fragment;
use PoP\GraphQLParser\Spec\Parser\Ast\FragmentBondInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\FragmentReference;
use PoP\GraphQLParser\Spec\Parser\Ast\InlineFragment;
use PoP\GraphQLParser\Spec\Parser\Ast\LeafField;
use PoP\GraphQLParser\Spec\Parser\Ast\RelationalField;
use PoP\Root\Services\BasicServiceTrait;
use SplObjectStorage;

/**
 * Fragments can be referenced by different Fields. Because values
 * are stored and cached under each Field, every reference to a Field
 * within a Fragment must be unique, as to make sure that a mutated
 * value is updated.
 *
 * For instance, in the following query (with nested mutations),
 * `PostData` is referenced twice, once before and after mutating
 * the post's title:
 * 
 *   ```
 *   mutation {
 *     post(by: { id: 1 }) {
 *       # This will print title "Hello world!"
 *       ...PostData
 *   
 *       # This will update the title
 *       update(input: {
 *         title: "Updated title"
 *       }) {
 *         post {
 *           # This must print title "Updated title"
 *           ...PostData
 *         }
 *       }
 *     }
 *   }
 *   
 *   fragment PostData on Post {
 *     title
 *   }
 *   ```
 * 
 * If the same Fragment AST node is used, both `title` Fields would
 * produce value "Hello world!", as that's the first value that then
 * gets cached at the AST node level. By duplicating the node, both
 * references will be resolved independently, and the second reference
 * will then produce "Updated title".
 */
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
            $clonedFragment = $this->recursivelyCloneFragment(
                $fragment,
                $fragments
            );
            $this->fragmentReferenceFragments[$fragmentReference] = $clonedFragment;
            return $clonedFragment;
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
            ASTNodesFactory::getNonSpecificLocation(),
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
                $clonedFieldsOrFragmentBonds[] = $fragmentReference;
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
            ASTNodesFactory::getNonSpecificLocation(),
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
            ASTNodesFactory::getNonSpecificLocation(),
        );
    }
}
