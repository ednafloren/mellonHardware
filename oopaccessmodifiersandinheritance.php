<?php
// ACCESS MODIFIERS
class User{
    public $name;//accessed all over 
    protected $email;//within the class and in classes derived from it
    private $password;//within that class only
 function __construct($name,$email){
        // Assigning a value in a variable to the name property in the current obect
        //object assignment operator that assigns properties n mtds to objects
        $this->name=$name;
        $this->email=$email;
    }
    // private function  setName(){}
    function get_userinfo(){
        return "Name: ".$this->name."<br>"."Email:
        ".$this->email."<br>";
    }
}
$user1=new User("mark","eewiuw");
// ACCESS MODIFIERS
// $user1->password="ghdhf";//this would give an error
// $user1->email="ghdhf";//this would give an error

$user1->name="john";
echo $user1->get_userinfo();

// INHERTANCE we extends keyword

class Car{
    public $name;
    public $brand;
    function __construct($name,$brand){
        $this->name=$name;
        $this->brand=$brand;

}
// inheriting a protected function

protected  function intro(){
     echo "This is {$this->name} from {$this->brand} <br>";
   }
}
 class SportCar extends Car{
  public $speed;
  public $color;
//   overridding the constructor
  function __construct($name,$brand,$speed,$color){
    $this->name=$name;
    $this->brand=$brand;
    $this->speed=$speed;
    $this->color=$color;
}
  function info(){
       // it is called with in a class
        $this->intro();
    echo "Name :{$this->name} <br> Speed :{$this->speed} <br> Color :{$this->color} <br>";
 

  }


 }
 $car1=new SportCar("Forestor","Subaru","2000cc","black");
//  $car1->intro();
 $car1->info();
//  final keyword is used to prevent inheritance or over riding
// to prevent inheritance
// final class Car{}
// to prevent overriding
// final function ntro(){}

// CONSTANT DATA WITH IN A CLASS 
class ConstData{
    const GREETING="hello php?<br>";
// calling a constant within the 
function co(){
echo self::GREETING;}
}
// calling a constant outside the class
echo ConstData::GREETING;
$greet=new ConstData;
$greet->co();
?>

