<?php
	session_start();
	$details = $_SESSION["Details"];
	if(isset( $_SESSION["Details"]) == false ){
		header('Location: /login.php');
	}
	
	if($details["role"]!=3 &&$details["role"]!=5){
		echo "User is not a paitent.";
	}
	else if($details["role"]==5){
		echo "Receptionist View Active.";
	}
	
	
	$username = "groupproject";
	$password = "groupProject560";
	$dbName = "groupDatabase";
	$dbHost = "hims.comnrcxa7372.eu-west-2.rds.amazonaws.com";
	$port = "3306";
	$dsn = "mysql:host=$dbHost;dbname=$dbName;port=$port";
	$opt = array (PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE =>PDO::FETCH_ASSOC,PDO::ATTR_EMULATE_PREPARES => false, array(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true));
	
	try {
		$pdo = new PDO($dsn,$username,$password,$opt);
	}
	catch (PDOException $e){
		exit("PDO Error: Connection closed: ".$e->getMessage()."<br>");
	}
?>
<!Doctype html>
<html>
<h1>Hospital Website Appointments Page</h1>
<table>
		<tbody>
		<tr>
		<td><form action="homePage.php"><input type="submit" value="Home Page" /></form></td>
		<td><form action="patient.php"><input type="submit" value="Patient" /></form></td>
		<td><form action="Appointments.php"><input type="submit" value="Appointments" /></form></td>		
		<td><form action="userInfo.php"><input type="submit" value=" Edit Personal" /></form></td>
		<?php
			if($details["role"]==1){
				echo "<td><form action='userInfo.php'><input type='submit' value='Admin Only' /></form></td>";
			}
		?>
		<td><form action="logout.php"><input type="submit" value="Logout" /></form></td>
		</tr>
		</tbody>
</table>
<br/>
<table border="1">
	<tbody>
		<?php 
			$query = "Select * From Patient WHERE user_id = ".$details["id"]; 
			try{
				$pdo = new PDO($dsn,$username,$password,$opt);		
				$stmt = $pdo->prepare($query);
				$stmt->execute();
				$patient = $stmt->fetch();
				if(!empty($patient)){
					
					$query = "Select * From Appointment Where patient_ID=".$patient["id"]." order by dateAppointmentScheduled desc";
					$get = $pdo->prepare($query);
					$get->execute();
					$appoinments = $get->fetchAll();
					echo "<tr><td>Date</td><td>Time</td><td>Doctor</td><td>Description</td></tr>";
					foreach($appoinments as $a){
						$date = date('Y-m-d', strtotime($a["dateAppointmentScheduled"]));
						$time = date('H:i:s', strtotime($a["dateAppointmentScheduled"]));
						$query = "SELECT UserDetails.foreName,UserDetails.surName,Doctor.id FROM UserDetails Inner join User on UserDetails.user_id = User.id Inner join Employee on Employee.user_id = UserDetails.user_id Inner join Doctor on Employee.id = Doctor.employee_id Where Doctor.id = ". $a['doctor_ID'];
						$stmt = $pdo->prepare($query);
						$stmt->execute(); 
						$name = $stmt->fetch();
						
						echo "<tr><td>".$date."</td><td>".$time."</td><td>".'Dr. '.$name['foreName'].' '.$name['surName']."</td><td>".$a["description"]."</td></tr>";
					}
				}else{
					if($details["role"]==5){
						echo "<tr><td>Staff view : No appointments.</td></tr>";
					}else{echo "<tr><td>Error: User not found in patient listing.</td></tr>";}
					
				}
				
			}catch(Exception $e){
				exit("PDO Error: ".$e->getMessage()."<br>");
			}
		
		?>
		
	
	</tbody>
</table>
<br/>
<form action='bookAppointment.php'><input type='submit' value='Book a New Appointment' /></form>


</html>