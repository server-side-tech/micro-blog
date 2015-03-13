<?php
session_start();

require_once '../includes/utilities.php';
require_once '../includes/head.php';
require_once '../includes/database.php';

function createLandingForm(){
    return '<header class="col-container clearfix">
                <div class="col-2 vertical-alignment">
                    <h1>Micro Blog</h1>
                </div>
                <div class="col-2 vertical-alignment">
                    <div class="col-container clearfix">
                        <div class="col-3">
                            <form name="signup-form" action="signup.php" method="post">
                                <input type="submit" name="sign-up" value="Sign up">
                            </form>                        
                        </div>
                        <div class="col-3">
                            <p>or</p>
                        </div>
                        <div class="col-3">
                            <form name="login-form" action="index.php" method="post">
                                <input type="submit" name="log-in" value="Log in">
                            </form>
                        </div>            
                    </div>
                </div>
            </header>';
}

function createLogoutForm(){
    return '<header class="col-container clearfix">
                <div class="col-3-logout vertical-alignment">
                    <p id="login-welcome-msg">Welcome,'.$_SESSION["myName"].'</p><p id="login-extra-msg">You are now logged in</p>'.
                '</div>
                <div class="col-3-logout vertical-alignment">
                    <h1>Micro Blog</h1>
                </div>
                <div class="col-3-logout vertical-alignment">
                            <form name="logout-form" action="index.php" method="post">
                                <input type="submit" name="logout" value="Log out">
                            </form>                        
                </div>
            </header>';    
}

function createSendMessageForm(){
    return '<footer>
                <form class="vertical-alignment col-container clearfix" name="send-msg-form" action="index.php" method="post">
                    <div class="col-2-msg-send vertical-alignment" >
                        <input type="text" name="txt-input-msg" required>
                    </div>
                    <div class="col-2-msg-send vertical-alignment" >
                        <input type="submit" name="send-msg-form" value="Post">
                    </div>            
                </form>                        
            </footer>';    
}

function createSigninForm(){
    $form= '<header class="col-container clearfix">
                <form name="signin-form" action="index.php" method="post">
                    <div class="col-3-sign-in">
                        <label for="username">username: <span class="mandatory">*</span></label>
                        <input type="text" id="username" name="username" value="" required>
                    </div>
                    <div class="col-3-sign-in">
                        <label for="passowrd">password: <span class="mandatory">*</span></label>
                        <input type="password" id="password" name="password" required>
                    </div>
                    <div class="col-3-sign-in">
                        <label for="sign-in">All fields marked with an (*) are required</label>
                        <input type="submit" id="sign-in" name="sign-in" value="Log in">
                    </div>
                </form> 
            </header>';
    return $form;
}

function loadMessages($pdo_link){
    $ul = '<ul>';
    $result = dbGetAllMessages($pdo_link);
    while($row = dbFetchRow($result)){
        $ul .= "<li><p>@".$row["time_stamp"]." ";
        $ul .= $row["user_name"]." wrote:"."<br>";
        $ul .= $row["message_text"]. "</p><hr></li>";
    }
    
    dbFreeResult($result);
    $ul .='</ul>';
    
    return $ul;
}

function validateUser($pdo_link, $username, $password){
    /* Get user information from database. */
    $credentials = dbGetUserCredentials($pdo_link, $username);
    
    if($credentials){
        /* Encrypt input password */
        $encryptedPassword = md5($password);
        /* save user id into global varibale, it will be saved into session as a next step.
         * I have to do so becacause I wanted this function to return only boolean and I
         * I wanted to save another SQL query for the sake of user_id.*/
        setUserId($credentials["user_id"]);
        
        /* Compare username and encrypted password */
        return (($username == $credentials["saved_username"]) &&
                ($encryptedPassword == $credentials["saved_password"]));
        
    }else{
        return FALSE;
    }
    
}

function isUserLoggedIn(){
    return isset($_SESSION["myName"]);
}

/* Check if login button in the landing form is submitted or not*/
function isLoginClicked(){
    return isset($_POST["log-in"]);
}

/* Check if login button in the login form is submitted or not*/
function isSinginClicked(){
    return isset($_POST["sign-in"]);
}

/* Check if logout botton in logout form is submitted or not*/
function isLogoutClicked(){
    return isset($_POST["logout"]);
}

/* Check if post message button in send-form is submitted or not*/
function isPostMessageClicked(){
    return isset($_POST["send-msg-form"]);
}

/* establish database connection using PDO API*/
$pdo_link = dbConnect();

/* Create head of html */
$head   = createHead("Micro Blog");

if(isUserLoggedIn()){ /* User has signed up or logged in successfully*/
    if(isLogoutClicked()){
        session_destroy(); /* destroy user's session */
        $header = createLandingForm();
        $footer = "";
    }else{
        $header = createLogoutForm(); /* Display logout form in the header*/
        $footer = createSendMessageForm(); /* Display send message form in the footer*/
        if (isPostMessageClicked()){
                $userId = $_SESSION["myId"];
                $newMsg = $_POST["txt-input-msg"];
                dbInsertNewMessage($pdo_link,$userId,$newMsg);
        }        
    }
}else{ /* User neither signed up nor logged in*/
    if(isLoginClicked()){ //login button in the landing form
        $header = createSigninForm();
        $footer = "";        
    }else if(isSinginClicked()){ //login button in the login form
        $username = trim($_POST["username"]);
        $password = $_POST["password"];
        if(validateUser($pdo_link, $username, $password)){
            $_SESSION['myId'] = getUserId();
            $_SESSION['myName'] = $username;
            
            $header = createLogoutForm();
            $footer = createSendMessageForm();
            
            resetUserId();

        }else{ /* User has entered wrong login information, get back to landing form.*/
            $header = createLandingForm();
            $footer = "";           
        }
    }else{
        $header = createLandingForm();
        $footer = "";
    }
}

$messagesSection  = '<section id="messages-section">';
$messagesSection .= loadMessages($pdo_link);
$messagesSection .= '</section>';

dbClose($pdo_link);
?>

<?=$head?>
<body>
<?=$header?>
<?=$messagesSection?>
<?=$footer?>
</body>
</html>
