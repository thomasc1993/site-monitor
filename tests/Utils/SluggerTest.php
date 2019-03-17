<?php
namespace App\Tests\Utils;

use App\Entity\Site;
use App\Repository\SiteRepository;
use App\Utils\Slugger;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class SluggerTest extends TestCase
{
    public function testReplacesSpacesWithDashes()
    {
        $insertedString = 'slug with spaces';
        $expectedResult = 'slug-with-spaces';
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $slugger = new Slugger($entityManager);
        $slug = $slugger->generateSlug($insertedString);
        $this->assertEquals($expectedResult, $slug);
    }

    public function testReturnsAllLowercase()
    {
        $insertedString = 'SLUG';
        $expectedResult = 'slug';
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $slugger = new Slugger($entityManager);
        $slug = $slugger->generateSlug($insertedString);
        $this->assertEquals($expectedResult, $slug);
    }

    public function testRemovesRepeatingDashes()
    {
        $insertedString = 'test & slug';
        $expectedResult = 'test-slug';
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $slugger = new Slugger($entityManager);
        $slug = $slugger->generateSlug($insertedString);
        $this->assertEquals($expectedResult, $slug);
    }

    public function testRemovesStartingAndEndingDashes()
    {
        $insertedString = '& test #';
        $expectedResult = 'test';
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $slugger = new Slugger($entityManager);
        $slug = $slugger->generateSlug($insertedString);
        $this->assertEquals($expectedResult, $slug);
    }

    public function testSlugExists()
    {
        $existingSlug = 'existing-slug';
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $repository = $this->createMock(SiteRepository::class);
        $entityManager->method('getRepository')
            ->willReturn($repository);
        $site = new Site();
        $site->setSlug($existingSlug);
        $repository->expects($this->once())
            ->method('findOneBy')
            ->with($this->equalTo(['slug' => $existingSlug]))
            ->willReturn($site);
        $slugger = new Slugger($entityManager);
        $slugExists = $slugger->checkIfSlugExists($existingSlug, 'Site');
        $this->assertEquals(true, $slugExists);
    }

    public function testSlugDoesNotExist()
    {
        $nonExistentSlug = 'slug';
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $repository = $this->createMock(SiteRepository::class);
        $entityManager->method('getRepository')
            ->willReturn($repository);
        $repository->expects($this->once())
            ->method('findOneBy')
            ->with($this->equalTo(['slug' => $nonExistentSlug]))
            ->willReturn(null);
        $slugger = new Slugger($entityManager);
        $slugExists = $slugger->checkIfSlugExists($nonExistentSlug, 'Site');
        $this->assertEquals(false, $slugExists);
    }

    public function testIgnoresSlugBelongingToCurrentEntityObject()
    {
        $existingSlug = 'slug';
        $currentSiteId = 1;
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $repository = $this->createMock(SiteRepository::class);
        $entityManager->method('getRepository')
            ->willReturn($repository);
        $site = $this->createMock(Site::class);
        $site->method('getId')
            ->willReturn($currentSiteId);
        $repository->expects($this->once())
            ->method('findOneBy')
            ->with($this->equalTo(['slug' => $existingSlug]))
            ->willReturn($site);
        $slugger = new Slugger($entityManager);
        $slugExists = $slugger->checkIfSlugExists($existingSlug, 'Site', $site->getId());
        $this->assertEquals(false, $slugExists);
    }

    public function testDoesNotIgnoreSlugBelongingToDifferentEntityObject()
    {
        $existingSlug = 'slug';
        $currentSiteId = 1;
        $differentSiteId = 2;
        $entityManager = $this->createMock(EntityManagerInterface::class);
        $repository = $this->createMock(SiteRepository::class);
        $entityManager->method('getRepository')
            ->willReturn($repository);
        $site = $this->createMock(Site::class);
        $site->method('getId')
            ->willReturn($currentSiteId);
        $repository->expects($this->once())
            ->method('findOneBy')
            ->with($this->equalTo(['slug' => $existingSlug]))
            ->willReturn($site);
        $slugger = new Slugger($entityManager);
        $slugExists = $slugger->checkIfSlugExists($existingSlug, 'Site', $differentSiteId);
        $this->assertEquals(true, $slugExists);
    }

}