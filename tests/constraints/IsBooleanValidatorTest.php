<?php

declare(strict_types=1);

namespace Garpor\PhpRequestValidator\tests\constraints;

use Garpor\PhpRequestValidator\ConstraintInterface;
use Garpor\PhpRequestValidator\constraints\IsBoolean;
use Garpor\PhpRequestValidator\constraints\IsBooleanValidator;
use Garpor\PhpRequestValidator\ValidatorError;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use stdClass;


final class IsBooleanValidatorTest extends TestCase
{
	private IsBooleanValidator $validator;

	protected function setUp(): void
	{
		parent::setUp();
		$this->validator = new IsBooleanValidator();
	}

	public function testValidateThrowsExceptionWithInvalidConstraint(): void
	{
		$this->expectException(InvalidArgumentException::class);
		$this->expectExceptionMessage('Expected instance of IsBoolean.');

		$mockConstraint = $this->createMock(ConstraintInterface::class);

		$this->validator->validate(true, $mockConstraint);
	}

	#[DataProvider('provideValidValues')]
	public function testValidateWithValidValues(mixed $value): void
	{
		$constraint = new IsBoolean();

		$this->assertNull($this->validator->validate($value, $constraint));
	}

	public static function provideValidValues(): array
	{
		return [
			'boolean true'  => [true],
			'boolean false' => [false],
			'string true'   => ['true'],
			'string false'  => ['false'],
			'integer one'   => [1],
			'integer zero'  => [0],
			'string one'    => ['1'],
			'string zero'   => ['0'],
		];
	}

	#[DataProvider('provideInvalidValues')]
	public function testValidateWithInvalidValues(mixed $value): void
	{
		$constraint = new IsBoolean();
		$errors = $this->validator->validate($value, $constraint);

		$this->assertIsArray($errors);
		$this->assertCount(1, $errors);
		$this->assertInstanceOf(ValidatorError::class, $errors[0]);
		$this->assertSame(IsBooleanValidator::ERROR_INVALID_BOOLEAN_MESSAGE, $errors[0]->getMessage());
		$this->assertSame(IsBooleanValidator::ERROR_INVALID_BOOLEAN_CODE, $errors[0]->getCode());
	}

	public static function provideInvalidValues(): array
	{
		return [
			'string with content'       => ['test'],
			'empty string'              => [''],
			'null'                      => [null],
			'integer other than 0 or 1' => [2],
			'float'                     => [1.5],
			'array'                     => [[]],
			'object'                    => [new stdClass()],
		];
	}

	public function testValidateWithCustomMessageAndCode(): void
	{
		$customMessage = 'Invalid bool value.';
		$customCode = 'custom.invalid.boolean';
		$constraint = new IsBoolean(message: $customMessage, code: $customCode);
		$errors = $this->validator->validate('not a boolean', $constraint);

		$this->assertIsArray($errors);
		$this->assertCount(1, $errors);
		$this->assertSame($customMessage, $errors[0]->getMessage());
		$this->assertSame($customCode, $errors[0]->getCode());
	}
}
