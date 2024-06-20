<!DOCTYPE html>
<html lang="el">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Register-Alignmet</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css"
        crossorigin="anonymous">
    <style>
        .h1 {
            background-image: linear-gradient(to bottom, #3b599c 0%, #663399 100%);
            animation: color-change 10s infinite alternate;
            padding: 15px 20px;
            border-radius: 5px;
            box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
            text-align: center;
            font-weight: bold;
            color: white;
        }

        body {
            font-family: sans-serif;
            min-height: 100vh;
            background-image: linear-gradient(to bottom, #3b599c 0%, #663399 100%);
            animation: color-change 10s infinite alternate;
        }

        @keyframes color-change {
            from {
                background-image: linear-gradient(to bottom, #3b599c 0%, #663399 100%);
            }

            to {
                background-image: linear-gradient(to bottom, #663399 0%, #3b599c 100%);
            }
        }

        .register-form {
            width: 300px;
            margin: 50px auto;
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
        }

        .back-button {
            position: absolute;
            top: 20px;
            left: 20px;
            color: white;
            font-size: 24px;
            text-decoration: none;
        }
    </style>
</head>

<body>
    <a href="{{ url('/') }}" class="back-button"><i class="fas fa-arrow-left"></i></a>
    <div class="container">
        <section class="vh-100" style="background-color: #eee;">
            <div class="container h-100">
                <div class="row d-flex justify-content-center align-items-center h-100">
                    <div class="col-lg-12 col-xl-11">
                        <div class="card text-black" style="border-radius: 25px;">
                            <div class="card-body p-md-5">
                                <div class="row justify-content-center">
                                    <div class="col-md-10 col-lg-6 col-xl-5 order-2 order-lg-1">
                                        <p class="text-center h1 fw-bold mb-5 mx-1 mx-md-4 mt-4">Register</p>
                                        <form class="mx-1 mx-md-4" action="{{ route('register') }}" method="POST">
                                            @csrf
                                            <div class="d-flex flex-row align-items-center mb-4">
                                                <i class="fas fa-user fa-lg me-3 fa-fw"></i>
                                                <div data-mdb-input-init class="form-outline flex-fill mb-0">
                                                    <input type="text" id="form3Example1c" class="form-control"
                                                        name="name" required />
                                                    <label class="form-label" for="form3Example1c">Name</label>
                                                </div>
                                            </div>
                                            <div class="d-flex flex-row align-items-center mb-4">
                                                <i class="fas fa-user fa-lg me-3 fa-fw"></i>
                                                <div data-mdb-input-init class="form-outline flex-fill mb-0">
                                                    <input type="text" id="form3Example3c" class="form-control"
                                                        name="username" required />
                                                    <label class="form-label" for="form3Example3c">Username</label>
                                                </div>
                                            </div>
                                            <div class="d-flex flex-row align-items-center mb-4">
                                                <i class="fas fa-lock fa-lg me-3 fa-fw"></i>
                                                <div data-mdb-input-init class="form-outline flex-fill mb-0">
                                                    <input type="password" id="form3Example4c" class="form-control"
                                                        name="password" required minlength="8" />
                                                    <label class="form-label" for="form3Example4c">Password (must be at
                                                        least 8 characters long)</label>
                                                </div>
                                            </div>

                                            <div class="d-flex flex-row align-items-center mb-4">
                                                <i class="fas fa-key fa-lg me-3 fa-fw"></i>
                                                <div data-mdb-input-init class="form-outline flex-fill mb-0">
                                                    <input type="password" id="form3Example4cd" class="form-control"
                                                        name="password_confirmation" required />
                                                    <label class="form-label" for="form3Example4cd">Repeat
                                                        Password</label>
                                                </div>
                                            </div>
                                            <div class="d-flex justify-content-center mx-4 mb-3 mb-lg-4">
                                                <button type="submit" data-mdb-button-init data-mdb-ripple-init
                                                    class="btn btn-primary btn-lg">Register</button>
                                            </div>
                                        </form>

                                    </div>
                                    <div
                                        class="col-md-10 col-lg-6 col-xl-7 d-flex align-items-center order-1 order-lg-2">
                                        <img src="https://img.freepik.com/free-vector/sign-page-abstract-concept-illustration_335657-2242.jpg?w=900&t=st=1718194432~exp=1718195032~hmac=0ff985bee12e5c2a01bebaef0987945b131f0a6f74bb4ac209f656c107bd6a48"
                                            alt="Εγγραφή" width="500px" height="450px">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
</body>

</html>