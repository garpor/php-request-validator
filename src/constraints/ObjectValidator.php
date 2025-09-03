<?php

declare(strict_types=1);

namespace Garpor\PhpRequestValidator\constraints;

use Exception;
use Garpor\PhpRequestValidator\ConstraintInterface;
use Garpor\PhpRequestValidator\Validator;
use Garpor\PhpRequestValidator\ValidatorError;
use Garpor\PhpRequestValidator\ValidatorInterface;
use InvalidArgumentException;


final readonly class ObjectValidator implements ValidatorInterface
{
	public const ERROR_INVALID_TYPE_MESSAGE = 'The value must be an object or an array.';
	public const ERROR_INVALID_TYPE_CODE = 'error.invalid_type';
	public const ERROR_NESTED_VALIDATION_MESSAGE = 'Validation failed for the nested object.';
	public const ERROR_NESTED_VALIDATION_CODE = 'error.nested_validation_failed';
	public const ERROR_VALIDATION_INTERNAL_MESSAGE = 'A validation error occurred with the nested object: ';
	public const ERROR_VALIDATION_INTERNAL_CODE = 'validation.internal_error';

	public function validate($value, ConstraintInterface $constraint): ?array
	{
		if (!$constraint instanceof ObjectConstraint) {
			throw new InvalidArgumentException('Expected instance of ObjectConstraint.');
		}

		if (!is_object($value) && !is_array($value)) {
			return [
				new ValidatorError(
					message: $constraint->message ?? self::ERROR_INVALID_TYPE_MESSAGE,
					code: $constraint->code ?? self::ERROR_INVALID_TYPE_CODE
				)
			];
		}

		try {
			$nestedValidator = new Validator((array)$value);
			if (!$nestedValidator->validate($constraint->constraints)) {
				return [
					new ValidatorError(
						message: self::ERROR_NESTED_VALIDATION_MESSAGE,
						code: self::ERROR_NESTED_VALIDATION_CODE,
						nestedErrors: $nestedValidator->getErrors()
					)
				];
			}
		} catch (Exception $e) {
			return [
				new ValidatorError(
					message: self::ERROR_VALIDATION_INTERNAL_MESSAGE . $e->getMessage(),
					code: self::ERROR_VALIDATION_INTERNAL_CODE
				)
			];
		}

		return null;
	}
}
