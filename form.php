<?php
//error checking
error_reporting(E_ALL);
ini_set('display_errors', 1);

//define variables
$name = $email = $comment = $gender = "";
$nameErr = $emailErr = $commentErr = $genderErr = "";

//form required
if($_SERVER["REQUEST_METHOD"] == 'POST'){
    $name=$_POST['name'];
    $email=$_POST['email'];
    $comment=$_POST['comment'];
    $gender=$_POST['gender'];

    //check if name has been entered
    if(empty($_POST['name'])){
        $nameErr = 'please enter your name';
    }

    //check if email has been entered
    if(empty($_POST['email'])){
        $emailErr = 'please enter your email adress';
    }

    //check if email adress is well formed
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $emailErr = 'invalid email format';
    }

    //check if comment has been entered
    if(empty($_POST['comment'])){
        $commentErr = 'please enter a comment';
    }

    //check if gender has been entered
    if(empty($_POST['gender'])){
        $genderErr = 'please enter your gender';
    }
    
    //if no errors submit form
    if(empty($nameErr) && empty($emailErr) && empty($commentErr) && empty($genderErr)){
        echo "welcome,$name!";

        //MYSQL database
        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "sqldb";

        //connect
        $conn = new mysqli($servername, $username, $password, $dbname);

        //check connection
        if($conn->connect_error){
            die("connection failed:" . $conn->connect_error);
        }

        //query to prevent sql injection
        $stmt = $conn->prepare("INSERT INTO persons(name, email, comment, gender) VALUES(?, ?, ?, ?)");
        $stmt->bind_param("ssss", $name, $email, $comment, $gender);

        //execute and check result
        if($stmt->execute()){
            echo "<P>Messages saved successfully</p>";
        } else{
            echo "<p>Error:" . $stmt->error . "</p>";
        }
        $stmt->close();
        $conn->close();
    }
}

//FORM VALIDATION
if($_SERVER["REQUEST_METHOD"] == 'POST'){
    $name = test_input($_POST['name']);
    $email = test_input($_POST['email']);
    $comment = test_input($_POST['comment']);
    $gender = test_input($_POST['gender']);
}
function test_input($data){
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div>
        <form action="form.php" method="post">
            <h1>Input your data</h1>
            <label for="name">NAME:</label><br>
            <input type="text" name="name" value ="<?php echo $name;?>"><br><br>
            <?php echo $nameErr;?><br><br>

            <label for="email">EMAIL:</label><br>
            <input type="text" name="email" value ="<?php echo $email;?>"><br><br>
            <?php echo $emailErr;?><br><br>


            <label for="comment">COMMENT:</label><br>
            <input type="text" name="comment" value ="<?php echo $comment;?>"><br><br>
            <?php echo $commentErr;?><br><br>

            <label for="gender">GENDER:</label><br>
            <input type="text" name="gender" value ="<?php echo $gender;?>"><br><br>
            <?php echo $genderErr;?><br><br>
            <button type="submit">SUBMIT</button>
        </form>
    </div>
</body>
</html>