<?php

declare(strict_types=1);

namespace Garpor\PhpRequestValidator\constraints;

use DateTimeImmutable;
use Garpor\PhpRequestValidator\ConstraintInterface;
use Garpor\PhpRequestValidator\ValidatorError;
use Garpor\PhpRequestValidator\ValidatorInterface;
use InvalidArgumentException;
use function is_scalar;


readonly final class DateTimeValidator implements ValidatorInterface
{
	public const ERROR_INVALID_DATETIME_MESSAGE = 'The value is not a valid date and time.';
	public const ERROR_INVALID_DATETIME_CODE = 'error.invalid_datetime';

	public function validate($value, ConstraintInterface $constraint): ?array
	{
		if (!$constraint instanceof DateTime) {
			throw new InvalidArgumentException('Expected instance of DateTime.');
		}

		$format = $constraint->format ?? 'Y-m-d H:i:s';
		$message = $constraint->message ?? self::ERROR_INVALID_DATETIME_MESSAGE;
		$code = $constraint->code ?? self::ERROR_INVALID_DATETIME_CODE;

		if (!is_scalar($value) && $value instanceof \Stringable) {
			return [
				new ValidatorError(message: $message, code: $code)
			];
		}

		$datetime = DateTimeImmutable::createFromFormat($format, $value);
		$isValid = ($datetime !== false && $datetime->format($format) === $value);

		if (!$isValid && $format === DATE_ATOM && str_ends_with($value, 'Z')) {
			$valueWithOffset = substr($value, 0, -1) . '+00:00';
			$datetimeWithOffset = DateTimeImmutable::createFromFormat($format, $valueWithOffset);
			$isValid = ($datetimeWithOffset !== false && $datetimeWithOffset->format($format) === $valueWithOffset);
		}

		if (!$isValid) {
			return [
				new ValidatorError(message: $message, code: $code)
			];
		}

		return null;
	}
}
