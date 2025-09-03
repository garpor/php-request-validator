<?php

declare(strict_types=1);

namespace Garpor\PhpRequestValidator\constraints;

use Garpor\PhpRequestValidator\ConstraintInterface;


final readonly class DateTime implements ConstraintInterface
{
	public function __construct(
		public string  $format = 'Y-m-d H:i:s',
		public ?string $message = null,
		public ?string $code = null,
	)
	{
	}
}
