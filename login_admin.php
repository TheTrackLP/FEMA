<?php include('./header.php'); ?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>FEMA | Admin/Staff Login</title>
    
<?php include('./db_connect.php'); ?>
<?php 
session_start();
if(isset($_SESSION['login_id'])){
    header("location:index.php?page=home");
}
?>
  <style>
    * {
        padding: 0;
        margin: 0;
        box-sizing: border-box;
    }
    body {
        background-image: linear-gradient(to bottom right, #00AFB9, #FED9B7);
        background-size: cover;
        background-repeat: no-repeat;
        background-attachment: fixed;
    }
    .row {
        background: white;
        border-radius: 30px;
        box-shadow: 12px 12px 22px grey;
    }
    img {
        border-top-left-radius: 30px;
        border-bottom-left-radius: 30px;
    }
    .btn-1 {
        border: none;
        outline: none;
        height: 50px;
        width: 100%;
        background-color: black;
        color: white;
        border-radius: 4px;
        font-weight: bold;

    }
    .btn-1:hover {
    background-color: white;
    border: 1px solid;
    color: black;
    }
    span {
        color: rgb(212, 155, 11);
    }
    .content {
        width: 500px;
    }
  </style>
</head>
  <body>

    <section class="form my-4 mx-5">
        <div class="container" style="height: 100%">
            <div class="content row g-0 mx-auto mt-5">
                <div class="col-sm-12 px-5 pt-5 text-center">
                    <h1 class="fw-bold py-3 mt-5 text-start">FEMA</h1>
                    <h2 class="fw-bolder"> <span>ADMINISTRATOR</span> <br> LOGIN</h2>
                    <form id="login-form" class="mt-5">
                        <div class="form-row">
                            <div class="col-sm-12 mx-auto">
                                <input type="username" id="username" name="username" class="form-control my-3 p-2" placeholder="username">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-sm-12 mx-auto">
                                <input type="password" id="password" name="password" class="form-control my-3 p-2" placeholder="********">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-sm-12 mx-auto">
                                <button class="btn-1 mt-3 btn_login">LOG IN</button>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-sm-12 mx-auto">
                                <button class="btn-1 mt-3 mb-5" onclick="location.href='login_main.php'">GO BACK</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>
  </body>
<script>
    $('#login-form').submit(function(e){
        e.preventDefault()
        if($(this).find('.alert-danger').length > 0 )
            $(this).find('.alert-danger').remove();
        $.ajax({
            url:'ajax.php?action=login',
            method:'POST',
            data:$(this).serialize(),
            error:err=>{
                console.log(err)
            },
            success:function(resp){
                if(resp == 1){
                    alert("Successfully Log In!...");
                    location.href ='index.php?page=home';
                }else{
                    $('#login-form').prepend('<div class="alert alert-danger">Username or password is incorrect.</div>')
                    $('#login-form button[type="button"]').removeAttr('disabled').html('Login');
                }
            }
        })
    })
</script>
</html>