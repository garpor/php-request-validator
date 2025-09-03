<?php

declare(strict_types=1);

namespace Garpor\PhpRequestValidator\constraints;

use Garpor\PhpRequestValidator\ConstraintInterface;


final readonly class IsBoolean implements ConstraintInterface
{
	public function __construct(
		public ?string $message = null,
		public ?string $code = null
	)
	{
	}
}
