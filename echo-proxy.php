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
		if (!is_null($TotalWeight)) { $this->TotalWeight = $TotalWeight; }
		if (!is_null($Items)) { $this->Items = $Items; }
		if (!is_null($Accessorials)) { $this->Accessorials = $Accessorials; }
		if (!is_null($Origin)) { $this->Origin = $Origin; }
		if (!is_null($Destination)) { $this->Destination = $Destination; }
		if (!is_null($PickupDate)) { $this->PickupDate = $PickupDate; }
		if (!is_null($ShipmentType)) { $this->ShipmentType = $ShipmentType; }
		if (!is_null($PalletQty)) { $this->PalletQty = $PalletQty; }
		if (!is_null($ReturnMultipleCarriers)) { $this->ReturnMultipleCarriers = $ReturnMultipleCarriers;
 }
		if (!is_null($SaveQuote)) { $this->SaveQuote = $SaveQuote; }
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
		if (!is_null($AccessorialId)) { $this->AccessorialId = $AccessorialId; }
		if (!is_null($Charge)) { $this->Charge = $Charge; }
		if (!is_null($PickDel)) { $this->PickDel = $PickDel; }
		if (!is_null($RateType)) { $this->RateType = $RateType; }
		if (!is_null($Description)) { $this->Description = $Description; }
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
		if (!is_null($Name)) { $this->Name = $Name; }
		if (!is_null($Address1)) { $this->Address1 = $Address1; }
		if (!is_null($Address2)) { $this->Address2 = $Address2; }
		if (!is_null($City)) { $this->City = $City; }
		if (!is_null($State)) { $this->State = $State; }
		if (!is_null($Zip)) { $this->Zip = $Zip; }
		if (!is_null($Id)) { $this->Id = $Id; }
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
  		if (!is_null($Class)) { $this->Class = $Class; }
		if (!is_null($Weight)) { $this->Weight = $Weight; }
		if (!is_null($OriginId)) { $this->OriginId = $OriginId; }
		if (!is_null($DestinationId)) { $this->DestinationId = $DestinationId; }
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
$item = new FAK(55, $_GET['weight'], null, null);
//$accessorial1 = new Accessorial(12, 0, null, null, null);
//$accessorial2 = new Accessorial(22, 0, null, null, null);
$origin = new Warehouse(null, null, null, null, null, $_GET['origin'], null);
$destination = new Warehouse(null, null, null, null, null, $_GET['destination'], null);
$shipDate = new DateTime($_GET['shipdate']);

if ($_GET['residential'] == 1) {
	$accessorial1 = new Accessorial(21, 0, null, null, null);
	$accessorial2 = new Accessorial(62, 0, null, null, null);

	$request = new Request($_GET['weight'], array($item), array($accessorial1, $accessorial2), $origin, $destination,
			$shipDate->format('Y-m-d'), "Third Party", 0, false, false);
}
else {
	$request = new Request($_GET['weight'], array($item), null, $origin, $destination,
			$shipDate->format('Y-m-d'), "Third Party", 0, false, false);
}

$echoRateRequest = new EchoRateRequest($authInfo, array($request));

$client = new SoapClient("http://services.echo.com/Quote.asmx?wsdl", array('trace' => 1));

//var_dump($client->__getFunctions());
//var_dump($client->__getTypes());
//var_dump($echoRateRequest);

$params = array(
	"echoRateRequest" => $echoRateRequest,
	);

$response = $client->GetQuote($params);

//echo "====== REQUEST HEADERS =====" . PHP_EOL;
//var_dump($client->__getLastRequestHeaders());
//echo "========= REQUEST ==========" . PHP_EOL;
//var_dump($client->__getLastRequest());
//echo "========= RESPONSE =========" . PHP_EOL;
//var_dump($response);


print $_GET['callback'] . '(' . json_encode($response) . ')';