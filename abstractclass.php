<?php
// abstract class-it can be used to ine defualt functions for the childclasses
// it can be used to create objects
abstract class Vehicle{
    public $name;
    public $brand;
    function __construct($name,$brand){
        $this->name=$name;
        $this->brand=$brand;
    }
    // string stands for datatype
    //  mtd cannot contain a body
    abstract function info():string;
}

class Car extends Vehicle{

    function info():string{
        return "hello $this->name";
    }
}
$car1=new Car('golf','toyota');
echo $car1->info();
echo "<br>";

//abstract function with arguments
abstract class Parentclass{
    public $name;
    public $brand;
    function __construct($name,$brand){
        $this->name=$name;
        $this->brand=$brand;
    }
    // string stands for datatype
    //  mtd cannot contain a body
    abstract function info($name):string;
}

class Child extends Parentclass{

    function info($name,$greet="Welcome to "):string{
        $seperator=".";
        return "{$greet}{$name} {$seperator}";
    }
}
$car1=new Child('golf','toyota');
echo $car1->info("golf");
echo "<br>";



// INTERFACES
// interface is a class define which methods a class should impliment
interface Animal{
    function makesound();
}
class Cow implements Animal{
    function makesound(){
        echo "mooo";
    }
}
class Dog implements Animal{
    function makesound(){
        echo "brakes";
    }
}
$cow= new Cow();
$dog=new Dog();
$animals= [$dog,$cow];

foreach($animals as $animal){
    $animal->makesound();
}
echo " <br>";

echo "TRAITS <br>";
// TRAITS
// traits -they are used in different class since in heritance support single class inhertance
trait MotorVehicle{
    public $name;
    public $brand;
    
    // string stands for datatype
    //  mtd cannot contain a body
    function setinfo($name){
        $this->name= $name;
    }

    function info(){
        return $this->name;
    }
}
trait Motor{
    public $name;
    public $brand;

    
    // string stands for datatype
    //  mtd cannot contain a body
    function setinfo2($brand){
        $this->brand= $brand;
    }

    function info2(){
        return $this->brand;
    }
}
    

class MyCar {
use Motor,MotorVehicle;
    
}
$car1=new MyCar();
$car1->setinfo('golf');
echo $car1->info();
echo "<br>";
$car1->setinfo2('toyota');
echo $car1->info2();
echo "<br>";


?>