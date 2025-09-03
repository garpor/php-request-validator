<?php

declare(strict_types=1);

namespace Garpor\PhpRequestValidator\constraints;

final class NotEmpty
{
	public function __construct(
		public readonly ?string $message = null,
		public readonly ?string $code = null,
	) {
	}
}
