

<?php include 'header.php';?>

<?php

$username = "root";
$password = "";
$servername = "127.0.0.1"; 
$dbname   = "supermarket";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$sql = "SELECT DISTINCT nume FROM categorie";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo '<div class="container">'; 
    echo '<h1 class="title">Categorii produse</h1>';
    echo '<div class="row gallery">';
    
    while($row = $result->fetch_assoc()) {
       $nume = $row["Nume"];
       $sql = "SELECT Brand FROM Categorie WHERE Nume = '$nume' ";
       $result2 = $conn->query($sql);
       echo '<div class="col-sm-3 wowload fadeInUp"><a href= images/photos/'.$row["Nume"].'.jpg' . 'title="Foods" class="gallery-image" > <img src=images/photos/'. $row["Nume"] .'.jpg class="img-responsive" style = "height :250px">' .$row["Nume"]. ": ";  
      
       echo '<ul>';
       while($row2 = $result2->fetch_assoc()) { echo '<li>'; echo $row2["Brand"]; echo '</li>';}
       echo ' </ul></a></div>';


    }
   
   echo '</div> ';
} 



?>
     <p>   
        <form method="POST" action="<?php echo $_SERVER['PHP_SELF'] ?>"> 
        Nume 
        <input type="text" name="name" id="name" maxlength="30" size="23" /> </br> </br>
        Brand 
        <input type="text" name="brand" id="brand" maxlength="30" size="23" />
        <input type="submit" value="INSERT" id="insert" name="insert" />
        <p> </p>
       </form> 

       <form method="POST" action="<?php echo $_SERVER['PHP_SELF'] ?>"> 
        Nume 
        <input type="text" name="name" id="name" maxlength="30" size="23" /> </br> </br>
        Brand 
        <input type="text" name="brand" id="brand" maxlength="30" size="23" />
        <input type="submit" value="DELETE" id="delete" name="delete" />
         <p> </p>
       </form> 

       <form method="POST" action="<?php echo $_SERVER['PHP_SELF'] ?>"> 
        Brand  
        <input type="text" name="name" id="name" maxlength="30" size="23" /> </br> </br>
        NEW_B
        <input type="text" name="brand" id="brand" maxlength="30" size="23" />
        <input type="submit" value="UPDATE" id="update" name="update" />
         <p> </p>
       
       </form> 

     </p>

   </div>


<?php 
       if (isset($_POST['insert'])) : 
              $nume = $_POST["name"];
              $marca = $_POST["brand"];

              $sql = "INSERT INTO Categorie(Nume, Brand)
                            VALUES ('$nume', '$marca')";

              if ($conn->query($sql) === TRUE) {
                  echo '<meta http-equiv="refresh" content= "1;URL="http://localhost/siteBD/categorii.php"" >';
              } else {
                  echo "Error: " . $sql . "<br>" . $conn->error;
              }
        endif; 

?>


<?php 
       if (isset($_POST['delete'])) : 
              $nume = $_POST["name"];
              $marca = $_POST["brand"];  
              $sql = "DELETE FROM Categorie WHERE Nume='$nume' OR Brand='$marca'";

              if ($conn->query($sql) === TRUE) {
                  echo '<meta http-equiv="refresh" content= "1;URL="http://localhost/siteBD/categorii.php"" >';
              } else {
                  echo "Error deleting record: " . $conn->error;
              }
       endif; 
 ?>

<?php 
       if (isset($_POST['update'])) : 
              $nume = $_POST["name"];
              $marca = $_POST["brand"];  
              $sql = "UPDATE Categorie SET Brand = '$marca' WHERE Brand ='$nume'";

               if ($conn->query($sql) === TRUE) {
                  echo '<meta http-equiv="refresh" content= "1;URL="http://localhost/siteBD/categorii.php"" >';
              } else {
                  echo "Error deleting record: " . $conn->error;
              }
       endif; ?>
    
<?php include 'footer.php';?>