<?php

declare(strict_types=1);

namespace Garpor\PhpRequestValidator\constraints;

use Garpor\PhpRequestValidator\ConstraintInterface;
use Garpor\PhpRequestValidator\ValidatorError;
use Garpor\PhpRequestValidator\ValidatorInterface;
use InvalidArgumentException;


readonly final class RequiredValidator implements ValidatorInterface
{
	public const ERROR_REQUIRED_MESSAGE = 'This field is required.';
	public const ERROR_REQUIRED_CODE = 'error.required';

	public function validate($value, ConstraintInterface $constraint): ?array
	{
		if (!$constraint instanceof Required) {
			throw new InvalidArgumentException('Expected instance of Required.');
		}

		if ($value === null || (is_string($value) && trim($value) === '')) {
			return [
				new ValidatorError(
					message: $constraint->message ?? self::ERROR_REQUIRED_MESSAGE,
					code: $constraint->code ?? self::ERROR_REQUIRED_CODE
				)
			];
		}
		return null;
	}
}
