<?php

namespace OpenConext\EngineBlock\Metadata\MetadataRepository;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\DBAL\Query\QueryBuilder;
use Mockery;
use OpenConext\EngineBlock\Metadata\Entity\ServiceProvider;
use OpenConext\EngineBlock\Metadata\MetadataRepository\Visitor\DisableDisallowedEntitiesInWayfVisitor;
use PHPUnit_Framework_TestCase;

/**
 * Class CachedDoctrineMetadataRepositoryTest
 * @package OpenConext\EngineBlock\Metadata\MetadataRepository
 */
class CachedDoctrineMetadataRepositoryTest extends PHPUnit_Framework_TestCase
{
    public function testMethodsCallsAreProxied()
    {
        $doctrineRepository = Mockery::mock('OpenConext\EngineBlock\Metadata\MetadataRepository\DoctrineMetadataRepository');
        $doctrineRepository->shouldReceive('findIdentityProviderByEntityId');
        $doctrineRepository->shouldReceive('findServiceProviderByEntityId');
        $doctrineRepository->shouldReceive('findIdentityProviderByEntityId');
        $doctrineRepository->shouldReceive('findIdentityProviders');
        $doctrineRepository->shouldReceive('findIdentityProvidersByEntityId');
        $doctrineRepository->shouldReceive('findAllIdentityProviderEntityIds');
        $doctrineRepository->shouldReceive('findReservedSchacHomeOrganizations');
        $doctrineRepository->shouldReceive('findEntitiesPublishableInEdugain');

        $repository = new CachedDoctrineMetadataRepository($doctrineRepository);
        $repository->findIdentityProviderByEntityId('test');
        $repository->findServiceProviderByEntityId('test');
        $repository->findIdentityProviderByEntityId('test');
        $repository->findIdentityProviders();
        $repository->findIdentityProvidersByEntityId(['test']);
        $repository->findAllIdentityProviderEntityIds();
        $repository->findReservedSchacHomeOrganizations();
        $repository->findEntitiesPublishableInEdugain();
    }

    public function testFetchIdentityProviderThrowExceptions()
    {
        $doctrineRepository = Mockery::mock('OpenConext\EngineBlock\Metadata\MetadataRepository\DoctrineMetadataRepository');
        $doctrineRepository->shouldReceive('findIdentityProviderByEntityId');

        $this->setExpectedException('OpenConext\\EngineBlock\\Metadata\\MetadataRepository\\EntityNotFoundException');

        $repository = new CachedDoctrineMetadataRepository($doctrineRepository);
        $repository->fetchIdentityProviderByEntityId('test');
    }

    public function testFetchServiceProviderThrowExceptions()
    {
        $doctrineRepository = Mockery::mock('OpenConext\EngineBlock\Metadata\MetadataRepository\DoctrineMetadataRepository');
        $doctrineRepository->shouldReceive('findServiceProviderByEntityId');

        $this->setExpectedException('OpenConext\\EngineBlock\\Metadata\\MetadataRepository\\EntityNotFoundException');

        $repository = new CachedDoctrineMetadataRepository($doctrineRepository);
        $repository->fetchServiceProviderByEntityId('test');
    }
}
