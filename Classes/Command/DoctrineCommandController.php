<?php

namespace WebSupply\DoctrineRollback\Command;

use Doctrine\DBAL\Exception;
use Doctrine\Migrations\Metadata\ExecutedMigration;
use Neos\Flow\Annotations as Flow;
use Neos\Flow\Cli\CommandController;
use WebSupply\DoctrineRollback\DoctrineService;

#[Flow\Scope("singleton")]
final class DoctrineCommandController extends CommandController
{
    #[Flow\Inject]
    protected DoctrineService $doctrineService;

    /**
     * Rollback latest executed migration
     *
     * Based on the executedAt property of executed migrations,
     * the latest migration will be executed, using the `down`
     * direction.
     *
     * @param bool $dryRun If set, the migration will not be executed, and the output will only display what version was expected to be executed
     *
     * @see neos.flow:doctrine:migrate
     * @see neos.flow:doctrine:migrationexecute
     * @return void
     */
    public function rollbackCommand(bool $dryRun = false): void
    {
        $executedMigrations = $this->doctrineService->getExecutedMigrations()->getItems();
        uasort($executedMigrations, function (ExecutedMigration $a, ExecutedMigration $b) {
            return ($a->getExecutedAt() === $b->getExecutedAt()) ? 0 : (($a->getExecutedAt() > $b->getExecutedAt()) ? 1 : -1);
        });

        $last = end($executedMigrations);
        if (!$last) {
            $this->outputLine('No migration to rollback');
            $this->quit(1);
        }

        if ($dryRun) {
            $this->outputLine('DRY RUN: Rollback migration "%s"', [(string) $last->getVersion()]);
            $this->quit();
        }

        try {
            $result = $this->doctrineService->executeMigration((string) $last->getVersion(), 'down');
            $this->output($result);
        } catch (Exception $exception) {
            $this->outputLine('<error>Rollback failed</error>');
            $this->outputLine($exception->getMessage());
            $this->quit(1);
        }
    }
}
