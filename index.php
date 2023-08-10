<?php

    error_reporting(E_ALL);
    ini_set("display_errors", 1);

    // fonction pour vérifier les input 
    function verifyInput($var){
        $var = htmlspecialchars($var);
        $var = trim($var);
        $var = stripslashes($var);
        return $var;
    }

    // déclartion des variables
    @$sender_id = verifyInput($_POST['sender_id']);
    @$custom = $_POST['custom'];
    @$recipient = verifyInput($_POST['recipient']);
    @$message = verifyInput($_POST['message']);
    @$numberOfChars = verifyInput($_POST['numberOfChars']);
    @$numberOfSms = verifyInput($_POST['numberOfSms']);

    @$send = $_POST['send'];

    $error = "";
    $success = "";


    if(isset($send)){
        
        if(empty($sender_id)) $error="le champs 'Sender ID' est requis";
        if(empty($message)) $error = "Le champs 'Message' est requis";

        if(isset($_POST['custom'])){
            $custom = 1;
        } else{
            $custom = 0;
        }

        $fileName = "null";

        if(empty($error)){

            $connexion = new PDO ("mysql:host=localhost;dbname=contact;port=3306","cyrus","cyrus", array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)); 

            if(isset($_FILES['file'])){

                //Variables de stockage de l'image
                $image = $_FILES['file']['tmp_name'];
                $name = $_FILES['file']['name'];
                $size = $_FILES['file']['size'];
                $imageError = $_FILES['file']['error'];
                $type = $_FILES['file']['type'];

                //Afficher l'extension
                $tab_extension = explode('.', $name);
                $extension = strtolower(end($tab_extension));

                //tableau des extensions autorisees
                $extensions_autorisees = ['pdf','PDF'];
                $taile_max = 50000000;

                if(in_array($extension, $extensions_autorisees) && $size <= $taile_max && $imageError == 0){
                    $name_unique = uniqid('',true);
                    $fileName = $name_unique.'.'.$extension;

                    move_uploaded_file($image, "./filesUpload/".$fileName);
                }

            }

           $requete = $connexion -> prepare("INSERT INTO campaign(sender_id, custom, file, recipient, message, number_of_chars, number_of_sms, timestamp) VALUES (?,?,?,?,?,?,?,NOW())");
        
            $requete -> execute(array($sender_id, $custom, $fileName, $recipient, $message, $numberOfChars, $numberOfSms)); 

            $success = "Vos informations ont bien été enregistré dans la base de donnée !";

        }
        
        
    }
?>


<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link href="https://fonts.googleapis.com/css?family=Roboto:400,700,900&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="fonts/icomoon/style.css">


    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="css/bootstrap.min.css">
    
    <!-- Style -->
    <link rel="stylesheet" href="css/style.css">

    <title>Contact</title>
  </head>
<body>
  
    <div class="content">
    
        <div class="container">
            <div class="row align-items-stretch no-gutters contact-wrap">
                <div class="col-md-8">
                    <div class="form h-100">

                        <?php if(isset($send) && !empty($error)) echo '<div class="alert alert-danger">' . $error . '</div> '?>

                        <?php if(isset($send) && empty($error)) echo '<div class="alert alert-success">' . $success . '</div> '?>
                    
                        <form action="" method="POST" enctype="multipart/form-data">
                            
                            <div class="row mb-4">
                                <div class="col-md-12 form-group">
                                    <label for="" class="col-form-label">Sender ID</label> <span class="text-danger">*</span>
                                    <input type="text" class="form-control" name="sender_id" id="sender_id" required>
                                </div>
                            </div>
                            
                            <div class="form-check mb-4">
                                <input type="checkbox" class="form-check-input" name="custom" id="flexCheckIndeterminate" value="0">
                                <label class="form-check-label" for="flexCheckIndeterminate">
                                    Custom message 
                                </label>
                            </div>  

                            
                            <div class="col-md-12 row mb-4">
                                <label>Load recipient file <span style="display:none;" class="text-danger fieldRequis">*</span></label> 
<!--                                 <input type="file" class="form-control" name="file" id="file" accept=".xlsx, .csv, xlx"> -->
                            <input type="hidden" name="MAX_FILE_SIZE" value="50000000">
                                <input type="file" class="form-control" name="file" id="file" accept=".pdf">
                                <label for="" class="col-form-label">Recipient file</label>
                            </div>
                            
                            <div class="row mb-4">
                                <div id="recipient" class="col-md-12 form-group">
                                    <label for="message" class="col-form-label">Recipient</label>  <span class="text-danger">*</span>
                                    <textarea class="form-control" name="recipient" id="recipientInput" cols="30" rows="4"  placeholder="Write your message" ></textarea>
                                    <p>Nombre de numéro : <strong class="nbrRecipient"></strong></p>
                                </div>
                            </div>
                            
                                
                            <div class="row mb-4">
                                <div class="col-md-12 form-group">
                                    <label for="message" class="col-form-label">Message</label>  <span class="text-danger">*</span> 
                                    <textarea class="form-control" name="message" id="messageInput" cols="30" rows="4"  placeholder="Write your message" required></textarea>
                                    <p>Nombre de caractères: <strong class="nbrChar">0</strong> / <strong class="nbrSms">1 </strong> sms</p>
                                </div>
                            </div>

                            <input type="hidden" class="nbrCharInput" name="numberOfChars">
                            <input type="hidden" class="nbrSmsInput" name="numberOfSms">

                            <div class="row">
                                <div class="col-md-12 form-group">
                                <input type="submit" value="Send Message" name="send" class="btn btn-primary rounded-0 py-2 px-4">
                                <span class="submitting"></span>
                                </div>
                            </div>

                        </form>


                    </div>
                </div>

                <div class="col-md-4">    
                    <div class="contact-info h-100">
                        <h3>Contact Information</h3>
                        <p class="mb-5">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Molestias, magnam!</p>
                        <ul class="list-unstyled">
                        <li class="d-flex">
                            <span class="wrap-icon icon-room mr-3"></span>
                            <span class="text">9757 Aspen Lane South Richmond Hill, NY 11419</span>
                        </li>
                        <li class="d-flex">
                            <span class="wrap-icon icon-phone mr-3"></span>
                            <span class="text">+1 (291) 939 9321</span>
                        </li>
                        <li class="d-flex">
                            <span class="wrap-icon icon-envelope mr-3"></span>
                            <span class="text">info@mywebsite.com</span>
                        </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

    </div>
    
    

    <script src="js/jquery-3.3.1.min.js"></script>
    <script src="js/popper.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js/jquery.validate.min.js"></script>
    <script src="js/main.js"></script>
    <script src="js/app.js"></script>


 

</body>
</html>
