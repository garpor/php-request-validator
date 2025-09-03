<?php

declare(strict_types=1);

namespace Garpor\PhpRequestValidator\constraints;

use Garpor\PhpRequestValidator\ConstraintInterface;
use Garpor\PhpRequestValidator\ValidatorError;
use Garpor\PhpRequestValidator\DataValidatorInterface;
use InvalidArgumentException;


readonly final class SameAsValidator implements DataValidatorInterface
{
	public const ERROR_DOES_NOT_MATCH_MESSAGE = 'The value does not match.';
	public const ERROR_DOES_NOT_MATCH_CODE = 'error.does_not_match';

	public function validate($value, ConstraintInterface $constraint, array $data): ?array
	{
		if (!$constraint instanceof SameAs) {
			throw new InvalidArgumentException('Expected instance of SameAs.');
		}

		$compareValue = $data[$constraint->field] ?? null;

		if ($value !== $compareValue) {
			return [
				new ValidatorError(
					message: $constraint->message ?? self::ERROR_DOES_NOT_MATCH_MESSAGE,
					code: $constraint->code ?? self::ERROR_DOES_NOT_MATCH_CODE
				)
			];
		}

		return null;
	}
}
