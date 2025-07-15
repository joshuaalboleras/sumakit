<?php 
include '../configuration/config.php';
include '../configuration/routes.php';

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <style>
        .standard-form{
            border: 1px solid black;
            padding:10px;
        }

        .standard-form div{
            border: 1px solid black;
            padding: 10px;
            display: inline-block;
        }

        .standard-form div label{
            display: block;
            
        }
    </style>
</head>
<body>
    <form action="../handler/superadmin/register.php" method="post" class="standard-form">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']?>">
        <div>
            <label for="name">Name</label>
            <input type="text" id="name" name="name" placeholder="Enter Name" autocomplete="off">
        </div>
        <div>
            <label for="email">Email</label>
            <input type="text" id="email" name="email" placeholder="Enter Email" autocomplete="off">
        </div>
        <div>
            <label for="password">Password</label>
            <input type="text" id="password" name="password" placeholder="Enter Password" autocomplete="off">
        </div>
        <div>
            <label for="role">Role</label>
            <input type="text" id="role" name="role_id" placeholder="Role" autocomplete="off">
        </div>
        <button>submit</button>
    </form>
    
    <hr>
    
    <form action="../handler/superadmin/register_province.php" class="standard-form" method="post">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']?>">
        <div>
            <label for="province">Province</label>
            <input type="text" name="province_name" placeholder="Enter Province Name">
        </div>
        <button>submit</button>
    </form>
    <hr>
    <form action="../handler/superadmin/register_municipality.php" class="standard-form" method="post">
        <input type="hidden" name="csrf_token" value="<?= $_SESSION['csrf_token']?>">
        <div>
            <label for="">Province</label>
            <select name="province_id" id="">
                <?php 
                    $stmt = $conn->query("SELECT * FROM provinces");
                    $stmt->setFetchMode(PDO::FETCH_ASSOC);
                    $stmt->execute();
                    $res = $stmt->fetchAll();
                    foreach($res as $province){
                        echo <<<HTML
                            <option value="{$province['id']}">{$province['province_name']}</option>
                        HTML;
                    }
                ?>
            </select>
        </div>
        <div>
            <label for="municipality">Municipality</label>
            <input type="text" name="municipality" placeholder="Enter Municipality">
        </div>
        <button>submit</button>
    </form>
</body>
</html>