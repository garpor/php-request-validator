# 🚧 Under Construction! 🚧

Hi everyone,

This project is still under active development. Please check back soon for updates!

Thanks for your patience.

----------------------------

# PHP Request Validator

A simple and extensible library for validating request data in PHP.

## 🚀 Installation

Ensure your `Constraint` and `Validator` classes are in your project. The main `Validator` class serves as the entry point for all validations.

## 🛠️ Usage

### 1\. The Main Validator

The `Validator` class is the engine that processes the rules. You initialize it with the data you want to validate.

```php
use Garpor\PhpRequestValidator\Validator;

$data = [
    'username' => 'JohnDoe12',
    'email' => 'john.doe@example.com',
    'age' => 25,
    'password' => 'password123',
    'password_confirmation' => 'password123',
    'profile' => [
        'first_name' => 'John',
        'last_name' => 'Doe',
    ],
    'tags' => ['php', 'web'],
];

$validator = new Validator($data);
```

### 2\. Defining the Validation Rules

Rules are defined as an associative array where the key is the field name and the value is an array of `Constraint` objects.

```php
use Garpor\PhpRequestValidator\constraints\Required;
use Garpor\PhpRequestValidator\constraints\EmailConstraint;
use Garpor\PhpRequestValidator\constraints\Length;
use Garpor\PhpRequestValidator\constraints\SameAs;
use Garpor\PhpRequestValidator\constraints\ObjectConstraint;
use Garpor\PhpRequestValidator\constraints\CollectionConstraint;

$rules = [
    'username' => [
        new Required(),
        new Length(min: 3, max: 20),
    ],
    'email' => [
        new Required(),
        new Email(),
    ],
    'password_confirmation' => [
        new SameAs(field: 'password'),
    ],
    'profile' => [
        new ObjectConstraint(rules: [
            'first_name' => [new Required()],
            'last_name' => [new Required()],
        ])
    ],
    'tags' => [
        new Collection(constraints: [
            new Length(min: 2),
        ]),
    ],
];

// ... run the validation ...
```

### 3\. Running and Getting Errors

The `validate` method returns `true` if all data is valid and `false` otherwise. Errors can be retrieved with the `getErrors()` method.

```php
if (!$validator->validate($rules)) {
    print_r($validator->getErrors());
}
```
