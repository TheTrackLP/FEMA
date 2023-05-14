<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>FEMA | Login as?</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-GLhlTQ8iRABdZLl6O3oVMWSktQOp6b7In1Zl3/Jr59b6EGGoI1aFkw7cmDA6j6gD" crossorigin="anonymous">
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
  <body>

    <section class="Form my-4 mx-5">
        <div class="container" style="height: 100%">
            <div class="content row g-0 mx-auto mt-5">
                <div class="col-sm-12 px-5 pt-5 text-center">
                    <h1 class="fw-bold py-3 mt-5 text-start">FEMA</h1>
                    <h2 class="fw-bolder"> <span>LOG IN</span> AS ? </h2>
                    <form action="" class="mt-5">
                        <div class="form-row">
                            <div class="col-sm-12 mx-auto">
                                <button type="button" class="btn-1 mt-3 mb-1" onclick="location.href='login_admin.php'">LOG IN AS ADMININSTRATOR</button>
                            </div>
                            <div class="col-sm-12 mx-auto">
                                <button type="button" class="btn-1 mt-3 mb-5" onclick="location.href='login_member.php'">LOG IN AS MEMBER</button>
                            </div>
                            <p>Don't have an account?<a href="registration.php"> Register here!</a></p>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-w76AqPfDkMBDXo30jS1Sgez6pr3x5MlQ1ZAGC+nuZB+EYdgRZgiwxhTBTkF7CXvN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js" integrity="sha384-mQ93GR66B00ZXjt0YO5KlohRA5SY2XofN4zfuZxLkoj1gXtW8ANNCe9d5Y3eG5eD" crossorigin="anonymous"></script>

  </body>
</html>