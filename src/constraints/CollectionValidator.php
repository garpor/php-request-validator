<?php

declare(strict_types=1);

namespace Garpor\PhpRequestValidator\constraints;

use Garpor\PhpRequestValidator\ConstraintInterface;
use Garpor\PhpRequestValidator\Validator;
use Garpor\PhpRequestValidator\ValidatorError;
use Garpor\PhpRequestValidator\ValidatorInterface;
use InvalidArgumentException;


readonly final class CollectionValidator implements ValidatorInterface
{
	public const ERROR_INVALID_TYPE_MESSAGE = 'The value must be an array.';
	public const ERROR_INVALID_TYPE_CODE = 'error.invalid_type';
	public const ERROR_INVALID_ITEM_MESSAGE = 'An item in the collection has errors.';
	public const ERROR_INVALID_ITEM_CODE = 'error.collection.invalid';

	public function validate(mixed $value, ConstraintInterface $constraint): ?array
	{
		if (!$constraint instanceof Collection) {
			throw new InvalidArgumentException('Expected instance of Collection.');
		}

		if (!is_array($value)) {
			return [
				new ValidatorError(
					message: $constraint->message ?? self::ERROR_INVALID_TYPE_MESSAGE,
					code: $constraint->code ?? self::ERROR_INVALID_TYPE_CODE
				)
			];
		}

		$allErrors = [];

		foreach ($value as $index => $item) {
			$itemValidator = new Validator($item);
			$itemIsValid = $itemValidator->validate($constraint->constraints);

			if (!$itemIsValid) {
				$itemErrors = $itemValidator->getErrors();
				$allErrors[$index] = $itemErrors;
			}
		}

		if (!empty($allErrors)) {
			return [
				new ValidatorError(
					message: $constraint->message ?? self::ERROR_INVALID_ITEM_MESSAGE,
					code: $constraint->code ?? self::ERROR_INVALID_ITEM_CODE,
					nestedErrors: $allErrors
				)
			];
		}

		return null;
	}
}
