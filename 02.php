<?php
//ランキング情報保存
if (file_exists("lock/lock")) return null;
file_put_contents("lock/lock","0");

setlocale( LC_ALL, 'ja_JP.UTF-8' );

$list = $_GET['list'];

$list = data_set($list);

//リスト書き込み
datawrite("gild", $list);

unlink("lock/lock");

if($flg_num){
  header('location: index.htm');
  exit();
  return TRUE;
}

  
return TRUE;

//
function data_set($data){
	$id_a = explode("|,-|", $data);
	foreach($id_a as $key => $val){
		$dat = explode("|_-|", $val);
		if($dat[0] == '') continue;
	  $ret[$dat[0]] = $dat[1];
	}
	return $ret;
}

function datawrite($OPD, $data){
  
  try{


    if(empty($data)){
      return;
    }
  
    // csvファイルをオープン
    $fp = fopen( $OPD, "r" );
    if(!$fp)return null;
  
    $olddata = null;
    $oldtime = null;
    $outputarray = array();
    $wdata = array();
    $i = 0;
    while ( $csvData = fgetcsv ( $fp ) ) {
      $wdata[$csvData[0]] = $csvData[1];
    }
    fclose($fp);
    unlink($OPD);
    $fp = fopen( $OPD, "w" );
    foreach($data as $id => $name){
      $wdata[$id] = $name;
    }
    foreach ($wdata as $key => $dd){


      fputcsv($fp, array($key,$dd));
    }
    fclose($fp);
  }catch(Exception $ex){
    return 10;
  }
}

