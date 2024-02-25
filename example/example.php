## Example

```php
<?php

require '../vendor/autoload.php';

use Phauthentic\AttributeSerializer\Serialize;
use Phauthentic\AttributeSerializer\Serializer;

class Aggregate
{
    #[Serialize('version')]
    public const SOME_CONSTANT = '0.0.1';
}

class Project extends Aggregate
{
    protected string $notSerialized = 'nope';

    #[Serialize('city')]
    public string $location = '';

    #[Serialize('company.name')]
    public string $company = '';

    #[Serialize('parent-project')]
    protected ?Project $parent;

    public function __construct(?Project $parentProject = null)
    {
        $this->parent = $parentProject;
    }
}

$parent = new Project();
$parent->company = 'some company name';
$parent->location = 'my value';

$childCompany = new Project($parent);
$childCompany->company = 'Parent Company!';
$childCompany->location = 'Second Value';

$serializer = new Serializer();
$data = $serializer->serialize($childCompany);

var_dump($data);
