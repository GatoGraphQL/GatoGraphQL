<?php

declare(strict_types=1);

namespace PoP\ComponentModel\HelperServices;

use PoP\ComponentModel\App;
use PoP\ComponentModel\Constants\QuerySyntax;
use PoP\GraphQLParser\Component;
use PoP\GraphQLParser\ComponentConfiguration;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\Variable;

class QueryHelperService implements QueryHelperServiceInterface
{
    public function isDynamicVariableReference(
        string $name,
        ?Variable $variable,
    ): bool {
        /** @var ComponentConfiguration */
        $componentConfiguration = App::getComponent(Component::class)->getConfiguration();
        if (!$componentConfiguration->enableDynamicVariables()) {
            return false;
        }

        return $variable === null
            && \str_starts_with(
                $name,
                QuerySyntax::DYNAMIC_VARIABLE_NAME_PREFIX
            );
    }
}
