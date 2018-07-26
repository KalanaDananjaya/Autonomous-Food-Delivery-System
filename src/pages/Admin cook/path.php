<?php


//*********************************  set the distance array  *****************************
$_distArr = array();
$_distArr[0][9] = 1;
$_distArr[1][9] = 1;
$_distArr[2][10] = 1;
$_distArr[3][13] = 1;
$_distArr[4][13] = 1;
$_distArr[5][14] = 1;
$_distArr[6][17] = 1;
$_distArr[7][17] = 1;
$_distArr[8][18] = 1;
$_distArr[9][0] = 1;
$_distArr[9][1] = 1;
$_distArr[9][11] = 1;
$_distArr[10][2] = 1;
$_distArr[10][12] = 1;
$_distArr[11][9] = 1;
$_distArr[11][12] = 1;
$_distArr[11][13] = 1;
$_distArr[12][10] = 1;
$_distArr[12][11] = 1;
$_distArr[12][14] = 1;
$_distArr[13][3] = 1;
$_distArr[13][11] = 1;
$_distArr[13][4] = 1;
$_distArr[13][15] = 1;
$_distArr[14][4] = 1;
$_distArr[14][12] = 1;
$_distArr[14][5] = 1;
$_distArr[14][16] = 1;
$_distArr[15][13] = 1;
$_distArr[15][16] = 1;
$_distArr[15][17] = 1;
$_distArr[16][15] = 1;
$_distArr[16][14] = 1;
$_distArr[16][18] = 1;
$_distArr[17][6] = 1;
$_distArr[17][15] = 1;
$_distArr[17][7] = 1;
$_distArr[18][7] = 1;
$_distArr[18][16] = 1;
$_distArr[18][8] = 1;


//********************************************** function Dijikstra ******************************************

function Dijkstra($_distArr, $source, $vertex){
$S = array();//the nearest path with its parent and weight
$Q = array();//the left nodes without the nearest path
foreach(array_keys($_distArr) as $val) $Q[$val] = 99999;
$Q[$source] = 0;

//start calculating
while(!empty($Q)){
    $min = array_search(min($Q), $Q);//the most min weight
    if($min == $vertex) break;
    foreach($_distArr[$min] as $key=>$val) if(!empty($Q[$key]) && $Q[$min] + $val < $Q[$key]) {
        $Q[$key] = $Q[$min] + $val;
        $S[$key] = array($min, $Q[$key]);
    }
    unset($Q[$min]);
}

//list the path
$path = array();
$pos = $vertex;
while($pos != $source){
    $path[] = $pos;
    $pos = $S[$pos][0];
}
$path[] = $source;
$path = array_reverse($path);
$length = $S[$vertex][1];
$Output = array($length,$path,$vertex);
return $Output;
}



//************************************* function Pathfinder ***********************************************


function Pathfinder($List,$_distArr){
	if(!empty($List)){
		$source = 0;
		$path = array($source);

		while (count($List) > 0) {
			$distance_path = array();
			foreach ($List as $i) {
				$details = Dijkstra($_distArr, $source, $i);
				array_push($distance_path,$details);
			}
			/*
			echo "distance_path:";
			echo "<pre>";
			print_r($distance_path);
			echo "</pre>";
			*/

			do
		{
			$swapped = false;
			for( $i = 0, $c = count( $distance_path ) - 1; $i < $c; $i++ )
			{
				if( $distance_path[$i][0] > $distance_path[$i + 1][0] )
				{
					list( $distance_path[$i + 1], $distance_path[$i] ) =
							array( $distance_path[$i], $distance_path[$i + 1] );
					$swapped = true;
				}
			}
		}
		while( $swapped );
			$path = array_merge($path, Slice($distance_path[0][1]));
			$source = $distance_path[0][1][count($distance_path[0][1]) - 1];
			unset($List[array_search($source, $List)]);
		}

		$returnToCook = Dijkstra($_distArr,end($path),0);
		$path = array_merge($path, Slice($returnToCook[1]));
		/*
		echo "returnToCook:";
		echo "<pre>";
		print_r($returnToCook);
		echo "</pre>";

		echo "path:";
		echo "<pre>";
		print_r($path);
		echo "</pre>";

		*/
		return $path;
	}
	else{
		echo "ZZZ";
		exit();
	}
}

//****************************************** $data *************************************************************


$data = array(
        array(array(9,-1,-1,-1,9)),
        array(array(9,-1,10,-1,9), array(10,-1,9,-1,10)),
        array(array(10,-1,-1,-1,10)),
        array(array(13,-1,-1,-1,13)),
        array(array(13,-1,14,-1,13),array(14,-1,13,-1,14)),
        array(array(14,-1,-1,-1,14)),
        array(array(17,-1,-1,-1,17)),
        array(array(17,-1,18,-1,17),array(18,-1,17,-1,18)),
        array(array(18,-1,-1,-1,18)),
        array(array(0,-1,1,11,0), array(1,11,0,-1,1),array(11,0,-1,1,11)),
        array(array(1,-1,2,12,1), array(2,12,1,-1,2), array(12,1,-1,2,12)),
        array(array(9,12,13,-1,9),array(12,13,-1,9,12),array(13,-1,9,12,13)),
        array(array(10,-1,14,11,10), array(11,10,-1,14,11), array(14,11,10,-1,14)),
        array(array(3,11,4,15,3), array(4,15,3,11,4), array(11,4,15,3,11),array(15,3,11,4,15)),
        array(array(4,12,5,16,4),array(5,16,4,12,5),array(12,5,16,4,12),array(16,4,12,5,16)),
        array(array(13,16,17,-1,13), array(16,17,-1,13,16), array(17,-1,13,16,17)),
        array(array(14,-1,18,15,14), array(15,14,-1,18,15), array(18,15,14,-1,18)),
        array(array(6,15,7,-1,6), array(7,-1,6,15,7), array(15,7,-1,6,15)),
        array(array(7,16,8,-1,7),array(8,-1,7,16,8),array(16,8,-1,7,16))
    );


//******************************************* function directionFinder ************************************************

function directionFinder($data,$route){
	$direction = array();

        for ($i=1; $i < count($route) - 1; $i++){
        $k = $route[$i - 1];
        $l = $route[$i + 1];


        	for ($j = 0; $j < count($data[$route[$i]]); $j++){

            	if ($data[$route[$i]][$j][0] == $k){

	                if($l == $data[$route[$i]][$j][1]){

	                	array_push($direction, "L");
	                    //$direction[count($direction)+1] = "L";
	                    //$direction[count($direction)+1] = "F";
	                }
	                else if($l == $data[$route[$i]][$j][2]){
						array_push($direction, "F");
	                    //$direction[count($direction)+1] = "F";
	                }
	                else if($l == $data[$route[$i]][$j][3]){
						array_push($direction, "R");
	                    //$direction[count($direction)+1] = "R";
	                    //$direction[count($direction)+1] = "F";
	                }
	                else if($l == $data[$route[$i]][$j][4]){
						array_push($direction, "W");
						array_push($direction, "B");
	                    //$direction[count($direction)+1] = "B";
	                    //$direction[count($direction)+1] = "F";
	                }
				}
			}
		}
		/*
	    echo "Directions:";
		echo "<pre>";
		print_r($direction);
		echo "</pre>";
		*/
		$toSendList = array($direction[0]);
		for ($i=1; $i < count($direction) ; $i++) { 
			if ($direction[$i] == "F") {
				if ($direction[$i - 1] == "F") {
					array_push($toSendList,"S");
				}
			}
        array_push($toSendList, $direction[$i]);
    }
        $mystring="";
        foreach ($toSendList as $key) {
           $mystring=$mystring.$key;
		}
		//print_r($direction);
        echo $mystring;
        exit();
        
}


//*****************************  function Slice ******************************************

function Slice($List){
	for ($i=0; $i < (count($List) - 1); $i++) { 
		$List[($i)] = $List[$i+1];
	}
	unset($List[count($List)-1]);
	return $List;
}

//**************************************** calling functions ***********************************************

/* Calling functions is done in getData.php  when a GET request with parameter 'deliverylist' is sent to it*/


/*//$List = $_GET['number'];
$List = array(2,4,11,18);
sort($List);
$route = Pathfinder($List,$_distArr);
$directionList = directionFinder($data,$route);
echo $directionList;
*/

?>