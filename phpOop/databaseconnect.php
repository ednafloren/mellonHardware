<?php
$serverName='localhost';
$username='root';
$password='';
$dbname='db2';
// $dbName='flexflow';
// create connection
$conn=new mysqli($serverName,$username,$password,$dbname);

// Check conn
if($conn->connect_error){
    die('connection failed:'.$conn->connect_error);
}

// creating the database

// $sql='CREATE DATABASE db2';
// if($conn->query($sql)=== TRUE){
//     echo 'dATABASE CREATED SUCCESSFULLY';
// }
// else{
//     echo'failed to create db.'.$conn->error;
// }

// create table
// sql to create table
// $sql = "CREATE TABLE clients (
//     id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
//     firstname VARCHAR(30) NOT NULL,
//     lastname VARCHAR(30) NOT NULL,
//     email VARCHAR(50),
//     reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
//     )";

//     if($conn->query($sql)=== TRUE){
//         echo'table created successfully';
//     }
//     else{
//         echo'table failed'.$conn->error;
//     }
    // $sqlins="DELETE FROM clients WHERE id=3";
    // if($conn->query($sqlins)===TRUE){
    //     ECHO'deleted successfully';}
    //     else{
    //         echo'faile'.$conn->error;
    //     }

    // Update records
//     $sql='UPDATE clients SET lastname="mark" WHERE ID=1';
//     $result=$conn->query($sql);

//  if($conn->query($sql)===TRUE){
//         ECHO'updated successfully';}
//         else{
//             echo'faile'.$conn->error;
//         }
        // /prepared stataement
        // With of records to display
    $stmt=("SELECT id, firstname, lastname FROM clients order by lastname DESC LIMIT 1");

    $result=$conn->query($stmt);

    if($result->num_rows>0){
        echo '<table> <tr> <td>ID</td><td>FIRSTNAME</td><td>LASTNAME</td></tr>';

        while($row=$result->fetch_assoc()){
            echo '<tr> <th>'.$row['id'].'</th> <th>'.$row['firstname'].'</th><th>'.$row['lastname'].'</th>';
        }
        echo '</table>';
    }
    else{
        echo '0 results';
    }
    
// the connection will be closed automatically
// when the script ends,BUT if U WANT TO CLOSE THE
// connection before the script ends use this:
$conn->close()
?>