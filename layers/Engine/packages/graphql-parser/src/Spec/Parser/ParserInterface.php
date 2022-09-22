<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\Spec\Parser;

use PoP\GraphQLParser\Exception\Parser\FeatureNotSupportedException;
use PoP\GraphQLParser\Exception\Parser\SyntaxErrorException;
use PoP\GraphQLParser\Spec\Parser\Ast\Directive;
use PoP\GraphQLParser\Spec\Parser\Ast\Document;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\Fragment;
use PoP\GraphQLParser\Spec\Parser\Ast\FragmentBondInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\MutationOperation;
use PoP\GraphQLParser\Spec\Parser\Ast\OperationInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\QueryOperation;
use PoP\GraphQLParser\Spec\Parser\Ast\Variable;

interface ParserInterface
{
    /**
     * @throws SyntaxErrorException
     * @throws FeatureNotSupportedException
     */
    public function parse(string $source): Document;

    /**
     * @param OperationInterface[] $operations
     * @param Fragment[] $fragments
     */
    public function createDocument(
        array $operations,
        array $fragments,
    ): Document;
    
    /**
     * @param Variable[] $variables
     * @param Directive[] $directives
     * @param array<FieldInterface|FragmentBondInterface> $fieldsOrFragmentBonds
     */
    public function createQueryOperation(
        string $name,
        array $variables,
        array $directives,
        array $fieldsOrFragmentBonds,
        Location $location,
    ): QueryOperation;

    /**
     * @param Variable[] $variables
     * @param Directive[] $directives
     * @param array<FieldInterface|FragmentBondInterface> $fieldsOrFragmentBonds
     */
    public function createMutationOperation(
        string $name,
        array $variables,
        array $directives,
        array $fieldsOrFragmentBonds,
        Location $location,
    ): MutationOperation;
}
