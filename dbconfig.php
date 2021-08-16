<?php
 class myDBC
    {
   public $server = 'localhost';
        public $user = 'zeyxazzaka';
        public $passwd = 'SZN2Qzn6rd';
        public $db = 'zeyxazzaka';

        public $dbCon;
       
        function __construct(){
            $this->dbCon = mysqli_connect($this->server, $this->user, $this->passwd, $this->db);
        }
       
        function __destruct(){
            mysqli_close($this->dbCon);
        }
       
    function MYQuery($Query)
    {
$result = mysqli_query($this->dbCon, $Query);
$row=mysqli_fetch_object($result);
       return $row;
    }
   
    function SingleQuery($Query)
    {
$result = mysqli_query($this->dbCon, $Query);
return $result;
    }
   
}
?>


