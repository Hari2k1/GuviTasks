
<?php
	//insert records to a json file
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
                    
        function get_data() {
        $uname=$_POST['username'];
        $email=$_POST['email'];
        $pwd=$_POST['password'];
        $dob=$_POST['dob'];
        $contact=$_POST['contact'];
        $age=$_POST['age'];
    
            $file_name='UserDetails'. '.json';
    
            if(file_exists("$file_name")) {
                $current_data=file_get_contents("$file_name");
                $array_data=json_decode($current_data, true);
                                
                $extra=array(
                    'Username' => $_POST['username'],
                    'Email' => $_POST['email'],
                    'Password' => $_POST['password'],
                    'DoB' => $_POST['dob'],
                    'Contact' => $_POST['contact'],
                    'Age' => $_POST['age'],
                );
                $array_data[]=$extra;
                //echo "file exist<br/>";
                return json_encode($array_data);
            }
            else {
                $datae=array();
                $datae[]=array(
                    'Username' => $_POST['username'],
                    'Email' => $_POST['email'],
                    'Password' => $_POST['password'],
                    'DoB' => $_POST['dob'],
                    'Contact' => $_POST['contact'],
                    'Age' => $_POST['age'],
                );
                return json_encode($datae);
            }
        }
    
        $file_name='UserDetails'. '.json';
        
        if(file_put_contents("$file_name", get_data())) {
        //echo "<script>alert('success')</script>";	
        $f=0;			
        
        }				
        else {
        //echo "<script>alert('There is some error')</script>";		
        $f=1;		
        }
    }
        
    ?>

<?php
require 'D:/xampp/htdocs/redis/predis/src/Autoloader.php';
Predis\Autoloader::register();
$redis= new Predis\Client([
    'scheme' =>'tcp',
    'host' =>'127.0.0.1',
    'port' =>6379,
]);
$data_cache=$redis->get('user_insert');

try{
    if($_POST && !empty($_POST['username']) && !empty($_POST['email']) && !empty($_POST['password']) && !empty($_POST['contact']) && !empty($_POST['dob']) && !empty($_POST['age']) ) 
    {
    //validate fields
    $ins= array(
        'username' => $_POST['username'],
        'email' => $_POST['email'], 
        'password' => $_POST['password'],
        'contact' => $_POST['contact'],
        'dob' => $_POST['dob'],
        'age' => $_POST['age'],
    );
    
    if($data_cache)
    {
        $temp=json_decode($data_cache,true);
        $temp[]=$ins;
        $redis->set('user_insert',json_encode($temp));
        print_r("cache exists and data saved in cache");
    }
    else
    {   
        $temp=$ins;
        $redis->set('user_insert',json_encode($temp));
    }
    //database operation
    //we can also separately use insert operation to insert records from cache to the db server and then erase the cache, regularly
    $mongo = new MongoDB\Driver\Manager("mongodb://127.0.0.1:27017");
    $bulkWrite = new MongoDB\Driver\BulkWrite;
    $bulkWrite->insert($ins);
    $mongo->executeBulkWrite('db.users', $bulkWrite);
}
else
{
    print_r("none to store");
}
}
catch(MongoConnectionException $e){
    var_dump($e);
}
?>