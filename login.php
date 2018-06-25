<?php
	session_start();
	
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
	$message;
?>


<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
	
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">


    <title>Signin</title>

    <link href="signin.css" rel="stylesheet">
  </head>

  <body class="text-center">
  
    <form method="post" class="form-signin" >
		<h1 class="h3 mb-3 font-weight-normal">Please sign in</h1>
		
		<label for="username" class="sr-only">Username</label>
		<input type="text" name="username" class="form-control" placeholder="Username" required >
		
		<label for="inputPassword" class="sr-only">Password</label>
		<input type="password" name="password" class="form-control" placeholder="Password" required>
		
		<div>
			<p style='color:red;' name='message' value="<?php echo $message; ?>"></p>
		</div>
		
		<button class="btn btn-lg btn-primary btn-block" name="submit" type="submit">Sign in</button>
		<p class="mt-5 mb-3 text-muted">Pre-Apha</p>
    </form>
	
 
 <form class = "system">

<?php
	if ( isset( $_POST['submit'] ) ) {
		//Get user details
		$username = $_POST['username'];
		$password = $_POST['password'];
		
		//Check if username present in database
		
		try{
			$query = "SELECT * FROM User WHERE User.username = '$username'";
			$stmt = $pdo->prepare($query);
			$stmt->execute();
			$user = $stmt->fetch();
			$salt = $user["password_salt"];
			
			$check = hash('sha256', $salt.$password.$salt);
			if($check == $user["password"]){
				$_SESSION["Details"] = $user;
				
				header('Location: homePage.php');
			}
			else{
				$message = "Incorrect Password";
				
				unset ($_POST["submit"]);
				header('Location: login.php');
			}
			
			
		}
		catch(PDOException $e){
			echo "<h1>Error Connecting to Database.<h1>";
		}
		
		
	}
	
?>

</form>
</body>
</html>


