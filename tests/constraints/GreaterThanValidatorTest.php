<?php

declare(strict_types=1);

namespace Garpor\PhpRequestValidator\tests\constraints;

use Garpor\PhpRequestValidator\ConstraintInterface;
use Garpor\PhpRequestValidator\ValidatorError;
use Garpor\PhpRequestValidator\constraints\GreaterThan;
use Garpor\PhpRequestValidator\constraints\GreaterThanValidator;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;


final class GreaterThanValidatorTest extends TestCase
{
	private GreaterThanValidator $validator;

	protected function setUp(): void
	{
		parent::setUp();
		$this->validator = new GreaterThanValidator();
	}

	public function testValidateThrowsWhenConstraintIsNotGreaterThan(): void
	{
		$this->expectException(InvalidArgumentException::class);
		$this->expectExceptionMessage('Expected instance of GreaterThan.');

		$mockConstraint = $this->createMock(ConstraintInterface::class);

		$this->validator->validate(true, $mockConstraint);
	}

	#[DataProvider('provideValidValues')]
	public function testValidateReturnsNullForValidValues(mixed $value, int $limit): void
	{
		$validator = new GreaterThanValidator();
		$constraint = new GreaterThan($limit);

		$result = $validator->validate($value, $constraint);

		self::assertNull($result);
	}

	public static function provideValidValues(): array
	{
		return [
			'int greater than limit'            => [6, 5],
			'float greater than limit'          => [5.1, 5],
			'numeric string greater than limit' => ['6', 5],
		];
	}

	#[DataProvider('provideInvalidValues')]
	public function testValidateReturnsErrorForInvalidValues(mixed $value, int $limit, string $expectedMessage, string $expectedCode): void
	{
		$validator = new GreaterThanValidator();
		$constraint = new GreaterThan($limit);

		$result = $validator->validate($value, $constraint);

		self::assertIsArray($result);
		self::assertCount(1, $result);
		self::assertInstanceOf(ValidatorError::class, $result[0]);
		self::assertSame($expectedMessage, $result[0]->getMessage());
		self::assertSame($expectedCode, $result[0]->getCode());
	}

	public static function provideInvalidValues(): array
	{
		$defaultMessage5 = sprintf(GreaterThanValidator::ERROR_TOO_LOW_MESSAGE, 5);
		$defaultCode = GreaterThanValidator::ERROR_TOO_LOW_CODE;

		return [
			'int equal to limit'   => [5, 5, $defaultMessage5, $defaultCode],
			'int lower than limit' => [4, 5, $defaultMessage5, $defaultCode],
			'non-numeric string'   => ['foo', 5, $defaultMessage5, $defaultCode],
			'bool false'           => [false, 5, $defaultMessage5, $defaultCode],
		];
	}

	public function testValidateUsesCustomMessageAndCode(): void
	{
		$validator = new GreaterThanValidator();

		$customMessage = 'Custom too low';
		$customCode = 'custom.code';

		$constraint = new GreaterThan(10, $customMessage, $customCode);

		$result = $validator->validate(10, $constraint);

		self::assertIsArray($result);
		self::assertCount(1, $result);
		self::assertSame($customMessage, $result[0]->getMessage());
		self::assertSame($customCode, $result[0]->getCode());
	}
}
