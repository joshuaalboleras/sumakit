<?php 
include './configuration/config.php';
include './configuration/routes.php';
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
    <form action="./handler/auth/login.php" method="post" class="standard-form">
        <div>
            <?php 
                
                if(isset($_SESSION['message'])){
                    echo "<h1>{$_SESSION['message']}</h1>";
                    unset($_SESSION['message']);
                }

                if(isset($_SESSION['errors'])){
                    foreach($_SESSION['errors'] as $key => $value){
                       $$key = $_SESSION['errors'][$key][0];
                    }
                }                  
            ?>
        </div>
        <div>
            <label for="email">Email</label>
            <input type="text" id="email" name="email" placeholder="<?= $email ?? 'Enter enail' ?>" autocomplete="off">
        </div>
        <div>
            <label for="password">Password</label>
            <input type="text" id="password" name="password" placeholder="<?= $password ?? "Enter Password" ?> " autocomplete="off">
        </div>
        <button>submit</button>
    </form>
</body>
</html>
<?php 
unset($_SESSION['errors']);
?>