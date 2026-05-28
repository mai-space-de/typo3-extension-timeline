<?php

declare(strict_types=1);

namespace Maispace\MaiTimeline\Tests\Unit\Domain\Repository;

use Maispace\MaiTimeline\Domain\Repository\QueryMetrics;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;

final class QueryMetricsTest extends TestCase
{
    #[Test]
    public function constructorStoresIterationCount(): void
    {
        $metrics = new QueryMetrics(42, 100.0, 1024);
        self::assertSame(42, $metrics->getIterationCount());
    }

    #[Test]
    public function constructorStoresExecutionTimeUs(): void
    {
        $metrics = new QueryMetrics(0, 250.5, 0);
        self::assertSame(250.5, $metrics->getExecutionTimeUs());
    }

    #[Test]
    public function constructorStoresMemoryDeltaBytes(): void
    {
        $metrics = new QueryMetrics(0, 0.0, 2048);
        self::assertSame(2048, $metrics->getMemoryDeltaBytes());
    }

    #[Test]
    public function isWithinTimeBudgetReturnsTrueWhenUnderBudget(): void
    {
        $metrics = new QueryMetrics(10, 500.0, 0);
        self::assertTrue($metrics->isWithinTimeBudget(1000.0));
    }

    #[Test]
    public function isWithinTimeBudgetReturnsTrueWhenExactlyAtBudget(): void
    {
        $metrics = new QueryMetrics(10, 1000.0, 0);
        self::assertTrue($metrics->isWithinTimeBudget(1000.0));
    }

    #[Test]
    public function isWithinTimeBudgetReturnsFalseWhenOverBudget(): void
    {
        $metrics = new QueryMetrics(10, 1001.0, 0);
        self::assertFalse($metrics->isWithinTimeBudget(1000.0));
    }

    #[Test]
    public function isWithinMemoryBudgetReturnsTrueWhenUnderBudget(): void
    {
        $metrics = new QueryMetrics(0, 0.0, 512);
        self::assertTrue($metrics->isWithinMemoryBudget(1024));
    }

    #[Test]
    public function isWithinMemoryBudgetReturnsTrueWhenExactlyAtBudget(): void
    {
        $metrics = new QueryMetrics(0, 0.0, 1024);
        self::assertTrue($metrics->isWithinMemoryBudget(1024));
    }

    #[Test]
    public function isWithinMemoryBudgetReturnsFalseWhenOverBudget(): void
    {
        $metrics = new QueryMetrics(0, 0.0, 1025);
        self::assertFalse($metrics->isWithinMemoryBudget(1024));
    }

    #[Test]
    public function zeroIterationCountIsValid(): void
    {
        $metrics = new QueryMetrics(0, 0.0, 0);
        self::assertSame(0, $metrics->getIterationCount());
    }

    #[Test]
    public function largeIterationCountIsPreserved(): void
    {
        $metrics = new QueryMetrics(100_000, 50_000.0, 4_096);
        self::assertSame(100_000, $metrics->getIterationCount());
        self::assertSame(50_000.0, $metrics->getExecutionTimeUs());
        self::assertSame(4_096, $metrics->getMemoryDeltaBytes());
    }
}
