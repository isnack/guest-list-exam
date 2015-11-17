<?php
require 'vendor/autoload.php';
require 'database/ConnectionFactory.php';
require 'guests/GuestsServices.php';

$app = new \Slim\Slim();


$app->get('/', function() use ( $app ) {
    
    
});


$app->get('/guests/', function() use ( $app ) {
   $guests = GuestsServices::getGuests(); 
   $app->response()->header('Content-Type', 'application/json');
   echo json_encode($guests);
   
});

$app->post('/guests/', function() use ( $app ) {
    //recupera o corpo
    $Json = $app->request()->getBody();
    //decodifica o json
    $newGuest = json_decode($Json, true);
    $teste = array(
        "name"=>$newGuest["name"],
        "email"=>$newGuest["email"]
        );
    if($teste){
        
        $guest=GuestsServices::add($teste);
        $newGuestAdd = GuestsServices::getGuestById($guest["id"]);
        $app->response()->header('Content-Type', 'application/json');
        echo json_encode($newGuestAdd);
        
        
    }else{
        $app->response->setStatus(400);
        echo "Malformat JSON";
    }
   
});

$app->delete('/guests/:id', function($id) use ( $app ) {
    if(GuestsServices::deleteGuest($id)) {
      $response = array(
          "status"=> "true",
          "message"=> "Guest deleted!"
          );
         $app->response()->header('Content-Type', 'application/json');
        echo  json_encode($response);
      
    }
    else {
      $app->response->setStatus('404');
         $response = array(
          "status"=> "false",
          "message"=> "Guest with ".$id." does not exit"
          );
        echo  json_encode($response);
    }
});

$app->run();

?>