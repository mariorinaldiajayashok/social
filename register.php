
<?php 

session_start();

$con = mysqli_connect('localhost','root','mysql','social'); //connection variable

if(mysqli_connect_errno($con))
{
	echo "Failed to connect".mysqli_connect_errno();
}

 //Declaring variables to prevent errors

$fname = ""; //Firstname
$lname = ""; //Lastname
$em =""; //Email
$em2 =""; //confirm email
$pw =""; //password
$pw2=""; //confirmEmail
$date="";//Date
$error_array=array(); //ErrorArray


if(isset($_POST['reg_button']))
{

	//Registration form values

	$fname = strip_tags($_POST['reg_fname']); //Removes all tags

	$fname = str_replace(" ", "", $fname); //Removes spaces

	$fname = ucfirst(strtolower($fname)); //Capitalizes the first letter.

	$_SESSION['reg_fname']= $fname; //Stores first name in to the session variable


	$lname = strip_tags($_POST['reg_lname']); //Removes all tags

	$lname = str_replace(" ", "", $lname); //Removes spaces

	$lname = ucfirst(strtolower($lname)); //Capitalizes the first letter.

	$_SESSION['reg_lname']= $lname; //Stores last name in to the session variable



	$em = strip_tags($_POST['reg_email']); //Removes all tags

	$em = str_replace(" ", "", $em); //Removes spaces

	$em = ucfirst(strtolower($em)); //Capitalizes the first letter.

	$_SESSION['reg_email']= $em; //Stores email  in to the session variable


    
	$em2 = strip_tags($_POST['reg_email2']); //Removes all tags

	$em2 = str_replace(" ", "", $em2); //Removes spaces

	$em2 = ucfirst(strtolower($em2)); //Capitalizes the first letter.

	$_SESSION['reg_email2']= $em2; //Stores confirm email in to the session variable



	$pw= strip_tags($_POST['reg_password']); //Removes all tags
	$_SESSION['reg_password']= $pw; //Stores password in to the session variable
 


	$pw2 = strip_tags($_POST['reg_password2']); //Removes all tags
    $_SESSION['reg_password2']= $pw2; //Stores confirm password in to the session variable

 $date = date("Y-m-d"); //gets the current date

//VALIDATION CHECKS


 if($em == $em2)

 {
 	//Check if email is in the correct format

 	if(filter_var($em,FILTER_VALIDATE_EMAIL))
 	{
 		$em = filter_var($em,FILTER_VALIDATE_EMAIL);

 		//Check if email exists

 		$e_check = mysqli_query($con,"SELECT email FROM users WHERE email = '$em'");

 		//Count the number of rows returened

 		$num_rows = mysqli_num_rows($e_check);

 		if($num_rows>0)
 		{
 	array_push($error_array, "Email is already in use <br>");
 		}


 	}else{

 	 array_push($error_array, "Invalid Email format<br>");


 	}	

 }else{

  	array_push($error_array, "Emails do not match<br>");	

 }

 if(strlen($fname)>25 || strlen($fname)<2)
 {
 	   array_push($error_array, "First name must be between 2 and 25 charecters<br>");
}

 if(strlen($lname)>25 || strlen($lname)<2)
 {
 	 array_push($error_array, "Last name must be between 2 and 25 charecters<br>");

}

if($pw!=$pw2)
{
	array_push($error_array, "Passwords do not match<br>");
 
}

else{

if(preg_match('/[^A-Za-z0-9 ]/', $pw))
	{
		 array_push($error_array, "Invalid Password pattern<br>");
	}

	}	


if(strlen($pw)>30 || strlen($pw)<5)
{
	 array_push($error_array, "Password must be between 5 and 30 charecters<br>");
}



if(empty($error_array))
{
	//INSERT INTO DB

	$encrypted_pw = md5($pw); //Encrypt the password before sending to the database

	//Generate username by concatenating first name and last name

	$username = strtolower($fname."_".$lname);

	$check_username_query = mysqli_query($con,"SELECT username FROM users WHERE username = '$username'");

	$i=0;
    //If username exists add number to username

    while(mysqli_num_rows($check_username_query)!=0)
    {
    	$i++; //Add 1 to i

    	$username = $username."_".$i;

    	$check_username_query = mysqli_query($con,"SELECT username FROM users WHERE username = '$username'");
    }

    //Profile Picture assignment

    $rand = rand(1,2); //Creates random number between 1 and 2

    if($rand==1)
    $profile_pic = "assets/images/profile_pics/defaults/aj.png";
    else if($rand==2)
    $profile_pic = "assets/images/profile_pics/defaults/bj.png";	
    

$query = mysqli_query($con,"INSERT INTO users VALUES('','$fname','$lname','$username','$em','$encrypted_pw','$date','$profile_pic','0','0','no',',')");

array_push($error_array, "<span style='color:#14c800;'>You're all set!Go ahead and login </span><br>");


//Clear Session variables

$_SESSION['reg_fname']= "";

$_SESSION["reg_lname"] ="";

$_SESSION["reg_email"] ="";

$_SESSION["reg_email2"] ="";

 

 


}//if the array is empty	






}//button







 ?>





<!DOCTYPE html>
<html>
<head>
	<title>Welcome to Swirl</title>
</head>
<body>

<form action="register.php" method="POST">
	
<input type="text" name="reg_fname" placeholder="First Name" value="
<?php

if(isset($_SESSION['reg_fname']))
{
	echo $_SESSION['reg_fname'];
}


 ?>"required>

<br>

<?php

if(in_array("First name must be between 2 and 25 charecters<br>", $error_array))
{
	echo "First name must be between 2 and 25 charecters<br>";
}


?>



<input type="text" name="reg_lname" placeholder="Last Name"value="
<?php

if(isset($_SESSION['reg_lname']))
{
	echo $_SESSION['reg_lname'];
}


 ?>"required>
<br>

<?php

if(in_array("Last name must be between 2 and 25 charecters<br>", $error_array))
{
	echo "Last name must be between 2 and 25 charecters<br>";
}


?>

 
 



<input type="email" name="reg_email" placeholder="Enter Email" value="
<?php

if(isset($_SESSION['reg_email']))
{
	echo $_SESSION['reg_email'];
}


 ?>"required>
<br>


<input type="email" name="reg_email2" placeholder="Confirm Email" value="
<?php

if(isset($_SESSION['reg_email2']))
{
	echo $_SESSION['reg_email2'];
}


 ?>" required>
<br>
<?php  if(in_array("Email is already in use <br>", $error_array))
{

echo "Email already in use <br>";

}
 else if(in_array("Invalid Email format<br>", $error_array))
{

echo "Invalid Email format<br>";

}

 else if(in_array("Emails do not match<br>", $error_array))
{

echo "Emails do not match<br>";

}


 ?>

  




<input type="password" name="reg_password" placeholder="Enter Password" required>
<br>

<input type="password" name="reg_password2" placeholder="Confirm Password" required>
<br>

<?php  if(in_array("Passwords do not match<br>", $error_array))
{

echo "Passwords do not match<br>";

}
else if(in_array("Invalid Password pattern<br>", $error_array))
{

echo "Invalid Password pattern<br>";

}

 else if(in_array("Password must be between 5 and 30 charecters<br>", $error_array))
{

echo "Password must be between 5 and 30 charecters<br>";

}


 ?>
  



<input type="submit" name="reg_button" value="Register">
<br>
 
<?php  if(in_array("<span style='color:#14c800;'>You're all set!Go ahead and login </span><br>", $error_array))


echo "<span style='color:#14c800;'>You're all set!Go ahead and login </span><br>";

?>



</form>



</body>
</html>