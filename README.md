# Migration rollback with one simple command for Neos Flow

Rollback the latest executed Doctrine migration by running

```
./flow doctrine:rollback
```

## Installation

```
composer require websupply/doctrine-rollback
```

## How can this package help you?
Did you execute a database migration while developing, and want to run the `down` (rollback) direction, the command looks as such

```
./flow doctrine:migrationexecute --version <your-migration-version> --direction down
```

With this package, you can run 

```
./flow doctrine:rollback
```

And the latest applied migration, will be executed with the `down` direction

## Dry run
By setting the `--dry-run` argument, the command will outpt what version it was meant to execute, but not actually execute it

## Example output

This output, is based on running the migrations from Flows base distribution

```
$ ./flow doctrine:migrate

[HERE COMES ALL THE MIGRATION EXECUTED]

[warning] Migration Neos\Flow\Persistence\Doctrine\Migrations\Version20150611154419 was executed but did not result in any SQL statements.
[notice] finished in 2001.8ms, used 24M memory, 27 migrations executed, 94 sql queries
```
Now we are setup

```
$ ./flow doctrine:rollback --dry-run
DRY RUN: Rollback migration "Neos\Flow\Persistence\Doctrine\Migrations\Version20200908155620"
```
A dry run, telling us that version `20200908155620` will be the one to rollback

```
$ ./flow doctrine:rollback
Migrating down to Neos\Flow\Persistence\Doctrine\Migrations\Version20200908155620

     -> ALTER TABLE neos_flow_resourcemanagement_persistentresource ADD md5 VARCHAR(32) NOT NULL

[notice] finished in 151.7ms, used 24M memory, 1 migrations executed, 1 sql queries
```
Executing the rollback, rolling back the migration expected

```
$ ./flow doctrine:migrate
Migrating up to Neos\Flow\Persistence\Doctrine\Migrations\Version20200908155620

     -> ALTER TABLE neos_flow_resourcemanagement_persistentresource DROP md5

[notice] finished in 204.9ms, used 22M memory, 1 migrations executed, 1 sql queries
```
And running the `doctrine:migrate` command again, to re-apply it (imagine, that you changed something in your migration)

## Support and sponsoring
Work on this package is supported by the danish web company **WebSupply ApS** 
