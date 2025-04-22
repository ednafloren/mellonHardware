<?php
class Course{
public $name;
public $category;

public function __construct($name,$category)
{$this->name=$name;
$this->category=$category;
}
public function intro(){
    echo "Welcome to {$this->category}.";
}
}
class PHP extends Course{
    public  function message(){
        echo "this is {$this->name}.";
    }
}

$course1=new PHP('PHP','programming');
$course1->intro();
echo "<br>";
$course1->message();

// Inherited methods can be overridden by redefining the methods (use the same name) in 
// the child class.


// The final keyword can be used to prevent class inheritance or
//  to prevent method overriding.
// final class Fruit {
    // some code
//   }
// Fruit cant be inherited

// constants
class Goodbye{
    const LEAVING_MESSAGE="THANK U FOR VISITING W3SCHOOLS";
    public function byebye(){
        ECHO "Acessing a constant inside the class,we use the self keyword";
        ECHO "<br>";


        echo self::LEAVING_MESSAGE;
     
    }

}
ECHO "<br>";
ECHO "Acessing a constant outside the class";
ECHO "<br>";
ECHO Goodbye::LEAVING_MESSAGE;
ECHO "<br>";
$goodbye=new Goodbye();
$goodbye->byebye();

// static methods-can be called even without creating object

class Calling{
    // static property
    static $greet='hello'
    static function ring(){
        echo 'A phone rings';
    }
}
Calling::ring();
Calling::r
?>