<?php

declare(strict_types=1);

namespace Maispace\MaiTimeline\Tests\Unit\Domain\Repository;

use Maispace\MaiTimeline\Domain\Repository\QueryCachingValidator;
use Maispace\MaiTimeline\Domain\Repository\QueryMetrics;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use TYPO3\CMS\Extbase\Persistence\QueryResultInterface;

final class QueryCachingValidatorTest extends TestCase
{
    private QueryCachingValidator $subject;

    protected function setUp(): void
    {
        $this->subject = new QueryCachingValidator();
    }

    #[Test]
    public function measureIterationsReturnsQueryMetrics(): void
    {
        $result = $this->buildQueryResult([]);
        $metrics = $this->subject->measureIterations($result, 1);

        self::assertInstanceOf(QueryMetrics::class, $metrics);
    }

    #[Test]
    public function measureIterationsCountsItemsAcrossAllPasses(): void
    {
        $items = ['a', 'b', 'c'];
        $result = $this->buildQueryResult($items);

        $metrics = $this->subject->measureIterations($result, 2);

        self::assertSame(6, $metrics->getIterationCount());
    }

    #[Test]
    public function measureIterationsSinglePassCountsAllItems(): void
    {
        $items = ['x', 'y'];
        $result = $this->buildQueryResult($items);

        $metrics = $this->subject->measureIterations($result, 1);

        self::assertSame(2, $metrics->getIterationCount());
    }

    #[Test]
    public function measureIterationsWithEmptyResultReturnsZeroCount(): void
    {
        $result = $this->buildQueryResult([]);

        $metrics = $this->subject->measureIterations($result, 3);

        self::assertSame(0, $metrics->getIterationCount());
    }

    #[Test]
    public function measureIterationsRecordsNonNegativeExecutionTime(): void
    {
        $result = $this->buildQueryResult(['item']);

        $metrics = $this->subject->measureIterations($result, 1);

        self::assertGreaterThanOrEqual(0.0, $metrics->getExecutionTimeUs());
    }

    #[Test]
    public function measureIterationsRecordsNonNegativeMemoryDelta(): void
    {
        $result = $this->buildQueryResult(['item']);

        $metrics = $this->subject->measureIterations($result, 1);

        self::assertGreaterThanOrEqual(0, $metrics->getMemoryDeltaBytes());
    }

    #[Test]
    public function assertCachingByConsistencyReturnsTrueWhenBothPassesYieldSameCount(): void
    {
        $items = ['a', 'b', 'c'];
        $result = $this->buildQueryResult($items);

        self::assertTrue($this->subject->assertCachingByConsistency($result));
    }

    #[Test]
    public function assertCachingByConsistencyReturnsTrueForEmptyResult(): void
    {
        $result = $this->buildQueryResult([]);

        self::assertTrue($this->subject->assertCachingByConsistency($result));
    }

    #[Test]
    public function assertCachingByConsistencyReturnsTrueForSingleItem(): void
    {
        $result = $this->buildQueryResult(['only']);

        self::assertTrue($this->subject->assertCachingByConsistency($result));
    }

    #[Test]
    public function assertCachingByConsistencyReturnsFalseWhenSecondPassHasFewerItems(): void
    {
        $result = $this->buildDriftingQueryResult([['a', 'b'], ['a']]);

        self::assertFalse($this->subject->assertCachingByConsistency($result));
    }

    #[Test]
    public function measureIterationsDefaultPassesIsTwo(): void
    {
        $items = ['x'];
        $result = $this->buildQueryResult($items);

        $metrics = $this->subject->measureIterations($result);

        self::assertSame(2, $metrics->getIterationCount());
    }

    private function buildQueryResult(array $items): QueryResultInterface
    {
        return new class($items) implements QueryResultInterface {
            private array $data;
            private int $position = 0;

            public function __construct(array $data)
            {
                $this->data = array_values($data);
            }

            public function rewind(): void { $this->position = 0; }
            public function current(): mixed { return $this->data[$this->position]; }
            public function key(): int { return $this->position; }
            public function next(): void { ++$this->position; }
            public function valid(): bool { return isset($this->data[$this->position]); }
            public function count(): int { return count($this->data); }
            public function offsetExists(mixed $offset): bool { return isset($this->data[$offset]); }
            public function offsetGet(mixed $offset): mixed { return $this->data[$offset] ?? null; }
            public function offsetSet(mixed $offset, mixed $value): void {}
            public function offsetUnset(mixed $offset): void {}
            public function setQuery(\TYPO3\CMS\Extbase\Persistence\QueryInterface $query): void {}
            public function getFirst(): mixed { return $this->data[0] ?? null; }
            public function getQuery(): \TYPO3\CMS\Extbase\Persistence\QueryInterface
            {
                throw new \RuntimeException('Not implemented in test stub.');
            }
            public function toArray(): array { return $this->data; }
        };
    }

    private function buildDriftingQueryResult(array $datasets): QueryResultInterface
    {
        return new class($datasets) implements QueryResultInterface {
            private array $datasets;
            private int $pass = -1;
            private int $position = 0;

            public function __construct(array $datasets) { $this->datasets = $datasets; }

            private function currentDataset(): array
            {
                $idx = max(0, min($this->pass, count($this->datasets) - 1));
                return array_values($this->datasets[$idx] ?? []);
            }

            public function rewind(): void { ++$this->pass; $this->position = 0; }
            public function current(): mixed { return $this->currentDataset()[$this->position]; }
            public function key(): int { return $this->position; }
            public function next(): void { ++$this->position; }
            public function valid(): bool { return isset($this->currentDataset()[$this->position]); }
            public function count(): int { return count($this->currentDataset()); }
            public function offsetExists(mixed $offset): bool { return isset($this->currentDataset()[$offset]); }
            public function offsetGet(mixed $offset): mixed { return $this->currentDataset()[$offset] ?? null; }
            public function offsetSet(mixed $offset, mixed $value): void {}
            public function offsetUnset(mixed $offset): void {}
            public function setQuery(\TYPO3\CMS\Extbase\Persistence\QueryInterface $query): void {}
            public function getFirst(): mixed { return $this->currentDataset()[0] ?? null; }
            public function getQuery(): \TYPO3\CMS\Extbase\Persistence\QueryInterface
            {
                throw new \RuntimeException('Not implemented in test stub.');
            }
            public function toArray(): array { return $this->currentDataset(); }
        };
    }
}
