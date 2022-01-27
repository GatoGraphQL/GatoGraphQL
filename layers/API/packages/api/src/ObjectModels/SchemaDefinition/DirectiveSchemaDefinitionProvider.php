<?php

declare(strict_types=1);

namespace PoPAPI\API\ObjectModels\SchemaDefinition;

use PoP\Root\App;
use PoPAPI\API\Schema\SchemaDefinition;
use PoPAPI\API\Schema\SchemaDefinitionHelpers;
use PoP\ComponentModel\Component;
use PoP\ComponentModel\ComponentConfiguration;
use PoP\ComponentModel\DirectiveResolvers\DirectiveResolverInterface;
use PoP\Root\Facades\Instances\InstanceManagerFacade;
use PoP\ComponentModel\TypeResolvers\RelationalTypeResolverInterface;
use PoP\ComponentModel\TypeResolvers\ScalarType\DangerouslyDynamicScalarTypeResolver;

class DirectiveSchemaDefinitionProvider extends AbstractSchemaDefinitionProvider implements SchemaDefinitionProviderInterface
{
    public function __construct(
        protected DirectiveResolverInterface $directiveResolver,
        protected RelationalTypeResolverInterface $relationalTypeResolver,
    ) {
    }

    public function getSchemaDefinition(): array
    {
        $schemaDefinition = $this->directiveResolver->getDirectiveSchemaDefinition($this->relationalTypeResolver);

        $dangerouslyDynamicScalarTypeResolver = null;
        /** @var ComponentConfiguration */
        $componentConfiguration = App::getComponent(Component::class)->getConfiguration();
        if ($skipExposingDangerouslyDynamicScalarTypeInSchema = $componentConfiguration->skipExposingDangerouslyDynamicScalarTypeInSchema()) {
            $instanceManager = InstanceManagerFacade::getInstance();
            /** @var DangerouslyDynamicScalarTypeResolver */
            $dangerouslyDynamicScalarTypeResolver = $instanceManager->getInstance(DangerouslyDynamicScalarTypeResolver::class);
        }

        foreach (($schemaDefinition[SchemaDefinition::ARGS] ?? []) as $directiveArgName => &$directiveArgSchemaDefinition) {
            $directiveArgTypeResolver = $directiveArgSchemaDefinition[SchemaDefinition::TYPE_RESOLVER];

            /**
             * If the directive arg must not be exposed, then remove it from the schema
             */
            $skipExposingDangerousDynamicType =
                $skipExposingDangerouslyDynamicScalarTypeInSchema
                && $directiveArgTypeResolver === $dangerouslyDynamicScalarTypeResolver;
            if ($skipExposingDangerousDynamicType || $this->directiveResolver->skipExposingDirectiveArgInSchema($this->relationalTypeResolver, $directiveArgName)) {
                unset($schemaDefinition[SchemaDefinition::ARGS][$directiveArgName]);
                continue;
            }

            $this->accessedTypeAndDirectiveResolvers[$directiveArgTypeResolver::class] = $directiveArgTypeResolver;
            SchemaDefinitionHelpers::replaceTypeResolverWithTypeProperties($schemaDefinition[SchemaDefinition::ARGS][$directiveArgName]);
        }

        return $schemaDefinition;
    }
}
