<?php
session_start();
require_once '../includes/utilities.php';
require_once '../includes/head.php';
require_once '../includes/database.php';

function createHeader(){
    return '<header>
        <h1 class="vertical-alignment">Micro Blog</h1>
    </header>';
}

function createSignUpForm($username,$errorMsg){
    $form= '<section id="signup-section">
                <p id="signup-welcome-msg">Please enter a username and password</p>
                <div class="signup-text-wrapper">
                <p id="mandatory-msg" class="vertical-spacing signup-text">All fields marked with an (*) are required</p>';
    
    if(!is_null($errorMsg)){
        $form .= '<p id="signup-error-msg" class="vertical-spacing signup-text">'.$errorMsg.'</p>';
    }
    $form .= "</div>"; //close signup-text-wrapper div.
    $form .= '<form name="signup-form" action="signup.php" method="post">
                <label for="username">username: <span class="mandatory">*</span></label>';
    $form .=        '<input class="vertical-spacing" type="text" id="username" name="username" value="'.
                    $username.'"required/>';
                    
    $form .=    '<label for="password">password: <span class="mandatory">*</span></label>
                    <input class="vertical-spacing" type="password" id="password" name="password" required/>

                    <input class="vertical-spacing" type="submit" name="register" value="Sign up">
            </form>
            <form name="cancel-form" action="index.php" method="post">
                <input class="vertical-spacing" type="submit" name="cancel" value="Cancel">
            </form>
            </section>';
                                        
    return $form;
}

function isFormSubmitted(){
    return isset($_POST["register"]);
}

function isValidUsername($dbConnection, $username){
 return dbCheckValidUsername($dbConnection,$username);
    
}

//var_dump($_POST);
if(isFormSubmitted()){
    $username = trim($_POST["username"]);
    $password = $_POST["password"];

    
    /* Establish database connection to check that the given username is valid*/
    $dbConnection = dbConnect();    
    
    if(isUsernameExist($dbConnection, $username)){
        define("ERROR_MSG", "username already exists.<br>Please choose another different one");
        $signUpForm = createSignUpForm($username, ERROR_MSG);
    }else{
        /* Make sure to encrypt the password using md5 function.*/
        $userId = dbAddusername($dbConnection, $username, md5($password));
        
        /* Create session variables to store  and redirect page to index.php again.*/
        if(-1 != $userId){
            $_SESSION['myId'] = $userId;
            $_SESSION['myName'] = $username;
            redirectToPage("index.php");
        }else{
            define("ERROR_MSG", "We encounter an error with our servers.<br>Please try again in 10min");
            $signUpForm = createSignUpForm("", ERROR_MSG);            
        }
    }
}else{
    define("INITIAL_USERNAME", "");
    define("ERROR_MSG", NULL);
    $signUpForm = createSignUpForm(INITIAL_USERNAME, ERROR_MSG);
}



$head = createHead("Sign up");
$header = createHeader();



if(isFormSubmitted()){
    dbClose($dbConnection);
}
?>

<?=$head?>
<body>
    <?=$header?>
    <?=$signUpForm?>
</body>
</html>