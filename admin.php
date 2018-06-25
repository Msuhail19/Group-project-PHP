
<!------PAGE TO CHANGE USER INFO ADMIN ONLY-------------->
<?php 
	session_start();
	$details = $_SESSION["Details"];
	
if(isset( $_SESSION["Details"]) == false ){
		header('Location: /login.php');
}
else if($details["role"]!=1){
		header('Location: /accessDenied.php');
		echo "Access denied.";
}
?>
<!Doctype html>
<html>
<head>

<h1>Change User Details (Admin) </h1>
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
	<th>User Information</th>
</tr>

	
	<tr>
		<td>User ID:</td>
			<form method="post">
			<td><input type="number" name="userID" value="<?php echo (@$_SESSION['id']); ?>"></td>
			<td><input type="submit" name="selectID" value="Find" /></td>
			</form>
		<td><p><?php echo (@$_SESSION["error"]);?></p></td>
	</tr>
	<tr>
	<form method="post" action="<?=$_SERVER['PHP_SELF']?>">
		<td>First Name:</td>
		<td><input type="text" name="firstName" value="<?php echo (@$_SESSION['firstname']); ?>" required></td>
	</tr>
	<tr>
		<td>Last Name:</td>
		<td><input type="text" name="lastName" value="<?php echo (@$_SESSION["lastname"]); ?>" required></td>
	</tr>
	<tr>
		<td>DateOfBirth:</td>
		<td><input id="date" type="date" name="DOB" value="<?php echo (@$_SESSION['DOB']); ?>" required></td>
	</tr>
	<tr>
		<td>Home Address:</td>
		<td><input type="text" name="address" value="<?php echo (@$_SESSION["address"]); ?>" required></td>
	</tr>
	<tr>
		<td>Post Code:</td>
		<td><input type="text" name="postCode" value="<?php echo (@$_SESSION["postCode"]); ?>" required></td>
	</tr>
	<tr>
		<td>Phone Number</td>
		<td><input type="text" name="phoneNo" value="<?php echo (@$_SESSION["phoneNo"]); ?>" required></td>
	</tr>
	<tr>
		<td>E-Mail Address:</td>
		<td><input type="text" name="email" value="<?php echo (@$_SESSION["email"]); ?>" required></td>
	</tr>
	<tr>
		<td><input type="submit" name="submitPersonal" value="Submit Changes" /></td>
		</form>
	</tr>
	
</tbody>
</table>
</body>
</html>
<?php

	if(isset($_POST['submitPersonal'])){
		$username = "groupproject";
		$password = "groupProject560";
		$dbName = "groupDatabase";
		$dbHost = "hims.comnrcxa7372.eu-west-2.rds.amazonaws.com";
		$port = "3306";
		$dsn = "mysql:host=$dbHost;dbname=$dbName;port=$port";
		$opt = array (PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE =>PDO::FETCH_ASSOC,PDO::ATTR_EMULATE_PREPARES => false, array(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true));
		
		$query = "UPDATE UserDetails SET foreName='".$_POST['firstName']."',surName='".$_POST['lastName']."',dateOfBirth ='".$_POST['DOB']."',
		phoneNumber = '".$_POST['phoneNo']."',address = '".$_POST['address']."',postCode='".$_POST['postCode']."',emailAddress='".$_POST['email']."'
		WHERE user_id = '".$_SESSION['id']."'";
		
		try {
			$pdo = new PDO($dsn,$username,$password,$opt);		
			$stmt = $pdo->prepare($query);
			$stmt->execute();
			header('Location: /successful.php');
		}catch(PDOException $e){
			exit("PDO Error: ".$e->getMessage()."<br>");
		}
	}
	else if(isset($_POST["selectID"]) && isset($_POST["userID"])){
		$userID = $_POST["userID"];
		$_SESSION["id"] = $_POST["userID"];
		$query = "Select * from UserDetails Where id = " . $userID;
		
		$username = "groupproject";
		$password = "groupProject560";
		$dbName = "groupDatabase";
		$dbHost = "hims.comnrcxa7372.eu-west-2.rds.amazonaws.com";
		$port = "3306";
		
		$dsn = "mysql:host=$dbHost;dbname=$dbName;port=$port";
		$opt = array (PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,PDO::ATTR_DEFAULT_FETCH_MODE =>PDO::FETCH_ASSOC,PDO::ATTR_EMULATE_PREPARES => false, array(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY => true));
	
	
		try {
			$pdo = new PDO($dsn,$username,$password,$opt);		
			$stmt = $pdo->prepare($query);
			$stmt->execute();
			
			$user = $stmt->fetch();
			if(!empty($user)){
			//Set fields equal to that in database
				$_SESSION["firstname"] = $user["foreName"];
				$_SESSION["lastname"] = $user["surName"];
				$_SESSION["DOB"] = $user["dateOfBirth"];
				$_SESSION["address"] = $user["address"];
				$_SESSION["postCode"] = $user["postCode"];
				$_SESSION["phoneNo"] = $user["phoneNumber"];
				$_SESSION["email"] = $user["emailAddress"];
				$_SESSION["error"] = NULL;
				header('Location: /admin.php');
			}else{
				$_SESSION["error"] = "Id not found.";
				$_SESSION["firstname"] = NULL;
				$_SESSION["lastname"] = NULL;
				$_SESSION["DOB"] = NULL;
				$_SESSION["address"] = NULL;
				$_SESSION["postCode"] = NULL;
				$_SESSION["phoneNo"] = NULL;
				$_SESSION["email"] = NULL;
				header('Location: /admin.php');
			}
			
		}
		catch(PDOException $e){
			exit("PDO Error: ".$e->getMessage()."<br>");
		}
	}
	
?>