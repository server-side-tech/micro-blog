<?php
include 'error_codes.php';
function dbConnect(){
    define("DB_USERNAME", "root");
    define("DB_SERVER", "localhost");
    define("DB_PASSWORD", "123");
    define("DB_NAME", "micro_blog");

    /* 1) Connect to database using SQLI API in PHP*/
    $dbConnection= mysqli_connect(DB_SERVER,
                                  DB_USERNAME,
                                  DB_PASSWORD,
                                  DB_NAME);

    /* Check if database connection has been established successfully or not*/
    if(mysqli_connect_errno()){
            die("Database connection failed:" .
                    mysqli_connect_error .
                    "(".mysqli_connect_errno().")");
    }
    return $dbConnection;
}

function dbClose($dbConnection){
    mysqli_close($dbConnection);
}

function dbInsertNewMessage($dbConnection,$userId,$newMsg){
    $query  = "INSERT INTO messages ";
    $query .= "(`user_id`, `message_text`) ";
    $query .= "VALUES (";
    $query .= $userId.",'{$newMsg}');";
    
    $result = mysqli_query($dbConnection, $query);
    if(!$result){
        /* Cant insert new message into the database.*/
        die("Database syntax error");
        return -1;
    }else{
        return mysqli_insert_id($dbConnection);
    }
}

function dbGetAllMessages($dbConnection){
    $query  = "SELECT m.message_text,m.time_stamp,u.user_name ";
    $query .="FROM messages AS m INNER JOIN users AS u ";
    $query .="WHERE m.user_id=u.user_id ";
    $query .="ORDER BY m.time_stamp DESC";
    
    $result = mysqli_query($dbConnection, $query);
    if(!$result){
            die("Database query syntax error");
    }
    return $result;
}

function dbAddusername($dbConnection,$username, $password){
    $query = "INSERT INTO users (`user_name` , `user_hash`) ";
    $query .= "VALUES ('{$username}', '{$password}')";	
    $result = mysqli_query($dbConnection, $query); 
    if(!$result){
           /* Cant insert new username to the database.*/
           return -1;
    }else{
        /* new username has been inserted successfully into database.
           return the new id of the iserted username. This id will be saved into session super global array.*/
        return mysqli_insert_id($dbConnection);
    }
    
}

function isUsernameExist($dbConnection,$username){
    $query  ="SELECT user_name FROM users ";
    $query .="WHERE user_name='";
    $query .= $username."'";
    $result = mysqli_query($dbConnection, $query);
    if($result && 0 == mysqli_affected_rows($dbConnection)){ 
        /* User does not exist in the database. Next step is to add it to the database.*/
        return FALSE;
    }else{
        /* Username exists already in the database. */
        return TRUE;
    }
}

function dbFetchRow($result){
    return mysqli_fetch_assoc($result);
}

function dbFreeResult($result){
    mysqli_free_result($result);
}