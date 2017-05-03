<?php
try
{
	$headers = array();
	$server_ip = 'https://animetrics.p.mashape.com/v2/detect';

	//$animetrics_api_key ='82b01a6b82e95bab8a945305548204b7';

	//$animetrics_api_key ='b744236540f6259a956ed185cc9ff84e';
	$animetrics_api_key ='qtJ4kDhzt1mshJEFV36QM9FpgkM7p1rMzcCjsnvAa89uKAzNLb';
	$path_to_local_file = "i.imgur.com/Fnuj0fj.jpg";

	$data = array('api_key'=>$animetrics_api_key,'selector'=>'FULL','url'=>'@'.$path_to_local_file);

	$ch = curl_init();

	curl_setopt($ch, CURLOPT_URL, $server_ip);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
	$response = curl_exec($ch);

	echo $response;

	$json = json_decode($response);

	if (empty($json) || sizeof($json->images) < 1 || sizeof($json->images[0]->faces) < 1)
		throw new Exception('No faces found in response!');

	$image_id = $json->images[0]->image_id;

	// {...} store $image_id locally

	$topLeftX = $json->images[0]->faces[0]->topLeftX;
	$topLeftY = $json->images[0]->faces[0]->topLeftY;
	$width    = $json->images[0]->faces[0]->width;
	$height   = $json->images[0]->faces[0]->height;

	echo "Face found with imageid $image_id at ($topLeftX, $topLeftY, $width, $height)" . PHP_EOL;
}
catch(Exception $e)
{
	echo $e->getMessage() . PHP_EOL;
}
         ?>
