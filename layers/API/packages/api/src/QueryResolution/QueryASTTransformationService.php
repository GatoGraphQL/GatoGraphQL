<?php

declare(strict_types=1);

namespace PoPAPI\API\QueryResolution;

use PoP\GraphQLParser\Spec\Parser\Ast\Document;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\Fragment;
use PoP\GraphQLParser\Spec\Parser\Ast\FragmentBondInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\OperationInterface;
use PoP\Root\Services\AbstractBasicService;
use SplObjectStorage;

class QueryASTTransformationService extends AbstractBasicService implements QueryASTTransformationServiceInterface
{
    /**
     * @param OperationInterface[] $operations
     * @param Fragment[] $fragments
     * @return SplObjectStorage<OperationInterface,array<FieldInterface|FragmentBondInterface>>
     */
    public function prepareOperationFieldAndFragmentBondsForExecution(
        Document $document,
        array $operations,
        array $fragments,
    ): SplObjectStorage {
        /** @var SplObjectStorage<OperationInterface,array<FieldInterface|FragmentBondInterface>> */
        $operationFieldOrFragmentBonds = new SplObjectStorage();
        foreach ($operations as $operation) {
            // Allow subclasses to override the operation's original fields
            // — the GraphQL service injects a SuperRoot wrapper here.
            $operationFieldOrFragmentBonds[$operation] = $this->getOperationFieldsOrFragmentBonds($document, $operation);
        }
        return $operationFieldOrFragmentBonds;
    }

    /**
     * Allow to override the original fields from the operation,
     * to inject the SuperRoot field for GraphQL
     *
     * @return array<FieldInterface|FragmentBondInterface>
     */
    protected function getOperationFieldsOrFragmentBonds(
        Document $document,
        OperationInterface $operation,
    ): array {
        return $operation->getFieldsOrFragmentBonds();
    }
}
