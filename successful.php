<?php 
	session_start();
	$details = $_SESSION["Details"];
?>



<html>
<h1>Hospital Website</h1>
<body>
<table>
		<tbody>
		<tr>
		<td><form action="homePage.php"><input type="submit" value="Home Page" /></form></td>
		<td><form action="patient.php"><input type="submit" value="Patient" /></form></td>
		<td><form action="Appointments.php"><input type="submit" value="Appointments" /></form></td>
		<td><form action="userInfo.php"><input type="submit" value=" Edit Personal" /></form></td>
		<?php
			if($details["role"]==1){
				echo "<td><form action='admin.php'><input type='submit' value='Admin Only' /></form></td>";
			}
		?>
		<td><form action="logout.php"><input type="submit" value="Logout" /></form></td>
		</tr>
		</tbody>
</table>


<?php 
	
	echo "Changes Submitted Successfully.";
	unset($_SESSION['id']);
	unset($_SESSION['DOB']);
	unset($_SESSION['firstname']);
	unset($_SESSION["lastname"]);
	unset($_SESSION["address"]);
	unset($_SESSION["postCode"]);
	unset($_SESSION["phoneNo"]);
	unset($_SESSION["email"]);
	unset($_SESSION["patientID"]);
	unset($_SESSION["postCode"]);
	unset($_SESSION["phoneNo"]);
	unset($_SESSION["email"]);
?> 
</body> 
</html>