
<?php
class DB
{
	private $conn;
	private $queryString;
	private static $instance;
	private $query;
	private $result;

	private function __construct($config)
	{
		$connection_string = $config['driver'];
		$connection_string .= ':host='.$config['host'].';';
		$connection_string .= 'dbname='.$config['dbname'].';';
		try{
			$this->conn = new PDO($connection_string, $config['username'], $config['password']);
			$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			$this->conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
		}catch(PDOException $e)
		{
			echo 'ERRORS: '. $e->getMessage();
		}
	}

	public function getInstance($config)
	{
		if(!isset(self::$instance))
		{
			$classname = __CLASS__;
			self::$instance = new $classname($config);
		}
		return self::$instance;
	}

	public function insert($tablename, $data = array())
	{
		$this->queryString = "INSERT INTO `".$tablename."`(";
		$columns = array_keys($data);
		for($i = 0; $i < count($columns); $i++)
		{
			if($i == (count($columns) - 1))
			{
				$this->queryString .= "`".$columns[$i].'`';
			}
			else
			{
				$this->queryString .= "`".$columns[$i].'`, ';
			}
		}
		$this->queryString .= ") VALUES(";
		$values = array_values($data);
		for($i = 0; $i < count($values); $i++)
		{
			if($i == (count($values) - 1))
			{
				$this->queryString .= ":".$columns[$i].")";
			}
			else
			{
				$this->queryString .= ":".$columns[$i].", ";
			}
			
		}
		$this->prepare($this->queryString);
		return $this->execute($this->premsPrepare($data));
	}

	public function retrieve($table, $pointer = array())
	{
		$this->queryString = "SELECT * FROM `$table` WHERE ";
		$keys = array_keys($pointer);
		$values = array_values($pointer);
		if(count($pointer) == 1)
		{
			foreach($pointer as $key => $value)
			{
				$this->queryString .= "`$key`=:$key";
			}
		}
		else if(count($pointer) > 0)
		{
			$i = 0;
			foreach ($pointer as $key => $value) 
			{
				if($i == (count($pointer) - 1))
				{
					$this->queryString .= "`$key`=:$key";
				}
				else
				{
					$this->queryString .= "`$key`=:$key ". "AND" ." ";
				}	
				$i++;
			}
		}
		$this->prepare($this->queryString);
		$this->execute($this->premsPrepare($pointer));
		return $this->query->fetch();
	}

	public function update($tablename, $data = array(), $id)
	{
		$this->queryString = "UPDATE `$tablename` SET ";
		$i = 0;
		$keys = array_keys($data);
		$values = array_values($data);
		foreach($keys as $key)
		{
			if($i == (count($data) - 1))
			{
				$this->queryString .= "`$key`=:$key ";
			}
			else
			{
				$this->queryString .= "`$key`=:$key, ";
			}
			$i++;
		}
		$this->queryString .= "WHERE `id`='$id'";
		$this->prepare($this->queryString);
		return $this->execute($this->premsPrepare($data));
	}

	function delete($tablename, $pointer = array())
	{
		$this->queryString .= "DELETE FROM `$tablename` WHERE ";
		$i = 0;
		$keys = array_keys($pointer);
		$values = array_values($pointer);
		foreach($pointer as $key => $value)
		{
			if($i == (count($pointer) - 1))
			{
				$this->queryString .= "`$key`=:$key";
			}
			else
			{
				$this->queryString .= "`$key`=:$key ". "AND" ." ";
			}
			$i++;
		}
		$this->prepare($this->queryString);
		return $this->execute($this->premsPrepare($pointer));
	}

	private function prepare($query)
	{
		$this->query = $this->conn->prepare($query);
	}

	private function premsPrepare($data = array())
	{
		$prems = array();
		foreach($data as $key => $value)
		{
			$prems[":$key"] = $value;
		}
		return $prems;
	}
	private function execute($prams)
	{
		return $this->query->execute($prams);
	}

	public function query($qry)
	{
		return $this->conn->query($qry);
	}
}