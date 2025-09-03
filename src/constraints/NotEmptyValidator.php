<?php

declare(strict_types=1);

namespace Garpor\PhpRequestValidator\constraints;

use Garpor\PhpRequestValidator\ConstraintInterface;
use Garpor\PhpRequestValidator\ValidatorError;
use Garpor\PhpRequestValidator\ValidatorInterface;
use InvalidArgumentException;


readonly final class NotEmptyValidator implements ValidatorInterface
{
	public const ERROR_EMPTY_MESSAGE = 'This field cannot be empty.';
	public const ERROR_EMPTY_CODE = 'error.empty';

	public function validate($value, ConstraintInterface $constraint): ?array
	{
		if (!$constraint instanceof NotEmpty) {
			throw new InvalidArgumentException('Expected instance of NotEmpty.');
		}

		if (empty($value) && $value !== '0' && $value !== 0) {
			return [
				new ValidatorError(
					message: $constraint->message ?? self::ERROR_EMPTY_MESSAGE,
					code: $constraint->code ?? self::ERROR_EMPTY_CODE
				)
			];
		}

		return null;
	}
}
