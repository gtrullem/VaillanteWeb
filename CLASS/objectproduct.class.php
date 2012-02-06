<?php

class Product {
	protected $id;
	protected $name;
	protected $quantity;
	protected $detail;
		
	function __construct($id, $name, $quantity, $detail)
	{
		$this->id = $id;
		$this->name = $name;
		$this->quantity = $quantity;
		$this->detail = $detail;

		return $this;
	}

	public function withReader($line)
	{
		return(new Product($line['productid'], $line['name'], $line['quantity'], $line['detail']));
	}
	
	/****************************** GETTER ******************************/
	function getID()
	{
		return $this->id;
	}
	
	function getName()
	{
		return $this->name;
	}
	
	function getQuantity()
	{
		return $this->quantity;
	}
	
	function getDetail()
	{
		return $this->detail;
	}

	/****************************** SETTER ******************************/
	function setID($id)
	{
		$this->id = $id;
	}
	
	function setName($name)
	{
		$this->name = $name;
	}
	
	function setQuantity($quantity)
	{
		$this->quantity = $quantity;
	}
	
	function setDetail($detail)
	{
		$this->detail = $detail;
	}
	
	/****************************** OTHER ******************************/
	function displayProduct()
	{
		echo " | ".$this->id." | ".$this->name." | ".$this->quantity." | ".$this->detail." |<br />";
	}
	
	function insert()
	{
		require("./CLASS/database.class.php");
		$database = new DatabaseProduct();
		$database->insertProduct($this);
	}
}

?>