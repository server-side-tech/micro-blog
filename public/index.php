<?php
require_once '../includes/head.php';
require_once '../includes/database.php';

function createHeader(){
    return '<header class="col-container clearfix">
                <div class="col-2">
                    <h1 class="vertical-alignment">Micro Blog</h1>
                </div>
                <div class="col-2">
                    <div class="col-container clearfix">
                        <div class="col-3 vertical-alignment">
                            <form name="signup-form" action="signup.php" method="post">
                                <input type="submit" name="sign-up" value="Sign up">
                            </form>                        
                        </div>
                        <div class="col-3 vertical-alignment">
                            <p>or</p>
                        </div>
                        <div class="col-3 vertical-alignment">
                            <form name="login-form" action="index.php" method="post">
                                <input type="submit" name="log-in" value="Log in">
                            </form>
                        </div>            
                    </div>
                </div>
            </header>';
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

$dbConnection = dbConnect();
$head   = createHead("Micro Blog");
$header = createHeader();

$messagesSection  = '<section id="messages-section">';
$messagesSection .= loadMessages($dbConnection);
$messagesSection .= '</section>';

dbClose($dbConnection);
?>

<?php
echo $head;
?>
<body>
<?php
echo $header;
echo $messagesSection;
?>    
</body>
</html>
