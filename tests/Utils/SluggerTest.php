<?php
namespace App\Tests\Utils;

use App\Entity\Site;
use App\Repository\SiteRepository;
use App\Utils\Slugger;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;

class SluggerTest extends TestCase
{
    protected $repository;
    protected $slugger;

    protected function setUp()
    {
        $this->repository = $this->createMock(SiteRepository::class);
        $this->slugger = new Slugger();
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

        $site = new Site();
        $site->setSlug($existingSlug);

        $this->repository->expects($this->once())
            ->method('findOneBy')
            ->with($this->equalTo(['slug' => $existingSlug]))
            ->willReturn($site);

        $slugExists = $this->slugger->checkIfSlugExists($existingSlug, $this->repository);

        $this->assertEquals(true, $slugExists);
    }

    public function testSlugDoesNotExist()
    {
        $nonExistentSlug = 'slug';

        $this->repository->expects($this->once())
            ->method('findOneBy')
            ->with($this->equalTo(['slug' => $nonExistentSlug]))
            ->willReturn(null);

        $slugExists = $this->slugger->checkIfSlugExists($nonExistentSlug, $this->repository);

        $this->assertEquals(false, $slugExists);
    }

    public function testIgnoresSlugBelongingToCurrentEntityObject()
    {
        $existingSlug = 'slug';
        $currentSiteId = 1;

        $site = $this->createMock(Site::class);
        $site->method('getId')
            ->willReturn($currentSiteId);

        $this->repository->expects($this->once())
            ->method('findOneBy')
            ->with($this->equalTo(['slug' => $existingSlug]))
            ->willReturn($site);

        $slugExists = $this->slugger->checkIfSlugExists(
            $existingSlug,
            $this->repository,
            $site->getId()
        );

        $this->assertEquals(false, $slugExists);
    }

    public function testDoesNotIgnoreSlugBelongingToDifferentEntityObject()
    {
        $existingSlug = 'slug';
        $currentSiteId = 1;
        $differentSiteId = 2;

        $site = $this->createMock(Site::class);
        $site->method('getId')
            ->willReturn($currentSiteId);

        $this->repository->expects($this->once())
            ->method('findOneBy')
            ->with($this->equalTo(['slug' => $existingSlug]))
            ->willReturn($site);

        $slugExists = $this->slugger->checkIfSlugExists(
            $existingSlug,
            $this->repository,
            $differentSiteId
        );

        $this->assertEquals(true, $slugExists);
    }

    public function testReturnsUniqueSlugWithoutAppendedIterator()
    {
        $insertedString = 'Test slug';
        $expectedResult = 'test-slug';

        $this->repository->expects($this->once())
            ->method('findOneBy')
            ->with($this->equalTo(['slug' => $expectedResult]))
            ->willReturn(null);

        $uniqueSlug = $this->slugger->generateUniqueSlug($insertedString, $this->repository);

        $this->assertEquals($expectedResult, $uniqueSlug);
    }

    public function testReturnsTwoUniqueSlugsFromSameString()
    {
        $insertedString = 'Test slug';

        $this->repository->expects($this->at(0))
            ->method('findOneBy')
            ->with($this->equalTo(['slug' => 'test-slug']))
            ->willReturn(null);
        $this->repository->expects($this->at(1))
            ->method('findOneBy')
            ->with($this->equalTo(['slug' => 'test-slug']))
            ->willReturn(new Site());
        $this->repository->expects($this->at(2))
            ->method('findOneBy')
            ->with($this->equalTo(['slug' => 'test-slug-1']))
            ->willReturn(null);

        $firstUniqueSlug = $this->slugger->generateUniqueSlug($insertedString, $this->repository);
        $secondUniqueSlug = $this->slugger->generateUniqueSlug($insertedString, $this->repository);

        $this->assertNotEquals($firstUniqueSlug, $secondUniqueSlug);
    }
}
