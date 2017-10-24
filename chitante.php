<?php include 'header.php';?>
<div class="container">

<h1 class="title">Chitante</h1>


<?php

$username = "root";
$password = "";
$servername = "127.0.0.1"; 
$dbname   = "Supermarket";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 

$sql = "SELECT FR.Data AS Data_Factura, C.Data AS Data_Chitanta, FR.Valoare_neta AS Val_neta, F.Nume AS Nume_Fr
		FROM Chitanta AS C, Factura AS FR, Furnizor AS F
		WHERE C.IDFactura = FR.IDFactura AND F.IDFurnizor = FR.IDFurnizor
		GROUP BY C.Data";

$result = $conn->query($sql);

echo '<table>
  <tr>
    <th>Data factura</th>
    <th>Data chitanta </th>
    <th>Valoare neta achitata</th>
    <th>Nume Furnizor </th>
  </tr>';

if ($result->num_rows > 0) {
    
    
    while($row = $result->fetch_assoc()) {
       echo '<tr>';  
       echo '<td>' . $row["Data_Factura"] . ' lei </td>';
       echo '<td>' . $row["Data_Chitanta"] . '</td>';
       echo '<td>' . $row["Val_neta"] . '</td>';
       echo '<td>' . $row["Nume_Fr"] . '</td>';
       echo '</tr>';
    }
   echo '</table>';
   echo '</br>';
  
} 

?>

  <div class="col-sm-6 wowload fadeInUp">
  	<div class="rooms">
  	  <form method="POST" action="<?php echo $_SERVER['PHP_SELF'] ?>"> 
  		<input type="submit" value="Bugetul total al produselor din fiecare categorie" id="total" name="total"  class="btn btn-default">
  		</br> </br>
  		<input type="submit" value="Prduse vandute din fiecare categorie" id="vandut" name="vandut"  class="btn btn-default">
  		</form>
  		</div>
  	</div>
 

<?php 
       if (isset($_POST['total'])) : 
       			echo '<table>
					  <tr>
					    <th>Categorie</th>
					    <th>Buget </th>
					  </tr>';

              $sql = "  SELECT C.Nume As Categ, SUM(P.Pretprodus * P.Cantitate_stoc) AS Buget
						FROM Categorie C, Produs P
						WHERE C.IDCategorie = P.IDCategorie
						GROUP BY C.Nume";


              $result = $conn->query($sql);


			  if ($result->num_rows > 0) {
			    while($row = $result->fetch_assoc()) {
			       echo '<tr>';  
			       echo '<td>' . $row["Categ"] . '</td>';
			       echo '<td>' . $row["Buget"] . ' lei </td>';
			       echo '</tr>';
			    }
    			echo '</table> </br>';
              
              } else {
                  echo "Error: " . $sql . "<br>" . $conn->error;
              }
        endif; 

?>

<?php 
       if (isset($_POST['vandut'])) : 
       			echo '<table>
					  <tr>
					    <th>Denumire produs</th>
					    <th>Cantitate vanduta </th>
					  </tr>';

              $sql = "  SELECT I.Denumire AS Nume, I.Cantitate - I.Cantitate_stoc AS Produse_vandute
						FROM   (SELECT IP.IDProdus, P.Denumire, P.Cantitate_stoc, SUM(IP.Cantitate_produs) AS Cantitate
								FROM Intrari_produse IP, Produs P
								WHERE IP.IDProdus = P.IDProdus
								GROUP BY P.Cantitate_stoc, P.Denumire, IP.IDProdus) AS I
								ORDER BY Produse_vandute";


              $result = $conn->query($sql);


			  if ($result->num_rows > 0) {
			    while($row = $result->fetch_assoc()) {
			       echo '<tr>';  
			       echo '<td>' . $row["Nume"] . '</td>';
			       echo '<td>' . $row["Produse_vandute"] . ' buc </td>';
			       echo '</tr>';
			    }
    			echo '</table> </br>';
              
              } else {
                  echo "Error: " . $sql . "<br>" . $conn->error;
              }
        endif; 

?>



<p> </br> &nbsp &nbsp Selectati categoria de alimente pentru care sa se afiseze nr de chitante/facturi:</p>
<div class="col-sm-5 ">   
    <form role="form" method="POST" action="<?php echo $_SERVER['PHP_SELF'] ?>" class="wowload fadeInLeft">
               
        <div class="form-group">
            <div class="row">
            <div class="col-xs-6">
            <select name = "tip" class="form-control">
              <option>Tip document</option>
              <option>chitanta</option>
              <option>factura</option>
            </select>
            </div>        
            <div class="col-xs-6">
            <select name = "categ" class="form-control">
              <option>Categ produse</option>

<?php

		$sql = "SELECT DISTINCT Nume FROM categorie";
		$result = $conn->query($sql);

		if ($result->num_rows > 0) {
		    while($row = $result->fetch_assoc()) {
		       echo '<option>' . $row["Nume"] . '</option>';
		    }
		   
		   
		} 
?>

            </select>
            </div></div>
        </div>
        
        <button  type="submit"  id="chitanta" name="chitanta" class="btn btn-default">Submit</button>

<?php
		 if (isset($_POST['chitanta'])) : 
       		$type = $_POST["tip"]; 
       	    $categ = $_POST["categ"];

       	    $var1 = "factura";
       	    $var2 = "chitanta";

       		if( strcmp($type, $var1) == 0 )
       		{
	        	$sql = "SELECT C.Nume AS Catg, SUM(IP.Cantitate_produs) AS sum
						FROM Intrari_produse AS IP, Categorie AS C, Produs AS P
						WHERE C.IDCategorie = P.IDCategorie AND IP.IDProdus = P.IDProdus AND C.Nume = '$categ'
						GROUP BY C.Nume";

				$result = $conn->query($sql);

				if ($result->num_rows > 0) {
				    $row = $result->fetch_assoc();
				     echo '<p>  </br><img src="images/photos/answer.png" style= "width:80px; heigth:80px;""> Din categoria <b>' . $row["Catg"] . "</b> avem practic <b>" . $row["sum"] . "</b> produse intrare in supermarket, nu neaparat achitate.</br>" . "</p>";
				}
				else {
		            echo "Error: " . $sql . "<br>" . $conn->error;
		        } 
			}

			

			if( strcmp($type, $var2) == 0 )
			{
	        	$sql = "
						SELECT C.Nume AS Catg, SUM(IP.Cantitate_produs) AS sum
						FROM Intrari_produse AS IP, Categorie AS C, Produs AS P, Chitanta AS CH
						WHERE C.IDCategorie = P.IDCategorie AND IP.IDProdus = P.IDProdus AND CH.IDFactura = IP.IDFactura AND C.Nume = '$categ'
						GROUP BY C.Nume";
				$result = $conn->query($sql);

				if ($result->num_rows > 0) {
				    $row = $result->fetch_assoc();
				    echo '<p> </br><img src="images/photos/answer.png" style= "width:80px; heigth:80px;""> Din categoria <b>'. $row["Catg"] . "</b> avem practic <b>" . $row["sum"] . "</b> produse achitate.</br>" . "</p>";
				}
				else {
		            echo "Error: " . $sql . "<br>" . $conn->error;
		        } 
			}

			endif;
         

?>


        <p> </p>
    </form>    
</div>
</div>  
</div>
</div>



</div>
<?php include 'footer.php';?>