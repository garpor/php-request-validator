<?php

declare(strict_types=1);

namespace Garpor\PhpRequestValidator\constraints;

readonly class GreaterThan implements \Garpor\PhpRequestValidator\ConstraintInterface
{
	public function __construct(
		public int     $value,
		public ?string $message = null,
		public ?string $code = null,
	)
	{
	}
}
