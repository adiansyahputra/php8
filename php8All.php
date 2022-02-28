<?php 

/*

echo "Belajar Fitur PHP 8" . PHP_EOL;

Named Argument
Biasanya saat kita memanggil function, maka kita harus memasukkan argument atau parameter sesuai dengan posisinya
Dengan kemampuan named argument, kita bisa memasukkan argument atau parameter tanpa harus mengikuti posisi nya
Namun penggunaan named argument harus disebutkan nama argument atau parameter nya
Named argument juga menjadikan kode program mudah dibaca ketika memanggil function yang memiliki argument yang sangat banyak
https://wiki.php.net/rfc/named_params 

Kode : Function
function sayHello(string $first, string $middle = "", string $last): void
{
    echo "Hello $first $middle $last" . PHP_EOL;
}

Kode : Named Argument
sayHello("Eko", "Kurniawan", "Khannedy");
// sayHello("Eko", "Khannedy"); // error

sayHello(last: "Khannedy", first: "Eko", middle: "Kurniawan");
sayHello(first: "Eko", last: "Khannedy");

Kode : Function Default Argument
function sayHello(string $first, string $middle = "", string $last): void
{
    echo "Hello $first $middle $last" . PHP_EOL;
}

Kode : Named Argument Default Value
sayHello("Eko", "Kurniawan", "Khannedy");
// sayHello("Eko", "Khannedy"); // error

sayHello(last: "Khannedy", first: "Eko", middle: "Kurniawan");
sayHello(first: "Eko", last: "Khannedy");

Attributes
Attributes adalah menambahkan metadata terhadap kode program yang kita buat.
Fitur ini adalah fitur yang sangat baru sekali di PHP, dan bisa memungkinkan fitur ini bakal diadopsi sangat banyak oleh framework-framework di PHP di masa yang akan datang
Fitur ini jika di bahasa pemrograman seperti Java bernama Annotation, Attributes di C# atau Decorator di Python dan JavaScript
https://www.php.net/manual/en/language.attributes.php 
https://wiki.php.net/rfc/attributes_v2 

Kode : Membuat Class Attribute
#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_CLASS)]
class NotBlank
{
}

Menggunakan Attribute
Attribute bisa kita gunakan di berbagai tempat, seperti di Class, Function, Method, Property, Class Constant dan Parameter
Untuk menggunakan Attribute, kita cukup gunakan tanda #[NamaAttribute] di target yang kita tentukan

Menggunakan Attribute di Property
class LoginRequest
{
    #[Length(min: 4, max: 10)]
    #[NotBlank]
    public ?string $username;

    #[NotBlank]
    #[Length(min: 8, max: 10)]
    public ?string $password;
}

Kode : Membaca Attribute via Reflection (1)
function validate(object $object): void
{
    $class = new ReflectionClass($object);
    $properties = $class->getProperties();
    foreach ($properties as $property) {
        validateNotBlank($property, $object);
        validateLength($property, $object);
    }
}

Kode : Membaca Attribute via Reflection (2)
function validateNotBlank(ReflectionProperty $property, object $object): void
{
    $attributes = $property->getAttributes(NotBlank::class);
    if (count($attributes) > 0) {
        if (!$property->isInitialized($object))
            throw new Exception("Property $property->name is null");
        if ($property->getValue($object) == null)
            throw new Exception("Property $property->name is null");
    }
}

Attribute Target
Secara default, attribute bisa digunakan di semua target (class, function, method, property, dan lain-lain)
Jika kita ingin membatasi hanya bisa digunakan di target tertentu, kita bisa tambahkan informasinya ketika membaut class attribute

Kode : Attribute Target
#[Attribute(Attribute::TARGET_PROPERTY | Attribute::TARGET_CLASS)]
class NotBlank
{
}

Attribute Class 
Attribute class  adalah class biasa, kita bisa menambahkan property, function / method dan constructor jika kita mau
Ini cocok ketika kita butuh menambahkan informasi tambahan di attribute class

Kode : Attribute Class
#[Attribute(Attribute::TARGET_PROPERTY)]
class Length
{
    public int $min;
    public int $max;

    public function __construct(int $min, int $max)
    {
        $this->min = $min;
        $this->max = $max;
    }
}

Kode : Menggunakan Attribute Class
class LoginRequest
{
    #[Length(min: 4, max: 10)]
    #[NotBlank]
    public ?string $username;

    #[NotBlank]
    #[Length(min: 8, max: 10)]
    public ?string $password;
}

Kode : Membuat Attribute Class Instance
function validateLength(ReflectionProperty $property, object $object): void
{
    if (!$property->isInitialized($object) || $property->getValue($object) == null) {
        return;
    }

    $value = $property->getValue($object);
    $attributes = $property->getAttributes(Length::class);
    foreach ($attributes as $attribute) {
        $length = $attribute->newInstance();
        $valueLength = strlen($value);
        if ($valueLength < $length->min)
            throw new Exception("Property $property->name is too short");
        if ($valueLength > $length->max)
            throw new Exception("Property $property->name is too long");
    }
}




*/