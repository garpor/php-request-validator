<?php

declare(strict_types=1);

namespace Garpor\PhpRequestValidator\constraints;

use Garpor\PhpRequestValidator\ConstraintInterface;
use Garpor\PhpRequestValidator\ValidatorError;
use Garpor\PhpRequestValidator\ValidatorInterface;
use InvalidArgumentException;


readonly final class LengthValidator implements ValidatorInterface
{
	public const ERROR_INVALID_TYPE_MESSAGE = 'The value must be a string.';
	public const ERROR_INVALID_TYPE_CODE = 'error.invalid_type';
	public const ERROR_TOO_SHORT_MESSAGE = 'The minimum length is %d characters.';
	public const ERROR_TOO_SHORT_CODE = 'error.too_short';
	public const ERROR_TOO_LONG_MESSAGE = 'The maximum length is %d characters.';
	public const ERROR_TOO_LONG_CODE = 'error.too_long';

	public function validate($value, ConstraintInterface $constraint): ?array
	{
		if (!$constraint instanceof Length) {
			throw new InvalidArgumentException('Expected instance of Length.');
		}

		if (!is_string($value)) {
			return [
				new ValidatorError(
					message: $constraint->message ?? self::ERROR_INVALID_TYPE_MESSAGE,
					code: $constraint->code ?? self::ERROR_INVALID_TYPE_CODE
				)
			];
		}

		$length = mb_strlen($value);
		$errors = [];

		if ($constraint->min !== null && $length < $constraint->min) {
			$errors[] = new ValidatorError(
				message: $constraint->minMessage ?? sprintf(self::ERROR_TOO_SHORT_MESSAGE, $constraint->min),
				code: $constraint->minCode ?? self::ERROR_TOO_SHORT_CODE
			);
		}

		if ($constraint->max !== null && $length > $constraint->max) {
			$errors[] = new ValidatorError(
				message: $constraint->maxMessage ?? sprintf(self::ERROR_TOO_LONG_MESSAGE, $constraint->max),
				code: $constraint->maxCode ?? self::ERROR_TOO_LONG_CODE
			);
		}

		return empty($errors) ? null : $errors;
	}
}
