<?php

declare(strict_types=1);

namespace PoPAPI\API\ObjectModels\SchemaDefinition;

use PoP\ComponentModel\DirectiveResolvers\FieldDirectiveResolverInterface;
use PoP\ComponentModel\Module;
use PoP\ComponentModel\ModuleConfiguration;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\DangerouslyNonSpecificScalarTypeScalarTypeResolver;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\Root\App;
use PoP\Root\Facades\Instances\InstanceManagerFacade;
use PoPAPI\API\Schema\SchemaDefinition;
use PoPAPI\API\Schema\SchemaDefinitionHelpers;

class DirectiveSchemaDefinitionProvider extends AbstractSchemaDefinitionProvider implements SchemaDefinitionProviderInterface
{
    public function __construct(
        protected FieldDirectiveResolverInterface $directiveResolver,
        protected RelationalTypeResolverInterface $relationalTypeResolver,
    ) {
    }

    /**
     * @return array<string,mixed>
     */
    public function getSchemaDefinition(): array
    {
        $schemaDefinition = $this->directiveResolver->getDirectiveSchemaDefinition($this->relationalTypeResolver);

        $dangerouslyNonSpecificScalarTypeScalarTypeResolver = null;
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getModule(Module::class)->getConfiguration();
        if ($skipExposingDangerouslyNonSpecificScalarTypeTypeInSchema = $moduleConfiguration->skipExposingDangerouslyNonSpecificScalarTypeTypeInSchema()) {
            $instanceManager = InstanceManagerFacade::getInstance();
            /** @var DangerouslyNonSpecificScalarTypeScalarTypeResolver */
            $dangerouslyNonSpecificScalarTypeScalarTypeResolver = $instanceManager->getInstance(DangerouslyNonSpecificScalarTypeScalarTypeResolver::class);
        }

        foreach (($schemaDefinition[SchemaDefinition::ARGS] ?? []) as $directiveArgName => &$directiveArgSchemaDefinition) {
            /** @var TypeResolverInterface */
            $directiveArgTypeResolver = $directiveArgSchemaDefinition[SchemaDefinition::TYPE_RESOLVER];

            /**
             * If the directive arg must not be exposed, then remove it from the schema
             */
            $skipExposingDangerousDynamicType =
                $skipExposingDangerouslyNonSpecificScalarTypeTypeInSchema
                && $directiveArgTypeResolver === $dangerouslyNonSpecificScalarTypeScalarTypeResolver;
            if ($skipExposingDangerousDynamicType || $this->directiveResolver->skipExposingDirectiveArgInSchema($this->relationalTypeResolver, $directiveArgName)) {
                unset($schemaDefinition[SchemaDefinition::ARGS][$directiveArgName]);
                continue;
            }

            $this->accessedTypeAndFieldDirectiveResolvers[$directiveArgTypeResolver::class] = $directiveArgTypeResolver;
            SchemaDefinitionHelpers::replaceTypeResolverWithTypeProperties($schemaDefinition[SchemaDefinition::ARGS][$directiveArgName]);
        }

        return $schemaDefinition;
    }
}
