<?php

declare(strict_types=1);

namespace App\Tests\Unit\Domain\Shared\ValueObject;

use App\Domain\Shared\Exception\DateTimeException;
use App\Domain\Shared\ValueObject\DateTime;
use PHPUnit\Framework\TestCase;

class DateTimeTest extends TestCase
{
    public const BAD_DATETIME = 'LOL';

    /**
     * @test
     *
     * @group unit
     *
     * @throws DateTimeException
     */
    public function given_a_bad_formatted_datetime_string_it_should_throw_an_exception_when_we_try_to_create_datetime(): void
    {
        $this->expectException(DateTimeException::class);
        DateTime::fromString(self::BAD_DATETIME);
    }
}
