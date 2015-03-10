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

function dbGetAllMessages($dbConnection){
    $query = "SELECT message_text FROM `messages`";
    
    $result = mysqli_query($dbConnection, $query);
    if(!$result){
            die("Database query syntax error");
    }
    return $result;
}

function dbFetchRow($result){
    return mysqli_fetch_assoc($result);
}

function dbFreeResult($result){
    mysqli_free_result($result);
}