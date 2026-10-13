<?php

declare(strict_types=1);

namespace PoPAPI\GraphQLAPI\DataStructureFormatters;

use PoPAPI\APIMirrorQuery\DataStructureFormatters\MirrorQueryDataStructureFormatter;
use PoPAPI\APIMirrorQuery\State\PreviouslyResolvedFieldsForObjectsStore;
use PoPAPI\GraphQLAPI\Module;
use PoPAPI\GraphQLAPI\ModuleConfiguration;
use PoPAPI\GraphQLAPI\State\ResponsePositionStore;
use PoP\ComponentModel\App;
use PoP\ComponentModel\Constants\Response;
use PoP\ComponentModel\ExtendedSpec\Execution\ExecutableDocument;
use PoP\ComponentModel\FeedbackItemProviders\FieldResolutionErrorFeedbackItemProvider;
use PoP\ComponentModel\Feedback\FeedbackCategories;
use PoP\ComponentModel\Feedback\FeedbackEntryManagerInterface;
use PoP\ComponentModel\Feedback\Tokens;
use PoP\ComponentModel\Module as ComponentModelModule;
use PoP\ComponentModel\ModuleConfiguration as ComponentModelModuleConfiguration;
use PoP\ComponentModel\Registries\TypeRegistryInterface;
use PoP\ComponentModel\Response\OutputServiceInterface;
use PoP\ComponentModel\Schema\OutputValueNullabilityServiceInterface;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\OutputValueCoercingLeafTypeResolverInterface;
use PoP\GraphQLParser\FeedbackItemProviders\GraphQLSpecErrorFeedbackItemProvider;
use PoP\GraphQLParser\Spec\Parser\Ast\FieldInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\LeafField;
use PoP\GraphQLParser\Spec\Parser\Ast\RelationalField;
use PoP\ComponentModel\Feedback\FeedbackItemResolution;
use SplObjectStorage;
use stdClass;

use function in_array;
use function is_array;
use function is_bool;

class GraphQLDataStructureFormatter extends MirrorQueryDataStructureFormatter
{
    private const ADDITIONAL_FEEDBACK = 'additionalFeedback';

    /**
     * Set only while formatting the response with
     * the spec-compliant error handling enabled
     */
    private ?ResponsePositionStore $responsePositionStore = null;

    private ?FeedbackEntryManagerInterface $feedbackEntryService = null;
    private ?TypeRegistryInterface $typeRegistry = null;
    private ?OutputValueNullabilityServiceInterface $outputValueNullabilityService = null;
    private ?OutputServiceInterface $outputService = null;

    final protected function getFeedbackEntryManager(): FeedbackEntryManagerInterface
    {
        if ($this->feedbackEntryService === null) {
            /** @var FeedbackEntryManagerInterface */
            $feedbackEntryService = $this->instanceManager->getInstance(FeedbackEntryManagerInterface::class);
            $this->feedbackEntryService = $feedbackEntryService;
        }
        return $this->feedbackEntryService;
    }
    final protected function getTypeRegistry(): TypeRegistryInterface
    {
        if ($this->typeRegistry === null) {
            /** @var TypeRegistryInterface */
            $typeRegistry = $this->instanceManager->getInstance(TypeRegistryInterface::class);
            $this->typeRegistry = $typeRegistry;
        }
        return $this->typeRegistry;
    }
    final protected function getOutputValueNullabilityService(): OutputValueNullabilityServiceInterface
    {
        if ($this->outputValueNullabilityService === null) {
            /** @var OutputValueNullabilityServiceInterface */
            $outputValueNullabilityService = $this->instanceManager->getInstance(OutputValueNullabilityServiceInterface::class);
            $this->outputValueNullabilityService = $outputValueNullabilityService;
        }
        return $this->outputValueNullabilityService;
    }
    final protected function getOutputService(): OutputServiceInterface
    {
        if ($this->outputService === null) {
            /** @var OutputServiceInterface */
            $outputService = $this->instanceManager->getInstance(OutputServiceInterface::class);
            $this->outputService = $outputService;
        }
        return $this->outputService;
    }

    public function getName(): string
    {
        return 'graphql';
    }

    /**
     * @return array<string,mixed>
     * @param array<string,mixed> $data
     */
    public function getFormattedData(array $data): array
    {
        /** @var ComponentModelModuleConfiguration */
        $componentModelModuleConfiguration = App::getModule(ComponentModelModule::class)->getConfiguration();
        $previousResponsePositionStore = $this->responsePositionStore;
        $this->responsePositionStore = $componentModelModuleConfiguration->enableSpecCompliantErrorHandling()
            ? $this->createResponsePositionStore($data)
            : null;
        try {
            return $this->doGetFormattedData($data);
        } finally {
            $this->responsePositionStore = $previousResponsePositionStore;
        }
    }

    /**
     * @return array<string,mixed>
     * @param array<string,mixed> $data
     */
    protected function doGetFormattedData(array $data): array
    {
        $ret = [];

        /**
         * Calculate the data first (even if printed later)
         * as it can also add errors.
         */
        $isNullData = false;
        $resultData = $this->formatData($data, $isNullData);

        /**
         * If the formatting produced additional feedback entries,
         * transfer them to the $data object.
         */
        if (isset($resultData[self::ADDITIONAL_FEEDBACK])) {
            foreach ($resultData[self::ADDITIONAL_FEEDBACK][Response::GENERAL_FEEDBACK] ?? [] as $category => $feedbackEntries) {
                $data[Response::GENERAL_FEEDBACK][$category] = array_merge(
                    $data[Response::GENERAL_FEEDBACK][$category] ?? [],
                    $feedbackEntries
                );
            }
            foreach ($resultData[self::ADDITIONAL_FEEDBACK][Response::DOCUMENT_FEEDBACK] ?? [] as $category => $feedbackEntries) {
                $data[Response::DOCUMENT_FEEDBACK][$category] = array_merge(
                    $data[Response::DOCUMENT_FEEDBACK][$category] ?? [],
                    $feedbackEntries
                );
            }
            foreach ($resultData[self::ADDITIONAL_FEEDBACK][Response::SCHEMA_FEEDBACK] ?? [] as $category => $feedbackEntries) {
                $data[Response::SCHEMA_FEEDBACK][$category] = array_merge(
                    $data[Response::SCHEMA_FEEDBACK][$category] ?? [],
                    $feedbackEntries
                );
            }
            foreach ($resultData[self::ADDITIONAL_FEEDBACK][Response::OBJECT_FEEDBACK] ?? [] as $category => $feedbackEntries) {
                $data[Response::OBJECT_FEEDBACK][$category] = array_merge(
                    $data[Response::OBJECT_FEEDBACK][$category] ?? [],
                    $feedbackEntries
                );
            }
            unset($resultData[self::ADDITIONAL_FEEDBACK]);
        }

        /**
         * Print the feedback at the top
         */
        $errors = array_merge(
            $this->reformatGeneralEntries($data[Response::GENERAL_FEEDBACK][FeedbackCategories::ERROR] ?? []),
            $this->reformatDocumentEntries($data[Response::DOCUMENT_FEEDBACK][FeedbackCategories::ERROR] ?? []),
            $this->reformatSchemaEntries($data[Response::SCHEMA_FEEDBACK][FeedbackCategories::ERROR] ?? []),
            $this->responsePositionStore !== null
                ? $this->reformatObjectErrorEntriesWithResponsePath($data[Response::OBJECT_FEEDBACK][FeedbackCategories::ERROR] ?? [], $this->responsePositionStore)
                : $this->reformatObjectEntries($data[Response::OBJECT_FEEDBACK][FeedbackCategories::ERROR] ?? []),
            $this->responsePositionStore?->getErrorEntries() ?? [],
        );
        if ($errors !== []) {
            $ret['errors'] = $errors;
        }

        // Add the "extensions" entry
        $this->maybeAddTopLevelExtensionsEntryToResponse($ret, $data);

        /**
         * When the `null` of a Non-Null response position was
         * propagated all the way up to the root, "data" is `null`.
         *
         * @see https://spec.graphql.org/draft/#sec-Handling-Execution-Errors
         */
        if ($isNullData) {
            $ret['data'] = null;
        } elseif ($resultData) {
            $ret['data'] = $resultData;
        }

        return $ret;
    }

    /**
     * @param array<string,mixed> $data
     */
    protected function createResponsePositionStore(array $data): ResponsePositionStore
    {
        $objectTypeResolvers = [];
        foreach ($this->getTypeRegistry()->getObjectTypeResolvers() as $objectTypeResolver) {
            $objectTypeResolvers[$objectTypeResolver->getTypeOutputKey()] = $objectTypeResolver;
        }
        $responsePositionStore = new ResponsePositionStore($objectTypeResolvers);

        /** @var array<string,SplObjectStorage<FieldInterface,array<array<string,mixed>>>> */
        $objectErrorEntries = $data[Response::OBJECT_FEEDBACK][FeedbackCategories::ERROR] ?? [];
        foreach ($objectErrorEntries as $typeOutputKey => $fieldItems) {
            /** @var FieldInterface $field */
            foreach ($fieldItems as $field) {
                foreach ($fieldItems[$field] as $item) {
                    /** @var array<string|int> */
                    $ids = $item[Tokens::IDS] ?? [];
                    foreach ($ids as $id) {
                        $responsePositionStore->addErroredObjectField($typeOutputKey, $id, $field);
                    }
                }
            }
        }

        /** @var array<string,SplObjectStorage<FieldInterface,array<array<string,mixed>>>> */
        $schemaErrorEntries = $data[Response::SCHEMA_FEEDBACK][FeedbackCategories::ERROR] ?? [];
        foreach ($schemaErrorEntries as $typeOutputKey => $fieldItems) {
            /** @var FieldInterface $field */
            foreach ($fieldItems as $field) {
                $responsePositionStore->addErroredField($typeOutputKey, $field);
            }
        }

        return $responsePositionStore;
    }

    /**
     * Print an error entry for every response position the error
     * applies to, under top-level entry "path". If the
     * (object, field) was not printed in the response, print
     * the error without the "path".
     *
     * @see https://spec.graphql.org/draft/#sec-Errors.Error-Result-Format
     *
     * @param array<string,SplObjectStorage<FieldInterface,array<array<string,mixed>>>> $entries
     * @return array<int,mixed[]>
     */
    protected function reformatObjectErrorEntriesWithResponsePath(
        array $entries,
        ResponsePositionStore $responsePositionStore,
    ): array {
        $ret = [];
        foreach ($entries as $typeOutputKey => $fieldItems) {
            /** @var FieldInterface $field */
            foreach ($fieldItems as $field) {
                foreach ($fieldItems[$field] as $item) {
                    /** @var array<string|int> */
                    $ids = $item[Tokens::IDS] ?? [];
                    if ($ids === []) {
                        $ret[] = $this->getObjectEntry($typeOutputKey, $item);
                        continue;
                    }
                    $idsWithoutResponsePath = [];
                    foreach ($ids as $id) {
                        $responsePaths = $responsePositionStore->getResponsePaths($typeOutputKey, $id, $field);
                        if ($responsePaths === []) {
                            $idsWithoutResponsePath[] = $id;
                            continue;
                        }
                        foreach ($responsePaths as $responsePath) {
                            $ret[] = $this->addResponsePathToEntry(
                                $this->getObjectEntry(
                                    $typeOutputKey,
                                    [
                                        ...$item,
                                        Tokens::IDS => [$id],
                                    ]
                                ),
                                $responsePath,
                            );
                        }
                    }
                    if ($idsWithoutResponsePath !== []) {
                        $ret[] = $this->getObjectEntry(
                            $typeOutputKey,
                            [
                                ...$item,
                                Tokens::IDS => $idsWithoutResponsePath,
                            ]
                        );
                    }
                }
            }
        }
        return $ret;
    }

    /**
     * Place "path" after "message" and "locations",
     * as in the examples in the GraphQL spec.
     *
     * @param array<string,mixed> $entry
     * @param array<string|int> $responsePath
     * @return array<string,mixed>
     */
    protected function addResponsePathToEntry(array $entry, array $responsePath): array
    {
        $entryWithResponsePath = [];
        foreach ($entry as $key => $value) {
            if ($key === 'extensions' || $key === 'causes') {
                $entryWithResponsePath['path'] ??= $responsePath;
            }
            $entryWithResponsePath[$key] = $value;
        }
        $entryWithResponsePath['path'] ??= $responsePath;
        return $entryWithResponsePath;
    }

    /**
     * @param array<string|int> $responsePath
     */
    protected function onFieldAddedToResponse(
        string $typeOutputKey,
        string|int $objectID,
        FieldInterface $field,
        array $responsePath,
    ): void {
        $this->responsePositionStore?->addResponsePath($typeOutputKey, $objectID, $field, $responsePath);
    }

    /**
     * Coerce the value of Scalar and Enum types, and set `null`
     * on lists with Non-Null items that contain a `null` item.
     *
     * @see https://spec.graphql.org/draft/#sec-Value-Completion
     *
     * @param array<string|int> $responsePath
     */
    protected function processLeafFieldValue(
        string $typeOutputKey,
        string|int $objectID,
        LeafField $leafField,
        mixed $value,
        array $responsePath,
    ): mixed {
        if ($this->responsePositionStore === null || $value === null) {
            return $value;
        }
        $objectTypeResolver = $this->responsePositionStore->getObjectTypeResolver($typeOutputKey);
        if ($objectTypeResolver === null) {
            return $value;
        }
        $fieldTypeModifiers = $objectTypeResolver->getFieldTypeModifiers($leafField);
        if ($fieldTypeModifiers === null) {
            return $value;
        }
        $fieldTypeResolver = $objectTypeResolver->getFieldTypeResolver($leafField);
        $outputValueCoercingLeafTypeResolver = $fieldTypeResolver instanceof OutputValueCoercingLeafTypeResolverInterface
            ? $fieldTypeResolver
            : null;

        $fieldIsArrayOfArrays = ($fieldTypeModifiers & SchemaTypeModifiers::IS_ARRAY_OF_ARRAYS) === SchemaTypeModifiers::IS_ARRAY_OF_ARRAYS;
        $fieldIsArray = $fieldIsArrayOfArrays || ($fieldTypeModifiers & SchemaTypeModifiers::IS_ARRAY) === SchemaTypeModifiers::IS_ARRAY;
        if (!$fieldIsArray) {
            return $this->coerceLeafValue($typeOutputKey, $objectID, $leafField, $value, $outputValueCoercingLeafTypeResolver, $responsePath);
        }

        if (!is_array($value)) {
            if ($outputValueCoercingLeafTypeResolver === null) {
                return $value;
            }
            $this->addCoercionError($typeOutputKey, $objectID, $leafField, $value, $outputValueCoercingLeafTypeResolver, $responsePath);
            return null;
        }

        $outputValueNullabilityService = $this->getOutputValueNullabilityService();
        foreach ($value as $key => $arrayItem) {
            if ($arrayItem === null) {
                continue;
            }
            $arrayItemResponsePath = [...$responsePath, $key];
            if (!$fieldIsArrayOfArrays) {
                $value[$key] = $this->coerceLeafValue($typeOutputKey, $objectID, $leafField, $arrayItem, $outputValueCoercingLeafTypeResolver, $arrayItemResponsePath);
                continue;
            }
            if (!is_array($arrayItem)) {
                if ($outputValueCoercingLeafTypeResolver !== null) {
                    $this->addCoercionError($typeOutputKey, $objectID, $leafField, $arrayItem, $outputValueCoercingLeafTypeResolver, $arrayItemResponsePath);
                    $value[$key] = null;
                }
                continue;
            }
            foreach ($arrayItem as $innerKey => $innerArrayItem) {
                if ($innerArrayItem === null) {
                    continue;
                }
                $arrayItem[$innerKey] = $this->coerceLeafValue($typeOutputKey, $objectID, $leafField, $innerArrayItem, $outputValueCoercingLeafTypeResolver, [...$arrayItemResponsePath, $innerKey]);
            }
            if (
                $outputValueNullabilityService->hasNonNullableArrayOfArraysItems($fieldTypeModifiers)
                && in_array(null, $arrayItem, true)
            ) {
                $this->maybeAddNullabilityError($typeOutputKey, $objectID, $leafField, FieldResolutionErrorFeedbackItemProvider::E9, $arrayItemResponsePath);
                $value[$key] = null;
                continue;
            }
            $value[$key] = $arrayItem;
        }

        if (
            $outputValueNullabilityService->hasNonNullableArrayItems($fieldTypeModifiers)
            && in_array(null, $value, true)
        ) {
            $this->maybeAddNullabilityError($typeOutputKey, $objectID, $leafField, FieldResolutionErrorFeedbackItemProvider::E6, $responsePath);
            return null;
        }
        return $value;
    }

    /**
     * @param array<string|int> $responsePath
     */
    protected function coerceLeafValue(
        string $typeOutputKey,
        string|int $objectID,
        LeafField $leafField,
        mixed $value,
        ?OutputValueCoercingLeafTypeResolverInterface $outputValueCoercingLeafTypeResolver,
        array $responsePath,
    ): mixed {
        if ($outputValueCoercingLeafTypeResolver === null) {
            return $value;
        }
        /** @var string|int|float|bool|mixed[]|stdClass $value */
        $coercedValue = $outputValueCoercingLeafTypeResolver->coerceOutputValue($value);
        if ($coercedValue === null) {
            $this->addCoercionError($typeOutputKey, $objectID, $leafField, $value, $outputValueCoercingLeafTypeResolver, $responsePath);
        }
        return $coercedValue;
    }

    /**
     * @param array<string|int> $responsePath
     */
    protected function addCoercionError(
        string $typeOutputKey,
        string|int $objectID,
        LeafField $leafField,
        mixed $value,
        OutputValueCoercingLeafTypeResolverInterface $outputValueCoercingLeafTypeResolver,
        array $responsePath,
    ): void {
        if (is_array($value) || $value instanceof stdClass) {
            $valueAsString = $this->getOutputService()->jsonEncodeArrayOrStdClassValue($value);
        } elseif (is_bool($value)) {
            $valueAsString = $value ? 'true' : 'false';
        } else {
            /** @var string|int|float $value */
            $valueAsString = (string) $value;
        }
        $this->addErrorEntry(
            $typeOutputKey,
            $objectID,
            $leafField,
            new FeedbackItemResolution(
                FieldResolutionErrorFeedbackItemProvider::class,
                FieldResolutionErrorFeedbackItemProvider::E16,
                [
                    $valueAsString,
                    $leafField->getName(),
                    $outputValueCoercingLeafTypeResolver->getMaybeNamespacedTypeName(),
                ]
            ),
            $responsePath,
        );
    }

    /**
     * Every `null` on a Non-Null response position must have an
     * error. If the (object, field) does not have one yet
     * (eg: a directive set the `null` but did not report it),
     * add it.
     *
     * @param array<string|int> $responsePath
     */
    protected function maybeAddNullabilityError(
        string $typeOutputKey,
        string|int $objectID,
        FieldInterface $field,
        string $errorCode,
        array $responsePath,
    ): void {
        if ($this->responsePositionStore === null) {
            return;
        }
        if ($this->responsePositionStore->hasError($typeOutputKey, $objectID, $field)) {
            return;
        }
        $this->addErrorEntry(
            $typeOutputKey,
            $objectID,
            $field,
            new FeedbackItemResolution(
                FieldResolutionErrorFeedbackItemProvider::class,
                $errorCode,
                [
                    $field->getName(),
                ]
            ),
            $responsePath,
        );
    }

    /**
     * @param array<string|int> $responsePath
     */
    protected function addErrorEntry(
        string $typeOutputKey,
        string|int $objectID,
        FieldInterface $field,
        FeedbackItemResolution $feedbackItemResolution,
        array $responsePath,
    ): void {
        if ($this->responsePositionStore === null) {
            return;
        }
        $item = $this->getFeedbackEntryManager()->formatObjectOrSchemaFeedbackCommonEntry(
            $field,
            null,
            [],
            $feedbackItemResolution,
            [$objectID],
        );
        $item[Tokens::FIELD] = $field->asASTNodeString();
        $this->responsePositionStore->addErrorEntry(
            $this->addResponsePathToEntry(
                $this->getObjectEntry($typeOutputKey, $item),
                $responsePath,
            )
        );
        $this->responsePositionStore->addErroredObjectField($typeOutputKey, $objectID, $field);
    }

    /**
     * @param array<string|int> $responsePath
     */
    protected function mustPropagateNullFromField(
        string $typeOutputKey,
        string|int $objectID,
        FieldInterface $field,
        array $responsePath,
    ): bool {
        if ($this->responsePositionStore === null) {
            return false;
        }
        $fieldTypeModifiers = $this->responsePositionStore->getObjectTypeResolver($typeOutputKey)?->getFieldTypeModifiers($field);
        if ($fieldTypeModifiers === null) {
            return false;
        }
        if (!$this->getOutputValueNullabilityService()->isNonNullable($fieldTypeModifiers)) {
            return false;
        }
        $this->maybeAddNullabilityError($typeOutputKey, $objectID, $field, FieldResolutionErrorFeedbackItemProvider::E3, $responsePath);
        return true;
    }

    /**
     * @param array<string|int> $responsePath
     */
    protected function mustPropagateNullFromListItem(
        string $typeOutputKey,
        string|int $objectID,
        FieldInterface $field,
        array $responsePath,
    ): bool {
        if ($this->responsePositionStore === null) {
            return false;
        }
        $fieldTypeModifiers = $this->responsePositionStore->getObjectTypeResolver($typeOutputKey)?->getFieldTypeModifiers($field);
        if ($fieldTypeModifiers === null) {
            return false;
        }
        return $this->getOutputValueNullabilityService()->hasNonNullableArrayItems($fieldTypeModifiers);
    }

    /**
     * "warnings", "deprecations" and "logs" are top-level entries:
     * since they are not part of the spec, place them under
     * the top-level entry "extensions":
     *
     * > This entry is reserved for implementors to extend the protocol however they see fit,
     * > and hence there are no additional restrictions on its contents.
     *
     * @see http://spec.graphql.org/June2018/#sec-Response-Format
     * @param array<string,mixed> $ret
     * @param array<string,mixed> $data
     */
    protected function maybeAddTopLevelExtensionsEntryToResponse(array &$ret, array $data): void
    {
        if (!$this->addTopLevelExtensionsEntryToResponse()) {
            return;
        }

        // Deprecations
        $deprecations = array_merge(
            $this->reformatSchemaEntries($data[Response::SCHEMA_FEEDBACK][FeedbackCategories::DEPRECATION] ?? []),
            $this->reformatObjectEntries($data[Response::OBJECT_FEEDBACK][FeedbackCategories::DEPRECATION] ?? []),
        );
        if ($deprecations !== []) {
            $ret['extensions']['deprecations'] = $deprecations;
        }

        // Warnings
        $warnings = array_merge(
            $this->reformatGeneralEntries($data[Response::GENERAL_FEEDBACK][FeedbackCategories::WARNING] ?? []),
            $this->reformatDocumentEntries($data[Response::DOCUMENT_FEEDBACK][FeedbackCategories::WARNING] ?? []),
            $this->reformatSchemaEntries($data[Response::SCHEMA_FEEDBACK][FeedbackCategories::WARNING] ?? []),
            $this->reformatObjectEntries($data[Response::OBJECT_FEEDBACK][FeedbackCategories::WARNING] ?? []),
        );
        if ($warnings !== []) {
            $ret['extensions']['warnings'] = $warnings;
        }

        // Logs
        $logs = array_merge(
            $this->reformatGeneralEntries($data[Response::GENERAL_FEEDBACK][FeedbackCategories::LOG] ?? []),
            $this->reformatDocumentEntries($data[Response::DOCUMENT_FEEDBACK][FeedbackCategories::LOG] ?? []),
            $this->reformatSchemaEntries($data[Response::SCHEMA_FEEDBACK][FeedbackCategories::LOG] ?? []),
            $this->reformatObjectEntries($data[Response::OBJECT_FEEDBACK][FeedbackCategories::LOG] ?? []),
        );
        if ($logs !== []) {
            $ret['extensions']['logs'] = $logs;
        }

        // Notices
        $notices = array_merge(
            $this->reformatGeneralEntries($data[Response::GENERAL_FEEDBACK][FeedbackCategories::NOTICE] ?? []),
            $this->reformatDocumentEntries($data[Response::DOCUMENT_FEEDBACK][FeedbackCategories::NOTICE] ?? []),
            $this->reformatSchemaEntries($data[Response::SCHEMA_FEEDBACK][FeedbackCategories::NOTICE] ?? []),
            $this->reformatObjectEntries($data[Response::OBJECT_FEEDBACK][FeedbackCategories::NOTICE] ?? []),
        );
        if ($notices !== []) {
            $ret['extensions']['notices'] = $notices;
        }

        // Suggestions
        $suggestions = array_merge(
            $this->reformatGeneralEntries($data[Response::GENERAL_FEEDBACK][FeedbackCategories::SUGGESTION] ?? []),
            $this->reformatDocumentEntries($data[Response::DOCUMENT_FEEDBACK][FeedbackCategories::SUGGESTION] ?? []),
            $this->reformatSchemaEntries($data[Response::SCHEMA_FEEDBACK][FeedbackCategories::SUGGESTION] ?? []),
            $this->reformatObjectEntries($data[Response::OBJECT_FEEDBACK][FeedbackCategories::SUGGESTION] ?? []),
        );
        if ($suggestions !== []) {
            $ret['extensions']['suggestions'] = $suggestions;
        }
    }

    /**
     * Indicate if to add entry "extensions" as a top-level entry
     */
    protected function addTopLevelExtensionsEntryToResponse(): bool
    {
        return true;
    }

    /**
     * @return array<int,mixed[]>
     * @param array<array<string,mixed>> $entries
     */
    protected function reformatGeneralEntries(array $entries): array
    {
        $ret = [];
        foreach ($entries as $item) {
            $ret[] = $this->getGeneralEntry($item);
        }
        return $ret;
    }

    /**
     * @return array<string,mixed>
     * @param array<string,mixed> $item
     */
    protected function getGeneralEntry(array $item): array
    {
        $entry = [
            'message' => $item[Tokens::MESSAGE],
        ];
        if ($extensions = $item[Tokens::EXTENSIONS]) {
            $entry['extensions'] = $extensions;
        }
        return $entry;
    }

    /**
     * @return array<int,mixed[]>
     * @param array<array<string,mixed>> $entries
     */
    protected function reformatDocumentEntries(array $entries): array
    {
        $ret = [];
        foreach ($entries as $item) {
            $ret[] = $this->getDocumentEntry($item);
        }
        return $ret;
    }

    /**
     * @return array<string,mixed>
     * @param array<string,mixed> $item
     */
    protected function getDocumentEntry(array $item): array
    {
        $entry = [
            'message' => $item[Tokens::MESSAGE],
        ];
        if ($locations = $item[Tokens::LOCATIONS]) {
            $entry['locations'] = $locations;
        }
        if (
            $extensions = array_merge(
                $this->getDocumentEntryExtensions($item),
                $item[Tokens::EXTENSIONS] ?? []
            )
        ) {
            $entry['extensions'] = $extensions;
        }
        return $entry;
    }

    /**
     * @return mixed[]
     * @param array<string,mixed> $item
     */
    protected function getDocumentEntryExtensions(array $item): array
    {
        $extensions = [];
        if ($path = $item[Tokens::PATH] ?? null) {
            $extensions['path'] = $path;
        }
        return $extensions;
    }

    /**
     * @param array<string,SplObjectStorage<FieldInterface,array<string,mixed>>> $entries
     * @return array<int,mixed[]>
     */
    protected function reformatSchemaEntries(array $entries): array
    {
        $ret = [];
        foreach ($entries as $typeOutputKey => $storage) {
            foreach ($storage as $field) {
                /** @var FieldInterface $field */
                $items = $storage[$field];
                /** @var array<string,mixed> $items */
                foreach ($items as $item) {
                    $ret[] = $this->getSchemaEntry($typeOutputKey, $item);
                }
            }
        }
        return $ret;
    }

    /**
     * @return array<string,mixed>
     * @param array<string,mixed> $item
     */
    protected function getSchemaEntry(string $typeOutputKey, array $item): array
    {
        $entry = [
            'message' => $item[Tokens::MESSAGE],
        ];
        if ($locations = $item[Tokens::LOCATIONS]) {
            $entry['locations'] = $locations;
        }
        /**
         * Add the causes of the error, if any.
         *
         * @see https://github.com/graphql/graphql-spec/issues/893
         */
        if ($causes = $item[Tokens::CAUSES] ?? []) {
            $entry['causes'] = $causes;
        }
        if (
            $extensions = array_merge(
                $this->getSchemaEntryExtensions($typeOutputKey, $item),
                $item[Tokens::EXTENSIONS] ?? []
            )
        ) {
            $entry['extensions'] = $extensions;
        }
        return $entry;
    }

    /**
     * @return array<string,mixed>
     * @param array<string,mixed> $item
     */
    protected function getSchemaEntryExtensions(string $typeOutputKey, array $item): array
    {
        $extensions = [];
        if ($path = $item[Tokens::PATH] ?? null) {
            $extensions['path'] = $path;
        }
        if (!$this->skipAddingDataForType($typeOutputKey)) {
            $extensions['type'] = $typeOutputKey;
        }
        if ($field = $item[Tokens::FIELD] ?? null) {
            $extensions['field'] = $field;
        } elseif ($dynamicField = $item[Tokens::DYNAMIC_FIELD] ?? null) {
            /**
             * Print the "dynamic field"? By default it is disabled,
             * because Operation Directives are handled via
             * SuperRoot Fields, and it's confusing to print
             * that dynamic field.
             *
             * @var ModuleConfiguration
             */
            $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
            if ($moduleConfiguration->printDynamicFieldInExtensionsOutput()) {
                $extensions['dynamicField'] = $dynamicField;
            }
        }
        return $extensions;
    }

    /**
     * @param array<string,SplObjectStorage<FieldInterface,array<array<string,mixed>>>> $entries
     * @return array<int,mixed[]>
     */
    protected function reformatObjectEntries(array $entries): array
    {
        $ret = [];
        foreach ($entries as $typeOutputKey => $fieldItems) {
            /** @var FieldInterface $field */
            foreach ($fieldItems as $field) {
                /** @var array<string,mixed> */
                $items = $fieldItems[$field];
                foreach ($items as $item) {
                    $ret[] = $this->getObjectEntry($typeOutputKey, $item);
                }
            }
        }
        return $ret;
    }

    /**
     * @return array<string,mixed>
     * @param array<string,mixed> $item
     */
    protected function getObjectEntry(string $typeOutputKey, array $item): array
    {
        $entry = [
            'message' => $item[Tokens::MESSAGE],
        ];
        if ($locations = $item[Tokens::LOCATIONS]) {
            $entry['locations'] = $locations;
        }
        /**
         * Add the causes of the error, if any.
         *
         * @see https://github.com/graphql/graphql-spec/issues/893
         */
        if ($causes = $item[Tokens::CAUSES] ?? []) {
            $entry['causes'] = $causes;
        }
        if (
            $extensions = array_merge(
                $this->getObjectEntryExtensions($typeOutputKey, $item),
                $item[Tokens::EXTENSIONS] ?? []
            )
        ) {
            $entry['extensions'] = $extensions;
        }
        return $entry;
    }

    /**
     * The entry is similar to Schema, plus the
     * addition of the object ID/IDs
     *
     * @return array<string,mixed>
     * @param array<string,mixed> $item
     */
    protected function getObjectEntryExtensions(string $typeOutputKey, array $item): array
    {
        $extensions = $this->getSchemaEntryExtensions($typeOutputKey, $item);

        /** @var array<string|int> */
        $ids = $item[Tokens::IDS];
        if (count($ids) === 1) {
            $extensions['id'] = $ids[0];
        } else {
            $extensions['ids'] = $ids;
        }

        return $extensions;
    }

    /**
     * Override for GraphQL to provide custom validations.
     * Return `false` if there is an error.
     *
     * Validate Field Selection Merging: 2 different fields
     * cannot have the same name/alias on the same block in
     * the response.
     *
     * @see https://spec.graphql.org/draft/#sec-Field-Selection-Merging     *
     *
     * @param array<string,mixed> $sourceRet
     * @param array<string,mixed> $resolvedObjectRet
     * @param SplObjectStorage<FieldInterface,mixed> $resolvedObject
     */
    protected function validateObjectData(
        FieldInterface $field,
        string $typeOutputKey,
        array &$sourceRet,
        array &$resolvedObjectRet,
        SplObjectStorage $resolvedObject,
        string|int $objectID,
    ): bool {
        $sameOutputKeyField = null;
        if (array_key_exists($field->getOutputKey(), $resolvedObjectRet)) {
            /** @var ExecutableDocument */
            $executableDocument = App::getState('executable-document-ast');
            $fragments = $executableDocument->getDocument()->getFragments();

            /** @var PreviouslyResolvedFieldsForObjectsStore */
            $previouslyResolvedFieldsForObjectsStore = App::getState('previously-resolved-fields-for-objects');
            $previouslyResolvedFieldsForObject = $previouslyResolvedFieldsForObjectsStore->getForObject($typeOutputKey, $objectID);

            /**
             * Check that the original field is indeed different to this one.
             * To find out, search for the previous fields with the same
             * outputKey but different query string (hence they are different)
             */
            $differentFieldsWithSameOutputKeyForObject = array_values(array_filter(
                $previouslyResolvedFieldsForObject,
                function (FieldInterface $previousField) use ($field, $fragments): bool {
                    if ($field->getOutputKey() !== $previousField->getOutputKey()) {
                        return false;
                    }

                    /** Either they are both LeafField or RelationalField */
                    if (get_class($field) !== get_class($previousField)) {
                        return true;
                    }

                    if ($field instanceof LeafField) {
                        /** @var LeafField */
                        $leafField = $field;
                        /** @var LeafField */
                        $previousLeafField = $previousField;
                        return !$leafField->isEquivalentTo($previousLeafField);
                    }

                    /** @var RelationalField */
                    $relationalField = $field;
                    /** @var RelationalField */
                    $previousRelationalField = $previousField;
                    return !$relationalField->isEquivalentTo($previousRelationalField, $fragments);
                }
            ));
            if ($differentFieldsWithSameOutputKeyForObject !== []) {
                $sameOutputKeyField = $differentFieldsWithSameOutputKeyForObject[0];
            }
        }
        if ($sameOutputKeyField !== null) {
            /**
             * Set response to null
             */
            $resolvedObjectRet[$field->getOutputKey()] = null;

            /**
             * Add an entry on the "errors" section
             */
            $item = $this->getFeedbackEntryManager()->formatObjectOrSchemaFeedbackCommonEntry(
                $field,
                null,
                [],
                new FeedbackItemResolution(
                    GraphQLSpecErrorFeedbackItemProvider::class,
                    GraphQLSpecErrorFeedbackItemProvider::E_5_3_2,
                    [
                        $field->asFieldOutputQueryString(),
                        $objectID,
                        $sameOutputKeyField->asFieldOutputQueryString(),
                        $field->getOutputKey()
                    ]
                ),
                [$objectID],
            );

            /** @var SplObjectStorage<FieldInterface,array<array<string,mixed>>> */
            $typeFeedbackEntries = $sourceRet[self::ADDITIONAL_FEEDBACK][Response::OBJECT_FEEDBACK][FeedbackCategories::ERROR][$typeOutputKey] ?? new SplObjectStorage();
            /** @var array<array<string,mixed>> */
            $fieldTypeFeedbackEntries = $typeFeedbackEntries[$field] ?? [];
            $fieldTypeFeedbackEntries[] = $item;
            $typeFeedbackEntries[$field] = $fieldTypeFeedbackEntries;
            $sourceRet[self::ADDITIONAL_FEEDBACK][Response::OBJECT_FEEDBACK][FeedbackCategories::ERROR][$typeOutputKey] = $typeFeedbackEntries;
            return false;
        }
        return parent::validateObjectData(
            $field,
            $typeOutputKey,
            $sourceRet,
            $resolvedObjectRet,
            $resolvedObject,
            $objectID,
        );
    }

    /**
     * Print an empty response as object, not as array.
     *
     * @param array<string,mixed> $data
     */
    public function getOutputContent(array &$data): string
    {
        if ($data === []) {
            return (string)json_encode($data, JSON_FORCE_OBJECT);
        }
        return parent::getOutputContent($data);
    }
}
