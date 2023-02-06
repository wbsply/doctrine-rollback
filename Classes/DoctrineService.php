<?php

namespace WebSupply\DoctrineRollback;

use Neos\Flow\Annotations as Flow;
use Doctrine\Migrations\Metadata\ExecutedMigrationsList;
use Neos\Flow\Persistence\Doctrine\Service;

#[Flow\Scope("singleton")]
final class DoctrineService extends Service
{
    public function getExecutedMigrations(): ExecutedMigrationsList
    {
        return $this->getDependencyFactory()->getMetadataStorage()->getExecutedMigrations();
    }
}
