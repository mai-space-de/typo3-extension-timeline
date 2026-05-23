<?php

declare(strict_types=1);

namespace Maispace\MaiTimeline\Domain\Model;

use TYPO3\CMS\Extbase\Annotation as Extbase;
use TYPO3\CMS\Extbase\Domain\Model\FileReference;
use TYPO3\CMS\Extbase\DomainObject\AbstractEntity;
use TYPO3\CMS\Extbase\Persistence\ObjectStorage;

/**
 * Domain model for timeline entry records (tx_maitimeline_entry).
 *
 * Represents standalone timeline events with title, content, date, year,
 * image, and category relations. Used by the mai_timeline_list plugin for
 * rendering a filterable list of historical events.
 */
final class Entry extends AbstractEntity
{
    protected string $title = '';

    protected string $content = '';

    protected ?\DateTimeImmutable $date = null;

    protected int $year = 0;

    /**
     * @var ObjectStorage<FileReference>
     * @Extbase\ORM\Lazy
     */
    protected ObjectStorage $image;

    /**
     * @var ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\Category>
     * @Extbase\ORM\Lazy
     */
    protected ObjectStorage $categories;

    public function __construct()
    {
        $this->image = new ObjectStorage();
        $this->categories = new ObjectStorage();
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function setContent(string $content): void
    {
        $this->content = $content;
    }

    public function getDate(): ?\DateTimeImmutable
    {
        return $this->date;
    }

    public function setDate(?\DateTimeImmutable $date): void
    {
        $this->date = $date;
    }

    public function getYear(): int
    {
        return $this->year;
    }

    public function setYear(int $year): void
    {
        $this->year = $year;
    }

    /**
     * @return ObjectStorage<FileReference>
     */
    public function getImage(): ObjectStorage
    {
        return $this->image;
    }

    /**
     * @param ObjectStorage<FileReference> $image
     */
    public function setImage(ObjectStorage $image): void
    {
        $this->image = $image;
    }

    /**
     * @return ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\Category>
     */
    public function getCategories(): ObjectStorage
    {
        return $this->categories;
    }

    /**
     * @param ObjectStorage<\TYPO3\CMS\Extbase\Domain\Model\Category> $categories
     */
    public function setCategories(ObjectStorage $categories): void
    {
        $this->categories = $categories;
    }
}
