<?php

declare(strict_types=1);

namespace Garpor\PhpRequestValidator;

readonly final class ValidatorError
{
	/**
	 * @param array<string, array<string, array>>|null $nestedErrors
	 */
	public function __construct(
		private string $message,
		private string $code,
		private ?array $nestedErrors = null
	)
	{
	}

	public function getMessage(): string
	{
		return $this->message;
	}

	public function getCode(): string
	{
		return $this->code;
	}

	public function getNestedErrors(): ?array
	{
		return $this->nestedErrors;
	}

	public function toArray(): array
	{
		$data = [
			'message' => $this->message,
			'code'    => $this->code,
		];

		if ($this->nestedErrors !== null) {
			$data['nestedErrors'] = $this->nestedErrors;
		}

		return $data;
	}
}
