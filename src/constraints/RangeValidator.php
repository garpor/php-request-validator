<?php

declare(strict_types=1);

namespace Garpor\PhpRequestValidator\constraints;

use Garpor\PhpRequestValidator\ConstraintInterface;
use Garpor\PhpRequestValidator\ValidatorError;
use Garpor\PhpRequestValidator\ValidatorInterface;
use InvalidArgumentException;


readonly final class RangeValidator implements ValidatorInterface
{
	public const ERROR_INVALID_TYPE_CODE = 'error.invalid_type';
	public const ERROR_INVALID_TYPE_MESSAGE = 'The value must be a number or a string.';
	public const ERROR_TOO_LOW_CODE = 'error.too_low';
	public const ERROR_TOO_LOW_MESSAGE = 'The value must be equal to or greater than %d.';
	public const ERROR_TOO_HIGH_CODE = 'error.too_high';
	public const ERROR_TOO_HIGH_MESSAGE = 'The value must be equal to or less than %d.';

	public function validate($value, ConstraintInterface $constraint): ?array
	{
		if (!$constraint instanceof Range) {
			throw new InvalidArgumentException('Expected instance of RangeConstraint.');
		}

		if (is_null($value)) {
			return null;
		}

		if (!is_numeric($value)) {
			return [
				new ValidatorError(
					message: $constraint->message ?? self::ERROR_INVALID_TYPE_MESSAGE,
					code: $constraint->code ?? self::ERROR_INVALID_TYPE_CODE
				)
			];
		}

		if ($constraint->min !== null && $value < $constraint->min) {
			return [
				new ValidatorError(
					message: $constraint->tooLowMessage ?? sprintf(self::ERROR_TOO_LOW_MESSAGE, $constraint->min),
					code: $constraint->tooLowCode ?? self::ERROR_TOO_LOW_CODE
				)
			];
		}

		if ($constraint->max !== null && $value > $constraint->max) {
			return [
				new ValidatorError(
					message: $constraint->tooHighMessage ?? sprintf(self::ERROR_TOO_HIGH_MESSAGE, $constraint->max),
					code: $constraint->tooHighCode ?? self::ERROR_TOO_HIGH_CODE
				)
			];
		}

		return null;
	}
}
