<?php

declare(strict_types=1);

namespace PoP\ComponentModel\MutationResolvers;

use PoP\ComponentModel\Exception\QueryResolutionException;
use PoP\ComponentModel\FeedbackItemProviders\MutationErrorFeedbackItemProvider;
use PoP\GraphQLParser\Spec\Parser\Ast\Argument;
use PoP\GraphQLParser\Spec\Parser\Ast\LeafField;
use PoP\GraphQLParser\Spec\Parser\Ast\WithArgumentsInterface;
use PoP\GraphQLParser\StaticHelpers\LocationHelper;
use PoP\Root\App;
use PoP\Root\Exception\AbstractException;
use PoP\Root\Feedback\FeedbackItemResolution;
use stdClass;

abstract class AbstractOneofMutationResolver extends AbstractMutationResolver
{
    /** @var array<string, MutationResolverInterface>|null */
    private ?array $consolidatedInputFieldNameMutationResolversCache = null;

    /**
     * The MutationResolvers contained in the OneofMutationResolver,
     * organized by inputFieldName
     *
     * @return array<string, MutationResolverInterface> Array of inputFieldName => MutationResolver
     */
    abstract protected function getInputFieldNameMutationResolvers(): array;

    /**
     * Consolidation of the mutation resolver for each input field. Call this function to read the data
     * instead of the individual functions, since it applies hooks to override/extend.
     */
    final public function getConsolidatedInputFieldNameMutationResolvers(): array
    {
        if ($this->consolidatedInputFieldNameMutationResolversCache !== null) {
            return $this->consolidatedInputFieldNameMutationResolversCache;
        }
        $this->consolidatedInputFieldNameMutationResolversCache = App::applyFilters(
            HookNames::INPUT_FIELD_NAME_MUTATION_RESOLVERS,
            $this->getInputFieldNameMutationResolvers(),
            $this,
        );
        return $this->consolidatedInputFieldNameMutationResolversCache;
    }

    /**
     * The oneof input object can receive only 1 input field at a time.
     * Retrieve it, or throw an Exception if this is not respected
     *
     * @throws QueryResolutionException If either there is none or more than 1 inputFieldNames being used
     */
    protected function getCurrentInputFieldName(stdClass $oneofInputObjectFormData): string
    {
        $oneofInputObjectFormDataSize = count((array)$oneofInputObjectFormData);
        if ($oneofInputObjectFormDataSize !== 1) {
            throw new QueryResolutionException(
                sprintf(
                    $this->__('Only and exactly 1 input field must be provided to the OneofMutationResolver, but %s were provided', 'component-model'),
                    $oneofInputObjectFormDataSize
                )
            );
        }
        // Retrieve the first (and only) element key
        return (string)key((array)$oneofInputObjectFormData);
    }

    /**
     * @throws QueryResolutionException If there is not MutationResolver for the input field
     */
    protected function getInputFieldMutationResolver(string $inputFieldName): MutationResolverInterface
    {
        $inputFieldMutationResolver = $this->getConsolidatedInputFieldNameMutationResolvers()[$inputFieldName] ?? null;
        if ($inputFieldMutationResolver === null) {
            throw new QueryResolutionException(
                sprintf(
                    $this->__('There is no MutationResolver for input field with name \'%s\'', 'component-model'),
                    $inputFieldName
                )
            );
        }
        return $inputFieldMutationResolver;
    }

    /**
     * @todo Review this commenting works for different oneof mutations
     * eg: http://graphql-by-pop-pro.lndo.site/graphiql/?query=mutation%20LoginUser%20%7B%0A%20%20loginUser(by%3A%20%7Bcredentials%3A%20%7BusernameOrEmail%3A%20%22admin%22%2C%20password%3A%20%22admin%22%7D%7D)%20%7B%0A%20%20%20%20id%0A%20%20%20%20name%0A%20%20%7D%0A%7D&operationName=LoginUser&variables=%7B%0A%20%20%22authorID%22%3A%203%0A%7D
     */
    // /**
    //  * @return array<string,mixed>|stdClass
    //  * @throws QueryResolutionException If the form data for the input field is not present in the array
    //  */
    // protected function getInputFieldFormData(string $inputFieldName, stdClass $oneofInputObjectFormData): array|stdClass
    // {
    //     $inputFieldFormData = $oneofInputObjectFormData->$inputFieldName ?? null;
    //     if ($inputFieldFormData === null) {
    //         throw new QueryResolutionException(
    //             sprintf(
    //                 $this->__('There is not form data for input field with name \'%s\'', 'component-model'),
    //                 $inputFieldName
    //             )
    //         );
    //     }
    //     return $inputFieldFormData;
    // }

    /**
     * Assume there's only one argument in the field,
     * for this OneofMutationResolver.
     * If that's not the case, this function must be overriden,
     * to avoid throwing an Exception
     *
     * @return string The current input field's name
     * @throws QueryResolutionException If more than 1 argument is passed to the field executing the OneofMutation
     */
    protected function getOneofInputObjectFieldName(WithArgumentsInterface $withArgumentsAST): string
    {
        $arguments = $withArgumentsAST->getArguments();
        $formDataSize = count($arguments);
        if ($formDataSize !== 1) {
            throw new QueryResolutionException(
                sprintf(
                    $this->__('The OneofMutationResolver expects only 1 argument is passed to the field executing the mutation, but %s were provided: \'%s\'', 'component-model'),
                    $formDataSize,
                    implode('\'%s\'',
                        array_map(
                            fn(Argument $argument) => $argument->getName(),
                            $arguments
                        )
                    )
                )
            );
        }
        return $arguments[0]->getName();
    }

    /**
     * @throws AbstractException In case of error
     */
    final public function executeMutation(WithArgumentsInterface $withArgumentsAST): mixed
    {
        [$inputFieldMutationResolver, $withArgumentsAST] = $this->getInputFieldMutationResolverAndOneOfAST($withArgumentsAST);
        /** @var MutationResolverInterface $inputFieldMutationResolver */
        return $inputFieldMutationResolver->executeMutation($withArgumentsAST);
    }

    /**
     * @return FeedbackItemResolution[]
     */
    final public function validateErrors(WithArgumentsInterface $withArgumentsAST): array
    {
        try {
            [$inputFieldMutationResolver, $withArgumentsAST] = $this->getInputFieldMutationResolverAndOneOfAST($withArgumentsAST);
            /** @var MutationResolverInterface $inputFieldMutationResolver */
            return $inputFieldMutationResolver->validateErrors($withArgumentsAST);
        } catch (QueryResolutionException $e) {
            // Return the error message from the exception
            return [
                new FeedbackItemResolution(
                    MutationErrorFeedbackItemProvider::class,
                    MutationErrorFeedbackItemProvider::E1,
                    [
                        $e->getMessage(),
                    ]
                ),
            ];
        }
    }

    /**
     * @return FeedbackItemResolution[]
     */
    final public function validateWarnings(WithArgumentsInterface $withArgumentsAST): array
    {
        try {
            [$inputFieldMutationResolver, $withArgumentsAST] = $this->getInputFieldMutationResolverAndOneOfAST($withArgumentsAST);
            /** @var MutationResolverInterface $inputFieldMutationResolver */
            return $inputFieldMutationResolver->validateWarnings($withArgumentsAST);
        } catch (QueryResolutionException $e) {
            // Do nothing since the Error will already return the problem
            return [];
        }
    }
    /**
     * @return mixed[] An array of 2 items: the current input field's mutation resolver, and the AST with the current input field's form data
     * @throws QueryResolutionException If there is not MutationResolver for the input field
     */
    final protected function getInputFieldMutationResolverAndOneOfAST(WithArgumentsInterface $withArgumentsAST): array
    {
        $inputFieldName = $this->getOneofInputObjectFieldName($withArgumentsAST);
        // Create a new Field, passing the corresponding Argument only
        $arguments = array_values(array_filter(
            $withArgumentsAST->getArguments(),
            fn (Argument $argument) => $argument->getName() === $inputFieldName
        ));
        // The name of the mutation does not matter, so provide a random one
        $field = new LeafField(
            'someOneOfMutation',
            null,
            $arguments,
            [],
            LocationHelper::getNonSpecificLocation(),
        );
        $inputFieldMutationResolver = $this->getInputFieldMutationResolver($inputFieldName);
        /**
         * @todo Review this commenting works for different oneof mutations
         * eg: http://graphql-by-pop-pro.lndo.site/graphiql/?query=mutation%20LoginUser%20%7B%0A%20%20loginUser(by%3A%20%7Bcredentials%3A%20%7BusernameOrEmail%3A%20%22admin%22%2C%20password%3A%20%22admin%22%7D%7D)%20%7B%0A%20%20%20%20id%0A%20%20%20%20name%0A%20%20%7D%0A%7D&operationName=LoginUser&variables=%7B%0A%20%20%22authorID%22%3A%203%0A%7D
         */
        // $inputFieldFormData = $this->getInputFieldFormData($inputFieldName, $oneofInputObjectFormData);
        // return [$inputFieldMutationResolver, $inputFieldFormData];
        return [$inputFieldMutationResolver, $field];
    }
}
