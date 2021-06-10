<?php

declare(strict_types=1);

namespace PoPSchema\Media\FieldResolvers;

use PoP\ComponentModel\FieldResolvers\AbstractDBDataFieldResolver;
use PoP\ComponentModel\Instances\InstanceManagerInterface;
use PoP\ComponentModel\Schema\FieldQueryInterpreterInterface;
use PoP\ComponentModel\Schema\SchemaDefinition;
use PoP\ComponentModel\Schema\SchemaHelpers;
use PoP\ComponentModel\TypeResolvers\TypeResolverInterface;
use PoP\Engine\CMS\CMSServiceInterface;
use PoP\Hooks\HooksAPIInterface;
use PoP\LooseContracts\NameResolverInterface;
use PoP\Translation\TranslationAPIInterface;
use PoPSchema\Media\Enums\MediaDeviceEnum;
use PoPSchema\Media\TypeAPIs\MediaTypeAPIInterface;
use PoPSchema\Media\TypeResolvers\MediaTypeResolver;

class MediaFieldResolver extends AbstractDBDataFieldResolver
{
    function __construct(
        TranslationAPIInterface $translationAPI,
        HooksAPIInterface $hooksAPI,
        InstanceManagerInterface $instanceManager,
        FieldQueryInterpreterInterface $fieldQueryInterpreter,
        NameResolverInterface $nameResolver,
        CMSServiceInterface $cmsService,
        protected MediaTypeAPIInterface $mediaTypeAPI,
    ) {
        parent::__construct(
            $translationAPI,
            $hooksAPI,
            $instanceManager,
            $fieldQueryInterpreter,
            $nameResolver,
            $cmsService,
        );
    }
    
    public function getClassesToAttachTo(): array
    {
        return array(MediaTypeResolver::class);
    }

    public function getFieldNamesToResolve(): array
    {
        return [
            'src',
            'width',
            'height',
        ];
    }

    public function getSchemaFieldType(TypeResolverInterface $typeResolver, string $fieldName): string
    {
        $types = [
            'src' => SchemaDefinition::TYPE_URL,
            'width' => SchemaDefinition::TYPE_INT,
            'height' => SchemaDefinition::TYPE_INT,
        ];
        return $types[$fieldName] ?? parent::getSchemaFieldType($typeResolver, $fieldName);
    }

    public function isSchemaFieldResponseNonNullable(TypeResolverInterface $typeResolver, string $fieldName): bool
    {
        $nonNullableFieldNames = [
            'src',
        ];
        if (in_array($fieldName, $nonNullableFieldNames)) {
            return true;
        }
        return parent::isSchemaFieldResponseNonNullable($typeResolver, $fieldName);
    }

    public function getSchemaFieldDescription(TypeResolverInterface $typeResolver, string $fieldName): ?string
    {
        $descriptions = [
            'src' => $this->translationAPI->__('Media element URL source', 'pop-media'),
            'width' => $this->translationAPI->__('Media element\'s width', 'pop-media'),
            'height' => $this->translationAPI->__('Media element\'s height', 'pop-media'),
        ];
        return $descriptions[$fieldName] ?? parent::getSchemaFieldDescription($typeResolver, $fieldName);
    }

    /**
     * @param array<string, mixed> $fieldArgs
     * @param array<string, mixed>|null $variables
     * @param array<string, mixed>|null $expressions
     * @param array<string, mixed> $options
     */
    public function resolveValue(
        TypeResolverInterface $typeResolver,
        object $resultItem,
        string $fieldName,
        array $fieldArgs = [],
        ?array $variables = null,
        ?array $expressions = null,
        array $options = []
    ): mixed {
        $media = $resultItem;
        switch ($fieldName) {
            case 'src':
            case 'width':
            case 'height':
                $size = $this->obtainImageSizeFromParameters($fieldArgs);
                $properties = $this->mediaTypeAPI->getImageProperties($typeResolver->getID($media), $size);
                return $properties[$fieldName];
        }

        return parent::resolveValue($typeResolver, $resultItem, $fieldName, $fieldArgs, $variables, $expressions, $options);
    }

    /**
     * Get parameter "size" based on its value, or the one for "device"
     *
     * @param array<string, mixed> $fieldArgs
     */
    protected function obtainImageSizeFromParameters(array $fieldArgs = []): ?string
    {
        // To calculate the size, offer the following strategies:
        // 1. Set it explicitly
        if (isset($fieldArgs['size'])) {
            return $fieldArgs['size'];
        }

        // 2. Return an optimized size according to the "device"
        if (isset($fieldArgs['device'])) {
            if ($fieldArgs['device'] == MediaDeviceEnum::AUTOMATIC) {
                $useragent = $_SERVER['HTTP_USER_AGENT'];
                if (preg_match('/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows (ce|phone)|xda|xiino/i', $useragent) || preg_match('/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i', substr($useragent, 0, 4))) {
                    $device = MediaDeviceEnum::MOBILE;
                } else {
                    $device = MediaDeviceEnum::DESKTOP;
                }
            } else {
                $device = $fieldArgs['device'];
            }

            if ($device == MediaDeviceEnum::MOBILE) {
                return 'medium';
            } elseif ($device == MediaDeviceEnum::DESKTOP) {
                return 'large';
            }
        }

        return null;
    }

    public function getSchemaFieldArgs(TypeResolverInterface $typeResolver, string $fieldName): array
    {
        $schemaFieldArgs = parent::getSchemaFieldArgs($typeResolver, $fieldName);
        switch ($fieldName) {
            case 'src':
            case 'width':
            case 'height':
                /**
                 * @var MediaDeviceEnum
                 */
                $mediaDeviceEnum = $this->instanceManager->getInstance(MediaDeviceEnum::class);
                return array_merge(
                    $schemaFieldArgs,
                    [
                        [
                            SchemaDefinition::ARGNAME_NAME => 'size',
                            SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_STRING,
                            SchemaDefinition::ARGNAME_DESCRIPTION => $this->translationAPI->__('Size of the image', 'pop-media'),
                        ],
                        [
                            SchemaDefinition::ARGNAME_NAME => 'device',
                            SchemaDefinition::ARGNAME_TYPE => SchemaDefinition::TYPE_ENUM,
                            SchemaDefinition::ARGNAME_DESCRIPTION => $this->translationAPI->__('Device where to show the image', 'pop-media'),
                            SchemaDefinition::ARGNAME_ENUM_NAME => $mediaDeviceEnum->getName(),
                            SchemaDefinition::ARGNAME_ENUM_VALUES => SchemaHelpers::convertToSchemaFieldArgEnumValueDefinitions(
                                $mediaDeviceEnum->getValues()
                            ),
                        ],
                    ]
                );
        }

        return $schemaFieldArgs;
    }
}
