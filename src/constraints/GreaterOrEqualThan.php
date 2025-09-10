<?php

declare(strict_types=1);

namespace Garpor\PhpRequestValidator\constraints;

readonly class GreaterOrEqualThan implements \Garpor\PhpRequestValidator\ConstraintInterface
{
	public function __construct(
		public int     $value,
		public ?string $message = null,
		public ?string $code = null,
	) {
	}
}
