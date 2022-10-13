/**
 * Same value as in:
 * PoP\GraphQLParser\Spec\Parser\Ast\OperationTypes::QUERY
 */
const OPERATION_TYPE_QUERY = 'query';

/**
 * Same value as in:
 * PoP\GraphQLParser\Spec\Parser\Ast\OperationTypes::MUTATION
 */
const OPERATION_TYPE_MUTATION = 'mutation';

/**
 * Same value as in:
 * PoP\GraphQLParser\Spec\Parser\Ast\OperationTypes::SUBSCRIPTION
 */
const OPERATION_TYPE_SUBSCRIPTION = 'subscription';

/**
 * Operations currently supported by the GraphQL server.
 * Please notice: Subscriptions are not yet supported.
 */
const SUPPORTED_OPERATION_TYPES = [
    OPERATION_TYPE_QUERY,
    OPERATION_TYPE_MUTATION,
];
 
export {
    OPERATION_TYPE_QUERY,
    OPERATION_TYPE_MUTATION,
    OPERATION_TYPE_SUBSCRIPTION,
    SUPPORTED_OPERATION_TYPES,
};
 