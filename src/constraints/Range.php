<?php

declare(strict_types=1);

namespace Garpor\PhpRequestValidator\constraints;

use Garpor\PhpRequestValidator\ConstraintInterface;
use InvalidArgumentException;


final readonly class Range implements ConstraintInterface
{
	public function __construct(
		public ?int    $min = null,
		public ?int    $max = null,
		public ?string $message = null,
		public ?string $code = null,
		public ?string $tooLowMessage = null,
		public ?string $tooLowCode = null,
		public ?string $tooHighMessage = null,
		public ?string $tooHighCode = null
	)
	{
		if ($this->min !== null && $this->max !== null && $this->min > $this->max) {
			throw new InvalidArgumentException('The "min" value cannot be greater than the "max" value.');
		}
	}
}
