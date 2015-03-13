<?php
function redirectToPage($page){
    header("Location: ".$page);
}

$g_userId=-1;

function setUserId($userId){
    global $g_userId;
    $g_userId = $userId;
}

function getUserId(){
    global $g_userId;
    return $g_userId;
}

function resetUserId(){
    global $g_userId;
    $g_userId = -1;
}