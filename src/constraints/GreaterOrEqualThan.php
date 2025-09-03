<?php

declare(strict_types=1);

namespace Garpor\PhpRequestValidator\constraints;

class GreaterOrEqualThan
{
	public function __construct(
		public readonly int $value,
		public readonly ?string $message = null,
		public readonly ?string $code = null,
	) {
	}
}
