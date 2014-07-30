<?php

date_default_timezone_set('America/New_York');

/** 
 * This object stores the authentcation credientials supplied by echo
 *
 * @property string $UserId 
 * @property string $Password
 */
class AuthInfo {
	function AuthInfo($UserId, $Password) {
		$this->UserId = $UserId;
		$this->Password = $Password;
	}
}


/** 
 * This object stores the request
 *
 * @property double $TotalWeight I'm not sure what this is
 * @property array $Items The array of FAK
 * @property array $Accessorials The array of Accesorials (not sure what this is)
 * @property Warehouse $Origin I'm not sure what this is
 * @property Warehouse $Destination I'm not sure what this is
 * @property datetime $PickupDate I'm not sure what this is
 * @property string $ShipmentType I'm not sure what this is
 * @property int $PalletQty I'm not sure what this is
 * @property boolean $ReturnMultipleCarriers 
 * @property boolean $SaveQuote
 */
class Request {
	function Request($TotalWeight, $Items, $Accessorials, $Origin, $Destination, 
			$PickupDate, $ShipmentType, $PalletQty, $ReturnMultipleCarriers, $SaveQuote) {
		$this->TotalWeight = $TotalWeight;
		$this->Items = $Items;
		$this->Accessorials = $Accessorials;
		$this->Origin = $Origin;
		$this->Destination = $Destination;
		$this->PickupDate = $PickupDate;
		$this->ShipmentType = $ShipmentType;
		$this->PalletQty = $PalletQty;
		$this->ReturnMultipleCarriers = $ReturnMultipleCarriers;
		$this->SaveQuote = $SaveQuote;
	} 

}

/** 
 * This object does something I'm not sure what
 *
 * @property int $AccessorialId
 * @property double $Charge I'm not sure what this is
 * @property string $PickDel I'm not sure what this is
 * @property string $RateType I'm not sure what this is
 * @property string $Description I'm not sure what this is
 */
class Accessorial {
	function Accessorial($AccessorialId, $Charge, $PickDel, $RateType, $Description) {
		$this->AccessorialId = $AccessorialId;
		$this->Charge = $Charge;
		$this->PickDel = $PickDel;
		$this->RateType = $RateType;
		$this->Description = $Description;
	}
}


/** 
 * This object does something I'm not sure what
 *
 * @property string $Name I'm not sure what this is
 * @property string $Address1 I'm not sure what this is
 * @property string $Address2 I'm not sure what this is
 * @property string $City I'm not sure what this is
 * @property string $State I'm not sure what this is
 * @property string $Zip I'm not sure what this is
 * @property string $Id I'm not sure what this is
 */
class Warehouse {
	function Warehouse($Name, $Address1, $Address2, $City, $State, $Zip, $Id) {
		$this->Name = $Name;
		$this->Address1 = $Address1;
		$this->Address2 = $Address2;
		$this->City = $City;
		$this->State = $State;
		$this->Zip = $Zip;
		$this->Id = $Id;
	}
}


/** 
 * This object stores the object to ship
 *
 * @property double $Class I'm not sure what this is
 * @property double $Weight I'm not sure what this is
 * @property string $OriginId I'm not sure what this is
 * @property string $DestinationId I'm not sure what this is
 */
class FAK {
  	function FAK($Class, $Weight, $OriginId, $DestinationId) {
  		$this->Class = $Class;
		$this->Weight = $Weight;
		$this->OriginId = $OriginId;
		$this->DestinationId = $DestinationId;
	}
}


/** 
 * This object is sent to GetQuote method
 *
 * @property AuthInfo $AuthInfo I
 * @property array(Requests) $Requests Array of Requests
 */
class EchoRateRequest {
	function EchoRateRequest($AuthInfo, $Requests) {
		$this->AuthInfo = $AuthInfo;
		$this->Requests = $Requests; 
	}
}

$authInfo = new AuthInfo("ER107135", "Echo7135");
$item = new FAK(70, 1000, null, null);
$accessorial1 = new Accessorial(12, 0, null, null, null);
$accessorial2 = new Accessorial(22, 0, null, null, null);
$origin = new Warehouse(null, null, null, null, null, "91101", null);
$destination = new Warehouse(null, null, null, null, null, "60425", null);

$request = new Request(1000, array($item), array($accessorial1, $accessorial2), $origin, $destination, 
			new DateTime("2014-08-11"), "Third Party", 0, false, false);


$echoRateRequest = new EchoRateRequest($authInfo, array($request));

$client = new SoapClient("https://services.echo.com/Quote.asmx?wsdl");

//var_dump($client->__getFunctions());
//var_dump($client->__getTypes());

var_dump($echoRateRequest);

$params = array(
	"echoRateRequest" => $echoRateRequest,
	);

$response = $client->GetQuote($params);

var_dump($response);