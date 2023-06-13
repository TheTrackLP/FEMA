<?php include('./header.php'); ?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
  </head>

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
        height: 100px;
        width: 100%;
        background-color: black;
        color: white;
        border-radius: 4px;
        font-weight: bold;
        font-size: 25px;

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
        width: 700px;
        height: 700px;      
    }
  </style>
  <body scroll="no" style="overflow: hidden">

    <section class="Form my-4 mx-5 py-5">
        <div class="container" style="height: 100%">
            <div class="content row g-0 mx-auto mt-5">
                <div class="col-sm-12 px-5 pt-5 text-center">
                    <h1 class="fw-bold display-3 py-3 mt-1 mb-5 text-start">FEMA</h1>
                    <h2 class="fw-bolder display-4"> <span>LOG IN</span> AS ? </h2>
                    <form action="" class="mt-5">
                        <div class="form-row">
                            <div class="col-sm-12 mx-auto">
                                <button type="button" class="btn-1 mt-3 mb-1" onclick="location.href='login_admin.php'">LOG IN AS ADMININSTRATOR</button>
                            </div>
                            <div class="col-sm-12 mx-auto">
                                <button type="button" class="btn-1 mt-3 mb-5" onclick="location.href='login_member.php'">LOG IN AS MEMBER</button>
                            </div>
                            <p class="h5">Don't have an account?<a href="registration.php"> Register here!</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

  </body>
</html>