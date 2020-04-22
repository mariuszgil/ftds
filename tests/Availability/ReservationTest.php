<?php

namespace Tests\Availability;

use Availability\Policy;
use Availability\Policy\LimitedDuration;
use Availability\Policy\NoGapsBetween;
use Availability\Policy\NoOverlapping;
use Availability\Policy\Rejection;
use Availability\Resource;
use Availability\ResourceId;
use League\Period\Duration;
use League\Period\Period;
use Munus\Collection\GenericList;
use PHPUnit\Framework\TestCase;

class ReservationTest extends TestCase
{
    /**
     * @var Resource
     */
    private $resource;

    /**
     * @var GenericList<Policy>
     */
    private $policies;

    /**
     * @test
     */
    public function reservationMayBeRequestedRightAfterPreviousOne()
    {
        // Act
        $this->resource->reserve(new Period('2020-03-01 10:00:00', '2020-03-01 12:00:00'), $this->policies);
        $this->resource->reserve(new Period('2020-03-01 12:00:00', '2020-03-01 14:00:00'), $this->policies);

        // Assert
        $this->assertEquals(2, $this->resource->getReservedPeriods()->length());
    }

    /**
     * @test
     */
    public function reservationCantOverlapWithPreviousOnes()
    {
        // Arrange
        $this->resource->reserve(new Period('2020-03-01 10:00:00', '2020-03-01 12:00:00'), $this->policies);

        // Act & Assert
        $this->assertInstanceOf(
            Rejection::class,
            $this->resource->reserve(new Period('2020-03-01 11:00:00', '2020-03-01 14:00:00'), $this->policies)->getLeft()
        );
    }

    /**
     * @test
     */
    public function reservationCantBeLongerThenGivenLimit()
    {
        // Act & Assert
        $this->assertInstanceOf(
            Rejection::class,
            $this->resource->reserve(new Period('2020-03-01 10:00:00', '2020-03-01 18:00:00'), $this->policies)->getLeft()
        );
    }

    /**
     *
     */
    protected function setUp(): void
    {
        // Arrange
        parent::setUp();

        $this->resource = new Resource(ResourceId::fromString('39acb44d-e4cc-4bb1-869e-f4aaa458751f'));
        $this->policies = GenericList::of(
            new NoOverlapping(),
            new NoGapsBetween(),
            new LimitedDuration(Duration::create('3 HOURS'))
        );
    }
}