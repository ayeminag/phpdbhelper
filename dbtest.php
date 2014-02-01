<?php
require './config.php';
require './db.php';

$db = new DB($config);

//Insert Test
// $result = $db->insert('user', array('username' => 'portgasdace', 'email' => 'portgasdace@gmail.com'));
// echo ($result) ? "Successfully Inserted A New Record" : "Insertation Fails";

//Retreive Test
// $devine = $db->retrieve('user', array('id' => 3, 'username' => 'aye min aung'));
// var_dump($devine);

//Update Test
// $success = $db->update('user', array('username' => 'morirarty devine', 'email' => 'morirarty@gmail.com'), 10);
// echo ($success) ? "Successfully Updated A Record" : "Update Fails";

//Delete Test
$success = $db->delete('user', array('email' => 'portgasdace@gmail.com', 'username' => 'portgasdace'));
echo ($success) ? "Successfully Deleted A Record" : "Deleting Fails";