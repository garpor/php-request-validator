<?php

declare(strict_types=1);

namespace Garpor\PhpRequestValidator\tests\constraints;

use Garpor\PhpRequestValidator\ConstraintInterface;
use Garpor\PhpRequestValidator\constraints\GreaterOrEqualThan;
use Garpor\PhpRequestValidator\constraints\GreaterOrEqualThanValidator;
use Garpor\PhpRequestValidator\ValidatorError;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;


final class GreaterOrEqualThanValidatorTest extends TestCase
{
	private GreaterOrEqualThanValidator $validator;

	protected function setUp(): void
	{
		parent::setUp();
		$this->validator = new GreaterOrEqualThanValidator();
	}

	public function testValidateThrowsWhenConstraintIsNotGreaterThan(): void
	{
		$this->expectException(InvalidArgumentException::class);
		$this->expectExceptionMessage('Expected instance of GreaterOrEqualThan.');

		$mockConstraint = $this->createMock(ConstraintInterface::class);

		$this->validator->validate(true, $mockConstraint);
	}

	#[DataProvider('provideValidValues')]
	public function testValidateReturnsNullForValidValues(mixed $value, int $limit): void
	{
		$constraint = new GreaterOrEqualThan($limit);

		$result = $this->validator->validate($value, $constraint);

		self::assertNull($result);
	}

	public static function provideValidValues(): array
	{
		return [
			'int greater than limit'            => [6, 5],
			'float greater than limit'          => [5.1, 5],
			'numeric string greater than limit' => ['6', 5],
			'int equal to limit'                => [5, 5],
		];
	}

	#[DataProvider('provideInvalidValues')]
	public function testValidateReturnsErrorForInvalidValues(mixed $value, int $limit, string $expectedMessage, string $expectedCode): void
	{
		$constraint = new GreaterOrEqualThan($limit);

		$result = $this->validator->validate($value, $constraint);

		self::assertIsArray($result);
		self::assertCount(1, $result);
		self::assertInstanceOf(ValidatorError::class, $result[0]);
		self::assertSame($expectedMessage, $result[0]->getMessage());
		self::assertSame($expectedCode, $result[0]->getCode());
	}

	public static function provideInvalidValues(): array
	{
		$defaultMessage5 = sprintf(GreaterOrEqualThanValidator::ERROR_TOO_LOW_MESSAGE, 5);
		$defaultCode = GreaterOrEqualThanValidator::ERROR_TOO_LOW_CODE;

		return [
			'int lower than limit' => [4, 5, $defaultMessage5, $defaultCode],
			'non-numeric string'   => ['foo', 5, $defaultMessage5, $defaultCode],
			'bool false'           => [false, 5, $defaultMessage5, $defaultCode],
		];
	}

	public function testValidateUsesCustomMessageAndCode(): void
	{
		$customMessage = 'Custom too low';
		$customCode = 'custom.code';

		$constraint = new GreaterOrEqualThan(10, $customMessage, $customCode);

		$result = $this->validator->validate(5, $constraint);

		self::assertIsArray($result);
		self::assertCount(1, $result);
		self::assertSame($customMessage, $result[0]->getMessage());
		self::assertSame($customCode, $result[0]->getCode());
	}
}
