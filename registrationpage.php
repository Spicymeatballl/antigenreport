<!DOCTYPE html>
<html>
    <head>
        <title>Registration Form</title>
        <link href="default.css" rel="stylesheet" />
    </head>  
    <body>
        
        <?php
            if(isset($_POST['REGISTRATION']))
            {

                $cipher = 'AES-128-CBC';
                $key = 'thebestsecretkey';

                $iv = random_bytes(16);
                $hexIntVector = bin2hex($iv); 

            //Collecting the information from the input fields
                $username = $_POST['USERNAME']; 
                $password = $_POST['PASSWORD'];
                $fullName  = $_POST['FULLNAME'];
                $DOB = $_POST['DOB'];
                $phoneNum = $_POST['PHONENUMBER'];
                $HomeAddress  = $_POST['HOMEADDRESS'];      
                $contactName = $_POST['CONTACTNAME'];
                $contactNum = $_POST['CONTACTNUM']; 
                $Proof = file_get_contents($_FILES['PROOF']['tmp_name']);


               //ecnrypt using CBC
               $cbcPass = hash('sha3-256',$password,true);
               $cbcFull = openssl_encrypt($fullName, $cipher, $key, OPENSSL_RAW_DATA, $iv);
               $cbcDOB = openssl_encrypt($DOB, $cipher, $key, OPENSSL_RAW_DATA, $iv);
               $cbcNum = openssl_encrypt($phoneNum, $cipher, $key, OPENSSL_RAW_DATA, $iv);
               $cbcAdd = openssl_encrypt($HomeAddress, $cipher, $key, OPENSSL_RAW_DATA, $iv);
               $cbcProof = openssl_encrypt($Proof, $cipher, $key, OPENSSL_RAW_DATA, $iv);
               $cbcFriend = openssl_encrypt($contactName, $cipher, $key, OPENSSL_RAW_DATA, $iv);
               $cbcFNum = openssl_encrypt($contactNum, $cipher, $key, OPENSSL_RAW_DATA, $iv);

               //converting to hexadecimal
                $hexPass = bin2hex($cbcPass);
                $hexFull =  bin2hex($cbcFull);    
                $hexDOB =  bin2hex($cbcDOB);               
                $hexNum =  bin2hex($cbcNum);  
                $hexAdd =  bin2hex($cbcAdd); 
                $hexProof = bin2hex($cbcProof);               
                $hexFriend =  bin2hex($cbcFriend);
                $hexFNum =  bin2hex($cbcFNum);
                
                
                   $connection = new mysqli('localhost','root','','hse');
                    
                   $sql = "INSERT INTO reported (id,  username, password, fullName, DOB,phoneNum, HomeAddress, Proof, contactName, contactNum,IntVector) values (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
                    
                    $statement = $connection -> prepare($sql); 
                    $id = NULL;                   
                    $statement -> bind_param('issssssssss', $id,  $username, $hexPass, $hexFull, $hexDOB, $hexNum, $hexAdd, $hexProof, $hexFriend, $hexFNum,$hexIntVector);                   
                    $statement -> execute(); 
                    if($statement -> affected_rows > 0)
                    {
                        echo '<script>alert("Your record has been saved."); location.href = "./index.php"</script>';
                    }
                    
                 
               
            }
        ?>
<div class="header">
  <h1 class="title">COVID-19 antigen test results</h1>	
<a href="loginpage.php"><button>Login here</button></a>
</div>
        <form class="user" method="post" enctype='multipart/form-data'>
        
        <h1>Registration Form</h1>

            Enter Username: <input type="text" class="input" id="USERNAME" name="USERNAME" required/>
            <br>
            Enter Password: <input type="password" class="input" id="PASSWORD" name="PASSWORD" required/>
            <br>
            Enter Fullname: <input type="text" class="input" id="FULLNAME" name="FULLNAME"  required/>
            <br>
            Enter DOB: <input type="date" class="input" id="DOB" name="DOB" required/>
            <br> 
            Enter Phonenumber: <input type="text" class="input" id="PHONENUMBER" name="PHONENUMBER" required/>
            <br>
            Enter Address: <input type="text" class="input" id="HOMEADDRESS" name="HOMEADDRESS"  required/>
            <br>
            Upload image of antigen test:<input type="file" id="PROOF" name="PROOF" accept="image/*" required/>
            <br>
            Enter close contact name:<input type="text" class="input" id="CONTACTNAME" name="CONTACTNAME" placeholder="Type no if there is none" required />
            <br>
            Confirm their phone number:<input type="text" class="input" id="CONTACTNUM" name="CONTACTNUM" placeholder="Type no if there is none" required/>
            <br>
            
            <br>
            <input type="submit" id="REGISTRATION" value="REGISTRATION" name="REGISTRATION" />
            </form>
            <br> 
    </body>
</html>