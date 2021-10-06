<?php

declare(strict_types=1);

namespace PoPSchema\Media\FieldResolvers\ObjectType;

use PoP\ComponentModel\FieldResolvers\ObjectType\AbstractQueryableObjectTypeFieldResolver;
use PoP\ComponentModel\FilterInput\FilterInputHelper;
use PoP\ComponentModel\Schema\SchemaTypeModifiers;
use PoP\ComponentModel\TypeResolvers\ConcreteTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ObjectType\ObjectTypeResolverInterface;
use PoP\Engine\TypeResolvers\ObjectType\RootObjectTypeResolver;
use PoP\Engine\TypeResolvers\ScalarType\IntScalarTypeResolver;
use PoPSchema\Media\ComponentConfiguration;
use PoPSchema\Media\ModuleProcessors\FormInputs\FilterInputModuleProcessor;
use PoPSchema\Media\ModuleProcessors\MediaFilterInputContainerModuleProcessor;
use PoPSchema\Media\TypeAPIs\MediaTypeAPIInterface;
use PoPSchema\Media\TypeResolvers\ObjectType\MediaObjectTypeResolver;
use PoPSchema\SchemaCommons\Constants\QueryOptions;
use PoPSchema\SchemaCommons\DataLoading\ReturnTypes;
use PoPSchema\SchemaCommons\ModuleProcessors\CommonFilterInputContainerModuleProcessor;
use PoPSchema\SchemaCommons\ModuleProcessors\FormInputs\CommonFilterInputModuleProcessor;
use PoPSchema\SchemaCommons\Resolvers\WithLimitFieldArgResolverTrait;
use Symfony\Contracts\Service\Attribute\Required;

class RootObjectTypeFieldResolver extends AbstractQueryableObjectTypeFieldResolver
{
    use WithLimitFieldArgResolverTrait;

    protected MediaTypeAPIInterface $mediaTypeAPI;
    protected IntScalarTypeResolver $intScalarTypeResolver;
    protected MediaObjectTypeResolver $mediaObjectTypeResolver;

    #[Required]
    final public function autowireRootObjectTypeFieldResolver(
        MediaTypeAPIInterface $mediaTypeAPI,
        IntScalarTypeResolver $intScalarTypeResolver,
        MediaObjectTypeResolver $mediaObjectTypeResolver,
    ): void {
        $this->mediaTypeAPI = $mediaTypeAPI;
        $this->intScalarTypeResolver = $intScalarTypeResolver;
        $this->mediaObjectTypeResolver = $mediaObjectTypeResolver;
    }

    public function getObjectTypeResolverClassesToAttachTo(): array
    {
        return [
            RootObjectTypeResolver::class,
        ];
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            'mediaItem',
            'mediaItems',
            'mediaItemCount',
        ];
    }

    public function getFieldDescription(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?string
    {
        return match ($fieldName) {
            'mediaItem' => $this->translationAPI->__('Get a media item', 'media'),
            'mediaItems' => $this->translationAPI->__('Get the media items', 'media'),
            'mediaItemCount' => $this->translationAPI->__('Number of media items', 'media'),
            default => parent::getFieldDescription($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeResolver(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ConcreteTypeResolverInterface
    {
        return match ($fieldName) {
            'mediaItems',
            'mediaItem'
                => $this->mediaObjectTypeResolver,
            'mediaItemCount'
                => $this->intScalarTypeResolver,
            default
                => parent::getFieldTypeResolver($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldTypeModifiers(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): int
    {
        return match ($fieldName) {
            'mediaItems' => SchemaTypeModifiers::NON_NULLABLE | SchemaTypeModifiers::IS_ARRAY,
            'mediaItemCount' => SchemaTypeModifiers::NON_NULLABLE,
            default => parent::getFieldTypeModifiers($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldFilterInputContainerModule(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName): ?array
    {
        return match ($fieldName) {
            'mediaItems' => [MediaFilterInputContainerModuleProcessor::class, MediaFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_MEDIAITEMS],
            'mediaItemCount' => [MediaFilterInputContainerModuleProcessor::class, MediaFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_MEDIAITEMCOUNT],
            'mediaItem' => [CommonFilterInputContainerModuleProcessor::class, CommonFilterInputContainerModuleProcessor::MODULE_FILTERINPUTCONTAINER_ENTITY_BY_ID],
            default => parent::getFieldFilterInputContainerModule($objectTypeResolver, $fieldName),
        };
    }

    public function getFieldArgDefaultValue(ObjectTypeResolverInterface $objectTypeResolver, string $fieldName, string $fieldArgName): mixed
    {
        switch ($fieldName) {
            case 'mediaItems':
            case 'mediaItemCount':
                // Assign a default value to "mimeTypes"
                $mimeTypeFilterInputName = FilterInputHelper::getFilterInputName([
                    FilterInputModuleProcessor::class,
                    FilterInputModuleProcessor::MODULE_FILTERINPUT_MIME_TYPES
                ]);
                if ($fieldArgName === $mimeTypeFilterInputName) {
                    return ['image'];
                }
                break;
        }
        switch ($fieldName) {
            case 'mediaItems':
                $limitFilterInputName = FilterInputHelper::getFilterInputName([
                    CommonFilterInputModuleProcessor::class,
                    CommonFilterInputModuleProcessor::MODULE_FILTERINPUT_LIMIT
                ]);
                if ($fieldArgName === $limitFilterInputName) {
                    return ComponentConfiguration::getMediaListDefaultLimit();
                }
                break;
        }
        return parent::getFieldArgDefaultValue($objectTypeResolver, $fieldName, $fieldArgName);
    }

    /**
     * Validate the constraints for a field argument
     *
     * @return string[] Error messages
     */
    public function validateFieldArgument(
        ObjectTypeResolverInterface $objectTypeResolver,
        string $fieldName,
        string $fieldArgName,
        mixed $fieldArgValue
    ): array {
        $errors = parent::validateFieldArgument(
            $objectTypeResolver,
            $fieldName,
            $fieldArgName,
            $fieldArgValue,
        );

        // Check the "limit" fieldArg
        switch ($fieldName) {
            case 'mediaItems':
                if (
                    $maybeError = $this->maybeValidateLimitFieldArgument(
                        ComponentConfiguration::getMediaListMaxLimit(),
                        $fieldName,
                        $fieldArgName,
                        $fieldArgValue
                    )
                ) {
                    $errors[] = $maybeError;
                }
                break;
        }
        return $errors;
    }

    /**
     * @param array<string, mixed> $fieldArgs
     * @param array<string, mixed>|null $variables
     * @param array<string, mixed>|null $expressions
     * @param array<string, mixed> $options
     */
    public function resolveValue(
        ObjectTypeResolverInterface $objectTypeResolver,
        object $object,
        string $fieldName,
        array $fieldArgs = [],
        ?array $variables = null,
        ?array $expressions = null,
        array $options = []
    ): mixed {
        $query = $this->convertFieldArgsToFilteringQueryArgs($objectTypeResolver, $fieldName, $fieldArgs);
        switch ($fieldName) {
            case 'mediaItemCount':
                return $this->mediaTypeAPI->getMediaItemCount($query);
            case 'mediaItems':
                return $this->mediaTypeAPI->getMediaItems($query, [QueryOptions::RETURN_TYPE => ReturnTypes::IDS]);
            case 'mediaItem':
                if ($mediaItems = $this->mediaTypeAPI->getMediaItems($query, [QueryOptions::RETURN_TYPE => ReturnTypes::IDS])) {
                    return $mediaItems[0];
                }
                return null;
        }

        return parent::resolveValue($objectTypeResolver, $object, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}
