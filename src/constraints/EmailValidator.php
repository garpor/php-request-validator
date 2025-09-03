<?php

declare(strict_types=1);

namespace Garpor\PhpRequestValidator\constraints;

use Garpor\PhpRequestValidator\ConstraintInterface;
use Garpor\PhpRequestValidator\ValidatorError;
use Garpor\PhpRequestValidator\ValidatorInterface;
use InvalidArgumentException;
use function is_scalar;


readonly final class EmailValidator implements ValidatorInterface
{
	public const ERROR_INVALID_EMAIL_MESSAGE = 'The email format is not valid.';
	public const ERROR_INVALID_EMAIL_CODE = 'error.invalid_email';
	public const ERROR_INVALID_TYPE_EMAIL_MESSAGE = 'The value must be a string.';
	public const ERROR_INVALID_TYPE_EMAIL_CODE = 'error.invalid_email_type';

	private const EMAIL_PATTERN = '/^[a-zA-Z0-9.!#$%&\'*+\\/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)+$/D';

	public function validate(mixed $value, ConstraintInterface $constraint): ?array
	{
		if (!$constraint instanceof Email) {
			throw new InvalidArgumentException('Expected instance of Email.');
		}

		if (null === $value || '' === $value) {
			return null;
		}

		if (!is_scalar($value) && !$value instanceof \Stringable) {
			return [
				new ValidatorError(
					message: self::ERROR_INVALID_TYPE_EMAIL_MESSAGE,
					code:  self::ERROR_INVALID_TYPE_EMAIL_CODE
				)
			];
		}

		$value = (string) $value;
		if ('' === $value) {
			return null;
		}

		if (!preg_match(self::EMAIL_PATTERN, $value)) {
			return [
				new ValidatorError(
					message: $constraint->message ?? self::ERROR_INVALID_EMAIL_MESSAGE,
					code: $constraint->code ?? self::ERROR_INVALID_EMAIL_CODE
				)
			];
		}
		return null;
	}
}
