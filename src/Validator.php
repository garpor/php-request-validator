<?php

declare(strict_types=1);

namespace Garpor\PhpRequestValidator;

use InvalidArgumentException;
use RuntimeException;


final class Validator
{
	private array $data;
	private array $errors = [];
	private array $validatorMap = [
		constraints\IsBoolean::class          => constraints\IsBooleanValidator::class,
		constraints\Collection::class         => constraints\CollectionValidator::class,
		constraints\Email::class              => constraints\EmailValidator::class,
		constraints\Length::class             => constraints\LengthValidator::class,
		constraints\ObjectConstraint::class   => constraints\ObjectValidator::class,
		constraints\Range::class              => constraints\RangeValidator::class,
		constraints\Regex::class              => constraints\RegexValidator::class,
		constraints\Required::class           => constraints\RequiredValidator::class,
		constraints\SameAs::class             => constraints\SameAsValidator::class,
		constraints\Text::class               => constraints\TextValidator::class,
		constraints\NotEmpty::class           => constraints\NotEmptyValidator::class,
		constraints\Positive::class           => constraints\GreaterThanValidator::class,
		constraints\PositiveOrZero::class     => constraints\GreaterOrEqualThanValidator::class,
		constraints\GreaterThan::class        => constraints\GreaterThanValidator::class,
		constraints\GreaterOrEqualThan::class => constraints\GreaterOrEqualThanValidator::class,
		constraints\Date::class               => constraints\DateValidator::class,
		constraints\DateTime::class           => constraints\DateTimeValidator::class,
	];

	public function __construct(array $data)
	{
		$this->data = $data;
	}

	public function validate(array $rules): bool
	{
		foreach ($rules as $attribute => $constraints) {
			foreach ($constraints as $constraint) {
				if (!is_object($constraint) || !$constraint instanceof ConstraintInterface || !isset($this->validatorMap[get_class($constraint)])) {
					throw new InvalidArgumentException('Invalid validator rule provided.');
				}

				$validatorClass = $this->validatorMap[get_class($constraint)];
				$validatorInstance = new $validatorClass();

				$value = $this->data[$attribute] ?? null;

				$errors = null;
				if ($validatorInstance instanceof DataValidatorInterface) {
					$errors = $validatorInstance->validate($value, $constraint, $this->data);
				} else if ($validatorInstance instanceof ValidatorInterface) {
					$errors = $validatorInstance->validate($value, $constraint);
				} else {
					throw new RuntimeException('Validator class must implement a valid validator interface.');
				}

				if ($errors) {
					foreach ($errors as $error) {
						$this->addError($attribute, $error);
					}
				}
			}
		}
		return empty($this->errors);
	}

	public function addError(string $attribute, ValidatorError $error): void
	{
		if (!isset($this->errors[$attribute])) {
			$this->errors[$attribute] = [];
		}
		$this->errors[$attribute][] = $error;
	}

	public function hasErrors(): bool
	{
		return !empty($this->errors);
	}

	public function getErrors(): array
	{
		$errors = [];
		foreach ($this->errors as $attribute => $errorObjects) {
			foreach ($errorObjects as $errorObject) {
				$errors[$attribute][] = $errorObject->toArray();
			}
		}
		return $errors;
	}
}
