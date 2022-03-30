<?php

declare(strict_types=1);

namespace PoPAPI\API\ModuleProcessors;

use PoP\ComponentModel\App;
use PoP\ComponentModel\GraphQLModel\ComponentModelSpec\Ast\LeafModuleField;
use PoP\ComponentModel\GraphQLModel\ComponentModelSpec\Ast\RelationalModuleField;
use PoP\ComponentModel\ModuleProcessors\AbstractQueryDataModuleProcessor;
use PoP\GraphQLParser\Exception\Parser\InvalidRequestException;
use PoP\GraphQLParser\Exception\Parser\SyntaxErrorException;
use PoP\GraphQLParser\ExtendedSpec\Execution\ExecutableDocument;
use PoP\GraphQLParser\ExtendedSpec\Parser\ParserInterface;
use PoP\GraphQLParser\Spec\Execution\Context;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\Fragment;
use PoP\GraphQLParser\Spec\Parser\Ast\FragmentReference;
use PoP\GraphQLParser\Spec\Parser\Ast\InlineFragment;
use PoP\GraphQLParser\Spec\Parser\Ast\LeafField;
use PoP\GraphQLParser\Spec\Parser\Ast\RelationalField;

abstract class AbstractRelationalFieldQueryDataModuleProcessor extends AbstractQueryDataModuleProcessor
{
    private ?ParserInterface $parser = null;
    
    final public function setParser(ParserInterface $parser): void
    {
        $this->parser = $parser;
    }
    final protected function getParser(): ParserInterface
    {
        return $this->parser ??= $this->instanceManager->getInstance(ParserInterface::class);
    }

    /**
     * @return FieldInterface[]
     */
    protected function getFields(array $module, ?array $moduleAtts): array
    {
        /**
         * If it is a virtual module, the fields are coded
         * inside the virtual module atts
         */
        if ($moduleAtts !== null) {
            return $moduleAtts['fields'];
        }

        /**
         * It is a normal module when calling the first time
         * (i.e. for the fields at the root level).
         *
         * Parse the requested GraphQL query, and extract
         * the root fields.
         */
        $query = App::getState('query');
        if ($query === null || trim($query) === '') {
            return [];
        }

        $variableValues = App::getState('variables');
        $operationName = App::getState('graphql-operation-name');

        try {
            $document = $this->getParser()->parse($query);
            $executableDocument = (
                new ExecutableDocument(
                    $document,
                    new Context($operationName, $variableValues)
                )
            )->validateAndInitialize();
        } catch (SyntaxErrorException | InvalidRequestException $e) {
            // @todo Show GraphQL error in client
            return [];
        }

        /**
         * If it is a normal module, it is the first added,
         * then parse the requested GraphQL query, and fill
         * its fields within the ensuing component model
         */
        return App::getState('executable-query') ?? [];
    }

    /**
     * @return LeafModuleField[]
     */
    public function getDataFields(array $module, array &$props): array
    {
        return array_map(
            fn (LeafField $leafField) => LeafModuleField::fromLeafField($leafField),
            $this->getLeafFields($module)
        );
    }

    /**
     * @return LeafField[]
     */
    protected function getLeafFields(array $module): array
    {
        // @todo Provide fragments from the parsed query!
        $fragments = [];
        $moduleAtts = $module[2] ?? null;
        $fields = $this->getFields($module, $moduleAtts);
        return array_filter(
            $fields,
            fn (FieldInterface $field) => $field instanceof LeafField
        );
    }

    /**
     * @return RelationalModuleField[]
     */
    public function getDomainSwitchingSubmodules(array $module): array
    {
        $ret = parent::getDomainSwitchingSubmodules($module);

        // @todo Provide fragments from the parsed query!
        $fragments = [];

        $relationalFields = $this->getRelationalFields($module);

        // Create a "virtual" module with the fields corresponding to the next level module
        foreach ($relationalFields as $relationalField) {
            $nestedFields = $this->getAllFieldsFromFieldsOrFragmentBonds(
                $relationalField->getFieldsOrFragmentBonds(),
                $fragments
            );
            $nestedModule = [$module[0], $module[1], ['fields' => $nestedFields]];
            $ret[] = RelationalModuleField::fromRelationalField(
                $relationalField,
                [
                    $nestedModule,
                ]
            );
        }
        return $ret;
    }

    /**
     * @return RelationalField[]
     */
    protected function getRelationalFields(array $module): array
    {
        // @todo Provide fragments from the parsed query!
        $fragments = [];
        $moduleAtts = $module[2] ?? null;
        $fields = $this->getFields($module, $moduleAtts);
        return array_filter(
            $fields,
            fn (FieldInterface $field) => $field instanceof RelationalField
        );
    }

    /**
     * @param FieldInterface[]|FragmentBondInterface[] $fieldsOrFragmentBonds
     * @param Fragment[] $fragments
     * @return FieldInterface[]
     */
    protected function getAllFieldsFromFieldsOrFragmentBonds(
        array $fieldsOrFragmentBonds,
        array $fragments,
    ): array {
        $fields = [];
        foreach ($fieldsOrFragmentBonds as $fieldOrFragmentBond) {
            if ($fieldOrFragmentBond instanceof FragmentReference) {
                /** @var FragmentReference */
                $fragmentReference = $fieldOrFragmentBond;
                $fragment = $this->getFragment($fragmentReference->getName(), $fragments);
                if ($fragment === null) {
                    continue;
                }
                $fields = array_merge(
                    $fields,
                    $this->getAllFieldsFromFieldsOrFragmentBonds(
                        $fragment->getFieldsOrFragmentBonds(),
                        $fragments
                    )
                );
                continue;
            }
            if ($fieldOrFragmentBond instanceof InlineFragment) {
                /** @var InlineFragment */
                $inlineFragment = $fieldOrFragmentBond;
                $fields = array_merge(
                    $fields,
                    $this->getAllFieldsFromFieldsOrFragmentBonds(
                        $inlineFragment->getFieldsOrFragmentBonds(),
                        $fragments
                    )
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
}
