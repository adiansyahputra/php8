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

Constructor Property Promotion
Kadang kita sering sekali membuat property sekaligus mengisi property tersebut menggunakan constructor
Sekarang kita bisa otomatis langsung membuat property dengan via constructor
Fitur ini mirip sekali di bahasa pemrograman seperti Kotlin dan TypeScript
https://wiki.php.net/rfc/constructor_promotion 

Kode : Property dan Constructor
class Person
{

    var string $name;
    var ?string $address = null;
    var string $country = "Indonesia";

    function __construct(string $name, ?string $address)
    {
        $this->name = $name;
        $this->address = $address;
    }

Kode : Constructor Property Promotion
class Product
{
    public function __construct(
        public string $id,
        public string $name,
        public int $price = 0,
        public int $quantity = 0,
        private bool $expensive = false
    ) {
    }
}

$product = new Product(id: "1", name: "Mie Ayam", price: 15000);
var_dump($product);

echo $product->name . PHP_EOL;

Union Types
PHP adalah bahasa pemrograman yang dynamic
Kita tahu sebenarnya saat membuat variabel, parameter, argument, return value, sebenarnya di PHP kita tidak wajib menyebutkan tipe datanya, dan PHP bisa berubah-ubah tipe data
Saat kita tambahkan tipe data, maka secara otomatis PHP akan memastikan tipe data tersebut harus sesuai dengan tipe data yang sudah kita definisikan
Di PHP 8, ada fitur Union Types, dimana kita bisa menambahkan lebih dari satu tipe data ke property, argument, parameter, atau return value
Penggunaan Union Types bisa menggunakan tanpa | diikuti dengan tipe data selanjutnya
https://wiki.php.net/rfc/union_types_v2 

Kode : Union Types di Property
class Example
{
    public string|int|bool|array $data;
}

$example = new Example();
$example->data = "Eko";
$example->data = 100;
$example->data = true;
$example->data = [];

Kode : Union Type di Argument
function sampleFunction(string|array $data): string|array
{
    if (is_array($data)) {
        return ["Array"];
    } else if (is_string($data)) {
        return "String";
    }
}

Kode : Union Type di Return Value
function sampleFunction(string|array $data): string|array
{
    if (is_array($data)) {
        return ["Array"];
    } else if (is_string($data)) {
        return "String";
    }
}

var_dump(sampleFunction("Eko"));
var_dump(sampleFunction([]));

Match Expression
PHP 8 menambahkan struktur kontrol baru bernama match expression
Match expression adalah struktur kontrol yang mirip dengan switch case, namun lebih baik
Match adalah expression, artinya dia bisa mengembalikan value
https://wiki.php.net/rfc/match_expression_v2
https://www.php.net/manual/en/control-structures.match.php 

Kode : Switch Statement
$value = "E";
$result = "";

switch ($value) {
    case "A":
    case "B":
    case "C":
        $result = "Anda Lulus";
        break;
    case "D":
        $result = "Anda tidak lulus";
        break;
    case "E":
        $result = "Mungkin Anda salah jurusan";
        break;
    default:
        $result = "Nilai apa itu?";
}

Kode : Match Expression
$result = match ($value) {
    "A", "B", "C" => "Anda Lulus",
    "D" => "Anda Tidak Lulus",
    "E" => "Mungkin Anda salah jurusan",
    default => "Nilai apa itu?"
};

echo $result . PHP_EOL;

Non Equals Check di Match Expression
Selain equals check, berbeda dengan switch case, di match expression, kita bisa melakukan pengecekan kondisi lainnya
Misal pengecekan menggunakan kondisi perbandingan, bahkan pengecekan kondisi berdasarkan boolean expression yang dihasilkan dari sebuah function

Kode : Match Expression Non Equals
$value = 65;

$result = match (true) {
    $value >= 80 => "A",
    $value >= 70 => "B",
    $value >= 60 => "C",
    $value >= 50 => "D",
    default => "E"
};

echo $result . PHP_EOL;

Kode : Match Expression dengan Kondisi
$name = "Mrs. Nani";

$result = match (true) {
    str_contains($name, "Mr.") => "Hello Sir",
    str_contains($name, "Mrs.") => "Hello Mam",
    default => "Hello"
};

echo $result . PHP_EOL;

Nullsafe Operator
PHP sekarang memiliki nullsafe operator seperti di bahasa pemrograman Kotlin atau TypeScript
Biasanya ketika kita ingin mengakses sesuatu dari sebuah object yang bisa memungkinan nilai null, maka kita akan melakukan pengecekan apakah object tersebut null atau tidak, jika tidak baru kita akses object tersebut
Dengan nullsafe operator, kita tidak perlu melakukan itu, kita hanya perlu menggunakan karakter ? (tanda tanya), secara otomatis PHP akan melakukan pengecekan null tersebut 
https://wiki.php.net/rfc/nullsafe_operator 

Kode : Nullable Class
class Address
{
    public ?string $country;
}

class User
{
    public ?Address $address;
}

Kode : Nullsafe Operator
function getCountry(?User $user): ?string
{
    return $user?->address?->country;
}

echo getCountry(null) . PHP_EOL;

String to Number Comparison
Salah satu yang membingungkan di PHP adalah ketika kita melakukan perbandingan number dan string
Misal saat kita bandingkan 0 == “eko”, maka hasilnya true
Kenapa true? Karena PHP akan melakukan type jugling dan mengubah “eko” menjadi 0, sehingga hasilnya true
Di PHP 8, khusus perbandingan String ke Number diubah, agar tidak membingungkan
https://wiki.php.net/rfc/string_to_number_comparison 

String to Number Comparison di PHP 8

Consistent Type Error
Saat kita membuat function, dan ketika kita mengirim argument dengan tipe data yang salah, maka akan berakibat terjadi TypeError
Sayangnya di PHP banyak function bawaan yang tidak mengembalikan TypeError, malah memberi warning
Agar konsisten, sekarang di PHP 8, banyak function bawaan yang akan error TypeError jika kita salah mengirim tipe data
https://wiki.php.net/rfc/consistent_type_errors 

Kode : Consistent Type Error
strlen([]);

Just-In-Time Compilation
PHP 8 mengenalkan fitur Just in Time Compilation
Dimana fitur ini akan mempercepat proses eksekusi program PHP yang kita buat
Namun sebelum kita bahas JIT, kita perlu tahu dulu bagaimana cara kerja PHP menjalankan kode program kita

OPcache
Secara default PHP akan selalu membaca kode PHP ketika menjalankan program PHP
OPCache digunakan untuk meningkatkan performance PHP, dengan cara menyimpan hasil kompilasi kode PHP di memory. 
Dengan demikian, PHP tidak perlu lagi membaca ulang kode program PHP setiap kali program dijalankan
PHP akan langsung membaca dari OPcache yang sudah disimpan di memory
Fitur OPcache harus diaktifkan terlebih dahulu, sebelum kita bisa menggunakannya
https://www.php.net/manual/en/book.opcache.php 

Mengaktifkan FItur Opcache
[opcache]
zend_extension=opcache.so

Mengecek Opcache
php -version                                                                                     5043
PHP 8.1.2 (cli) (built: Jan 20 2022 05:55:20) (NTS)
Copyright (c) The PHP Group
Zend Engine v4.1.2, Copyright (c) Zend Technologies
    with Zend OPcache v8.1.2, Copyright (c), by Zend Technologies

Just-In-Time Compilation
Opcache akan membuat kode program kita terhindar dari harus melakukan tokenize, parsing dan compile lagi secara terus menerus tiap request
JIT, akan membuat hasil kompilasi kita tidak perlu diterjemahkan oleh virtual machine PHP, melainkan langsung dijalankan oleh machine
JIT di PHP menggunakan library bahasa pemrograman C bernama DynASM, oleh karena itu JIT bisa mentranslate hasil compile opcodes ke instruksi machine

Validation untuk Abstract Function di Trait
Di PHP 8, sekarang terdapat validasi ketika mengimplementasikan abstract function di class dari trait
Di PHP 7, saat kita mengubah seperti parameter dan return value nya, hal itu tidak menjadi masalah
Namun di PHP 8, jika kita mengubah implementasinya dari abstract function nya, maka otomatis akan error
https://wiki.php.net/rfc/abstract_trait_method_validation 

Kode : Validation di Abstract Function Trait
trait SampleTrait
{
    public abstract function sampleFunction(string $name): string;
}

class SampleClass
{
    use SampleTrait;

    public function sampleFunction(int $name): string
    {
    }
}

Validation di Function Overriding
Sebelumnya kita tahu bahwa melakukan override dengan mengubah signature function hanya akan menimbulkan warning
Di PHP 8, hal tersebut sekarang akan menimbulkan error
Sehingga kita tidak bisa lagi mengubah signature dari function yang kita override, seperti mengubah argument atau mengubah return value
https://wiki.php.net/rfc/lsp_errors 

Kode : Validation di Function Overriding

class ParentClass
{
    public function method(string $name)
    {
    }
}

class ChildClass extends ParentClass
{

    public function method(int $name)
    {
    }
}

Private Function Overriding
Di PHP 7, saat kita membuat function, tapi ternyata di parent nya terdapat function dengan nama yang sama, walaupun private, hal itu dianggap overriding
Padahal sudah jelas bahwa private function tidak bisa diakses oleh turunannya
Di PHP 8, sekarang private function tidak ada hubungannya lagi dengan child class nya, sehingga kita bebas membuat function dengan nama yang sama walaupun di parent ada function private dengan nama yang sama
https://wiki.php.net/rfc/inheritance_private_methods 

Kode : Private Function Overriding
class Manager
{
    private function test(): void
    {
    }
}

class VicePresident extends Manager
{

    public function test(string $name): string
    {
        return "VP";
    }
}





*/