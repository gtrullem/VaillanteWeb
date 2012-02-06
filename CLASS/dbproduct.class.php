<?php

require_once("./CLASS/database.class.php");
require_once("./CLASS/objectproduct.class.php");

class DBProduct extends DB {
	
	function __construct()
	{
		parent::__construct();
	}

	public function getProducts($where=null, $order=null)
	{
		$query = "SELECT * FROM xtr_product";

		if(!empty($where)) $query .= " ".$where;
		if(!empty($order)) $query .= " ".$order;
		else $query .= " ORDER BY name";

		$this->openConnection();
		$result = mysql_query($query,$this->db) or trigger_error("SQL ERROR : SELECT FAILED (Product) !<br />QUERY : $query<br /><br />$result<br />".mysql_error(), E_USER_ERROR);
		
		$objectList = array();
		while ($line = mysql_fetch_array($result, MYSQL_ASSOC))
			array_push($objectList, Product::withReader($line));
		
		return $objectList;
	}

	public function getProduct($productid)
	{
		$query = "SELECT * FROM xtr_product WHERE productid = $productid";

		$this->openConnection();
		$result = mysql_query($query,$this->db) or trigger_error("SQL ERROR : SELECT FAILED (Product) !<br />QUERY : $query<br /><br />$result<br />".mysql_error(), E_USER_ERROR);
		$this->closeConnection();
		
		return Product::withReader(mysql_fetch_array($result, MYSQL_ASSOC));
	}
	
	public function insertProduct($product)
	{
		$query = "INSERT INTO xtr_product (name, quantity, detail) VALUES ('".$product->getName()."', '".$product->getQuantity()."', '".$product->getDetail()."')";

		$this->openConnection();
		$result = mysql_query($query,$this->db) or trigger_error("SQL ERROR : INSERT FAILED (Product) !<br />QUERY : $query<br /><br />$result<br />".mysql_error(), E_USER_ERROR);
		$this->closeConnection();
	}

	public function updateProduct($product)
	{
		$query = "UPDATE xtr_product SET name = '".$product->getName()."', quantity = '".$product->getQuantity()."', detail = '".$product->getDetail()."' WHERE productid = ".$product->getID();

		$this->openConnection();
		$result = mysql_query($query,$this->db) or trigger_error("SQL ERROR : UPDATE FAILED (Product) !<br />QUERY : $query<br /><br />$result<br />".mysql_error(), E_USER_ERROR);
		$this->closeConnection();
	}

	public function deleteProduct($productid)
	{
		$query = "DELETE FROM xtr_product WHERE productid = ".$productid;

		$this->openConnection();
		$result = mysql_query($query,$this->db) or trigger_error("SQL ERROR : DELETE FAILED (Product) !<br />QUERY : $query<br /><br />$result<br />".mysql_error(), E_USER_ERROR);
		$this->closeConnection();
	}
		
}

?>