<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Spec\Parser\Ast;

use PoP\GraphQLParser\ExtendedSpec\Parser\Ast\MetaDirective;
use PoP\GraphQLParser\Spec\Parser\Ast\Directive;
use PoP\GraphQLParser\Spec\Parser\Location;

class RelationalField extends AbstractAst implements FieldInterface, WithFieldsOrFragmentBondsInterface
{
    use WithArgumentsTrait;
    use WithDirectivesTrait;
    use WithFieldsOrFragmentBondsTrait;
    use FieldTrait {
        FieldTrait::doAsQueryString as upstreamAsQueryString;
    }

    protected RelationalField|Fragment|InlineFragment|OperationInterface $parent;

    /**
     * @param Argument[] $arguments
     * @param FieldInterface[]|FragmentBondInterface[] $fieldsOrFragmentBonds
     * @param Directive[] $directives
     */
    public function __construct(
        protected readonly string $name,
        protected readonly ?string $alias,
        array $arguments,
        array $fieldsOrFragmentBonds,
        array $directives,
        Location $location,
    ) {
        parent::__construct($location);
        $this->setFieldsOrFragmentBonds($fieldsOrFragmentBonds);
        $this->setArguments($arguments);
        $this->setDirectives($directives);
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
            $this->upstreamAsQueryString(),
            $strFieldFieldsOrFragmentBonds,
        );
    }

    /**
     * @todo Temporarily calling ->asQueryString, must work with AST directly!
     * @todo Completely remove this function!!!!
     */
    public function asFieldOutputQueryString(): string
    {
        // Generate the string for arguments
        $strFieldArguments = '';
        if ($this->getArguments() !== []) {
            $strArguments = [];
            foreach ($this->getArguments() as $argument) {
                $strArguments[] = $argument->asQueryString();
            }
            $strFieldArguments = sprintf(
                '(%s)',
                implode(', ', $strArguments)
            );
        }

        /**
         * @todo Temporarily calling ->asQueryString, must work with AST directly!
         */
        $strFieldDirectives = $this->getDirectivesQueryString($this->getDirectives());

        return sprintf(
            '%s%s%s%s',
            $this->getAlias() !== null ? sprintf('%s: ', $this->getAlias()) : '',
            $this->getName(),
            $strFieldArguments,
            $strFieldDirectives,
        );
    }

    /**
     * @todo Temporarily calling ->asQueryString, must work with AST directly!
     * @todo Completely remove this function!!!!
     * @param Directive[] $directives
     */
    private function getDirectivesQueryString(array $directives): string
    {
        $strFieldDirectives = '';
        if ($directives !== []) {
            $directiveQueryStrings = [];
            foreach ($directives as $directive) {
                // Remove the initial "@"
                $directiveQueryString = substr($directive->asQueryString(), 1);
                if ($directive instanceof MetaDirective) {
                    /** @var MetaDirective */
                    $metaDirective = $directive;
                    $directiveQueryString .= $this->getDirectivesQueryString($metaDirective->getNestedDirectives());
                }
                $directiveQueryStrings[] = $directiveQueryString;
            }
            $strFieldDirectives .= '<' . implode(', ', $directiveQueryStrings) . '>';
        }
        return $strFieldDirectives;
    }

    public function setParent(RelationalField|Fragment|InlineFragment|OperationInterface $parent): void
    {
        $this->parent = $parent;
    }

    public function getParent(): RelationalField|Fragment|InlineFragment|OperationInterface
    {
        return $this->parent;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getAlias(): ?string
    {
        return $this->alias;
    }
}
