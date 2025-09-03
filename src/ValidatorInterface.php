<?php

declare(strict_types=1);

namespace Garpor\PhpRequestValidator;

interface ValidatorInterface
{
	/**
	 * @param mixed $value
	 * @param ConstraintInterface $constraint
	 * @return ValidatorError[]|null Un array de objetos ValidatorError o null si es válido.
	 */
	public function validate(mixed $value, ConstraintInterface $constraint): ?array;
}
