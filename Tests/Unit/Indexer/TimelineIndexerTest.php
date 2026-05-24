<?php

declare(strict_types=1);

namespace Maispace\MaiTimeline\Tests\Unit\Indexer;

use Maispace\MaiTimeline\Domain\Model\Entry;
use Maispace\MaiTimeline\Indexer\TimelineIndexer;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class TimelineIndexerTest extends TestCase
{
    private TimelineIndexer $subject;

    protected function setUp(): void
    {
        $this->subject = new TimelineIndexer();
    }

    #[Test]
    public function getTypeReturnsTimeline(): void
    {
        self::assertSame('timeline', $this->subject->getType());
    }

    #[Test]
    public function supportsTimelineTable(): void
    {
        self::assertTrue($this->subject->supports('tx_maitimeline_entry'));
    }

    #[Test]
    public function doesNotSupportOtherTables(): void
    {
        self::assertFalse($this->subject->supports('tx_mainews_news'));
        self::assertFalse($this->subject->supports('pages'));
        self::assertFalse($this->subject->supports('tt_content'));
    }

    #[Test]
    public function getIconReturnsExpectedValue(): void
    {
        self::assertSame('content-timeline', $this->subject->getIcon('timeline'));
    }

    #[Test]
    public function buildContentStripsHtmlTags(): void
    {
        $entry = new Entry();
        $entry->setTitle('Test entry');
        $entry->setContent('<p>Timeline content with <strong>bold</strong> text.</p>');

        $content = $this->invokeBuildContent($entry);

        self::assertStringNotContainsString('<p>', $content);
        self::assertStringNotContainsString('<strong>', $content);
        self::assertStringContainsString('Timeline content', $content);
        self::assertStringContainsString('bold', $content);
    }

    #[Test]
    public function buildContentReturnsEmptyStringForNonEntryRecord(): void
    {
        $content = $this->invokeBuildContent(new \stdClass());

        self::assertSame('', $content);
    }

    #[Test]
    public function formatResultReturnsSearchResultWithCorrectType(): void
    {
        $solrDoc = [
            'title_s' => 'Foundation of the City',
            'content_t' => 'Historical event description.',
            'url_s' => '/timeline',
            'score' => 2.0,
        ];

        $result = $this->subject->formatResult($solrDoc);

        self::assertSame('timeline', $result->type);
        self::assertSame('Foundation of the City', $result->title);
        self::assertSame('/timeline', $result->url);
        self::assertSame('content-timeline', $result->icon);
        self::assertSame(2.0, $result->score);
    }

    #[Test]
    public function formatResultDefaultsToEmptyStringsWhenFieldsAreMissing(): void
    {
        $result = $this->subject->formatResult([]);

        self::assertSame('', $result->title);
        self::assertSame('', $result->url);
        self::assertSame(0.0, $result->score);
        self::assertNull($result->date);
    }

    private function invokeBuildContent(object $record): string
    {
        $reflection = new \ReflectionMethod($this->subject, 'buildContent');
        $reflection->setAccessible(true);

        /** @var string $result */
        return $reflection->invoke($this->subject, $record);
    }
}
