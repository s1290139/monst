<?php
$name = "";

if (isset($_POST['send']) == true) {
    $name = $_POST["name"];
    $pattern1 = '/「(.*?)」\s*(.+)/';

    if (preg_match($pattern1, $name, $matches)){
        $questName = $matches[1];
        $url = $matches[2];

        $url = preg_replace('/\s*↑このURLをタップすると、タップした人達同士で一緒にマルチプレイができるよ！$/', '', $url);

        $fp = fopen("bord.txt", "a");
        fwrite($fp, "{$questName} {$url} " . time(). "\n");//なぜか時間がちがう
        fclose($fp);
        // 再送信阻止
        header("Location: ".$_SERVER['PHP_SELF']);
        exit;
    }
}
// テキストファイル削除
$interval = 5 * 60;
$file = 'bord.txt';
if (file_exists($file)) {
    if (time() - filemtime($file) >= $interval) {
        file_put_contents($file, '');
    }
}

$fp = fopen("bord.txt", "r");
$bord_array = [];
while ($line = fgets($fp)) {
    $temp = explode(" ", $line);
    if (!empty($temp[0])) {
        $temp_array = [
            "name" => trim($temp[0]),
            "url" => trim($temp[1]),
            "time" => trim($temp[2])
        ];

        $bord_array[] = $temp_array;
    }
}
fclose($fp);

// 逆にしてる
$bord_array = array_reverse($bord_array);

$questNames = [
    "獄炎の神殿（時の間・弐）",
    "獄炎の神殿（時の間・壱）",
    "秘泉の神殿（時の間・弐）",
    "秘泉の神殿（時の間・壱）",
    "樹縛の神殿（時の間・弐）",
    "樹縛の神殿（時の間・壱）",
    "光明の神殿（時の間・弐）",
    "光明の神殿（時の間・壱）",
    "常闇の神殿（時の間・弐）",
    "常闇の神殿（時の間・壱）",
    "黄金の神殿（特級の間）"
];

if (isset($_POST['quest_select'])) {
    $selectedQuest = $_POST['quest_select'];
    if ($selectedQuest != "all") {
        $bord_array = array_filter($bord_array, function($item) use ($selectedQuest) {
            return $item['name'] == $selectedQuest;
        });
    }
}
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>【モンスト】英雄の神殿限定掲示板</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Reggae+One&display=swap" rel="stylesheet">
</head>

<body>

<div class="monst">
    <h1>【モンスト】英雄の神殿限定掲示板</h1>
</div>

<form action="" method="post">
    <div class = "mon">
        <label for="name">募集URL</label>
        <input id="name" name="name">
    </div>
    <input type="submit" name="send" value="募集する">
</form>

<form action="" method="post">
    <div class = "mon">
        <label for="quest_select">クエスト名を選択してください:</label>
        <select name="quest_select" id="quest_select">
            <option value="all">全てのクエスト</option>
            <?php foreach ($questNames as $quest): ?>
                <option value="<?= $quest; ?>"><?= $quest; ?></option>
            <?php endforeach; ?>
        </select>
        <input type="submit" value="検索する">
    </div>
</form>

<div class = "bigmon">
<h1>募集一覧</h1>
</div>
<ul>
    <?php foreach ($bord_array as $data): ?>
        <li>
        <?php
            $questClass = '';
            switch ($data["name"]) {
                case '獄炎の神殿（時の間・弐）':
                    $questClass = 'red';
                    break;
                case '獄炎の神殿（時の間・壱）':
                    $questClass = 'red';
                    break;

                case '秘泉の神殿（時の間・弐）':
                    $questClass = 'blue';
                    break;
                case '秘泉の神殿（時の間・壱）':
                    $questClass = 'blue';
                    break;

                case '樹縛の神殿（時の間・弐）':
                    $questClass = 'green';
                    break;
                case '樹縛の神殿（時の間・壱）':
                    $questClass = 'green';
                    break;

                case '光明の神殿（時の間・弐）':
                    $questClass = 'yellow';
                    break;
                case '光明の神殿（時の間・壱）':
                    $questClass = 'yellow';
                    break;

                case '常闇の神殿（時の間・弐）':
                    $questClass = 'purple';
                    break;
                case '常闇の神殿（時の間・壱）':
                    $questClass = 'purple';
                    break;

                case '黄金の神殿（特級の間）':
                    $questClass = 'gold';
                    break;
                default:
                    $questClass = '';
                    break;
            }
            ?>
            <span class="<?php echo $questClass; ?>"></span>
            <a href="<?= $data["url"]; ?>" target="_blank"><?= $data["name"]; ?></a>
            <span class="timestamp"><?php echo date("Y-m-d H:i:s", $data["time"]); ?></span>
        </li>
    <?php endforeach; ?>
</ul>
</body>

</html>
