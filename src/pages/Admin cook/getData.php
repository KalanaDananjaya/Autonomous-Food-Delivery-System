<?php

require_once('connection.php');

/*************************************    Main Code  ************************************************************** */
$tocook=array();
$toDeliver=array();
$deliveringIDs=array();

//**queued means the food is queued for delivery on the robot,not just added to the to deliver list

//showcook=0 and queued=0 and delivered=0 means the item is shown in the to cook list
//showcook=1 and queued=0 and delivered=0 means the item is finished preparing,removed from cook list and added to to deliver list
//showcook=1 and queued=1 and delivered=0 means the delivery has started 
//showcook=1 and queued=1 and delivered=1 means delivery is finished.So it should be removed from both lists.

$toCookQuery= "SELECT * from user_info WHERE showcook=0 ORDER BY id";
$toDeliverQuery="SELECT * from user_info WHERE showcook=1 and queued=0 ORDER BY id";
$deliveringIDsQuery="SELECT  * from user_info WHERE showcook=1 and queued=1 and delivered=0 ORDER BY id";


    $toCookResponse = mysqli_query($connect,$toCookQuery);
    $toDeliverResponse=mysqli_query($connect,$toDeliverQuery);
    $deliveringIDsResponse=mysqli_query($connect,$deliveringIDsQuery);
    //This part queries the database for food items which have been ordered and put them in the to cook list
    if($toCookResponse){
        while($row=mysqli_fetch_array($toCookResponse)){
            $name= $row['name'];
            $id = $row['id'];
            $tableno = $row['tableno'];
            $food = $row['food'];
            $tocook[] = array('name'=>$name,'id'=>$id, 'tableno' => $tableno, 'food'=>$food);
        }
    }

    //This part queries the database for food items which have been prepared and to be delivered and put them in the to deliver list
    if($toDeliverResponse){
        while ($row=mysqli_fetch_array($toDeliverResponse)) {
            $name= $row['name'];
            $id = $row['id'];
            $tableno = $row['tableno'];
            $food = $row['food'];
            $toDeliver[]= array('name'=>$name,'id'=>$id, 'tableno' => $tableno,'food'=>$food);
        }
    }

    //This part queries the database for food items which have been prepared and being delivered at the moment and put them in the to deliveringIDs list
    if($toDeliverResponse){
        while ($row=mysqli_fetch_array($deliveringIDsResponse)) { 
            $id = $row['id'];
            $deliveringIDs[]= array('id'=>$id);
        }
    }


/********************************** Main Code Finish  **********************************************/
    

//This function travese through a nested array until it gets an array element which has 'id'==$id and returns its index    
function searchForId($id, $array) {
   foreach ($array as $key => $val) {
       if ($val['id'] == $id) {
           return $key;
       }
   }
   return null;
}

/***********************************Handling HTTP Requests******************************************************** */


//This part is called when prepared button is clicked.All the clicked list items in the cook list raises a GET['element']request.
//When that request is called the element is marked as prepared and to deliver(showcook=1 and queued=0) in the database
//element is removed from the cook list and added to the deliver list
if( isset($_GET["element"])){
    $element=$_GET["element"];
    $element = str_replace("<pre>","",$element);
    echo $element;

    $sqlElement=mysql_real_escape_string($element);

    $updateCookArrayQuery= "UPDATE user_info SET showcook=1 WHERE id='$sqlElement'";
    $updateCookArray=mysqli_query($connect,$updateCookArrayQuery);

    if ($connect->query($updateCookArrayQuery)===TRUE) {
        echo "data updated succesfully";
       // ;
    }
    else{
        echo "error". $connect->error;
    }
    $connect->close();

    array_push($toDeliver,$element);
    $removingElementKey=searchForId($element,$tocook);
    echo $removingElementKey;
    unset($tocook[$removingElementKey]);
    $tocook = array_values($tocook);
    echo '<pre>'; print_r($tocook); echo '</pre>';
    echo '<pre>'; print_r($toDeliver); echo '</pre>';

}

//This part happens if reset button is clicked
//All the element in to deliver list is reset to showcook=0 at database
//element is removed from to deliver list and added to cook list
if(isset($_GET["reset"])){
    foreach ($toDeliver as $value) {
        
        $id=mysql_real_escape_string($value['id']);
        echo $id;
        $ResetQuery="UPDATE user_info SET showcook=0 and queued=0 WHERE id='$id'";
        $ResetResult = mysqli_query($connect,$ResetQuery);
        
        if ($connect->query($ResetQuery)===TRUE) {
            ;
        //echo "data updated succesfully";
        }
        else{
            echo "error". $connect->error;
        }
        

    }
    $connect->close();
}






if(isset($_GET["deliverylist"])){
    require("path.php");
    $robotDeliveryQueue=array();
    foreach($toDeliver as $value){                  //iterate through the $toDeliver list set their status as queued=1,add to robotDeliveryQueue and remove from toDeliver array
        $id=mysql_real_escape_string($value['id']);
        $sendToRobotQuery="UPDATE user_info SET queued=1 WHERE id='$id'";
        $sendToRobotResponse=mysqli_query($connect,$sendToRobotQuery);


        if ($connect->query($sendToRobotQuery)===TRUE) {
            ;
        //echo "robot delivery queue updated succesfully";
        }
        else{
            echo "error". $connect->error;
        }
        array_push($robotDeliveryQueue,$value['tableno']);  //push the table number of the element to the robotDeliveryQueue
        global $deliveringIDs;
        array_push($deliveringIDs,$value['id']);
        /*echo "delivering IDS are\n ";
        print_r($deliveringIDs);*/
        $removingElementKey=searchForId($value['id'],$toDeliver);

        //echo "removing".$removingElementKey."from robotDeliverQueue" ;
        unset($toDeliver[$removingElementKey]);     //remove the element from toDeliver queue and rearrange the array
        $toDeliver = array_values($toDeliver);

        
    }
    $connect->close();
    
    /*
    echo "going through robot deliver queue";
    echo print_r($robotDeliveryQueue);
    */

    //Call the path calculation for the table No.s in robotDeliveryQueue
    $List = $robotDeliveryQueue;
    sort($List);
    $route = Pathfinder($List,$_distArr);
    $directionList = directionFinder($data,$route);
    
}
    
   
if(isset($_GET["delivered"])){
    global $deliveringIDs;
    if(!empty($deliveringIDs)){
        echo "food delivered to";
        print_r($deliveringIDs);
        foreach($deliveringIDs as $value){

            $id=mysql_real_escape_string($value['id']);
            $deliveryCompleteQuery="UPDATE user_info SET delivered=1 WHERE id='$id'";
            $deliveryCompleteResponse=mysqli_query($connect,$deliveryCompleteQuery);


        if ($connect->query($deliveryCompleteQuery)===TRUE) {
            ;
           
        }
        else{
            echo "error". $connect->error;
        }

            $removingElementKey=searchForId($value['id'],$deliveringIDs);
            unset($deliveringIDs[$removingElementKey]);
        }
        
        exit();
    }
    else{
        echo "";
        exit();
    }
}


?>