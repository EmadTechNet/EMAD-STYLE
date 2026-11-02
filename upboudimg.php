<form method="post" enctype="multipart/form-data">
    <input type="file" name="img"><br><br>
    <input type="text" name="imgNmae" placeholder="enter the name for your image"><br>
</form>
<?php
$conn=new PDO("mysql:host=localhost;dbname=image","root","")
if(isset($_POST['send']))
{
    $imgname=$_POST['imgname'];
    $image=$_FILES['img'];
    $photo=$image['name'];
    $soldpath=$image['tmp-name'];
    $newpath="images/".photo;
    move_uploaded_file($soldpath,$newpath);
    $sql=$conn->prepare("insert into img (imgNmae,image) values(:n,:i)");
    $sql->execute(array(
        ":n"=>$imgname,
        ":i"=>$photo
    ));
    header("location:uploadImages.php")
}

