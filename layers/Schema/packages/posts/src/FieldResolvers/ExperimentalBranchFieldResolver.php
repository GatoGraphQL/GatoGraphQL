<?php

declare(strict_types=1);

namespace PoPSchema\Posts\FieldResolvers;

use PoPSchema\Posts\TypeResolvers\PostTypeResolver;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\Translation\Facades\TranslationAPIFacade;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoPSchema\CustomPosts\FieldResolvers\CustomPostFieldResolver;

class ExperimentalBranchFieldResolver extends CustomPostFieldResolver
{
    /**
     * Attach to Posts only
     */
    public function getClassesToAttachTo(): array
    {
        return [
            PostTypeResolver::class,
        ];
    }

    /**
     * The priority with which to attach to the class. The higher the priority, the sooner it will be processed
     * Have a higher priority than the class it extends, as to override it
     *
     * @return integer|null
     */
    public function getPriorityToAttachToClasses(): int
    {
        return 20;
    }

    public function resolveCanProcess(TypeResolverInterface $typeResolver, string $fieldName, array $fieldArgs = []): bool
    {
        // Must specify fieldArg 'branch' => 'experimental'
        return isset($fieldArgs['branch']) && $fieldArgs['branch'] == 'experimental';
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            'excerpt',
        ];
    }

    public function getImplementedFieldInterfaceResolverClasses(): array
    {
        return [];
    }

    public function getSchemaFieldArgs(TypeResolverInterface $typeResolver, string $fieldName): array
    {
        $schemaFieldArgs = parent::getSchemaFieldArgs($typeResolver, $fieldName);
        $translationAPI = TranslationAPIFacade::getInstance();
        switch ($fieldName) {
            case 'excerpt':
                return array_merge(
                    $schemaFieldArgs,
                    [
                        [
                            SchemaDefinition::ARGNAME_NAME => 'branch',
                            SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_STRING,
                            SchemaDefinition::ARGNAME_DESCRIPTION => $translationAPI->__('The branch name, set to value \'experimental\', enabling to use this fieldResolver', 'pop-posts'),
                        ],
                        [
                            SchemaDefinition::ARGNAME_NAME => 'length',
                            SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_INT,
                            SchemaDefinition::ARGNAME_DESCRIPTION => $translationAPI->__('Maximum length for the except, in number of characters', 'pop-posts'),
                        ],
                        [
                            SchemaDefinition::ARGNAME_NAME => 'more',
                            SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_STRING,
                            SchemaDefinition::ARGNAME_DESCRIPTION => $translationAPI->__('String to append at the end of the excerpt (if it is shortened by the \'length\' parameter)', 'pop-posts'),
                        ],
                    ]
                );
        }

        return $schemaFieldArgs;
    }

    /**
     * @param array<string, mixed> $fieldArgs
     * @param array<string, mixed>|null $variables
     * @param array<string, mixed>|null $expressions
     * @param array<string, mixed> $options
     * @return mixed
     */
    public function resolveValue(
        TypeResolverInterface $typeResolver,
        object $resultItem,
        string $fieldName,
        array $fieldArgs = [],
        ?array $variables = null,
        ?array $expressions = null,
        array $options = []
    ) {
        switch ($fieldName) {
            case 'excerpt':
                // Obtain the required parameter values (or default to some basic values)
                $length = $fieldArgs['length'] ?? 100;
                $more = $fieldArgs['more'] ?? '';
                $excerpt = parent::resolveValue($typeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
                return (strlen($excerpt) > $length) ? mb_substr($excerpt, 0, $length) . $more : $excerpt;
        }

        return parent::resolveValue($typeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }
}
