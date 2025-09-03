<?php

declare(strict_types=1);

namespace Garpor\PhpRequestValidator\constraints;

use Garpor\PhpRequestValidator\ConstraintInterface;
use Garpor\PhpRequestValidator\ValidatorError;
use Garpor\PhpRequestValidator\ValidatorInterface;
use InvalidArgumentException;


readonly final class GreaterOrEqualThanValidator implements ValidatorInterface
{
	public const ERROR_TOO_LOW_MESSAGE = 'The value must be greater than or equal to %d.';
	public const ERROR_TOO_LOW_CODE = 'error.too_low';

	public function validate($value, ConstraintInterface $constraint): ?array
	{
		if (!$constraint instanceof GreaterOrEqualThan) {
			throw new InvalidArgumentException('Expected instance of GreaterOrEqualThan.');
		}
		$limit = $constraint->value ?? null;

		if ($limit === null) {
			return null;
		}

		if (!is_numeric($value) || $value < $limit) {
			return [
				new ValidatorError(
					message: $constraint->message ?? sprintf(self::ERROR_TOO_LOW_MESSAGE, $limit),
					code: $constraint->code ?? self::ERROR_TOO_LOW_CODE
				)
			];

		}

		return null;
	}
}
