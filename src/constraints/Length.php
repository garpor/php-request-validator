<?php

declare(strict_types=1);

namespace Garpor\PhpRequestValidator\constraints;

use Garpor\PhpRequestValidator\ConstraintInterface;
use InvalidArgumentException;


final readonly class Length implements ConstraintInterface
{
	public function __construct(
		public ?int    $min = null,
		public ?int    $max = null,
		public ?string $message = null,
		public ?string $code = null,
		public ?string $minMessage = null,
		public ?string $maxMessage = null,
		public ?string $minCode = null,
		public ?string $maxCode = null,
	)
	{
		if ($this->min !== null && $this->min < 0) {
			throw new InvalidArgumentException('The "min" value must be a positive integer.');
		}

		if ($this->max !== null && $this->max < 0) {
			throw new InvalidArgumentException('The "max" value must be a positive integer.');
		}
	}
}
