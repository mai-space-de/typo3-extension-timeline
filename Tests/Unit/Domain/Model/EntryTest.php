<?php

declare(strict_types=1);

namespace Maispace\MaiTimeline\Tests\Unit\Domain\Model;

use Maispace\MaiTimeline\Domain\Model\Entry;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use TYPO3\CMS\Extbase\Domain\Model\Category;
use TYPO3\CMS\Extbase\Domain\Model\FileReference;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

final class EntryTest extends TestCase
{
    private Entry $subject;

    protected function setUp(): void
    {
        $this->subject = new Entry();
    }

    #[Test]
    public function getTitleReturnsInitialValueForString(): void
    {
        self::assertSame('', $this->subject->getTitle());
    }

    #[Test]
    public function setTitleForStringSetsTitle(): void
    {
        $this->subject->setTitle('Timeline Event');
        self::assertSame('Timeline Event', $this->subject->getTitle());
    }

    #[Test]
    public function getContentReturnsInitialValueForString(): void
    {
        self::assertSame('', $this->subject->getContent());
    }

    #[Test]
    public function setContentForStringSetsContent(): void
    {
        $this->subject->setContent('<p>Event description</p>');
        self::assertSame('<p>Event description</p>', $this->subject->getContent());
    }

    #[Test]
    public function getDateReturnsInitialValueForDateTime(): void
    {
        self::assertNull($this->subject->getDate());
    }

    #[Test]
    public function setDateForDateTimeSetsDate(): void
    {
        $date = new \DateTimeImmutable('2024-01-15');
        $this->subject->setDate($date);
        self::assertSame($date, $this->subject->getDate());
    }

    #[Test]
    public function getYearReturnsInitialValueForInt(): void
    {
        self::assertSame(0, $this->subject->getYear());
    }

    #[Test]
    public function setYearForIntSetsYear(): void
    {
        $this->subject->setYear(2024);
        self::assertSame(2024, $this->subject->getYear());
    }

    #[Test]
    public function getImageReturnsInitialValueForObjectStorage(): void
    {
        $storage = $this->subject->getImage();
        self::assertInstanceOf(ObjectStorage::class, $storage);
        self::assertCount(0, $storage);
    }

    #[Test]
    public function setImageForObjectStorageSetsImage(): void
    {
        $image = $this->createMock(FileReference::class);
        $storage = new ObjectStorage();
        $storage->attach($image);

        $this->subject->setImage($storage);
        self::assertSame($storage, $this->subject->getImage());
        self::assertCount(1, $this->subject->getImage());
    }

    #[Test]
    public function getCategoriesReturnsInitialValueForObjectStorage(): void
    {
        $storage = $this->subject->getCategories();
        self::assertInstanceOf(ObjectStorage::class, $storage);
        self::assertCount(0, $storage);
    }

    #[Test]
    public function setCategoriesForObjectStorageSetsCategories(): void
    {
        $category = $this->createMock(Category::class);
        $storage = new ObjectStorage();
        $storage->attach($category);

        $this->subject->setCategories($storage);
        self::assertSame($storage, $this->subject->getCategories());
        self::assertCount(1, $this->subject->getCategories());
    }
}
