<?php
    $flag=$_POST['flag'];
    $con = new mysqli("localhost","root","","todolist");
        if(!$con)
        {
            die('Could not connect'.mysql_error());
        }   
    if($flag==0){
        $id=$_POST['task_id'];
        $sql = "UPDATE tasks SET done='yes' WHERE task_id='".$id."'";
        mysqli_query($con,$sql);
        header('location:todo.php');
    }
    elseif($flag==1){
        $id=$_POST['task_id'];
        $sql = "UPDATE tasks SET done='no' WHERE task_id='".$id."'";
        mysqli_query($con,$sql);
        header('location:todo.php');
    }
?>