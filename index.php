<!DOCTYPE HTML>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title>Highcharts Example</title>

		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
		<style type="text/css">
${demo.css}
		</style>
		<script type="text/javascript">


$(function() {

  $(document).ready(function(){
    line(0700,2330);
  });
  
	$("#save").click(function(){
	  
	  $.ajax({
      type: 'POST',
      url: './01.php',
      data: {"oponum":$('#op').val(),"mynum":$('#my').val()},
      dataType: 'json',
      async : false,
      success: function(obj) {
	line($('#from').val(),$('#to').val());
      }
	  });
	  
	});
	

  $("#serchb").click(function(){
    line($('#from').val(),$('#to').val());
  });
	
  line = function(from,to){
    $.ajax({
      type: 'POST',
      url: './00.php',
      data: {"from":from,"to":to},
      dataType: 'json',
      async : false,
      success: function(obj) {
        $('#container').highcharts({
          title: {
              text: 'メモしたい気持ちだけはあった',
              x: -20 //center
          },
          xAxis: {
              categories: obj.time
          },
          yAxis: {
              title: {
                  text: '貢献度'
              },
              plotLines: [{
                  value: 0,
                  width: 1,
                  color: '#808080'
              }]
          },
          tooltip: {
              valueSuffix: ''
          },
          legend: {
              layout: 'vertical',
              align: 'right',
              verticalAlign: 'middle',
              borderWidth: 0
          },
          series: [{
              name: '僕たち私たち',
              data: obj.myd
          }, {
              name: '相手騎空団',
              data: obj.opd
          }, {
              name: '相手との差',
              data: obj.sa
          }]
        });
      }
    });
  };
	
});

		</script>
	</head>
	<body>

<div id="input" style="min-width: 310px;  margin:20px " >
　これは霧クマ艦隊せんよう貢献度メモ帳です：<br>
貢献度を入れると五分ごとの情報として記録しグラフで相手との差とかがなんとなくわかったりします。<br>
定期的に記録しないとあまり意味のないグラフになっちゃいますので、暇な方は思い出したときにでも記入していただけるとハッピーになれたりします
<br><br>

自分達の貢献度：<input type="text" title="半角でイれて" value="" id="my" name="my" class="highcharts-title"><br>
相手共の貢献度：<input type="text" title="半角でイれて" value="" id="op" name="op" class="highcharts-title"><br>
<input type="button" title="信頼度が低いボタン" value="登録するという気持ちをサーバーに送ったりするがたまに失敗するボタン" id="save" name="save">
</div>
<div id="serch" style="min-width: 310px; margin:20px ">
表示絞り込みします　<B>format: 午前七時ちょうどの場合「0700」、午後十一時半の場合「2330」</B><br>
from<input type="text" title="半角でイれて" value="0700" id="from" name="from">　～　to<input type="text" title="半角でイれて" value="2330" id="to" name="to"><br>
<input type="button" title="絞り込みするんだ" value="フォーマットの通りに入れないと、次のガチャが青くなる絞り込みボタン" id="serchb" name="serchb">
</div>

<script src="./js/highcharts.js"></script>
<script src="./js/modules/exporting.js"></script>


<div id="container" style="min-width: 310px; height: 600px; margin: 0 auto"></div>

	</body>
</html>
