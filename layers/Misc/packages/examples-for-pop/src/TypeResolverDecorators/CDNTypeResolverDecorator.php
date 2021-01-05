<?php

declare(strict_types=1);

namespace Leoloso\ExamplesForPoP\TypeResolverDecorators;

use PoPSchema\Media\TypeResolvers\MediaTypeResolver;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoPSchema\CDNDirective\DirectiveResolvers\CDNDirectiveResolver;
use PoP\ComponentModel\Facades\Schema\FieldQueryInterpreterFacade;
use PoPSchema\CDNDirective\ComponentConfiguration as CDNComponentConfiguration;
use PoP\AccessControl\TypeResolverDecorators\AbstractPublicSchemaTypeResolverDecorator;

class CDNTypeResolverDecorator extends AbstractPublicSchemaTypeResolverDecorator
{
    public static function getClassesToAttachTo(): array
    {
        return [
            MediaTypeResolver::class,
        ];
    }

    /**
     * If the from/to URLs were defined for the CDN, then attach the CDN directive
     * to the corresponding fields
     *
     * @param TypeResolverInterface $typeResolver
     * @return array
     */
    public function getMandatoryDirectivesForFields(TypeResolverInterface $typeResolver): array
    {
        $mandatoryDirectivesForFields = [];
        if (
            CDNComponentConfiguration::getFromURLSection() &&
            CDNComponentConfiguration::getToURLSection()
        ) {
            // Add the mapping
            $fieldQueryInterpreter = FieldQueryInterpreterFacade::getInstance();
            $cdnDirective = $fieldQueryInterpreter->getDirective(
                CDNDirectiveResolver::getDirectiveName()
            );
            $mandatoryDirectivesForFields['src'] = [
                $cdnDirective,
            ];
        }
        return $mandatoryDirectivesForFields;
    }
}
