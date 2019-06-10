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
}
catch( PDOException $e ) { exit( "PDO error [show tables]: " . $e->getMessage() ); }

if( $has_tables ) exit( 'Tablice Members/Ranking već postoje. Obrišite ih pa probajte ponovno.' );

try
{
    $st = $db->prepare
    (
		'CREATE TABLE IF NOT EXISTS Members (' .
		'id int NOT NULL PRIMARY KEY AUTO_INCREMENT,' .
		'username varchar(50) NOT NULL,' .
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
		'id int NOT NULL PRIMARY KEY AUTO_INCREMENT,' .
		'points int)'
	);

	$st->execute();
}
catch( PDOException $e ) { exit( "PDO error [create Members]: " . $e->getMessage() ); }

echo "Napravile tablicu Ranking.<br />";

?>