<?php

require_once '../../model/db.class.php';

$db = DB::getConnection();

$has_tables = false;

try
{
	$st = $db->prepare('SHOW TABLES LIKE :tblname');
	$st->execute( array( 'tblname' => 'Members' ) );
	if( $st->rowCount() > 0 ) $has_tables = true;

	$st->execute( array( 'tblname' => 'Ranking' ) );
	if( $st->rowCount() > 0 ) $has_tables = true;

	$st->execute( array( 'tblname' => 'Account' ) );
	if( $st->rowCount() > 0 ) $has_tables = true;
}
catch( PDOException $e ) { exit( "PDO error [show tables]: " . $e->getMessage() ); }

if( $has_tables ) exit( 'Tablice Members/Ranking/Account već postoje. Obrišite ih pa probajte ponovno.' );

try
{
    $st = $db->prepare
    (
		'CREATE TABLE IF NOT EXISTS Members (' .
		'username varchar(50) NOT NULL PRIMARY KEY,' .
		'password_hash varchar(255) NOT NULL,'.
		'email varchar(50) NOT NULL)'
	);

	$st->execute();
}
catch( PDOException $e ) { exit( "PDO error [create Members]: " . $e->getMessage() ); }

echo "Napravile tablicu Members.<br />";

try
{
    $st = $db->prepare
    (
		'CREATE TABLE IF NOT EXISTS Ranking (' .
		'username varchar(50) NOT NULL PRIMARY KEY,' .
		'points int)'
	);

	$st->execute();
}
catch( PDOException $e ) { exit( "PDO error [create Ranking]: " . $e->getMessage() ); }

echo "Napravile tablicu Ranking.<br />";

try
{
    $st = $db->prepare
    (
		'CREATE TABLE IF NOT EXISTS Account (' .
		'username varchar(50) NOT NULL PRIMARY KEY,' .
		'email varchar(50) NOT NULL,' .
		'first_name varchar(50),' .
		'last_name varchar(50),' .
		'info varchar(300))'
	);

	$st->execute();
}
catch( PDOException $e ) { exit( "PDO error [create Account]: " . $e->getMessage() ); }

echo "Napravile tablicu Account.<br />";

?>