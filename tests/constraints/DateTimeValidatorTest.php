<?php

declare(strict_types=1);

namespace Garpor\PhpRequestValidator\tests\constraints;

use Garpor\PhpRequestValidator\ConstraintInterface;
use Garpor\PhpRequestValidator\constraints\DateTime as DateTimeConstraint;
use Garpor\PhpRequestValidator\constraints\DateTimeValidator;
use Garpor\PhpRequestValidator\ValidatorError;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use stdClass;


final class DateTimeValidatorTest extends TestCase
{
	private DateTimeValidator $validator;

	protected function setUp(): void
	{
		parent::setUp();
		$this->validator = new DateTimeValidator();
	}

	public function testValidateThrowsExceptionWithInvalidConstraint(): void
	{
		$this->expectException(InvalidArgumentException::class);
		$this->expectExceptionMessage('Expected instance of DateTime.');

		$mockConstraint = $this->createMock(ConstraintInterface::class);

		$this->validator->validate('2023-01-01', $mockConstraint);
	}

	/**
	 * @dataProvider provideValidValues
	 */
	public function testValidateWithValidValues(string $value, string $format): void
	{
		$constraint = new DateTimeConstraint(format: $format);
		$this->assertNull($this->validator->validate($value, $constraint));
	}

	public static function provideValidValues(): array
	{
		return [
			'default format'      => ['2023-01-01 10:00:00', 'Y-m-d H:i:s'],
			'custom format d/m/Y' => ['01/01/2023 10:00', 'd/m/Y H:i'],
			'with timezone'       => ['2023-01-01T10:00:00+02:00', DATE_ATOM],
			'with timezone Z'     => ['2023-01-01T10:00:00Z', DATE_ATOM],
			'just date'           => ['2023-01-01 00:00:00', 'Y-m-d H:i:s'],
		];
	}

	/**
	 * @dataProvider provideInvalidValues
	 */
	public function testValidateWithInvalidValues(mixed $value, string $format, string $expectedMessage, string $expectedCode): void
	{
		$constraint = new DateTimeConstraint(format: $format, message: $expectedMessage, code: $expectedCode);
		$errors = $this->validator->validate($value, $constraint);

		$this->assertIsArray($errors);
		$this->assertCount(1, $errors);
		$this->assertInstanceOf(ValidatorError::class, $errors[0]);
		$this->assertSame($expectedMessage, $errors[0]->getMessage());
		$this->assertSame($expectedCode, $errors[0]->getCode());
	}

	public static function provideInvalidValues(): array
	{
		return [
			'invalid string format'    => ['2023-13-01 10:00:00', 'Y-m-d H:i:s', 'The value is not a valid date and time.', 'error.invalid_datetime'],
			'string with wrong format' => ['01-01-2023 10:00:00', 'Y-m-d H:i:s', 'The value is not a valid date and time.', 'error.invalid_datetime'],
			'non-string value'         => [123, 'Y-m-d H:i:s', 'The value is not a valid date and time.', 'error.invalid_datetime'],
			'empty string'             => ['', 'Y-m-d H:i:s', 'The value is not a valid date and time.', 'error.invalid_datetime'],
			'null value'               => [null, 'Y-m-d H:i:s', 'The value is not a valid date and time.', 'error.invalid_datetime'],
			'custom error message'     => ['invalid', 'Y-m-d H:i:s', 'Custom invalid datetime message.', 'custom.code'],
			'non-existent date'        => ['2023-02-30 10:00:00', 'Y-m-d H:i:s', 'The value is not a valid date and time.', 'error.invalid_datetime'],
			'object value'             => [new stdClass(), 'Y-m-d H:i:s', 'The value is not a valid date and time.', 'error.invalid_datetime'],
		];
	}
}
