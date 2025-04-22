<?php
// classes
class Book{
    public $title;
    public $author;
    // mtds
    function setName($name){
// this refers to the current obj and only found in mtds

$this->name=$name;//here the obect name will equalto wa is in the name variable
    }
    function getName(){
        return $this->name;
    }
   
}
// object
$book1= new Book;
$book1->setName('john');
echo $book1->getName();
// checks whether it is an object of that class
var_dump($book1 instanceof Book);

// constructor intializes the object properties upon object creation
// You dont need to call the set mtd instead u put it in brackets on object creation
class User{
    public $name;
    public $email;
    function __construct($name,$email){
        // Assigning a value in a variable to the name property in the current obect
        //object assignment operator that assigns properties n mtds to objects
        $this->name=$name;
        $this->email=$email;
    }
    function get_userinfo(){
        return "Name: ".$this->name."<br>"."Email:
        ".$this->email."<br>";
    }
}
$user1=new User("mark","eewiuw");
echo $user1->get_userinfo();

// destructor is excuted at the end of the script
// Using it u dont need to ue the getfunction

class Product{
 public $name;
 public $price;
  function __construct($name,$price){
    $this->name=$name;
    $this->price=$price;}
  function __destruct(){
    echo "The price of a {$this->name} is {$this->price} <br>";
  }

  
}
$product1=new Product("pipe",20000);


?>

