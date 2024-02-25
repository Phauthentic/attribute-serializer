# Attribute Based Serializer

![PHP >= 8.1](https://img.shields.io/static/v1?label=PHP&message=^8.1&color=787CB5&style=for-the-badge&logo=php)
![phpstan Level 8](https://img.shields.io/static/v1?label=phpstan&message=Level%208&color=%3CCOLOR%3E&style=for-the-badge)
![License: MIT](https://img.shields.io/static/v1?label=License&message=MIT&color=%3CCOLOR%3E&style=for-the-badge)

Serializer class for extracting data from objects annotated with the `#[Serialize]` attribute.

This class provides a method, serialize, to transform an object into an associative array by extracting data from its properties and constants based on the `#[Serialize]` attribute.

## Installation

```sh
composer require phauthentic/attribute-serializer
```

## How to use it?

Add the `#[Serialize()]` attribute to the property or constant. You can rename the property in the resulting array by providing a name to the attribute `#[Serialize('other-name')]`.

```php
class Example {
    #[Serialize('username')]
    private $name = 'serializer';
}

var_dump((new Serializer())->serialize(new Example()));
```

```text
[
    'username' => 'serializer'
]
```

### Dot notation for deep arrays

Field names can be dynamically renamed, even into deeper array structures, by using the dot notation.

```php
class Example2 {
    #[Serialize('first.second')]
    private $name = 'serializer';
}

var_dump((new Serializer())->serialize(new Example2());
```

```text
[
    'first' => [
        'second' => 'serializer'
    ]
]
```

### ToArrayTrait

```php
class Example3 {
    use ToArrayTrait;

    #[Serialize('username')]
    private $name = 'serializer';
}

var_dump((new Example3)->toArray());
```

```text
[
    'username' => 'serializer'
]
```

## License

Copyright Florian Krämer

Licensed under the [MIT license](LICENSE).
