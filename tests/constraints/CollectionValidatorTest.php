<?php

declare(strict_types=1);

namespace Garpor\PhpRequestValidator\tests\constraints;

use Garpor\PhpRequestValidator\ConstraintInterface;
use Garpor\PhpRequestValidator\constraints\Collection;
use Garpor\PhpRequestValidator\constraints\CollectionValidator;
use Garpor\PhpRequestValidator\constraints\Required;
use Garpor\PhpRequestValidator\constraints\Text;
use Garpor\PhpRequestValidator\ValidatorError;
use InvalidArgumentException;
use PHPUnit\Framework\TestCase;
use stdClass;


final class CollectionValidatorTest extends TestCase
{
	private CollectionValidator $validator;

	protected function setUp(): void
	{
		parent::setUp();
		$this->validator = new CollectionValidator();
	}

	public function testValidateThrowsExceptionWithInvalidConstraint(): void
	{
		$this->expectException(InvalidArgumentException::class);
		$this->expectExceptionMessage('Expected instance of Collection.');

		$mockConstraint = $this->createMock(ConstraintInterface::class);

		$this->validator->validate([], $mockConstraint);
	}

	/**
	 * @dataProvider provideValidCollections
	 */
	public function testValidateWithValidCollection(array $value, Collection $constraint): void
	{
		$this->assertNull($this->validator->validate($value, $constraint));
	}

	public static function provideValidCollections(): array
	{
		return [
			'single item valid'    => [
				[['name' => 'John']],
				new Collection(constraints: ['name' => [new Required()]])
			],
			'multiple items valid' => [
				[
					['name' => 'John'],
					['name' => 'Jane']
				],
				new Collection(constraints: ['name' => [new Required()]])
			],
			'complex valid data'   => [
				[
					['name' => 'Product A', 'sku' => 'A123'],
					['name' => 'Product B', 'sku' => 'B456']
				],
				new Collection(constraints: [
					'name' => [new Required()],
					'sku'  => [new Required(), new Text(min: 4, max: 5)]
				])
			]
		];
	}

	/**
	 * @dataProvider provideInvalidCollections
	 */
	public function testValidateWithInvalidCollections(mixed $value, Collection $constraint, array $expectedNestedErrors): void
	{
		$errors = $this->validator->validate($value, $constraint);

		$this->assertIsArray($errors);
		$this->assertCount(1, $errors);
		$error = $errors[0];

		$this->assertInstanceOf(ValidatorError::class, $error);
		$this->assertSame(CollectionValidator::ERROR_INVALID_ITEM_MESSAGE, $error->getMessage());
		$this->assertSame(CollectionValidator::ERROR_INVALID_ITEM_CODE, $error->getCode());
		$this->assertEquals($expectedNestedErrors, $error->getNestedErrors());
	}

	public static function provideInvalidCollections(): array
	{
		return [
			'missing required field'      => [
				[['name' => '']],
				new Collection(constraints: ['name' => [new Required()]]),
				[
					0 => [
						'name' => [
							['message' => 'This field is required.', 'code' => 'error.required']
						]
					]
				]
			],
			'multiple errors on one item' => [
				[['name' => 'a']],
				new Collection(constraints: [
					'name' => [
						new Text(min: 5)
					]
				]),
				[
					0 => [
						'name' => [
							['message' => 'The minimum length is 5 characters.', 'code' => 'error.min_length']
						]
					]
				]
			],
			'errors on multiple items'    => [
				[
					['name' => 'a'],
					['name' => '']
				],
				new Collection(constraints: [
					'name' => [
						new Required(),
						new Text(min: 5)
					]
				]),
				[
					0 => [
						'name' => [
							['message' => 'The minimum length is 5 characters.', 'code' => 'error.min_length']
						]
					],
					1 => [
						'name' => [
							['message' => 'This field is required.', 'code' => 'error.required'],
							['message' => 'The minimum length is 5 characters.', 'code' => 'error.min_length']
						]
					]
				]
			]
		];
	}

	/**
	 * @dataProvider provideInvalidTypes
	 */
	public function testValidateWithInvalidTypes(mixed $value): void
	{
		$constraint = new Collection(constraints: ['name' => [new Required()]]);
		$errors = $this->validator->validate($value, $constraint);

		$this->assertIsArray($errors);
		$this->assertCount(1, $errors);
		$error = $errors[0];

		$this->assertInstanceOf(ValidatorError::class, $error);
		$this->assertSame(CollectionValidator::ERROR_INVALID_TYPE_MESSAGE, $error->getMessage());
		$this->assertSame(CollectionValidator::ERROR_INVALID_TYPE_CODE, $error->getCode());
	}

	public static function provideInvalidTypes(): array
	{
		return [
			'string'  => ['string'],
			'integer' => [123],
			'boolean' => [true],
			'null'    => [null],
			'object'  => [new stdClass()]
		];
	}
}
