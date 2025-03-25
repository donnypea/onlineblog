<?php include("database/get_users.php") ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<style>
input[type=text], select {
  width: 100%;
  padding: 12px 20px;
  margin: 8px 0;
  display: inline-block;
  border: 1px solid #ccc;
  border-radius: 4px;
  box-sizing: border-box;
}

input[type=submit] {
  width: 100%;
  background-color: #4CAF50;
  color: white;
  padding: 14px 20px;
  margin: 8px 0;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

input[type=submit]:hover {
  background-color: #45a049;
}

div {
  border-radius: 5px;
  background-color: #f2f2f2;
  padding: 20px;
}


#customers {
  font-family: Arial, Helvetica, sans-serif;
  border-collapse: collapse;
  width: 100%;
}
#customers td, #customers th {
  border: 1px solid #ddd;
  padding: 8px;
}
#customers tr:nth-child(even){background-color: #f2f2f2;}
#customers tr:hover {background-color: #ddd;}
#customers th {
  padding-top: 12px;
  padding-bottom: 12px;
  text-align: left;
  background-color: #04AA6D;
  color: white;
}
</style>
<body>


<div>
  <form action="users.php" method="POST">
    <label for="fname">First Name</label>
    <input type="text" id="fname" name="firstname" placeholder="Your name..">

    <label for="lname">Last Name</label>
    <input type="text" id="lname" name="lastname" placeholder="Your last name..">

    <label for="email">Email</label>
    <input type="text" name="email" placeholder="Your email..">

    <input name="submit_btn" type="submit" value="Submit">
  </form>
</div>

<?php
    if ( isset($_POST['submit_btn']) ) {
       
        require_once("database/insert_users.php");

    }
?>
<table id="customers">
  <tr>
    <th>ID</th>
    <th>FIRSTNAME</th>
    <th>LASTNAME</th>
    <th>EMAIL</th>
    <th>REG_DATE</th>
  </tr>
 
  <?php 
        foreach ($result as $user) {
            echo "<tr>";
            echo '<td>' . htmlspecialchars($user["id"]) . '</td>'; 
            echo '<td>' . htmlspecialchars($user["firstname"]) . '</td>'; 
            echo '<td>' . htmlspecialchars($user["lastname"]) . '</td>'; 
            echo '<td>' . htmlspecialchars($user['email']) . '</td>'; 
            echo '<td>' . htmlspecialchars($user["reg_date"]) . '</td>'; 
                    
            echo "</tr>";
        }
    ?>
</table>
</body>
</html>