<?php
if (file_exists("lock/lock")) return null;
file_put_contents("lock/lock","0");

setlocale( LC_ALL, 'ja_JP.UTF-8' );

$date_opo = $_POST['oponum'];
$date_my = $_POST['mynum'];
$flg_num = FALSE;

if(empty($date_opo) && empty($date_my)){
	$date_opo = $_GET['oponum'];
	$date_my = $_GET['mynum'];
	$flg_num = TRUE;
}
//if(($date_opo)) return null;
//if(empty($date_my)) return null;

datawrite("opponentdata", $date_opo);
datawrite("mypartydata", $date_my);


unlink("lock/lock");

if($flg_num){
  header('location: index.htm');
  exit();
  return TRUE;
}
return TRUE;

function datawrite($OPD, $data){
  
  try{
    if(empty($data) || 0 == (int)$data){
      return;
    }
  
    $now_datetime = date("Y/m/d H:i");
    $split_time = explode(":", $now_datetime);
    $now_datetime = $split_time[0] . ":" . (floor($split_time[1] / 5) * 5);
    
    
    $dat[0]=$data;
    $dat[1]=$now_datetime;
  
    // csvファイルをオープン
    $fp = fopen( $OPD, "r" );
    if(!$fp)return null;
  
    $olddata = null;
    $oldtime = null;
    $outputarray = array();
    $wdata = array();
    $i = 0;
    while ( $csvData = fgetcsv ( $fp ) ) {
      $wdata[$i++] = $csvData;
    }
    fclose($fp);
    unlink($OPD);
    $fp = fopen( $OPD, "w" );
    
    if($csvData[1] == $now_datetime ) {
      $wdata[$i-1] = $dat;
    }else{
      $wdata[$i]= $dat;
    }
    
    foreach ($wdata as $dd){
      fputcsv($fp, $dd);
    }
  
    fclose($fp);
  }catch(Exception $ex){
    return 10;
  }
}

