<?php 
	session_start();
	$details = $_SESSION["Details"];
	
	if(isset( $_SESSION["Details"]) == false ){
			header('Location: /login.php');
	}else if($details["role"]!=3 && $details["role"]!=5){			//Only patient or nurse may book appointments
		echo "<h1>User is not a paitent.<h1>";
		echo "\n <h2>Now Redirecting.....<h2>";
		header('Location: /homePage.php');
	}
	
		$username = "groupproject";
		$password = "groupProject560";
		$dbName = "groupDatabase";
		$dbHost = "hims.comnrcxa7372.eu-west-2.rds.amazonaws.com";
		$port = "3306";
		$dsn = "mysql:host=$dbHost;dbname=$dbName;port=$port";
		$opt = array (PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE =>PDO::FETCH_ASSOC,PDO::ATTR_EMULATE_PREPARES => false, array(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true));
		$pdo;
		try {
			$pdo = new PDO($dsn,$username,$password,$opt);
		}catch(PDOException $e){
			exit("PDO Error: ".$e->getMessage()."<br>");
		}
	
	
	
?>
<!Doctype html>
<html>
<h1>Hospital Website Appointment Creation Page</h1>
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

<body>
</table>
<table>
	<tbody>
		<tr>
			<th>Book a new Appointment :</th>
		</tr>
		<tr>			
		
			<?php
				if($details["role"]==5){
					echo "<form method='post'>
						<td>Enter User ID : </td>
						<td><input type='number' name='userID' value='".(@$_SESSION['bookuserID'])."' required</td>
						<td><input type='submit' name='selectID' value='Find Patient' /></td>
						<td><p name='message' />".(@$_SESSION['bookMessage'])."</p></td>
						</form>";
					echo "<tr><td>Patient :</td><td><input type='text' name='name' value='".(@$_SESSION['bookforename'])."' placeholder= '*patient forename*' readonly/></td></tr>";
					echo "<tr><td></td><td><input type='text' name='surname' value='".(@$_SESSION['booksurname'])."' placeholder='*patient surname*' readonly/></td></tr>";
				}
				
			?>
			</tr>
		<form method="post">
			<tr><td>Date : </td>	<td><input type="date" name="date"   required/></td></tr>
			<tr><td>Time :</td>		<td><input type="time" name="time"   required/></td> </tr>
			<tr><td>Doctor :</td>	<td><select name = "doctorID" required>
				<?php
					try {
						$query = "SELECT UserDetails.foreName,UserDetails.surName,UserDetails.user_id,Doctor.employee_id,Doctor.id FROM UserDetails Inner join User on UserDetails.user_id = User.id Inner join Employee on Employee.user_id = UserDetails.user_id Inner join Doctor on Employee.id = Doctor.employee_id";				
						$pdo = new PDO($dsn,$username,$password,$opt);
						$doctors = $pdo->prepare($query);
						$doctors->execute();
						
						foreach($doctors as $doctor){
								echo "<option value='".$doctor['id']."'>"." Dr. ".$doctor['surName'].",".$doctor['foreName']." </option>";
								echo $doctor['id'];
							
						}
					}catch(PDOException $e){
						exit("PDO Error: ".$e->getMessage()."<br>");
					}
						
					?>
					</select></td>
			</td><tr>
			<tr><td>Description :</td><td><input type="text" name="description"  placeholder = "description" required/></td></tr>
			<tr><td><input type="submit" name="book" value="Book Appointment"/></td></tr>
		</form>
		</tr>
		</tbody>
</table>

<!---Code to set patient Id-->
<?php
	$patientID = null;
	if(isset($_SESSION['patientID'])){
		$patientID = $_SESSION['patientID'];
	}
	
	//Depending on role get patientID in 2 different ways
	if($details["role"]==3){
		try{
			$query = "Select id From Patient where user_id = ".$details["id"];
			$stmt = $pdo->prepare($query);
			$stmt->execute();
			$temp = $stmt->fetch();
			$_SESSION['patientID'] = $temp['id'];
			
		}catch(PDOException $e){
			exit("PDO Error: ".$e->getMessage()."<br>");
		}
	}
	
	if($details["role"]==5){
		if(isset($_POST['selectID'])){
			try{
				if(isset($_POST["userID"])){
					$query = "Select id From Patient where user_id = ".$_POST["userID"];
					$stmt = $pdo->prepare($query);
					$stmt->execute();
					$temp = $stmt->fetch();
					$patientID = $temp['id'];
					if($temp['id'] == NULL){
						$_SESSION['bookMessage'] = "Patient not found";
						header('Location: bookAppointment.php');
						return;
					}
					
					$query = "Select user_id,foreName,surName From UserDetails where user_id = ".$_POST["userID"];
					$stmt = $pdo->prepare($query);
					$stmt->execute();
					$temp = $stmt->fetch();
					
					$_SESSION['bookforename'] = $temp['foreName']; 
					$_SESSION['booksurname'] = $temp['surName']; 
					$_SESSION['bookuserID'] = $temp['user_id'];
					$_SESSION['patientID'] = $patientID;
					$_SESSION['bookMessage'] = "";
					
					unset($_POST["userID"]);
					unset($_POST["selectID"]);
					header('Location: bookAppointment.php');
				}
				else{
					echo "Error : User ID not set.";
				}
			}catch(PDOException $e){
				exit("PDO Error: ".$e->getMessage()."<br>");
			}
		}
		
	}
	
		try{
			if(isset($_POST["book"])){
				if($patientID !=NULL){
					//query to post code
					$date = $_POST['date']." ".$_POST['time'].":00";
					$query = "INSERT INTO Appointment(patient_ID,doctor_ID,dateAppointmentRequested,dateAppointmentScheduled,description)
								 VALUES('$patientID',".$_POST['doctorID'].",'".$date."','".$date."','".$_POST['description']."')";
					$stmt = $pdo->prepare($query);
					$stmt->execute();
					unset($_SESSION['bookforename']); 
					unset($_SESSION['booksurname']);
					unset($_SESSION['bookuserID']);
					unset($_SESSION['patientID']);
					unset($_SESSION['bookMessage']);
					header('Location: successful.php');
				}else{
					echo "Enter a user Id";
				}
				}
			}catch(PDOException $e){
				exit("PDO Error: ".$e->getMessage()."<br>");
		}
	
?>
	
	
	
	

</body>
</html>