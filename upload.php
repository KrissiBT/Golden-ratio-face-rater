<?php

//=========================================================== Uplaod =========================================
//getinfo("boobs");

move_uploaded_file(

    // this is where the file is temporarily stored on the server when uploaded
    // do not change this
    $_FILES['file']['tmp_name'],

    // this is where you want to put the file and what you want to name it

    // and giving it the original filename
    'tmp/' . $_FILES['file']['name']
);
    if ( 0 < $_FILES['file']['error'] ) {
        echo 'Error: ' . $_FILES['file']['error'] . '<br>';
    }
    else{
      #echo $_FILES;
        move_uploaded_file($_FILES['file']['tmp_name'], 'tmp/' . $_FILES['file']['name']);

        $path = "k.kristofer.is/face/tmp/".$_FILES['file']['name'];

        $info = getinfo($path);
        //======================================= IF there is no face ================
        if (sizeof($info->images[0]->faces) < 1) {
          echo  $info->images[0]->faces[0];
          echo "failed";
          unlink('tmp/' . $_FILES['file']['name']);
        }
        else {
          //================= If there is a Face IN pic==============
          $info = json_decode(json_encode($info));
          //echo $arr;
          $info->images[0]->faces[0]->{"url"} = $path;
          $info = json_encode($info->images[0]->faces[0]);
          storeFaces($info);
          echo $info;
          //echo print_r($info->images[0]->faces[0]);

        }
    }


    //========================================================================== Store data in json =================================
    function storeFaces($api_output) {
      // Lets assume following is the return form the API
      $data = json_decode($api_output);
      // Stored data
      $stored = file_get_contents('face.json', 'r');
      // decode stored
      $decoded = json_decode($stored);

      if ( ! $decoded
          	OR ! is_object($decoded)
          	OR ! property_exists($decoded, 'faces')
          	OR ! is_array($decoded->faces)) {
        die('U dun goofd');
      }

      // Assuming $stored looks like so: {"faces": []}
      $decoded->faces[] = $data;

      // Now encode the new object
      $newJson = json_encode($decoded);

      // And write back to file
      file_put_contents('face.json', $newJson);
    }

    //========================================= Rate FACE=============================================================================


    function getinfo($url)
    {
      try
      {
      	$headers = array();
      	$server_ip = 'http://23.21.173.192/v2/detect';

      	//$animetrics_api_key ='82b01a6b82e95bab8a945305548204b7';
        $animetrics_api_key ='bc5d97f6aa48b6fa101b0844ba775914';
      	//$animetrics_api_key ='b744236540f6259a956ed185cc9ff84e';

      	//$path_to_local_file = "i.imgur.com/Fnuj0fj.jpg";
        $path_to_local_file = $url;

      	$data = array('api_key'=>$animetrics_api_key,'selector'=>'FULL','url'=>'@'.$path_to_local_file);

      	$ch = curl_init();

      	curl_setopt($ch, CURLOPT_URL, $server_ip);
      	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
      	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
      	curl_setopt($ch, CURLOPT_POST, true);
      	curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
      	curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
      	$response = curl_exec($ch);



      	$json = json_decode($response);
        return $json;
      	if (empty($json) || sizeof($json->images) < 1 || sizeof($json->images[0]->faces) < 1)
      		throw new Exception('No faces found in response!');




      	$image_id = $json->images[0]->image_id;

      	// {...} store $image_id locally

      	$topLeftX = $json->images[0]->faces[0]->topLeftX;
      	$topLeftY = $json->images[0]->faces[0]->topLeftY;
      	$width    = $json->images[0]->faces[0]->width;
      	$height   = $json->images[0]->faces[0]->height;

      	//echo "Face found with imageid $image_id at ($topLeftX, $topLeftY, $width, $height)" . PHP_EOL;
      }
      catch(Exception $e)
      {
      	echo $e->getMessage() . PHP_EOL;
        return "fail";
      }

    }

?>
