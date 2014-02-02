phpdbhelper
===========

phpdbhelper is a simple php multidriver database helper class

###How To use
  first set up your database configuration in config.php
  then include both `config.php` file and `db.php` file

    <?php
         include 'config.php';
         include 'db.php';

  then create a DB object by calling  `DB::getInstance()` method and  passing `$config` as the parameter

    <?php
         include 'config.php';
         include 'db.php';
         
         $db = DB::getInstance($config);

  after that u can use all insert, retrieve, update, delete method. Check the source codes for more details.
