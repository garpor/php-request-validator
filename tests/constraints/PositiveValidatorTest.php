<?php

declare(strict_types=1);

namespace Garpor\PhpRequestValidator\tests\constraints;

use Garpor\PhpRequestValidator\ConstraintInterface;
use Garpor\PhpRequestValidator\constraints\Positive;
use Garpor\PhpRequestValidator\ValidatorError;
use Garpor\PhpRequestValidator\constraints\GreaterThan;
use Garpor\PhpRequestValidator\constraints\GreaterThanValidator;
use InvalidArgumentException;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;


final class PositiveValidatorTest extends TestCase
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

		$mockConstraint = $this->createMock(ConstraintInterface::class);

		$this->validator->validate(true, $mockConstraint);
	}

	#[DataProvider('provideValidValues')]
	public function testValidateReturnsNullForValidValues(mixed $value): void
	{
		$constraint = new Positive();

		$result = $this->validator->validate($value, $constraint);

		self::assertNull($result);
	}

	public static function provideValidValues(): array
	{
		return [
			'positive int'            => [6],
			'positive float'          => [5.1],
			'positive numeric string' => ['6'],
		];
	}

	#[DataProvider('provideInvalidValues')]
	public function testValidateReturnsErrorForInvalidValues(mixed $value, string $expectedMessage, string $expectedCode): void
	{
		$constraint = new Positive();

		$result = $this->validator->validate($value, $constraint);

		self::assertIsArray($result);
		self::assertCount(1, $result);
		self::assertInstanceOf(ValidatorError::class, $result[0]);
		self::assertSame($expectedMessage, $result[0]->getMessage());
		self::assertSame($expectedCode, $result[0]->getCode());
	}

	public static function provideInvalidValues(): array
	{
		$defaultMessage = Positive::DEFAULT_MESSAGE;
		$defaultCode = Positive::DEFAULT_CODE;

		return [
			'negative int'       => [-5, $defaultMessage, $defaultCode],
			'zero'               => [0, $defaultMessage, $defaultCode],
			'non-numeric string' => ['foo', $defaultMessage, $defaultCode],
			'bool false'         => [false, $defaultMessage, $defaultCode],
		];
	}

	public function testValidateUsesCustomMessageAndCode(): void
	{
		$customMessage = 'Custom too low';
		$customCode = 'custom.code';

		$constraint = new Positive( $customMessage, $customCode);

		$result = $this->validator->validate(-5, $constraint);

		self::assertIsArray($result);
		self::assertCount(1, $result);
		self::assertSame($customMessage, $result[0]->getMessage());
		self::assertSame($customCode, $result[0]->getCode());
	}
}
