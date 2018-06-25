<?php
	session_start();
	$details = $_SESSION["Details"];
	if(isset( $_SESSION["Details"]) == false ){
		header('Location: /login.php');
	}else if($details == 3 ||$details== 6 ){
		header('Location: /accessDenied.php');
	}
?>


<!Doctype Html>
<html>
<h1>Hospital Website Patient Information Page</h1>
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
<table>
<tbody>
	<tr>
		<th>Patient Information</th>
	</tr>
	<tr>
		<td>Patient ID:</td>
		<td><textarea cols="20" name="patientID" rows="1"></textarea></td>
		<td><form action=""><input type="submit" value="Find" /></form></td>
	</tr>
	<tr>
		<td>First Name:</td>
		<td><textarea cols="20" name="firstName" rows="1"></textarea></td>
	</tr>
	<tr>
		<td>Last Name:</td>
		<td><textarea cols="20" name="lastName" rows="1"></textarea></td>
	</tr>
	<tr>
		<td>Height:</td>
		<td><textarea cols="5" name="height" rows="1"></textarea>cm</td>
	</tr>
	<tr>
		<td>Weight:</td>
		<td><textarea cols="5" name="weight" rows="1"></textarea>kg</td>
	</tr>
	<tr>
		<td>Current Ward:</td>
		<td><textarea cols="20" name="ward" rows="1"></textarea></td>
	</tr>
	<tr>
		<td>Current Bed:</td>
		<td><textarea cols="20" name="patientBed" rows="1"></textarea></td>
	</tr>
	<tr>
		<td>Medical History:</td>
		<td><textarea cols="75" name="medHistory" rows="12"></textarea></td>
	</tr>
	
	<tr>
		<td><form action=""><input type="submit" value="Update" /></form></td>
	</tr>
	<tr>
		<td><form action="bookTest.php"><input type="submit" value="Book a Test for this Patient" /></td>
		<td><form><button onclick="editTest.php" value="View/Edit an Existing Test for this Patient" /></td>
	</tr>
</tbody>
</table>
</html>