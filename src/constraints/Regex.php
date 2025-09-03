<?php

declare(strict_types=1);

namespace Garpor\PhpRequestValidator\constraints;

use Garpor\PhpRequestValidator\ConstraintInterface;
use InvalidArgumentException;


final readonly class Regex implements ConstraintInterface
{
	public function __construct(
		public string  $pattern,
		public ?string $message = null,
		public ?string $code = null
	)
	{
		if (empty($this->pattern)) {
			throw new InvalidArgumentException('The "pattern" parameter must not be empty for the RegexConstraint.');
		}
	}
}
