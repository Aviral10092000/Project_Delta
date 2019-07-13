<?php
// Initialize the session
session_start();
 
// Check if the user is logged in, if not then redirect to login page
if(!isset($_SESSION["loggedin"]) || $_SESSION["loggedin"] !== true){
    header("location: login.php");
    exit;
}

require_once "config.php";
$friendname = "";
$friendname_err = "";

if($_SERVER["REQUEST_METHOD"] == "POST")
{

	if(isset($_POST["friendname"]))
	{
		if(empty(trim($_POST["friendname"])))
		{
			$friendname_err = "Please enter the Friend's name correctly.";
			echo '<script type="text/javascript">
					alert("'.$friendname_err.'");
					</script>' ;
		}
		else
		{
			$friendname = trim($_POST["friendname"]);
			$sql = 'SELECT * FROM users WHERE username = ?';
			if($stmt = $mysqli->prepare($sql))
			{
				$stmt->bind_param("s",$friendname);
				if($stmt->execute())
				{
					$stmt->store_result();
					if($stmt->num_rows == 1)
					{
						$_SESSION["friendname"] = trim($_POST["friendname"]);
						header('location: friend_status.php');
						exit;
						
					}
					else
					{
						$friendname_err = "Friend Not Found.";
						echo '<script type="text/javascript">
						alert("'.$friendname_err.'");
						</script>' ;
					}
				}
			}
		}
	}

	
}

$count = 0;
$sql = "SELECT senderId FROM contactlist WHERE reciverId = '".$_SESSION['username']."'";
$r = $mysqli->query($sql);
while($row = $r->fetch_assoc())
{
	$sql_request_check = "SELECT * FROM contactlist WHERE senderId='".$_SESSION['username']."' AND reciverId='".$row['senderId']."'";
	$stmt = $mysqli->prepare($sql_request_check);
  	$stmt->execute();
  	$stmt->store_result();
  	if($stmt->num_rows==0)
  	{
  		$count++;
  	}
}

?>

<!DOCTYPE html>
<html>
<head>
	<title>Whatsaap</title>
	<meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
    <style type="text/css">
        body{ font: 14px sans-serif; }
        .wrapper{ width: 350px; padding: 20px; }
    </style>
</head>
<body id='content_area'>
	<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <a class="navbar-brand" href="welcome.php">Settings</a>
  <a class="navbar-brand" href="see_request.php">Friend Request&nbsp;<span class="badge badge-pill badge-info" id='friend_request'><?php echo $count; ?></span></a>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>

  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
    </ul>
    <form class="form-inline my-2 my-lg-0" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
      <input class="form-control mr-sm-2" placeholder="Search" aria-label="Search" name="friendname" value="" style = "width: 30rem;">
      <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search Friends</button></a>
    </form>
  </div>
</nav>
			
<?php
	$print_friend_templates = 0;
	$sql = "SELECT reciverId FROM contactlist WHERE senderId = '".$_SESSION['username']."'";
	$r = $mysqli->query($sql);
	echo '<center><table><tr>';
	while($row = $r->fetch_assoc())
	{
		foreach ($row as $key => $value) {
		$print_friend_templates++;
		echo '<td>
		<div class="card" style="width: 18rem;">
  <img class="card-img-top" src="default_img.png" >
  <div class="card-body">
  <center>
    <h5 class="card-title">'.$value.'</h5>
    <form action="chating.php" method="POST">
    <input type="hidden" name="chatingGuy" value="'.$value.'">
    <button class="btn btn-primary">Start Chating</button>
    </form>
    </center>
  </div>
</div>
		</td>';
		if($print_friend_templates%4==0)
		{
			echo '</tr><tr>';
		}
		}
	}
	echo '</tr></table>'
?>


	

</body>
</html>