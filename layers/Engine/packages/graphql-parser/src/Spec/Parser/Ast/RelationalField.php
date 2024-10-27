<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Spec\Parser\Ast;

use PoP\GraphQLParser\AST\ASTHelperServiceInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\Fragment;
use PoP\GraphQLParser\Spec\Parser\Ast\FragmentBondInterface;
use PoP\GraphQLParser\Spec\Parser\Location;
use PoP\Root\Facades\Instances\InstanceManagerFacade;

class RelationalField extends AbstractField implements WithFieldsOrFragmentBondsInterface
{
    use WithFieldsOrFragmentBondsTrait;

    private ?ASTHelperServiceInterface $astHelperService = null;

    final protected function getASTHelperService(): ASTHelperServiceInterface
    {
        if ($this->astHelperService === null) {
            /** @var ASTHelperServiceInterface */
            $astHelperService = InstanceManagerFacade::getInstance()->getInstance(ASTHelperServiceInterface::class);
            $this->astHelperService = $astHelperService;
        }
        return $this->astHelperService;
    }

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

        $astHelperService = $this->getASTHelperService();

        $thisFields = $this->getASTHelperService()->getAllFieldsFromFieldsOrFragmentBonds($this->getFieldsOrFragmentBonds(), $fragments);
        $againstFields = $this->getASTHelperService()->getAllFieldsFromFieldsOrFragmentBonds($relationalField->getFieldsOrFragmentBonds(), $fragments);

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
                fn (FieldInterface $oppositeField) => $astHelperService->isFieldEquivalentToField($thisField, $oppositeField, $fragments)
            );
            if ($equivalentFieldsInOppositeSet === []) {
                return false;
            }
        }
        foreach ($againstFields as $againstField) {
            $equivalentFieldsInOppositeSet = array_filter(
                $thisFields,
                fn (FieldInterface $oppositeField) => $astHelperService->isFieldEquivalentToField($againstField, $oppositeField, $fragments)
            );
            if ($equivalentFieldsInOppositeSet === []) {
                return false;
            }
        }

        return true;
    }
}
