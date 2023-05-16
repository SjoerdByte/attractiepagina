<?php
session_start();
require_once '../backend/config.php';
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
        $fast_pass = true;
    }
    else
    {
        $fast_pass = false;
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

    move_uploaded_file($_FILES['img_file']['tmp_name'], $target_dir . $target_file);

    require_once 'conn.php';
    $query = "INSERT INTO rides (title, themeland, fast_pass, img_file) VALUES(:title, :themeland, :fast_pass, :img_file)";
    $statement = $conn->prepare($query);
    $statement->execute([
        ":title" => $title,
        ":themeland" => $themeland,
        ":fast_pass" => $fast_pass,
        ":img_file" => $target_file,
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