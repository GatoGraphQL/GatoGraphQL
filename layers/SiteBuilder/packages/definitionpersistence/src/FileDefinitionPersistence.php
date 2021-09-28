<?php

declare(strict_types=1);

namespace PoP\DefinitionPersistence;

use PoP\FileStore\File\AbstractFile;
use PoP\FileStore\Store\FileStoreInterface;
use PoP\Definitions\AbstractDefinitionPersistence;

class FileDefinitionPersistence extends AbstractDefinitionPersistence
{
    protected FileStoreInterface $fileStore;
    protected AbstractFile $file;
    
    #[\Symfony\Contracts\Service\Attribute\Required]
    public function autowireFileDefinitionPersistence(
        FileStoreInterface $fileStore,
        AbstractFile $file
    ) {
        $this->fileStore = $fileStore;
        $this->file = $file;
    }

    protected function getPersistedData(): array
    {
        return (array)$this->fileStore->get($this->file);
    }

    protected function persist(array $data): void
    {
        $this->fileStore->save($this->file, $data);
    }
}
