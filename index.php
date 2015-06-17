<html>
<head>
	<link rel="stylesheet" type="text/css" href="view.css">
</head>
<body>
	<?php

	// OAuthライブラリの読み込み(事前にクローンしておく)
	require "twitteroauth/autoload.php";
	use Abraham\TwitterOAuth\TwitterOAuth;
	require_once('index.php');

	//認証
	$consumerKey = "HOGEHOGEHOGE";
	$consumerSecret = "HOGEHOGEHOGE";
	$accessToken = "HOGEHOGEHOGE";
	$accessTokenSecret = "HOGEHOGEHOGE";
	$connection = new TwitterOAuth($consumerKey, $consumerSecret, $accessToken, $accessTokenSecret);
	
	$key_word = $_GET['keyword'];//検索キーワード取得
	//$key_word = "Twitter";//for TEST
	?>

	<form method="get" action="./index.php">
		<?php echo "<input type=\"text\" name=\"keyword\" value=\"".$key_word."\" />"; 	?>
		<input type="submit" value="SHOW" />
	</form>
	
	<?php
	$oldest_id = 0;//取得した画像URLのうち，最小のIDを保存
	$media_num = 0;//取得した画像URL数
	for($i=0;$key_word!="" && $i<10 && $media_num<50 ;$i++){//適当な分だけが画像URLを取得するように
		//"max_id"=>$oldest_idで$oldest_id未満のツイートを取得
		$query = array(	"q" => $key_word,"count"=>100, "max_id"=>$oldest_id , "result_type"=>"recent", "include_entities"=>true);
		$results = $connection->get("search/tweets", $query);//ツイート取得 jsonで帰ってくる
		//var_dump($results);

		if(isset($results->statuses)){
			foreach ($results->statuses as $result) {
				$tweet_url = "\"https://twitter.com/".$result->user->screen_name."/status/".$result->id."\"";
				if(isset($result->entities->media)){
					foreach ($result->entities->media as $meida_info) {

						echo "<a href=".$tweet_url.">";	//画像リンク先
						echo "<img width =\"100\" src=\"".$meida_info->media_url."\" class=\"grow\">";	//画像表示
						echo "</a>";

						$oldest_id = $result->id;
						$media_num++;
					}	
				}
			}
		}
	}
	?>

</body>
</html>