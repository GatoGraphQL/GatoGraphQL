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
use PoP\GraphQLParser\Spec\Parser\RuntimeLocation;
use PoP\Root\Services\AbstractBasicService;
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
class ASTNodeDuplicatorService extends AbstractBasicService implements ASTNodeDuplicatorServiceInterface
{
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
    public function getExclusiveFragment(
        FragmentReference $fragmentReference,
        array $fragments,
    ): ?Fragment {
        if ($this->fragmentReferenceFragments->offsetExists($fragmentReference)) {
            return $this->fragmentReferenceFragments[$fragmentReference];
        }
        foreach ($fragments as $fragment) {
            if ($fragment->getName() !== $fragmentReference->getName()) {
                continue;
            }
            $clonedFragment = $this->recursivelyCloneFragment($fragment);
            $this->fragmentReferenceFragments[$fragmentReference] = $clonedFragment;
            return $clonedFragment;
        }
        return null;
    }

    protected function recursivelyCloneFragment(Fragment $fragment): Fragment
    {
        return new Fragment(
            $fragment->getName(),
            $fragment->getModel(),
            $fragment->getDirectives(),
            $this->recursivelyCloneFieldOrFragmentBonds(
                $fragment->getFieldsOrFragmentBonds(),
            ),
            new RuntimeLocation($fragment),
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
                $clonedInlineFragment = $this->cloneInlineFragment($inlineFragment);
                $clonedFieldsOrFragmentBonds[] = $clonedInlineFragment;
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

    /**
     * The FragmentReference must also be cloned, as the exclusive
     * Fragment is stored under it. Otherwise, a Fragment nested
     * within a Fragment that is referenced several times would
     * resolve to the same cloned Fragment for all of them.
     */
    protected function cloneFragmentReference(
        FragmentReference $fragmentReference,
    ): FragmentReference {
        return new FragmentReference(
            $fragmentReference->getName(),
            new RuntimeLocation($fragmentReference),
            $fragmentReference->getDirectives(),
        );
    }

    /**
     * Keep the InlineFragment, as its type condition must
     * still be satisfied to resolve its fields.
     */
    protected function cloneInlineFragment(
        InlineFragment $inlineFragment,
    ): InlineFragment {
        return new InlineFragment(
            $inlineFragment->getTypeName(),
            $this->recursivelyCloneFieldOrFragmentBonds(
                $inlineFragment->getFieldsOrFragmentBonds(),
            ),
            $inlineFragment->getDirectives(),
            new RuntimeLocation($inlineFragment),
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
            new RuntimeLocation($relationalField),
        );
    }

    protected function cloneLeafField(
        LeafField $leafField,
    ): LeafField {
        return new LeafField(
            $leafField->getName(),
            $leafField->getAlias(),
            $leafField->getArguments(),
            $leafField->getDirectives(),
            new RuntimeLocation($leafField),
        );
    }
}
