<?php

declare(strict_types=1);

namespace Garpor\PhpRequestValidator\constraints;

use Garpor\PhpRequestValidator\ConstraintInterface;
use Garpor\PhpRequestValidator\ValidatorError;
use Garpor\PhpRequestValidator\ValidatorInterface;
use InvalidArgumentException;


readonly final class IsBooleanValidator implements ValidatorInterface
{
	public const ERROR_INVALID_BOOLEAN_MESSAGE = 'The value must be a boolean.';
	public const ERROR_INVALID_BOOLEAN_CODE = 'error.invalid_boolean';

	public function validate(mixed $value, ConstraintInterface $constraint): ?array
	{
		if (!$constraint instanceof IsBoolean) {
			throw new InvalidArgumentException('Expected instance of IsBoolean.');
		}

		if (!is_bool($value) && $value !== 'true' && $value !== 'false' && $value !== 1 && $value !== 0 && $value !== '1' && $value !== '0') {
			return [
				new ValidatorError(
					message: $constraint->message ?? self::ERROR_INVALID_BOOLEAN_MESSAGE,
					code: $constraint->code ?? self::ERROR_INVALID_BOOLEAN_CODE
				)
			];
		}
		return null;
	}
}
