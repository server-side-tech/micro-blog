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
                <div class="col-3-login vertical-alignment">
                    <p id="login-welcome-msg">Welcome,'.$_SESSION["myName"].'</p><p id="login-extra-msg">You are now logged in</p>'.
                '</div>
                <div class="col-3-login vertical-alignment">
                    <h1>Micro Blog</h1>
                </div>
                <div class="col-3-login vertical-alignment">
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

function loadMessages($dbConnection){
    $ul = '<ul>';
    $result = dbGetAllMessages($dbConnection);
    while($row = dbFetchRow($result)){
        $ul .= "<li><p>@".$row["time_stamp"]." ";
        $ul .= $row["user_name"]." wrote:"."<br>";
        $ul .= $row["message_text"]. "</p><hr></li>";
    }
    
    dbFreeResult($result);
    $ul .='</ul>';
    
    return $ul;
}

function isUserLoggedIn(){
    return isset($_SESSION["myName"]);
}

function isLogoutClicked(){
    return isset($_POST["logout"]);
}

function isPostMessageClicked(){
    return isset($_POST["send-msg-form"]);
}

$dbConnection = dbConnect();
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
                dbInsertNewMessage($dbConnection,$userId,$newMsg);
        }        
    }
}else{ /* User neither signed up nor logged in*/
    $header = createLandingForm();
    $footer = "";
}

$messagesSection  = '<section id="messages-section">';
$messagesSection .= loadMessages($dbConnection);
$messagesSection .= '</section>';

dbClose($dbConnection);
?>

<?=$head?>
<body>
<?=$header?>
<?=$messagesSection?>
<?=$footer?>
</body>
</html>
