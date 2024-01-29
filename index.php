<?php
$servername = "127.0.0.1";
$username = "root";
$password = "";
$dbname = "base1";
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
error_reporting(E_ERROR | E_PARSE);

class Delta{
    
    public function que($conn,$word,$key){
        $a=$conn->query($word);
        $a=$a->fetch_assoc();
        $a=$a[$key];
        return $a;
    }
    
    public function sin($conn,$name,$pass) {
        $a=$conn->query("Select name from test where name like "."'$name'");
        $b=$conn->query("Select pass from test where pass like "."'$pass'");
        $a=$a->fetch_assoc();
        $b=$b->fetch_assoc();
        if($a && $b){return true;}
        
    }
    
    public function sinup($conn,$name,$pass){
        $a=$conn->query("Insert into test (name,pass) values ("."'$name',"."'$pass')");
    }
    
    public function up($conn,$id,$formcol){
        $conn->query("update form set formcol = '$formcol' where id =$id");
    }
    
    public function in($conn,$text){
        $conn->query("Insert into form (formcol,countview) values ('$text',0)");
    }
    
    public function ind($conn,$text,$id){
        $conn->query("Insert into video (id,place) values ($id,'$text')");
    }
    
    public function del($conn,$id){
        $conn->query("delete from form where id=$id");
    }
}
echo "First function its a authorization at to the page";
?>
<html>
    <head>
        <meta charset="UTF-8">
        <title></title>
    </head>
    <body>
    <form action="index.php" method="post">
        <label for="text">Put ur name and pass:</label>
        <br>
        <input type="text" name="name" id="name">
        <br>
        <input type="text" name="pass" id="pass">
        <input type="submit" value="log in">
    </form>
    <form action="index.php" method="post">
        <label for="text">if u wanna sing up:</label>
        <br>
        <input type="text" name="name1" id="name1">
        <br>
        <input type="text" name="pass1" id="pass1">
        <input type="submit" name = "button" value="Sing up">
    </form>
        <?php
        
        $name=$_POST["name"];
        $pass=$_POST["pass"];
        $delta= new Delta();    
        
        $a=$delta->que($conn,"Select name from test where name like "."'$name'","name"); 
        $a=$delta->sin($conn,$name,$pass);
        if($a){echo"Hello To the World ".$name;
        }else{echo"Ur not log in";echo"<br>";}
        
        $name1=$_POST["name1"];
        $pass1=$_POST["pass1"];
        if (isset($_POST["button"])){
        if($name1!=NULL && $pass1!=NULL){
            $delta->sinup($conn, $name1, $pass1);
            echo "Success";
        }else{echo"Something happened";}}
        echo "<br><br>";
       
        
        $name='';
        $pass='';
        
        ?>
        <style>
            .a{
                width: 300px;
                white-space: pre-wrap;
            }
        </style>
    <form action="index.php" method="post">
        <?php
        echo "Second its a CRUD, At first u can update and read a text, Second, create a new text, thierd delete raws "."<br>";
            $a=$conn->query("select max(id) from form");
            $a=$a->fetch_assoc();
            $a=$a['max(id)'];
            $b=0;
            for($i=1;$i<=$a;$i++){
                $c=$delta->que($conn,"select id from form where id = $i","id");
                if($c!=null){echo"$i".",";}
            }
            
        ?>
    <label for="id">There are all id`s which u can modify:</label><br>
    <input type="text" name="id" id="id">
    <input type="submit" name="button1" value="Put id text">
</form>

<?php
if(isset($_POST["button1"])){
    
    $id = $_POST['id'];
    $text = $delta->que($conn, "SELECT formcol FROM form WHERE id = $id", "formcol");

    echo '<form action="index.php" method="post">';
    echo '<label for="text">Text:</label><br>';
    echo "<textarea type='text' class='a' name='text$id' id='text$id'>$text</textarea>";
    echo '<input type="submit" name="button2" value="Update Text">';
    echo '<input type="hidden" name="id" value="'.$id.'">';  
    echo '</form>';
}

if(isset($_POST["button2"])){
    $id = $_POST['id'];
    $text = $_POST["text$id"];
    echo $text;
    $delta->up($conn, $id, $text);
    echo $text;
}
?>
        <form action="index.php" method="post">
            <label for="text">Write text:</label><br>
            <textarea type='text' class='a' name='newtext' id='newtext'>Write something</textarea>
            <input type="submit" name="button3" value="Update Text">
        </form>
<?php
        if(isset($_POST['button3'])){
          $text=$_POST['newtext'];
          $delta->in($conn, $text);
          echo "U added a text";
          }
?>
        <form action="index.php" method="post">
            <label for="text">Write id which u wanna delete a text:</label><br>
            <input type="text" name="del" id="del">
            <input type="submit" name="button4" value="Update Text">
        </form>
        <?php
        if(isset($_POST['button4'])){
          $id=$_POST['del'];
          $delta->del($conn, $id);
          echo "U delete a text";
          }
        echo "Thierd, upload ur videos <br>";
        echo "And Fourth, whatch it <br>";
        ?>
    </body>
</html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>download ur file</title>
</head>
<body>
    <h2>download ur video</h2>
    <form action="index.php" method="post" enctype="multipart/form-data">
        <label for="file">Chooose ur file:</label>
        <input type="file" name="file" id="file" required>
        <br>
        <input type="submit" value="Загрузить">
    </form>
</body>
<?php
ini_set('upload_max_filesize', '40M');
ini_set('post_max_size', '63M');
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $uploadDir = "C:/xampp/htdocs/PhpProject1/";
    $uploadFile = $uploadDir . basename($_FILES["file"]["name"]);
    
    $id=$delta->que($conn,"select max(id) from video","max(id)");
    $id++;
    echo $id.$uploadFile;
    $delta->ind($conn, $uploadFile, $id);
    if (move_uploaded_file($_FILES["file"]["tmp_name"], $uploadFile)) {
        echo "Ok: " . $uploadDir;
    } else {
        echo "Errr, be sure that ur file does not exceed 40mb.";
    }
}
?>
</html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Video</title>
</head>
<body>
    <h2>Video player, but be sure, that u are give permissions to reading files</h2>

    <video width="640" height="360" controls>
        <?php
            $a=$delta->que($conn,"select max(id) from video","max(id)");
            $c="video/mp4";
            for($i=1;$i<=$a-1;$i++){
                $b=$delta->que($conn,"select place from video where id = $i", "place");
                echo "<source src=$b type=$c>";
            }
            
        ?>
        
    </video>
</body>
</html>
<?php
$conn->close();
?>
