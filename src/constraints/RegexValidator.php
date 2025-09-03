<?php

declare(strict_types=1);

namespace Garpor\PhpRequestValidator\constraints;

use Garpor\PhpRequestValidator\ConstraintInterface;
use Garpor\PhpRequestValidator\ValidatorError;
use Garpor\PhpRequestValidator\ValidatorInterface;
use InvalidArgumentException;


readonly final class RegexValidator implements ValidatorInterface
{
	public const ERROR_INVALID_FORMAT_MESSAGE = 'The format is not valid.';
	public const ERROR_INVALID_FORMAT_CODE = 'error.invalid_format';

	public function validate($value, ConstraintInterface $constraint): ?array
	{
		if (!$constraint instanceof Regex) {
			throw new InvalidArgumentException('Expected instance of Regex.');
		}

		if (!is_string($value) || !preg_match($constraint->pattern, $value)) {
			return [
				new ValidatorError(
					message: $constraint->message ?? self::ERROR_INVALID_FORMAT_MESSAGE,
					code: $constraint->code ?? self::ERROR_INVALID_FORMAT_CODE
				)
			];
		}

		return null;
	}
}
