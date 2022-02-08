<?php

require 'D:/xampp/htdocs/redis/predis/src/Autoloader.php';
Predis\Autoloader::register();
$redis= new Predis\Client([
    'scheme' =>'tcp',
    'host' =>'127.0.0.1',
    'port' =>6379,
]);
$data_cache=$redis->get('users');

if($data_cache)
{
    //display result
    echo "From Redis Cache";
    echo $data_cache;
}
else{
    //connect db and create key ,cache key
try{

$mongo = new MongoDB\Driver\Manager("mongodb://127.0.0.1:27017");
$filter  = [];
$options = [];
#constructing the querry
$query = new MongoDB\Driver\Query($filter, $options);
#executing
$cursor = $mongo->executeQuery('db.users', $query);
//echo "dumping results<br>";
$temp='';
foreach ($cursor as $document) 
{   
    $document = json_decode(json_encode($document),true);
    echo $document['username']."\n".$document['email']."\n".$document['password']."\n".$document['age']."\n".$document['dob']."\n".$document['contact'];
    $temp.= $document['username'].'  '.$document['email'].'  '.$document['password'].'  '.$document['age'].'  '.$document['dob'].'  '.$document['contact']. '<br>';
}
$redis->set('users',$temp);
$redis->expire('users',10); //keys will expire after 10secs , in order to avoid stale data
}
catch(MongoConnectionException $e){
    var_dump($e);
}

}
?>