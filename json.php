<?php
/*
$info = '{"images":[{"time":1.1016,"status":"Complete","file":"http:\/\/i.imgur.com\/Fnuj0fj.jpg","width":730,"height":974,"image_id":"8d9a753469078b37e52b4a13dd908ac2","image_expiration":"2017-04-20 17:48 -0400","faces":[{"topLeftX":224,"topLeftY":229,"width":263,"height":263,"leftEyeCenterX":306.1875,"leftEyeCenterY":336.39166666667,"rightEyeCenterX":402.62083333333,"rightEyeCenterY":332.00833333333,"noseTipX":353.45242150025,"noseTipY":384.14149750186,"noseBtwEyesX":354.55487482491,"noseBtwEyesY":325.81806533522,"chinTipX":365.24125114878,"chinTipY":478.48613220409,"leftEyeCornerLeftX":288.17473958333,"leftEyeCornerLeftY":338.7203125,"leftEyeCornerRightX":327.35078125,"leftEyeCornerRightY":336.93958333333,"rightEyeCornerLeftX":381.59453125,"rightEyeCornerLeftY":334.47395833333,"rightEyeCornerRightX":419.26380208333,"rightEyeCornerRightY":332.76171875,"rightEarTragusX":459.19353839586,"rightEarTragusY":357.87159655115,"leftEarTragusX":243.61924972035,"leftEarTragusY":367.73028294368,"leftEyeBrowLeftX":268.45994142495,"leftEyeBrowLeftY":321.78140273045,"leftEyeBrowMiddleX":295.31556468055,"leftEyeBrowMiddleY":309.43964654389,"leftEyeBrowRightX":329.19745210052,"leftEyeBrowRightY":312.0075046329,"rightEyeBrowLeftX":382.75491903162,"rightEyeBrowLeftY":310.32952825755,"rightEyeBrowMiddleX":411.50449002497,"rightEyeBrowMiddleY":304.00657045988,"rightEyeBrowRightX":436.58714935603,"rightEyeBrowRightY":313.23434152219,"nostrilLeftHoleBottomX":340.71151835574,"nostrilLeftHoleBottomY":401.76934969514,"nostrilRightHoleBottomX":371.1718614913,"nostrilRightHoleBottomY":398.40999719303,"nostrilLeftSideX":329.46881574244,"nostrilLeftSideY":393.24488499954,"nostrilRightSideX":386.08147576687,"nostrilRightSideY":391.38373302864,"lipCornerLeftX":323.05087039561,"lipCornerLeftY":439.62230818617,"lipLineMiddleX":358.36876656448,"lipLineMiddleY":440.57098193074,"lipCornerRightX":393.41189934004,"lipCornerRightY":436.93686603532,"pitch":-1.6708985201635,"yaw":-1.4225782469006,"roll":-2.4656416693902,"attributes":{"gender":{"time":0.06738,"type":"M","confidence":"100%"}}}]}]}';
echo gettype($info);
$info = json_decode($info);
echo "<br>";
$info->images[0]->faces[0]->{"url"} = "jsut some text blaasfksaklfsdlfkjsdlfjsdlflkfjlsj";
print_r($info->images[0]->faces);
//echo $info;
echo gettype($info);
*/
//$file = file_get_contents("face.json", "r") or die("Unable to open file!");

$info = '{"topLeftX":224,"topLeftY":229,"width":263,"height":263,"leftEyeCenterX":306.1875,"leftEyeCenterY":336.39166666667,"rightEyeCenterX":402.62083333333,"rightEyeCenterY":332.00833333333,"noseTipX":353.45242150025,"noseTipY":384.14149750186,"noseBtwEyesX":354.55487482491,"noseBtwEyesY":325.81806533522,"chinTipX":365.24125114878,"chinTipY":478.48613220409,"leftEyeCornerLeftX":288.17473958333,"leftEyeCornerLeftY":338.7203125,"leftEyeCornerRightX":327.35078125,"leftEyeCornerRightY":336.93958333333,"rightEyeCornerLeftX":381.59453125,"rightEyeCornerLeftY":334.47395833333,"rightEyeCornerRightX":419.26380208333,"rightEyeCornerRightY":332.76171875,"rightEarTragusX":459.19353839586,"rightEarTragusY":357.87159655115,"leftEarTragusX":243.61924972035,"leftEarTragusY":367.73028294368,"leftEyeBrowLeftX":268.45994142495,"leftEyeBrowLeftY":321.78140273045,"leftEyeBrowMiddleX":295.31556468055,"leftEyeBrowMiddleY":309.43964654389,"leftEyeBrowRightX":329.19745210052,"leftEyeBrowRightY":312.0075046329,"rightEyeBrowLeftX":382.75491903162,"rightEyeBrowLeftY":310.32952825755,"rightEyeBrowMiddleX":411.50449002497,"rightEyeBrowMiddleY":304.00657045988,"rightEyeBrowRightX":436.58714935603,"rightEyeBrowRightY":313.23434152219,"nostrilLeftHoleBottomX":340.71151835574,"nostrilLeftHoleBottomY":401.76934969514,"nostrilRightHoleBottomX":371.1718614913,"nostrilRightHoleBottomY":398.40999719303,"nostrilLeftSideX":329.46881574244,"nostrilLeftSideY":393.24488499954,"nostrilRightSideX":386.08147576687,"nostrilRightSideY":391.38373302864,"lipCornerLeftX":323.05087039561,"lipCornerLeftY":439.62230818617,"lipLineMiddleX":358.36876656448,"lipLineMiddleY":440.57098193074,"lipCornerRightX":393.41189934004,"lipCornerRightY":436.93686603532,"pitch":-1.6708985201635,"yaw":-1.4225782469006,"roll":-2.4656416693902,"attributes":{"gender":{"time":0.46253,"type":"M","confidence":"100%"}},"url":"k.kristofer.is\/face\/tmp\/me.jpg"}';

storeFaces($info);

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
?>
