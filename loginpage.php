<!DOCTYPE html>
<html>
<head>
<title>Login Page</title>
<link href="default.css" rel="stylesheet"/>
</head>  
<body>
<?php
            if(isset($_POST['LOGIN']))
            {
                $savedName = $_POST['USERNAME'];
                $savedPhrase = $_POST['PASSWORD']; 
               $satledPassphrase = hash('sha3-256',  $savedPhrase, true);
                $hexHash = bin2hex($satledPassphrase);


                $connection = new mysqli('localhost','root','' ,'hse');
                $sql = "SELECT * FROM reported"; 
                $table = $connection -> query($sql); 
                
               while($element = $table -> fetch_object()){
                    $compareUsername = $element -> username;  
                    $comparePassword = $element -> password;   
                    if($compareUsername == $savedName && $comparePassword == $hexHash){
                        $location = "patientinformation.php?username=".$savedName; 
                        echo "<script>window.location='$location'</script>";
                    } 
               
               }
            }
        ?> 
<div class="header">
<h1 class="title">COVID-19 antigen test results</h1>	
<a href="registrationpage.php"><button>Register here</button></a>
<a href="index.php"><button>Homepage</button></a>
</div>
       <div class="box">
            <form id="loginForm" method="post">
            <h1 class="title">Login Page</h1>
            Enter Username:<input type="text" class="input" id="USERNAME" name="USERNAME" required/>
            <br>
            Enter Password: <input type="password" class="input" id="PASSWORD" name="PASSWORD" required/>
            <br><br>
            <input type="submit"  id="LOGIN" value="LOGIN" name="LOGIN" />
            
</form>
       
</body>
        
</html>