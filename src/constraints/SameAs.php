<?php

declare(strict_types=1);

namespace Garpor\PhpRequestValidator\constraints;

use Garpor\PhpRequestValidator\ConstraintInterface;


final readonly class SameAs implements ConstraintInterface
{
	public function __construct(
		public string  $field,
		public ?string $message = null,
		public ?string $code = null
	)
	{
	}
}
