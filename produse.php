<?php include 'header.php';?>



<?php

$username = "root";
$password = "";
$servername = "127.0.0.1"; 
$dbname   = "Supermarket";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$sql = "SELECT DISTINCT P.Denumire AS Denumire, P.Pretprodus AS Pretprodus, P.Cantitate_stoc AS Cantitate_stoc, C.Brand AS Brand
        FROM Produs AS P, Categorie AS C
        WHERE P.IDCategorie = C.IDCategorie";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    echo '<div class="container">';
    echo'<h1 class = "title">Produse</h2>';
    echo'<div class="row">';
    while($row = $result->fetch_assoc()) {
      
       echo '<div class="col-sm-3 wowload fadeInUp"><div class="rooms" style="height:400px"><img src="images/photos/' . $row["Denumire"] . '.jpg" class="img-responsive"  style="height:230px" ><div class="info"><h3>'. $row["Denumire"] .'</h3><p> Brand: '. $row["Brand"] .'</br> Stoc: '. $row["Cantitate_stoc"] . ' bucati </br> Pret: '. $row["Pretprodus"] .' lei</p></div></div></div>'; 
      // echo $row["Denumire"];

    }
    echo '</div>'; //div de la rows

    echo'<div class="row">';

    //INSERT
    echo '<div class="col-sm-3 wowload fadeInUp"><div class="rooms">'; 
    echo '<div class="info" style="height:350px"><h3> Adauga produs</h3><p>'; 
    echo '<form method="POST" action="' .$_SERVER['PHP_SELF']. '"> ';
    echo 'Denumire: <br> <input type="text" name="Denumire" id="Denumire" maxlength="30" size="23" /> </br> </br>';
    echo 'Cantitate: <br><input type="text" name="Cantitate" id="Cantitate" maxlength="30" size="23" /> </br> </br>'; 
    echo 'Pret: <br><input type="text" name="Pret" id="Pret" maxlength="30" size="23" /> </br> </br> '; 
    echo 'Categorie: <br><input type="text" name="Categorie" id="Categorie" maxlength="30" size="23" /> </br> </br> '; 
    echo 'Brand: <br><input type="text" name="Brand" id="Brand" maxlength="30" size="23" /> </br> </br> '; 
    echo '<input type="submit" value="INSERT" id="insert" name="insert" /> </p>';
    echo '</form> </div> </div> </div>';
 
    //UPDATE
    echo '<div class="col-sm-3 wowload fadeInUp"><div class="rooms">'; 
    echo '<div class="info" style="height:350px"><h3> Update pret produs</h3><p>'; 
    echo '<form method="POST" action="' . $_SERVER['PHP_SELF'] . '"> ';
    echo 'Denumire: <br> <input type="text" name="Denumire" id="Denumire" maxlength="30" size="23" /> </br> </br>'; 
    echo 'Pret: <br><input type="text" name="Pret" id="Pret" maxlength="30" size="23" /> </br> </br> '; 
    echo '<input type="submit" value="UPDATE" id="update" name="update" /> </p>';
    echo '</form> </div> </div> </div>';

    //DELETE
    echo '<div class="col-sm-3 wowload fadeInUp"><div class="rooms">'; 
    echo '<div class="info" style="height:350px"><h3> Sterge produs</h3><p>'; 
    echo '<form method="POST" action="' .$_SERVER['PHP_SELF']. '"> ';
    echo 'Denumire: <br> <input type="text" name="Denumire" id="Denumire" maxlength="30" size="23" /> </br> </br>';
     
    echo '<input type="submit" value="DELETE" id="delete" name="delete" /> </p>';
    echo '</form> </div> </div> </div>';
   


     if (isset($_POST['insert'])) : 
        $nume = $_POST["Denumire"];
        $cant = $_POST["Cantitate"];
        $pret = $_POST["Pret"];
        $categ = $_POST["Categorie"];
        $marca = $_POST["Brand"];
        $sql = "INSERT INTO Produs(Denumire, IDCategorie, Pretprodus, Cantitate_stoc)
                      VALUES ('$nume', (SELECT IDCategorie FROM Categorie WHERE Nume = '$categ' AND Brand = '$marca'),$pret, $cant)";
        
        if ($conn->query($sql) === TRUE) {
            echo '<meta http-equiv="refresh" content= "1;URL="http://localhost/siteBD/produse.php"" >';
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
        endif; 


    if (isset($_POST['delete'])) : 
      $nume = $_POST["Denumire"];

      $sql = "DELETE FROM Produs WHERE Denumire='$nume'";

      if ($conn->query($sql) === TRUE) {
          echo '<meta http-equiv="refresh" content= "1;URL="http://localhost/siteBD/produse.php"" >';
      } else {
          echo "Error deleting record: " . $conn->error;
      }
    endif; 

    if (isset($_POST['update'])) : 
       $nume = $_POST["Denumire"];
       $pret = $_POST["Pret"]; 

       $sql = "UPDATE Produs SET Pretprodus=$pret WHERE Denumire='$nume'";

      if ($conn->query($sql) === TRUE) {
           echo '<meta http-equiv="refresh" content= "1;URL="http://localhost/siteBD/produse.php"" >';
      } else {
          echo "Error updating record: " . $conn->error;  
      }
    endif;
   echo '</div>'; //div de la rows

  echo '</div>'; //div de la container
  
} 
?>

  
 <!-- <a href="room-details.php" class="btn btn-default">Check Details</a>-->
   <div class="text-center">
   <ul class="pagination">
   <li class="disabled"><a href="#">«</a></li>
   <li class="active"><a href="#">1 <span class="sr-only">(current)</span></a></li>
   <li><a href="#">2</a></li>
   <li><a href="#">3</a></li>
   <li><a href="#">4</a></li>
   <li><a href="#">5</a></li>
   <li><a href="#">»</a></li>
   </ul>
   </div>



<?php include 'footer.php';?>