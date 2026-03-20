<?php

declare(strict_types=1);

namespace Maispace\Timeline\Domain\Model;

use TYPO3\CMS\Extbase\Domain\Model\Category;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

/**
 * A single entry on the timeline.
 */
class TimelineEntry extends AbstractEntity
{
    /**
     * The year this event occurred (Jahr).
     */
    protected int $year = 0;

    /**
     * The title of the timeline entry (Titel).
     */
    protected string $title = '';

    /**
     * A detailed description of the event (Beschreibung).
     */
    protected string $description = '';

    /**
     * Media files attached to this entry (Medien).
     *
     * @var ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\FileReference>
     */
    protected ObjectStorage $media;

    /**
     * Categories assigned to this entry (Kategorie).
     *
     * @var ObjectStorage<Category>
     */
    protected ObjectStorage $categories;

    public function __construct()
    {
        $this->media = new ObjectStorage();
        $this->categories = new ObjectStorage();
    }

    public function initializeObject(): void
    {
        $this->media = $this->media ?? new ObjectStorage();
        $this->categories = $this->categories ?? new ObjectStorage();
    }

    public function getYear(): int
    {
        return $this->year;
    }

    public function setYear(int $year): void
    {
        $this->year = $year;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function setDescription(string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\FileReference>
     */
    public function getMedia(): ObjectStorage
    {
        return $this->media;
    }

    /**
     * @param ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\FileReference> $media
     */
    public function setMedia(ObjectStorage $media): void
    {
        $this->media = $media;
    }

    public function addMedia(\TYPO3\CMS\Extbase\Domain\Model\FileReference $mediaItem): void
    {
        $this->media->attach($mediaItem);
    }

    public function removeMedia(\TYPO3\CMS\Extbase\Domain\Model\FileReference $mediaItem): void
    {
        $this->media->detach($mediaItem);
    }

    /**
     * @return ObjectStorage<Category>
     */
    public function getCategories(): ObjectStorage
    {
        return $this->categories;
    }

    /**
     * @param ObjectStorage<Category> $categories
     */
    public function setCategories(ObjectStorage $categories): void
    {
        $this->categories = $categories;
    }

    public function addCategory(Category $category): void
    {
        $this->categories->attach($category);
    }

    public function removeCategory(Category $category): void
    {
        $this->categories->detach($category);
    }
}
