<?php
$wsdl="http://ita-provisioner.cis.fiu.edu:8080/axis2/services/VirtualLabs?wsdl";
$location ="http://ita-provisioner.cis.fiu.edu:8080/axis2/services/VirtualLabs";

ini_set("soap.wsdl_cache_enabled", "0");

$page = $_POST['page'];
$rp = $_POST['rp'];
$sortname = $_POST['sortname'];
$sortorder = $_POST['sortorder'];

if (!$sortname) $sortname = 'name';
if (!$sortorder) $sortorder = 'desc';

$sort = "ORDER BY $sortname $sortorder";

if (!$page) $page = 1;
if (!$rp) $rp = 10;

$start = (($page-1) * $rp);

$limit = "LIMIT $start, $rp";


header("Expires: Mon, 26 Jul 1997 05:00:00 GMT" );
header("Last-Modified: " . gmdate( "D, d M Y H:i:s" ) . "GMT" );
header("Cache-Control: no-cache, must-revalidate" );
header("Pragma: no-cache" );
header("Content-type: text/x-json");

if (isset($_POST['requestingUser'])){
		$requestingUser = $_POST['requestingUser']; 
		}

try {
		
        $active = true;

        $params1 = array( 'requestingUser' => $requestingUser, 'active' => $active );

        $client=new SoapClient($wsdl,array('location'=>$location));

        $result = $client->getHostList($params1);

        $hosts = $result->host;

        $active = false;

        $params1 = array( 'requestingUser' => $requestingUser, 'active' => $active );

        $client=new SoapClient($wsdl,array('location'=>$location));

        $result2 = $client->getHostList($params1);

        $hosts2 = $result2->host;


        $results = array();
        $final_cont = 0;

        if($hosts!=null)
        {
            if(is_array($hosts))
            {
                foreach($hosts as $host)
                {
                    $results[$final_cont++]=$host;
                }

            }else
            {
                 $results[$final_cont++]=$hosts;

            }

        }



        if($hosts2!=null)
        {
            if(is_array($hosts2))
            {
                foreach($hosts2 as $host)
                {
                    $results[$final_cont++]=$host;
                }

            }else
            {
                 $results[$final_cont++]=$hosts2;
            }
        }


/*
        foreach($hosts as $host)
        {
                $results[$final_cont++]=$host;
        }

        foreach($hosts2 as $host)
        {
                $results[$final_cont++]=$host;
        }
*/
		
		
		

} catch (Exception $e) {

	echo $e->getMessage();

}catch (SoapFault $soapfault) {

	echo $soapfault->getMessage();
}


$cont = 0; 
while($results[$cont]!=null)
{
		$host = $results[$cont];
		$rows[] = array(
				"id" => $host->id,
				"cell" => array(
				$host->id,
				$host->name,
				$host->sshPort,
				$host->username,
				$host->password,
				$host->veNumCap,
				$host->veFirstFreePort,
				$host->vePortNum,
				$host->active
				
				
				)
			);
			
			$cont++;

}


$data['rows'] = $rows;

$data['page'] = $page;
$data['total'] = count($hosts);

echo json_encode($data);






?>


