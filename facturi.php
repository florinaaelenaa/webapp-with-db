<?php include 'header.php';?>



<div class="container">


<h1 class="title">Facturi</h1>

<?php

$username = "root";
$password = "";
$servername = "127.0.0.1"; 
$dbname   = "Supermarket";

$conn = new mysqli($servername, $username, $password, $dbname);;
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$sql = "SELECT F.Valoare_neta AS Val, F.Serie AS S, F.Nr AS Nr, FR.Nume AS Nume
		FROM Factura AS F, Furnizor AS FR
		WHERE F.IDFurnizor = FR.IDFurnizor
		ORDER BY F.Valoare_neta";

$result = $conn->query($sql);

echo '<table>
  <tr>
    <th>Valoare factura</th>
    <th>Serie </th>
    <th>Numar</th>
    <th>Nume Furnizor </th>
  </tr>';

if ($result->num_rows > 0) {
    
    
    while($row = $result->fetch_assoc()) {
       echo '<tr>';  
       echo '<td>' . $row["Val"] . ' lei </td>';
       echo '<td>' . $row["S"] . '</td>';
       echo '<td>' . $row["Nr"] . '</td>';
       echo '<td>' . $row["Nume"] . '</td>';
       echo '</tr>';
    }
   echo '</table>';
   echo '</br>';
  
} 

?>

  <div class="col-sm-12 wowload fadeInUp">
  	<div class="rooms">
  	  <form method="POST" action="<?php echo $_SERVER['PHP_SELF'] ?>"> 
  		<input type="submit" value="Facturi achitate" id="achitat" name="achitat"  class="btn btn-default">
  		</br> </br>
  		<input type="submit" value="Facturi neachitate" id="neachitat" name="neachitat"  class="btn btn-default">
  		</form>

  		</div>
  	</div>
  <div class="col-sm-12 wowload fadeInUp">
  	<div class="rooms">
  	<p> Selectati din calendar pentru a verifica ce produse au intrat in stoc inainte de o anumita data:</p>
  		<form method="POST" action="<?php echo $_SERVER['PHP_SELF'] ?>"> 
		    <input type="submit" value="Date" id="date" name="date" class="btn btn-default"/>
		    <input type="" id="datepicker" name = "data"/>
		    
		</form>
	</div>
</div>
	

<?php 
       if (isset($_POST['achitat'])) : 
       			echo '<table>
					  <tr>
					    <th>Valoare factura</th>
					    <th>Serie </th>
					    <th>Numar</th>
					  </tr>';

              $sql = "SELECT Valoare_neta, Serie, Nr 
					  FROM Factura
					  WHERE Achitat = 'DA'";

			   $sql2 = "SELECT SUM(Valoare_neta) AS Suma
						FROM Factura
						WHERE Achitat = 'DA'";

              $result = $conn->query($sql);

              $result2 = $conn->query($sql2);

			  if ($result->num_rows > 0) {
			    while($row = $result->fetch_assoc()) {
			       echo '<tr>';  
			       echo '<td>' . $row["Valoare_neta"] . ' lei </td>';
			       echo '<td>' . $row["Serie"] . '</td>';
			       echo '<td>' . $row["Nr"] . '</td>';
			       echo '</tr>';
			    }
    			echo '</table> </br>';

    			$row = $result2->fetch_assoc();
    			//echo $result2->fetch_assoc();
    			echo '</br><img src="images/photos/answer.png" style= "width:80px; heigth:80px;""> Suma totala achitata: <b>' . $row["Suma"] . ' lei </b> </br></br>';
                  //echo '<meta http-equiv="refresh" content= "1;URL="http://localhost/siteBD/categorii.php"" >';
              
              } else {
                  echo "Error: " . $sql . "<br>" . $conn->error;
              }
        endif; 

?>


<?php 
       if (isset($_POST['neachitat'])) : 
       	       echo '<table>
					  <tr>
					    <th>Valoare factura</th>
					    <th>Serie </th>
					    <th>Numar</th>
					  </tr>';
              $sql = "SELECT Valoare_neta, Serie, Nr 
					  FROM Factura
					  WHERE Achitat = 'NU'";

			   $sql2 = "SELECT SUM(Valoare_neta) AS Suma
						FROM Factura
						WHERE Achitat = 'NU'";

              $result = $conn->query($sql);

              $result2 = $conn->query($sql2);

			  if ($result->num_rows > 0) {
			    while($row = $result->fetch_assoc()) {
			       echo '<tr>';  
			       echo '<td>' . $row["Valoare_neta"] . ' lei </td>';
			       echo '<td>' . $row["Serie"] . '</td>';
			       echo '<td>' . $row["Nr"] . '</td>';
			       echo '</tr>';
			    }
    			echo '</table> </br>';

    			$row = $result2->fetch_assoc();
    			//echo $result2->fetch_assoc();
    			echo '</br><img src="images/photos/answer.png" style= "width:80px; heigth:80px;""> Suma totala neachitata: <b>' . $row["Suma"] . ' lei </b></br></br>';
                  //echo '<meta http-equiv="refresh" content= "1;URL="http://localhost/siteBD/categorii.php"" >';
              
              } else {
                  echo "Error: " . $sql . "<br>" . $conn->error;
              }
       endif; 


       if (isset($_POST['date'])) : 

       		echo '<table>
					  <tr>
					    <th>Data factura</th>
					    <th>Denumire produs </th>
					    <th>Cantitate produs</th>
					  </tr>';

            $d = $_POST["data"];  
            
            echo '</br>';
           	$vector = explode("/",$d);
           	$new_date = $vector[2] . '-' . $vector[1] . '-' . $vector[0];
           	
           	echo '</br>';
   			$sql = "SELECT F.Data AS Dfact, IP.Cantitate_produs AS Cant, P.Denumire AS Den
					FROM Factura AS F, Produs AS P, Intrari_produse AS IP
					WHERE F.IDFactura = IP.IDFactura AND P.IDProdus = IP.IDProdus AND F.Data < '$new_date'
					ORDER BY F.Data";
			$result = $conn->query($sql);
			if ($result->num_rows > 0) {
			    while($row = $result->fetch_assoc()) {
			       echo '<tr>';  
			       echo '<td>' . $row["Dfact"] . '</td>';
			       echo '<td>' . $row["Den"] . '</td>';
			       echo '<td>' . $row["Cant"] . ' buc</td>';
			       echo '</tr>';
			    }
    			echo '</table> </br>';
    		}
    		else {
    			echo "Error: " . $sql . "<br>" . $conn->error;
    		}
						

        endif; 


?>

  
</div> <!-- div container -->

<?php include 'footer.php';?>