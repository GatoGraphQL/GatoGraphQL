<?php

declare(strict_types=1);

namespace PoP\GraphQLParser\ExtendedSpec\Parser\Ast;

use PoP\GraphQLParser\Module;
use PoP\GraphQLParser\ModuleConfiguration;
use PoP\GraphQLParser\ExtendedSpec\Parser\Ast\ArgumentValue\DynamicVariableReferenceInterface;
use PoP\GraphQLParser\Spec\Parser\Ast\ArgumentValue\VariableReference;
use PoP\GraphQLParser\Spec\Parser\Ast\Document as UpstreamDocument;
use PoP\Root\App;

class Document extends UpstreamDocument
{
    /**
     * Do not validate if dynamic variables have been
     * defined in the Operation
     */
    protected function isVariableDefined(
        VariableReference $variableReference,
    ): bool {
        /** @var ModuleConfiguration */
        $moduleConfiguration = App::getComponent(Module::class)->getConfiguration();
        if ($moduleConfiguration->enableDynamicVariables() && $variableReference instanceof DynamicVariableReferenceInterface) {
            return true;
        }
        return parent::isVariableDefined($variableReference);
    }
}
