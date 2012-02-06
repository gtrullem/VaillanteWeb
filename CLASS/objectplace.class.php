<?php

Class Place {
	protected $id;
	protected $name;
	protected $address;
	protected $postal;
	protected $city;
	protected $country;
	protected $nbkm;
	protected $isLocal;

	function __construct($placeid, $name, $address, $postal, $city, $country, $nbkm, $isLocal)
	{
		$this->id = $placeid;
		$this->name = $name;
		$this->address = $address;
		$this->postal = $postal;
		$this->city = $city;
		$this->country = $country;
		$this->nbkm = $nbkm;
		$this->isLocal = $isLocal;

		return $this;
	}

	public function withReader($line)
	{
		return (new Place($line['placeid'], $line['name'], $line['address'], $line['postal'], $line['city'], $line['country'], $line['nbkm'], $line['isLocal']));
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

	function getAddress()
	{
		return $this->address;
	}
	
	function getPostal()
	{
		return $this->postal;
	}

	function getCity()
	{
		return $this->city;
	}
	
	function getCountry()
	{
		return $this->country;
	}

	function getNbKm()
	{
		return $this->nbkm;
	}

	function isLocal()
	{
		return $this->isLocal;
	}

	/****************************** SETTER ******************************/
	function setID($id)
	{
		$this->id = $id;
	}
	
	/****************************** TOOLS ******************************/

}

?>