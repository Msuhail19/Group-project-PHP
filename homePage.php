<!Doctype html>
<?php
	session_start();
	$details = $_SESSION["Details"];
	if(isset( $_SESSION["Details"]) == false ){
		header('Location: /login.php');
	}
?>

<html>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">

<h1>Hospital Website Home Page</h1>
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
</html>