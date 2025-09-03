<?php

declare(strict_types=1);

namespace Garpor\PhpRequestValidator\constraints;

final class Positive extends GreaterThan
{
	public const DEFAULT_VALUE = 0;
	public const DEFAULT_MESSAGE = 'The value must be positive.';
	public const DEFAULT_CODE = 'error.not_positive';

	public function __construct(
		?string $message = null,
		?string $code = null,
	) {
		parent::__construct(
			value: self::DEFAULT_VALUE,
			message: $message ?? self::DEFAULT_MESSAGE,
			code: $code ?? self::DEFAULT_CODE
		);
	}
}
