<?php
include 'error_codes.php';
function dbConnect(){
    define("DB_USERNAME", "root");
    define("DB_SERVER", "localhost");
    define("DB_PASSWORD", "123");
    define("DB_NAME", "micro_blog");

    try{
        $pdo_link = new PDO("mysql:host=".DB_SERVER.";dbname=".DB_NAME,
                            DB_USERNAME,
                            DB_PASSWORD);
        return $pdo_link;
    }catch (PDOException $exception){
        die("Connection error!!!".$exception->getMessage());
    }
}

function dbClose($pdo_link){
    $pdo_link = NULL;
}

function dbInsertNewMessage($pdo_link,$userId,$newMsg){
    $query  = "INSERT INTO messages ";
    $query .= "(`user_id`, `message_text`) ";
    $query .= "VALUES (?,?);";
    
    $result = $pdo_link->prepare($query);
    $result->execute(array($userId,$newMsg));
    
    if(!$result){
        /* Cant insert new message into the database.*/
        die("Database syntax error");
        return -1;
    }else{
        return $pdo_link->lastInsertId();
    }
}

function dbGetAllMessages($pdo_link){
    $query  = "SELECT m.message_text,m.time_stamp,u.user_name ";
    $query .="FROM messages AS m INNER JOIN users AS u ";
    $query .="WHERE m.user_id=u.user_id ";
    $query .="ORDER BY m.time_stamp DESC";
    
    $result = $pdo_link->query($query);
    if(!$result){
            die("Database query syntax error");
    }
    return $result;
}

function dbAddusername($pdo_link,$username, $password){
    $query = "INSERT INTO users (`user_name` , `user_hash`) VALUES (?, ?)";
    $result = $pdo_link->prepare($query);
    
    $result->execute(array($username,$password));
    if(!$result){
           /* Cant insert new username to the database.*/
           return -1;
    }else{
        /* new username has been inserted successfully into database.
           return the new id of the inserted username. This id will be saved into session super global array.*/
        return $pdo_link->lastInsertId();
    }
}

function isUsernameExist($pdo_link,$username){
    $query  ="SELECT user_name FROM users WHERE user_name = ?";
    $result = $pdo_link->prepare($query);
    
    $result->execute(array($username));
    /* Check that result is not null and number of affected columns is zero. This indicates that user 
     already exists.*/
    if($result && 0 == $result->rowCount()){ 
        /* User does not exist in the database. Next step is to add it to the database.*/
        return FALSE;
    }else{
        /* Username exists already in the database. */
        return TRUE;
    }
}

function dbFetchRow($result){
    return $result->fetch(PDO::FETCH_ASSOC);
}

function dbFreeResult($result){
    mysqli_free_result($result);
}