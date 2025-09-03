<?php

declare(strict_types=1);

namespace Garpor\PhpRequestValidator\constraints;

use Garpor\PhpRequestValidator\ConstraintInterface;
use Garpor\PhpRequestValidator\ValidatorError;
use Garpor\PhpRequestValidator\ValidatorInterface;
use InvalidArgumentException;


readonly final class TextValidator implements ValidatorInterface
{
	public const ERROR_INVALID_TYPE_MESSAGE = 'The value must be a string.';
	public const ERROR_INVALID_TYPE_CODE = 'error.invalid_type';
	public const ERROR_MIN_LENGTH_MESSAGE = 'The minimum length is %d characters.';
	public const ERROR_MIN_LENGTH_CODE = 'error.min_length';
	public const ERROR_MAX_LENGTH_MESSAGE = 'The maximum length is %d characters.';
	public const ERROR_MAX_LENGTH_CODE = 'error.max_length';

	public function validate($value, ConstraintInterface $constraint): ?array
	{
		if (!$constraint instanceof Text) {
			throw new InvalidArgumentException('Expected instance of Text.');
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
				message: $constraint->minMessage ?? sprintf(self::ERROR_MIN_LENGTH_MESSAGE, $constraint->min),
				code: $constraint->minCode ?? self::ERROR_MIN_LENGTH_CODE
			);
		}

		if ($constraint->max !== null && $length > $constraint->max) {
			$errors[] = new ValidatorError(
				message: $constraint->maxMessage ?? sprintf(self::ERROR_MAX_LENGTH_MESSAGE, $constraint->max),
				code: $constraint->maxCode ?? self::ERROR_MAX_LENGTH_CODE
			);
		}

		return empty($errors) ? null : $errors;
	}
}
