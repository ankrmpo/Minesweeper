<?php

if(!isset($_SESSION))
    session_start();

require_once __DIR__ . '/db.class.php';

class MemberService
{
    function register()
    {
        try
        {
            $db=DB::getConnection();
            $st = $db->prepare('INSERT INTO Members (username, password, mail) VALUES (:username, :password, :mail)');
            $st->execute(array('username' => $_SESSION['username'], 'password' => password_hash($_SESSION['password'], PASSWORD_DEFAULT ), 'mail' => $_SESSION['mail']));
        }
        catch(PDOException $e)
        {
            echo 'Greska: ' . $e->getMessage();
        }

        return true;
    }

    function login()
    {
        try
        {
            $db=DB::getConnection();
            $st = $db->prepare('SELECT username, password FROM Members WHERE username=:username');
            $st->execute(array('username' => $_SESSION['username']));
        }
        catch(PDOException $e)
        {
            echo 'Greska: ' . $e->getMessage();
        }

        if($st->rowCount() !== 1) return false;

        $row = $st->fetch();

        if(password_verify($_SESSION['password'], $row['password'] ) ) return $_SESSION['username'];
        else return false;
    }

    function getAccountDetails()
    {
        try
        {
            $db = DB::getConnection();
            $st = $db->prepare('SELECT * FROM Account WHERE username=:username');
            $st->execute(array('username' => $_SESSION['username']));
        }
        catch(PDOException $e)
        {
            echo 'Greska: ' . $e->getMessage();
        }

        if($st->rowCount() !== 1)
            return null;
        
        $row = $st->fetch();
        return $row;
    }

};

?>