<?php
namespace App\Tests\Utils;

use App\Entity\Site;
use App\Repository\SiteRepository;
use App\Utils\Slugger;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class SluggerTest extends TestCase
{
    protected $entityManager;
    protected $repository;
    protected $slugger;

    protected function setUp()
    {
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->repository = $this->createMock(SiteRepository::class);
        $this->slugger = new Slugger($this->entityManager);
    }

    public function testReplacesSpacesWithDashes()
    {
        $insertedString = 'slug with spaces';
        $expectedResult = 'slug-with-spaces';

        $slug = $this->slugger->generateSlug($insertedString);

        $this->assertEquals($expectedResult, $slug);
    }

    public function testReturnsAllLowercase()
    {
        $insertedString = 'SLUG';
        $expectedResult = 'slug';

        $slug = $this->slugger->generateSlug($insertedString);

        $this->assertEquals($expectedResult, $slug);
    }

    public function testRemovesRepeatingDashes()
    {
        $insertedString = 'test & slug';
        $expectedResult = 'test-slug';

        $slug = $this->slugger->generateSlug($insertedString);

        $this->assertEquals($expectedResult, $slug);
    }

    public function testRemovesStartingAndEndingDashes()
    {
        $insertedString = '& test #';
        $expectedResult = 'test';

        $slug = $this->slugger->generateSlug($insertedString);

        $this->assertEquals($expectedResult, $slug);
    }

    public function testSlugExists()
    {
        $existingSlug = 'existing-slug';

        $this->entityManager->method('getRepository')
            ->willReturn($this->repository);

        $site = new Site();
        $site->setSlug($existingSlug);

        $this->repository->expects($this->once())
            ->method('findOneBy')
            ->with($this->equalTo(['slug' => $existingSlug]))
            ->willReturn($site);

        $slugExists = $this->slugger->checkIfSlugExists($existingSlug, 'Site');

        $this->assertEquals(true, $slugExists);
    }

    public function testSlugDoesNotExist()
    {
        $nonExistentSlug = 'slug';

        $this->entityManager->method('getRepository')
            ->willReturn($this->repository);
        $this->repository->expects($this->once())
            ->method('findOneBy')
            ->with($this->equalTo(['slug' => $nonExistentSlug]))
            ->willReturn(null);

        $slugExists = $this->slugger->checkIfSlugExists($nonExistentSlug, 'Site');

        $this->assertEquals(false, $slugExists);
    }

    public function testIgnoresSlugBelongingToCurrentEntityObject()
    {
        $existingSlug = 'slug';
        $currentSiteId = 1;

        $this->entityManager->method('getRepository')
            ->willReturn($this->repository);

        $site = $this->createMock(Site::class);
        $site->method('getId')
            ->willReturn($currentSiteId);

        $this->repository->expects($this->once())
            ->method('findOneBy')
            ->with($this->equalTo(['slug' => $existingSlug]))
            ->willReturn($site);

        $slugExists = $this->slugger->checkIfSlugExists($existingSlug, 'Site', $site->getId());

        $this->assertEquals(false, $slugExists);
    }

    public function testDoesNotIgnoreSlugBelongingToDifferentEntityObject()
    {
        $existingSlug = 'slug';
        $currentSiteId = 1;
        $differentSiteId = 2;

        $this->entityManager->method('getRepository')
            ->willReturn($this->repository);

        $site = $this->createMock(Site::class);
        $site->method('getId')
            ->willReturn($currentSiteId);

        $this->repository->expects($this->once())
            ->method('findOneBy')
            ->with($this->equalTo(['slug' => $existingSlug]))
            ->willReturn($site);

        $slugExists = $this->slugger->checkIfSlugExists($existingSlug, 'Site', $differentSiteId);

        $this->assertEquals(true, $slugExists);
    }

}
