<html>
<head>
	<link rel="stylesheet" type="text/css" href="view.css">
</head>
<body>
	<?php

	// OAuthライブラリ　こちらを利用させていただく：https://github.com/abraham/twitteroauth
	require "twitteroauth/autoload.php";
	use Abraham\TwitterOAuth\TwitterOAuth;
	require_once('index.php');

	//認証
	$consumerKey = "HOGEHOGEHOGE";
	$consumerSecret = "HOGEHOGEHOGE";
	$accessToken = "HOGEHOGEHOGE";
	$accessTokenSecret = "HOGEHOGEHOGE";
	$connection = new TwitterOAuth($consumerKey, $consumerSecret, $accessToken, $accessTokenSecret);

	
	$key_word = $_GET['keyword'];//GETで渡された検索キーワード取得
	?>

	<form method="get" action="./index.php">
		<?php echo "<input type=\"text\" name=\"keyword\" value=\"".$key_word."\" />"; 	?>
		<input type="submit" value="SHOW" />
	</form>
	
	<?php
	$oldest_id = 0;//取得した画像URLのうち，最小のIDを保存
	$media_num = 0;//取得した画像URL数
	for($i=0;$key_word!="" && $i<5 && $media_num<50 ;$i++){//適当な分だけが画像URLを取得するように
		//"max_id"=>$oldest_idで$oldest_id未満のツイートを取得
		$query = array(	"q" => $key_word,"count"=>100, "max_id"=>$oldest_id , "result_type"=>"recent");
		$results = $connection->get("search/tweets", $query);//ツイート取得

		foreach ($results->statuses as $result) {
			if(isset($result->retweeted_status->entities->media[0]->media_url)){
				//画像リンク先
				echo "<a href=\"https://twitter.com/".$result->user->screen_name."/status/".$result->id."\">";
				//画像表示
				echo "<img width =\"100\" src=\"".$result->retweeted_status->entities->media[0]->media_url ."\" class=\"grow\">";
				echo "</a>";

				$oldest_id = $result->id;
				$media_num++;
			}
		}
	}
	?>

</body>
</html>