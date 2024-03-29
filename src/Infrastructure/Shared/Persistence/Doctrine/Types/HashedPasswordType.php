<?php

declare(strict_types=1);

namespace App\Infrastructure\Shared\Persistence\Doctrine\Types;

use App\Domain\User\ValueObject\Auth\HashedPassword;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\StringType;
use Throwable;

final class HashedPasswordType extends StringType
{
    private const TYPE = 'hashed_password';

    /**
     * @param mixed $value
     *
     * @return mixed|string|null
     *
     * @throws ConversionException
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (null === $value) {
            return null;
        }

        if (!$value instanceof HashedPassword) {
            throw ConversionException::conversionFailedInvalidType($value, $this->getName(), ['null', HashedPassword::class]);
        }

        return $value->toString();
    }

    /**
     * @param HashedPassword|string|null $value
     *
     * @return HashedPassword|null
     *
     * @throws ConversionException
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if (null === $value || $value instanceof HashedPassword) {
            return $value;
        }

        try {
            $hashedPassword = HashedPassword::fromHash($value);
        } catch (Throwable $e) {
            throw ConversionException::conversionFailedFormat($value, $this->getName(), $platform->getDateTimeFormatString());
        }

        return $hashedPassword;
    }

    /**
     * @param AbstractPlatform $platform
     * @return bool
     */
    public function requiresSQLCommentHint(AbstractPlatform $platform)
    {
        return true;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return self::TYPE;
    }
}
