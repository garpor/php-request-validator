<?php

declare(strict_types=1);

namespace Garpor\PhpRequestValidator\constraints;

use Garpor\PhpRequestValidator\ConstraintInterface;
use InvalidArgumentException;


readonly final class ObjectConstraint implements ConstraintInterface
{
	public function __construct(
		public array $constraints,
		public ?string $message = null,
		public ?string $code = null,
	)
	{
		if (empty($constraints)) {
			throw new InvalidArgumentException('The "rules" parameter must not be empty for the ObjectConstraint.');
		}
	}
}
