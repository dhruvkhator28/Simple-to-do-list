
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TO-DO List</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
</head>
<body>
<?php
        $errors="";
        $con = new mysqli("localhost","root","","todolist");
        if(!$con)
        {
            die('Could not connect'.mysql_error());
        }   
        if(isset($_POST['submit'])){
            if(empty($_POST['task'])){
                $errors = "Task cannot be empty!!";
            }
            else{
                $task = $_POST['task'];

                $sql1 = "SELECT * FROM tasks";
                $result = $con->query($sql1);
                $n_rows = $result->num_rows;
                $insert_id = $n_rows+1;

                $sql2 = "SELECT * FROM tasks WHERE task_id='".$insert_id."'";
                $result2 = $con->query($sql2);
                $n_rows2 = $result2->num_rows;

                if($n_rows2>0){

                    $sql3 = "SELECT MAX(task_id) AS maximum FROM tasks";
                    $result3 = $con->query($sql3);
                    while($values = $result3->fetch_array()){
                        $max = $values['maximum'];
                        //echo $max;
                    }
                    $i_id = $max + 1;

                    $sql4 = "INSERT INTO tasks (task_id,task_nm,done) VALUES ('$i_id','$task','no')";
                    mysqli_query($con,$sql4);
                }
                else{
                    $n_rows+=1;
                    $sql5 = "INSERT INTO tasks (task_id,task_nm,done) VALUES ('$n_rows','$task','no')";
                    mysqli_query($con,$sql5);
                }    
                header('location:todo.php');
            }
        }
        
        if (isset($_GET['del_task'])) {
	        $id = $_GET['del_task'];

	        mysqli_query($con, "DELETE FROM tasks WHERE task_id='".$id."'");
	        header('location:todo.php');
        }
?>
    <div class="container">
        <div class="heading">
            <h2>To Do List</h2>
        </div>
        <div class="app">
            <form method="POST" action="todo.php">
                <?php if (isset($errors)) { ?>
	                <p><?php echo $errors; ?></p>
                <?php } ?>
                <input type="text" name='task' placeholder="Enter Task" class="task_input">
                <button type="submit" name="submit" id="btn" class="sub_btn">Add Task</button>
            </form>
        </div>
        <table>
	<thead>
		<tr>
			<th style="width: 60px;">Sr.No.</th>
            <th>Tasks</th>
            <th style="width: 60px;">Status</th>
			<th style="width: 60px;">Action</th>
		</tr>
	</thead>

	<tbody>
		<?php 
		// select all tasks if page is visited or refreshed
		$tasks = mysqli_query($con, "SELECT * FROM tasks");

        $i = 1; 
        while ($row = mysqli_fetch_array($tasks)) { ?>
			<tr>
				<td> <?php echo $i; ?> </td>
                <td class="task"> <?php echo $row['task_nm']; ?> </td>
                <?php if($row['done']=="yes"){ ?>
                    <td class="status"><input type="checkbox" checked="true" onchange="checking(this,<?php echo $row['task_id']; ?>)"></td>
                <?php }else{ ?>
                    <td class="status"><input type="checkbox" onchange="checking(this,<?php echo $row['task_id']; ?>)"></td>
                <?php } ?>
				<td class="delete"> 
					<a href="todo.php?del_task=<?php echo $row['task_id']; ?>">x</a> 
				</td>
			</tr>
		<?php $i++; } ?>	
	</tbody>
</table>
    </div>
    <script>
    
        function checking(check_box,id){
            console.log("hiii");
            if(check_box.checked){
                console.log("hiii dhruv");
                let flag = 0;
                $.ajax({
                    
                    url:"status_change.php",
                    method:'POST',
                    data:{"task_id":id,"flag":flag},
                    dataType:"json",
                    
                });
            }
            else{
                console.log("hi d");
                let flag = 1;
                $.ajax({
                    
                    url:"status_change.php",
                    method:'POST',
                    data:{"task_id":id,"flag":flag}
                    
                });
            }
        }
    </script>
</body>
</html>