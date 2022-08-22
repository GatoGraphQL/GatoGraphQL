<?php

declare(strict_types=1);

namespace PoP\ComponentModel\ObjectSerialization;

class ObjectSerializationManager implements ObjectSerializationManagerInterface
{
    /**
     * @var array<string,ObjectSerializerInterface>
     */
    public array $objectSerializers = [];

    final public function addObjectSerializer(ObjectSerializerInterface $objectSerializer): void
    {
        $this->objectSerializers[$objectSerializer->getObjectClassToSerialize()] = $objectSerializer;
    }

    public function serialize(object $object): string
    {
        // Find the Serialize that serializes this object
        $objectSerializer = null;
        /** @var string|false */
        $classToSerialize = $object::class;
        while ($objectSerializer === null && $classToSerialize !== false) {
            $objectSerializer = $this->objectSerializers[$classToSerialize] ?? null;
            $classToSerialize = \get_parent_class($classToSerialize);
        }
        if ($objectSerializer !== null) {
            return $objectSerializer->serialize($object);
        }

        /**
         * No Serializer found. Then call the '__serialize' method of the object,
         * expecting it to implement it. If it doesn't, it will throw an exception,
         * so the developer will be made aware to create the corresponding Serializer
         * for that object class
         */
        return $object->__serialize();
    }
}
