<?php

setlocale( LC_ALL, 'ja_JP.UTF-8' );
$oppdata = dataload("opponentdata");
$mydata = dataload("mypartydata");

$date_from = $_POST['from'];
$date_to = $_POST['to'];

$oppdata2 = $oppdata + array_my_fill($mydata);
$mydata2 = $mydata + array_my_fill($oppdata);
ksort($oppdata2);
ksort($mydata2);



$dataarray = array(
    "time" => array_keys  ( cutArray($mydata2,  $date_from, $date_to)),
    "myd"  => array_values( cutArray($mydata2,  $date_from, $date_to)) , 
    "opd"  => array_values( cutArray($oppdata2, $date_from, $date_to)), 
    "sa"   => array_values( cutArraySa($mydata2, $oppdata2))
);


echo json_encode($dataarray);

function array_my_fill($array, $fill = 0){
  foreach ($array as $key => $dat){
    $array[(string)$key] = $fill;
  }
  return $array;
}

function cutArray($array, $f = 700, $t = 2300){
  $olddat = 0;
  foreach ($array as $key => $dat){
    if($f > $key || $t < $key) continue;
    if($dat == 0){
      $ret[$key] =$olddat;
    }else{
      $ret[$key] = (int)$dat;
      $olddat = $dat;
    }
  }
  
  $ret2 = array();
  $olddat = 0;
  $keyval = array_reverse(array_keys($ret));
  foreach (array_reverse($ret) as $key => $dat){
    if($dat == 0){
      $ret2[$keyval[$key]] =$olddat;
    }else{
      $ret2[$keyval[$key]] = (int)$dat;
      $olddat = $dat;
    }
  }
  ksort($ret2);
  return $ret2;
}

function cutArraySa($ore, $aite){
  foreach($ore as $key => $val){
   $ret[$key] = $val - $aite[$key];
  }
return $ret;
}


function datekey($intime, $format = "Hi"){

  $ret =  date($format ,strtotime($intime));
  return (int)$ret;
}


function dataload($OPD){
  
  $now_datetime = date("Y/m/d");
  
  // csvファイルをオープン
  $fp = fopen( $OPD, "r" );
  
  $olddata = null;
  $oldtime = null;
  $outputarray = array();
  
  while ( $csvData = fgetcsv ( $fp ) ) {
    //基礎読み込み情報チェック
    $_date = explode(" ",$csvData[1]);
    if($_date[0] != $now_datetime) continue;
    if(count($csvData) != 2 ) continue;
    $dat  = $csvData[0];
    $time = $csvData[1];
    
    //初回ループ対応
    if($olddata == null){
      $olddata = $dat;
      $oldtime = $time;
      $outputarray[datekey($oldtime)] = $olddata;
      continue;
    }
    
    //次データとの差分埋め
    $trget_time = $oldtime;
    $passedcount = 0;
  
    $_tim = strtotime($time) - strtotime($trget_time);
    $passedcount = round($_tim/300);
    
    $_dat = $dat;
    if($passedcount == 0){
      $outputarray[datekey($time)] = (string)$_dat;
    }else{
      $dis = $_dat - $olddata;//差分
      $add_av = round($dis/($passedcount));//平均値取得
      $_dat = $olddata;
      for ($i = 1 ; $passedcount > $i ; $i++ ){
        $_dat = $_dat + $add_av;
        echo "";
        $outputarray[(int)date("Hi" , strtotime($oldtime)+ $i*300)] = (string)$_dat;
      }
      $outputarray[datekey($time)] = (string)$dat;
    }
  
    $olddata = $dat;
    $oldtime = $time;
  }
  
  
  return $outputarray;
}

