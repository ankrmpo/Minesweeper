<?php

if(!isset($_SESSION))
    session_start();

require_once __DIR__ . '/db.class.php';
require_once __DIR__ . '/ranking.class.php';

class MemberService
{
    function register()
    {
        $_SESSION['username']=htmlentities($_SESSION['username'], ENT_QUOTES);
        if(!(preg_match('/[A-Za-z0-9]+/',$_SESSION['username'])) || strlen($_SESSION['username'])>50) return false;
        $_SESSION['password']=htmlentities($_SESSION['password'], ENT_QUOTES);
        if(!(preg_match('/[A-Za-z0-9]+/',$_SESSION['password'])) || strlen($_SESSION['password'])>20 || strlen($_SESSION['password'])<5) return false;
        $_SESSION['mail']=filter_var($_SESSION['mail'], FILTER_SANITIZE_EMAIL);
        if(filter_var($_SESSION['mail'], FILTER_VALIDATE_EMAIL)==false) return false;

        try
        {
            $db=DB::getConnection();
            $st = $db->prepare('SELECT username FROM Members');
            $st->execute();
        }
        catch(PDOException $e)
        {
            echo 'Greska: ' . $e->getMessage();
        }

        while($row=$st->fetch())
        {
            $name=$row['username'];
            if($name==$_SESSION['username']) return false;
        }
        
        try
        {
            $db=DB::getConnection();
            $st = $db->prepare('INSERT INTO Members (username, password_hash, email) VALUES (:username, :password_hash, :email)');
            $st->execute(array('username' => $_SESSION['username'], 'password_hash' => password_hash($_SESSION['password'], PASSWORD_DEFAULT ), 'email' => $_SESSION['mail']));
        }
        catch(PDOException $e)
        {
            echo 'Greska: ' . $e->getMessage();
        }

        try
        {
            $db=DB::getConnection();
            $st = $db->prepare('INSERT INTO Ranking (username, points) VALUES (:username, :points)');
            $st->execute(array('username' => $_SESSION['username'], 'points' => 0));
        }
        catch(PDOException $e)
        {
            echo 'Greska: ' . $e->getMessage();
        }

        try
        {
            $db=DB::getConnection();
            $st = $db->prepare('INSERT INTO Account (username, email) VALUES (:username, :email)');
            $st->execute(array('username' => $_SESSION['username'], 'email' => $_SESSION['mail']));
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
            $st = $db->prepare('SELECT username, password_hash FROM Members WHERE username=:username');
            $st->execute(array('username' => $_SESSION['username']));
        }
        catch(PDOException $e)
        {
            echo 'Greska: ' . $e->getMessage();
        }
       
        if($st->rowCount() != 1) return false;

        $row = $st->fetch();

        if(password_verify($_SESSION['password'], $row['password_hash'] ) ) return true;
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

    function getRanking()
    {
        $ranks=array();
        
        try
        {
            $db = DB::getConnection();
            $st = $db->prepare('SELECT * FROM Ranking ORDER BY points DESC');
            $st->execute();
        }
        catch(PDOException $e)
        {
            echo 'Greska: ' . $e->getMessage();
        }

        while($row=$st->fetch())
        {
            $ranks[]=new Rank($row['username'],$row['points']);
        }

        return $ranks;
    }

};

?>