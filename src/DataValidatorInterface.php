<?php

declare(strict_types=1);

namespace Garpor\PhpRequestValidator;

interface DataValidatorInterface
{
	/**
	 * @return ValidatorError[]|null
	 */
	public function validate($value, ConstraintInterface $constraint, array $data): ?array;
}
