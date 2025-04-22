<?php
class Fruit{
  public $name;
  public $color; 
  
  function set_name($name){
$this->name=$name;
}
function get_name(){
    return $this->name; 
}
}

$apple= new Fruit();
$banana= new Fruit();
$apple->set_name('apple');
$banana->set_name('banana');
// directly changing the name value without the mtd
$apple->name="berry";

echo $banana->get_name();
echo "<br>";
echo $apple->get_name();
var_dump($apple instanceof Fruit);


// constructor helps intialize(to assign a value)
//  the object  properties on object collect 
// creation

class Car{
    public $brand;
    public $color;
    function __construct($brand,$color){
        $this->brand=$brand;
        $this->color=$color;
    }

    function get_name(){
        return $this->color;
    }
    function get_brand(){
       return $this->brand;

    }

}
$car1=new Car('subaru','black');

echo "Brand : {$car1->get_brand()}";
echo "<br>";
echo "Color : {$car1->get_name()}";
echo "<br>";

// destruct function called at the end of the scrip
// you may not need a get function

class Lesson{
    public $course;
    public $topic;
    function __construct($course,$topic){
        $this->course=$course;
        $this->topic=$topic;
    }
    function __destruct(){
        echo "Am learning {$this->course}";
        echo "<br>";
        echo "{$this->topic} is so interesting";

    }
}
$course1=new Lesson('PHP','OOP');
?>