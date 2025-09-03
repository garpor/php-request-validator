<?php

declare(strict_types=1);

namespace Garpor\PhpRequestValidator\constraints;

use Garpor\PhpRequestValidator\ConstraintInterface;
use InvalidArgumentException;


final readonly class Collection implements ConstraintInterface
{
	public function __construct(
		public array   $constraints,
		public ?string $message = null,
		public ?string $code = null
	)
	{
		if (empty($this->constraints)) {
			throw new InvalidArgumentException('The "constraints" parameter must not be empty for the Collection.');
		}
	}
}
