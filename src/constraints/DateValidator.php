<?php

declare(strict_types=1);

namespace Garpor\PhpRequestValidator\constraints;

use Garpor\PhpRequestValidator\ConstraintInterface;
use Garpor\PhpRequestValidator\ValidatorError;
use Garpor\PhpRequestValidator\ValidatorInterface;
use DateTimeImmutable;
use InvalidArgumentException;


readonly final class DateValidator implements ValidatorInterface
{
	public const ERROR_INVALID_DATE_MESSAGE = 'The value is not a valid date.';
	public const ERROR_INVALID_DATE_CODE = 'error.invalid_date';

	public function validate(mixed $value, ConstraintInterface $constraint): ?array
	{
		if (!$constraint instanceof Date) {
			throw new InvalidArgumentException('Expected instance of Date.');
		}

		$format = $constraint->format ?? 'Y-m-d';
		$message = $constraint->message ?? self::ERROR_INVALID_DATE_MESSAGE;
		$code = $constraint->code ?? self::ERROR_INVALID_DATE_CODE;

		if (!is_string($value)) {
			return [
				new ValidatorError(message: $message, code: $code)
			];
		}

		$date = DateTimeImmutable::createFromFormat($format, $value);

		if ($date === false || $date->format($format) !== $value) {
			return [
				new ValidatorError(message: $message, code: $code)
			];
		}

		return null;
	}
}
