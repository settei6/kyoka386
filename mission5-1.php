<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8;">
    <title>mission_5-1</title>

<br>
    <br>
</head>
<body>
<?php
    
    //DB接続設定
    $dsn = 'mysql:dbname=tb******db;host=localhost';
    $user = 'tb-******';
    $password='PASSWORD';
    $pdo=new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
    //☆PDO関数：PHPでMySQLを操作するときに利用する関数


    //CREATE文：テーブル作成
    //SOL文
    $sql="CREATE TABLE IF NOT EXISTS mission500"
    //もしまだこのテーブルが存在しないなら↑
    ."("
    ."id INT AUTO_INCREMENT PRIMARY KEY,"
    //id:自動で登録されているナンバリング
    ."name char(32),"
    //name:名前を入れる。文字列(半角英数で32文字)
    ."comment TEXT,"
    //comment:コメントを入れる。文字列，長めの文章もOK
    ."date TEXT,"
    //投稿時間
    ."pass1 TEXT"
    .");";
    $stmt = $pdo->query($sql);
    

    //新規投稿機能
    //データベースに書き込む
    if(empty($_POST["secretNum"]) && !empty($_POST["name"]) && !empty($_POST["comment"]) && !empty($_POST["pass1"])){
        $name=$_POST["name"];
        $comment=$_POST["comment"];
        $date=date("Y年m月d日 H:i:s");
        $pass=$_POST["pass1"];
    
        //INSERT文：データ(レコード)を挿入 レコード：データ1件のこと
        //テーブルそれぞれに上の定数を入力
        $sql = $pdo -> prepare("INSERT INTO mission500(name, comment, date, pass1) VALUES(:name, :comment, :date, :pass1)");
        $sql -> bindParam(':name', $name, PDO::PARAM_STR);
        $sql -> bindParam(':comment', $comment, PDO::PARAM_STR);
        $sql -> bindParam(':date', $date, PDO::PARAM_STR);
        $sql -> bindParam(':pass1', $pass, PDO::PARAM_STR);
        $sql -> execute();

       //編集機能上書き 
    }/*elseif(){
        
    }*/
    
    
    //削除機能
    if(!empty($_POST["deleteNum"]) && !empty($_POST["deletePass"])){
        $deleteNum=$_POST["deleteNum"];
        $deletePass=$_POST["deletePass"];

        //SELECT文：データレコードを取得
        $sql = 'SELECT * FROM mission500';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
        
        foreach($results as $row){
            //idとpassが一致したら削除
            if($row['id'] == $deleteNum && $row['pass1'] == $deletePass){
            //DELETE文：データレコードを削除
            $id = $deleteNum;
            $sql = 'delete from mission500 where id=:id';
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            }
        }
    }
        
    
    //編集フォーム
    //送信された番号に合う書き込みの内容をフォームに表示する編集選択機能
    if(!empty($_POST["editNum"])){
        $editNum=$_POST["editNum"];
        $editPass=$_POST["editPass"];    
    
        //SELECT文：データレコードを取得
        $sql = 'SELECT * FROM mission500';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
        foreach($results as $row){
            //投稿番号と編集対象番号を比較
            //パスワードが一致した時のみ入力フォームに投稿内容を表示
            if($row['id'] == $editNum && $row['pass1'] == $editPass){
                $newNum=$row['id'];
                $editName=$row['name'];
                $editCom=$row['comment'];
                
            }
        }
    }
    
    //上書きする編集実行機能        
    if(!empty($_POST["secretNum"])){
        $id=$_POST["secretNum"];
        $name=($_POST["name"]);
        $comment=($_POST["comment"]);
        $date=date("Y年m月d日 H:i:s");
        $pass=($_POST["pass1"]);
                    
        //UPDATE文：データレコードの編集
        $sql = 'UPDATE mission500 SET name=:name,comment=:comment,date=:date WHERE id=:id';
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->bindParam(':name', $name, PDO::PARAM_STR);
        $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
        $stmt->bindParam(':date', $date, PDO::PARAM_STR);
        $stmt->execute();
    }
?>
<!--投稿-->
<form action="" method="post">
    
    <input type="text" name="name" placeholder="名前" value="<?php if(isset($editName)){echo $editName; }?>"><br>
        <input type="text" name="comment" placeholder="コメント" value="<?php if(isset($editCom)){echo $editCom; }?>"><br>
        <input type="password" name="pass1" placeholder="パスワード">
        <input type="hidden" name="secretNum"  value="<?php  if(!empty($newNum)){echo $newNum; }?>">
        
        <input type="submit" name="submit"><br>
        <br>
        <input type="text" name="deleteNum" placeholder="削除対象番号"><br>
        <input type="password" name="deletePass" placeholder="パスワード">
        <input type="submit" name="delete"><br><br>
        <input type="text" name="editNum" placeholder="編集対象番号"><br>
        <input type="password" name="editPass" placeholder="パスワード">
        <input type="submit" name="edit"><br>
</form>
<?php
//SELECT文：データレコードを取得して表示！
        $sql = 'SELECT * FROM mission500';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();
        foreach($results as $row){
            //$rowの中にはテーブルのカラム名が入る
            echo $row['id'].' ';
            echo $row['name'].' ';
            echo $row['comment'].' ';
            echo $row['date']. "<br>";
            echo "<hr>";
        }
?>
</body>
</html>