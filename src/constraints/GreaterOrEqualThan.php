<?php

declare(strict_types=1);

namespace Garpor\PhpRequestValidator\constraints;

use Garpor\PhpRequestValidator\ConstraintInterface;


class GreaterOrEqualThan implements ConstraintInterface
{
	public function __construct(
		public int     $value,
		public ?string $message = null,
		public ?string $code = null,
	)
	{
	}
}
