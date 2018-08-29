<?php
	require_once("include/DB.php");
	require_once("include/session.php");
	require_once("include/functions.php");
?>
<?php Confirm_Login(); ?>

<?php
	if(isset($_POST['Submit'])){
	$Title=$_POST["Title"];
	$Category=$_POST["Category"];
	$Post=$_POST["Post"];
	date_default_timezone_set("Asia/Kolkata");
	$Current = time();
	//$DateTime = strftime("%Y-%m-%d %H:%M:%S",$Current);
	$DateTime = strftime("%B-%d-%Y %H:%M:%S",$Current);
	$DateTime;
	$Admin = $_SESSION["Username"];
	$Image = $_FILES['Image']['name'];
	$Image_Store = "Upload/".basename($_FILES['Image']['name']);
	
	if(empty($Title)){
			$_SESSION['ErrorMessage'] ="Title can't be empty.";
			Redirect_to("addnewpost.php");
		}elseif(strlen($Title)<2){
			$_SESSION['ErrorMessage']= "Title must have more than two character.";
			Redirect_to("addnewpost.php");
		}else{
			global $Connection;
			$Insert_Query = "INSERT INTO admin_panel(datetime,title,category,author,image,post) VALUES('$DateTime','$Title','$Category','$Admin','$Image','$Post')";
			$Execute = mysqli_query($Connection, $Insert_Query);
			move_uploaded_file($_FILES['Image']['tmp_name'], $Image_Store);
		if($Execute){
			$_SESSION['SuccessMessage']= "Added Successfully";
			Redirect_to("addnewpost.php");

		}else{
			$_SESSION['ErrorMessage']= "Something went wrong";
			Redirect_to("addnewpost.php");
		}
	}
}

?>


<!DOCTYPE html>
<html>
<head>
	<title>Add New Post</title>
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
	<script src="js/jquery-min-3.2.1.min.js" ></script>
	<script src="js/bootstrap.min.js"></script>
	<link rel="stylesheet" type="text/css" href="css/adminstyle.css">
<style type="text/css">
	.FieldInfo{
		color:rgb(251,174,44);
		font-size: 1.2em;
		font-family: Bitter,Georgia,"Times New Roman", Times, serif;
	}
</style>
</head>

<body>
	<div style="height: 10px; background: #27aae1;"></div>
	<nav class="navbar navbar-inverse" role="navigation">
		<div class="container">
			<div class="navbar-header">
				<button type="button" class="navbar-toggle collapsed" data-toggle="collapsed" data-target="#collapse">
					<span class="sr-only">Toggle Navigation</span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</button>
				<a href="blog.php" class="navbar-brand">
					<img src="images/manis.png" style="margin-top: -8px;margin-left: -68px" width="250" height="40">
				</a>
				
			</div>
			<div class="collapse navbar-collapse" id="collapse">
			<ul class="nav navbar-nav" style="margin-left: 12px; ">
				<li><a href="#">Home</a></li>
				<li class="active"><a href="blog.php" target="_blank">Blog</a></li>
				<li><a href="#">About Us</a></li>
				<li><a href="#">Services</a></li>
				<li><a href="#">Contact Us</a></li>
				<li><a href="#">Feature</a></li>
				
			</ul>
			<form action="blog.php" class="navbar-form navbar-right">
				<div class="form-group">
					<input type="text" name="Search" placeholder="Search" class="form-control">
				</div>
				<button class="btn btn-default" name="SearchButton">Go</button>
			</form>
		</div>
		</div>
	</nav>
	<div class="Line" style="height: 10px; background: #27aae1;"></div>

	<!---Ending Navigation Bar-->
	<div class="container-fluid">
		<div class="row">
			
			<div class="col-sm-2">
				<br><br>
				
				<ul id="Side_Menu" class="nav nav-pills nav-stacked">
					<li ><a href="Dashboard.php"><span class="glyphicon glyphicon-home"></span> Dashboard</a></li>
					<li class="active"><a href="addnewpost.php"><span class="glyphicon glyphicon-list-alt"></span> &nbsp;Add New Post</a></li>
					<li><a href="categories.php"><span class="glyphicon glyphicon-tags"></span> &nbsp;Categories</a></li>
					
					
					<li><a href="manageadmins.php"><span class="glyphicon glyphicon-user"></span> Manage Admins</a></li>
					<li><a href="comments.php"><span class="glyphicon glyphicon-comment"></span> Comments
		<!--Count number of Un-Approved Comments Code Starts--->
				<?php
					$Connection;
					$TotalUnApproved_Query="SELECT COUNT(*) FROM comment WHERE status='OFF'";
					$ApprovedExecute = mysqli_query($Connection,$TotalUnApproved_Query);
					$RowUnApproved = mysqli_fetch_array($ApprovedExecute);
					$TotalUnApproved = array_shift($RowUnApproved);//Shifting array value in to String or Number
					if($TotalUnApproved>0){

				?>
				<span class="label label-warning pull-right">
					<?php echo $TotalUnApproved; ?>
				</span>
			<?php } ?> <!--Count number of Un-Approved Comments Code Ending-->
					</a>
					</li>
					<li><a href="#"><span class="glyphicon glyphicon-equalizer"></span> Live Blog</a></li>
					<li><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
				</ul>
			</div><!-- Ending of Side Area-->
			<div class="col-sm-10">
				<h1>Add New Post</h1>

				<div><?php echo Message(); 
							echo SuccessMessage();
					?></div>

				<div>
					<form action="addnewpost.php" method="POST" enctype="multipart/form-data">
					<fieldset>
					<div class="form-group">
						<label for="title"><span class="FieldInfo">Title : </span></label>
						<input class="form-control" type="text" name="Title" id="title" placeholder="Title ">
						
					</div>
					<div class="form-group">
						<label for="categoryselect"><span class="FieldInfo">Category : </span></label>
						<select class="form-control" id="categoryselect" name="Category">

					<?php
					global $Connection;
					$View_Query = "SELECT * FROM category ORDER BY datetime desc";
					$Execute = mysqli_query($Connection,$View_Query);
					
					while($DataRows =mysqli_fetch_array($Execute)){
						
						$Name = $DataRows['name'];
					?>

							<option><?php echo $Name; ?></option>
							<?php } ?>
						</select>
						
					</div>
					<div class="form-group">
						<label for="imageselect"><span class="FieldInfo">Select Image : </span></label>
						<input class="form-control" type="File" name="Image" id="imageselect">
					</div>
					<div class="form-group">
						<label for="postarea"><span class="FieldInfo">Post : </span></label>
						<textarea class="form-control" id="postarea" name="Post"></textarea>
					</div>
					<br>
					<input class="btn btn-success btn-block" type="submit" name="Submit" value="Add New Post">
				</fieldset>
					<br>
					</form>
				</div>
				

				
				</div>
			</div><!-- Ending of Main Area-->
		</div><!-- Ending of Row-->
	</div><!-- Ending of Container-->

	<div id="footer">
		<hr>
		<p>Developed by Manish Kumar | &copy;2018 ---All right released</p>
		<a href="https://www.facebook.com/manish.bs.kr"><p>This is Simple Admin Panel of blog Site</p></a>
		
	</div>
	<div style="background: #27AAE1; height: 10px;"></div>

</body>
</html>