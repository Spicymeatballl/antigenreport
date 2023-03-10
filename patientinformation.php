<!DOCTYPE html>
<html>
    <head>
        <title>Saved Information</title>
        <link href="default.css" rel="stylesheet"/>
    </head>  
    <body>

        <?php
            if($_SERVER['REQUEST_METHOD'] == 'GET')
            {
                if(!empty($_GET['username']))
                {
                    $cipher = 'AES-128-CBC';
                    $key = 'thebestsecretkey';
                    $username = $_GET['username']; 
                    
                   $connection = new mysqli('localhost','root','' ,'hse');
                    
                    $sql = "SELECT * FROM reported WHERE username ='$username'";
                    
                    $table = $connection -> query($sql); 
                    
                    if($element = $table -> fetch_object())
                    {

                        $iv = hex2bin($element -> IntVector); 
                        $username = $element -> username; 

                        //converting to binary
                            $binFull =  hex2bin($element -> fullName);    
                            $binDOB =  hex2bin($element -> DOB);               
                            $binNum =  hex2bin($element -> phoneNum); 
                            $binAdd =  hex2bin($element -> HomeAddress);
                            $binProof = hex2bin($element -> Proof);               
                            $binFriend = hex2bin($element -> contactName);
                            $binFNum =  hex2bin($element -> contactNum);

                           $fullName = openssl_decrypt($binFull, $cipher, $key, OPENSSL_RAW_DATA, $iv);
                           $DOB = openssl_decrypt($binDOB, $cipher, $key, OPENSSL_RAW_DATA, $iv);
                           $phoneNum = openssl_decrypt($binNum, $cipher, $key, OPENSSL_RAW_DATA, $iv);
                           $HomeAddress = openssl_decrypt($binAdd, $cipher, $key, OPENSSL_RAW_DATA, $iv);
                           $Proof = openssl_decrypt($binProof, $cipher, $key, OPENSSL_RAW_DATA, $iv);
                           $contactName = openssl_decrypt($binFriend, $cipher, $key, OPENSSL_RAW_DATA, $iv);
                           $contactNum = openssl_decrypt($binFNum, $cipher, $key, OPENSSL_RAW_DATA, $iv);

                    }                   
                }
            }
            
            else
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
                $hexFriend = bin2hex($cbcFriend);
                $hexFNum =  bin2hex($cbcFNum);
                
                                
                    $connection = new mysqli('localhost','root','' ,'hse');
                    $sql = "UPDATE reported SET  fullName  = ?,DOB = ? , phoneNum = ?, HomeAddress = ?, Proof = ?, contactName = ?, contactNum = ?,IntVector = ? WHERE username = ?";          
                    $statement = $connection -> prepare($sql);  
                    $statement -> bind_param('sssssssss', $hexFull, $hexDOB, $hexNum, $hexAdd, $hexProof, $hexFriend, $hexFNum,$hexIntVector,$username);                   
                    if($statement -> execute())
                    {
                        $location = "patientinformation.php?username=".$username; 
                        echo "<script type='text/javascript'>alert('Record Updated');window.location='$location'</script>";
                    }
           
            }
        ?>

        <div class="header">
<h1 class="title">COVID-19 antigen test results</h1>	
<a href="index.php"><button>Homepage</button></a>
</div>

        <form class="user" method="post" enctype='multipart/form-data'>
        
        <h1>Patient Details</h1>
        <?php $display_img = '<img src="data:image/jpeg;base64,'.base64_encode( $Proof ).'" width="300px" height="300px"/>'; ?>
            <label><?php echo $display_img?></label>
            <br>
           Username: <input type="text" class="input" id="USERNAME" name="USERNAME" value="<?php echo $username?>" readonly/>
            <br>
            Enter Fullname: <input type="text" class="input" id="FULLNAME" name="FULLNAME" value="<?php echo $fullName?>" required/>
            <br>
            Enter DOB: <input type="date" class="input" id="DOB" name="DOB"value="<?php echo $DOB?>" required/>
            <br> 
            Enter Phonenumber: <input type="text" class="input" id="PHONENUMBER" name="PHONENUMBER"value="<?php echo $phoneNum?>" required/>
            <br>
            Enter Address: <input type="text" class="input" id="HOMEADDRESS" name="HOMEADDRESS" value="<?php echo $HomeAddress?>" required/>
            <br>
            Upload image of antigen test:<input type="file" id="PROOF" name="PROOF" accept="image/*" required/>
            <br>
            Enter close contact name:<input type="text" class="input" id="CONTACTNAME" name="CONTACTNAME" value="<?php echo $contactName?>" placeholder="Type no if there is none" required />
            <br>
            Confirm their phone number:<input type="text" class="input" id="CONTACTNUM" name="CONTACTNUM" value="<?php echo $contactNum?>" placeholder="Type no if there is none" required/>
            <br>
           
            <input type="submit" id="UPDATE" value="UPDATE" name="UPDATE" />
            <br>
            
            </form>

        </div>
        
        
    </body>
</html>