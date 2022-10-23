<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>mission5-1</title>
</head>
<body>
    
    <?php
        //変数定義フェーズ（p_はPOSTのP、受け取った値を入れる変数の頭文字）

        $date = date("Y/m/d H:i:s");                    //日付
        $edit_name = "";                                //編集対象ネーム
        $edit_comment = "";                             //編集対象コメント）
        $passward = "kazuki";                           //パスワード
        
        if(isset($_POST["name"])){                      //ネーム
            $p_name = $_POST["name"];
        }else{
            $p_name = "";
        }
        
        if(isset($_POST["comment"])){                   //コメント
            $p_comment = $_POST["comment"];
        }else{
            $p_comment = "";
        }
        
        if(isset($_POST["delete_number"])){                    //投稿番号
            $p_delete_number = $_POST["delete_number"];
        }else{
            $p_delete_number = "";
        }
        
        if(isset($_POST["edit_number"])){               //編集対象投稿番号
            $p_edit_number = $_POST["edit_number"];
        }else{
            $p_edit_number = "";
        }
        
        if(isset($_POST["password"])){                  //パスワード
            $p_password = $_POST["password"];
        }else{
            $p_password = "";
        }


        // DB接続設定・テーブル作成フェーズ
        $dsn = 'データベース名';
        $user = 'ユーザー名';
        $password = 'パスワード';
        $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
        $sql = "CREATE TABLE IF NOT EXISTS tbtest"
        ." ("
        . "id INT AUTO_INCREMENT PRIMARY KEY,"
        . "name char(32),"                                  
        . "comment TEXT"
        .");";
        $stmt = $pdo->query($sql);

    ?>



    <!--入力フォームフェーズ-->
    
    <form action="" method="post">

        <!--情報入力用フォーム-->
        <input type="text" name="name" placeholder="名前"><br>
        
        <!--コメント入力用フォーム-->
        <input type="text" name="comment" placeholder="コメント">
        <input type="submit" name="submit" value="送信"><br><br>
        
        <!--編集番号入力用フォーム-->
        <input type="number" name="edit_number" placeholder="投稿番号" >
        <input type="submit" name="edit" value="編集"><br>
        
        <!--削除番号入力用フォーム-->
        <input type="number" name="delete_number" placeholder="投稿番号">
        <input type="submit" name="delete" value="削除"><br><br>
        
        <!--パスワード入力用フォーム-->
        <input type="text" name="password" placeholder="パスワード">

    </form>



    <?php

        //データ送信フェーズ

            //名前とコメント、パスワードが書きこまれた場合（入力フェーズ）
         if($p_name != "" && $p_comment != "" && $p_delete_number == "" && $p_edit_number == "" && $p_password == $passward){
            $sql = $pdo -> prepare("INSERT INTO tbtest (name, comment) VALUES (:name, :comment)");      
            $sql -> bindParam(':name', $sql_name, PDO::PARAM_STR);      
            $sql -> bindParam(':comment', $sql_comment, PDO::PARAM_STR);
            $sql_name = $p_name;
            $sql_comment = $p_comment;
            $sql -> execute();

            //編集対象番号、名前、コメント、パスワードが入力された場合（編集フェーズ）
         }else if($p_name != "" && $p_comment != "" && $p_delete_number == "" && $p_edit_number != "" && $p_password == $passward){
            $id = $p_edit_number;
            $name = $p_name;
            $comment = $p_comment; 
            $sql = 'UPDATE tbtest SET name=:name,comment=:comment WHERE id=:id';
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':name', $name, PDO::PARAM_STR);
            $stmt->bindParam(':comment', $comment, PDO::PARAM_STR);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();

            //削除番号,パスワードのみが書き込まれた場合（削除フェーズ）
         }else if($p_name == "" && $p_comment == "" && $p_delete_number != "" && $p_edit_number == "" && $p_password == $passward){
            $id = $p_delete_number;
            $sql = 'delete from tbtest where id=:id';
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
         }


        //ブラウザ表示フェーズ
        $sql = 'SELECT * FROM tbtest';
        $stmt = $pdo->query($sql);
        $results = $stmt->fetchAll();       
        foreach ($results as $row){
            //$rowの中にはテーブルのカラム名が入る
            echo $row['id'].',';
            echo $row['name'].',';
            echo $row['comment'].'<br>';
        echo "<hr>";
        }



    ?>


</body>
</html>