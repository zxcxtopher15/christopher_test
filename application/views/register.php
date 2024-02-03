<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <!-- JQuery CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <title>Christopher</title>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar navbar-dark bg-dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="#"><b>Christopher</b></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavAltMarkup" aria-controls="navbarNavAltMarkup" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavAltMarkup">
                <div class="navbar-nav">
                    <a class="nav-link" href="<?= base_url('mycontroller'); ?>">Home</a>
                    <a class="nav-link active" href="<?= base_url('mycontroller/registration'); ?>">Register</a>
                    <a class="nav-link" href="<?= base_url('mycontroller/login'); ?>">Login</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="row">
            <div class="md-col-4"></div>
            <div class="md-col-4">
                <div class="card" style="margin-top: 50px;">
                    <div class="card-header text-center">
                        Registration Form
                    </div>
                    <div class="card-body">
                        <form id="registrationForm">
                            <div class="mb-3">
                                <label for="exampleInputUsername1" class="form-label">Username</label>
                                <input type="text" class="form-control" id="uname" name="uname" aria-describedby="unameHelp" Required>
                            </div>
                            <div class="mb-3">
                                <label for="exampleInputEmail1" class="form-label">Email address</label>
                                <input type="email" class="form-control" id="email" name="email" aria-describedby="emailHelp" Required>
                            </div>
                            <div class="mb-3">
                                <label for="exampleInputPassword1" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" Required>
                            </div>
                            <button type="button" class="btn btn-primary" id="submitBtn">Register</button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="md-col-4"></div>
        </div>
    </div>

    <script>
        $(document).ready(function() {
            $("#submitBtn").on("click", function() {
                var formData = $("#registrationForm").serialize();

                var uname = $('#uname').val();
                var email = $('#email').val();
                var password = $('#password').val();

                if (uname.trim() === '' || email.trim() === '' || password.trim() === '') {
                    alert('Please fill in all the fields.');
                    return;
                }

                $.ajax({
                    type: "POST",
                    url: "<?= base_url('mycontroller/register'); ?>",
                    data: formData + "&register=1",
                    dataType: "json",
                    success: function(response) {
                        if (response.status === "success") {
                            alert("Registration Successful!");
                            window.location.href = "<?= base_url('mycontroller/login');?>";
                            $("#registrationForm")[0].reset();
                        } else {
                            if (response.message.includes("Username already exists.")) {
                                alert("Registration failed. Username already exists.");
                                $("#uname").val('');
                            } else if (response.message.includes("Email already exists.")) {
                                alert("Registration failed. Email already exists.");
                                $("#email").val('');
                            } else {
                                alert(response.message);
                            }
                        }
                    },
                    error: function() {
                        alert("AJAX request failed");
                    }
                });
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
</body>

</html>