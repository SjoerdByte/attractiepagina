<?php
session_start();
require_once '../backend/conn.php';
if(!isset($_SESSION['user_id']))
{
    $msg = "U moet eerst Inloggen!";
    header("Location: $base_url/admin/login.php?msg=$msg");
    exit;
}



$action = $_POST['action'];
if($action == 'create')
{
    $title = $_POST['title'];
    if(empty($title))
    {
        $errors[] = "Vul eerst een titel in!";
    }

    $themeland = $_POST['themeland'];
    if(empty($themeland))
    {
        $errors[] = "Vul eest een themagebied in!";
    }

    if(isset($_POST['fast_pass']))
    {
        $fast_pass = 1;
    }
    else
    {
        $fast_pass = 0;
    }

    $min_length = $_POST['min_length'];
    if(empty($min_length))
    {
        $errors[] = "Vul eerst een minimale lengte in!";
    }

    $description = $_POST['description'];
    if(empty($description))
    {
        $errors[] = "Vul eerst een beschrijving in!";
    }

    $target_dir = "../../img/attracties/";
    $target_file = $_FILES['img_file']['name'];
    if(file_exists($target_dir . $target_file))
    {
        $errors[] = "Bestand bestaat al!";
    }
    if(isset($errors))
    {
        var_dump($errors);
        die();
    }

    $imageFileType = strtolower(pathinfo($target_dir . $target_file, PATHINFO_EXTENSION));
    $allowedTypes = ["jpg", "jpeg", "png", "gif"];
    if(!in_array($imageFileType, $allowedTypes))
    {
        $errors[] = "Alleen JPG, JPEG, PNG & GIF bestanden zijn toegestaan!";
    }

    if($_FILES['img_file']['size'] > 500000)
    {
        $errors[] = "Bestand is te groot!";
    }

    if(isset($errors))
    {
        var_dump($errors);
        die();
    }

    move_uploaded_file($_FILES['img_file']['tmp_name'], $target_dir . $target_file);

    $sql = "INSERT INTO rides (title, themeland, fast_pass, min_length, description, img_file) VALUES (:title, :themeland, :fast_pass, :min_length, :description, :img_file)";
    $statement = $conn->prepare($sql);
    $statement->execute([
        ":title" => $title,
        ":themeland" => $themeland,
        ":fast_pass" => $fast_pass,
        ":min_length" => $min_length,
        ":description" => $description,
        ":img_file" => $target_file
    ]);


    header("Location: ../attracties/index.php");
    exit;
}

if($action == "update")
{
    $id = $_POST['id'];
    $title = $_POST['title'];
    $themeland = $_POST['themeland'];
    if(isset($_POST['fast_pass']))
    {
        $fast_pass = true;
    }
    else
    {
        $fast_pass = false;
    }

    if(empty($_FILES['img_file']['name']))
    {
        $target_file = $_POST['old_img'];
    }
    else
    {
        $target_dir = "../../img/attracties/";
        $target_file = $_FILES['img_file']['name'];
        if(file_exists($target_dir . $target_file))
        {
            $errors[] = "Bestand bestaat al!";
        }

        move_uploaded_file($_FILES['img_file']['tmp_name'], $target_dir . $target_file);
    }

    if(isset($errors))
    {
        var_dump($errors);
        die();
    }

    require_once 'conn.php';
    $query = "UPDATE rides SET title = :title, themeland = :themeland, fast_pass = :fast_pass, img_file = :img_file WHERE id = :id";
    $statement = $conn->prepare($query);
    $statement->execute([
        ":title" => $title,
        ":themeland" => $themeland,
        ":fast_pass" => $fast_pass,
        ":img_file" => $target_file,
        ":id" => $id
    ]);

    header("Location: ../attracties/index.php");
    exit;
}

if($action == "delete")
{
    $id = $_POST['id'];
    require_once 'conn.php';
    $query = "DELETE FROM rides WHERE id = :id";
    $statement = $conn->prepare($query);
    $statement->execute([
        ":id" => $id
    ]);
    header("Location: ../attracties/index.php");
    exit;
}