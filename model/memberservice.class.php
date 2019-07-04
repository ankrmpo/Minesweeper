<?php

require_once __DIR__ . '/db.class.php';

class MemberService
{
    function register()
    {
        try
        {
            $db=DB::getConnection();
            $st = $db->prepare('INSERT INTO Members (username, password, mail) VALUES (:username, :password, :mail)');
            $st->execute(array('username' => $_POST['username'], 'password' => password_hash($_POST['password'], PASSWORD_DEFAULT ), 'mail' => $_POST['mail']));
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
            $st->execute(array('username' => $_POST['username']));
        }
        catch(PDOException $e)
        {
            echo 'Greska: ' . $e->getMessage();
        }

        if($st->rowCount() !== 1) return false;

        $row = $st->fetch();

        if(password_verify($_POST['password'], $row['password'] ) ) return $_POST['username'];
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