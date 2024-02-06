<?php
defined('BASEPATH') or exit('No direct script access allowed');

// Check if the user is not logged in; if not, redirect to the login page
if (!$this->session->userdata('logged_in')) {
    redirect('mycontroller/login');
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <!-- JQuery CDN -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <title>Christopher - Dashboard</title>
</head>

<body class="p-4">
    <div class="float-end">
        <button type="button" class="btn btn-primary" id="logoutBtn">Logout</button>
    </div>

    <div class="container-fluid">
        <table class="table table-striped text-center">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Username</th>
                    <th scope="col">Email</th>
                    <th scope="col">Operation</th>
                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($users as $user) {
                    echo "<tr>";
                    echo "<td>" . $user->id . "</td>";
                    echo "<td>", $user->username, "</td>";
                    echo "<td>" . $user->email . "</td>";
                    echo "<td>
                    <button type='button' class='btn btn-warning' onclick='openEditModal(" . $user->id . ", \"" . $user->username . "\", \"" . $user->email . "\")'>Edit</button>
                            <button type='button' class='btn btn-danger' onclick='confirmDelete(" . $user->id . ")'>Delete</button>
                          </td>";
                    echo "</tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editModalLabel">Edit User</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" onclick="urlReset()" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="editForm">
                        <div class="mb-3">
                            <label for="editUsername" class="form-label">Username</label>
                            <input type="text" class="form-control" id="editUsername" name="editUsername">
                        </div>
                        <div class="mb-3">
                            <label for="editEmail" class="form-label">Email</label>
                            <input type="email" class="form-control" id="editEmail" name="editEmail">
                        </div>
                        <input type="hidden" id="editUserId" name="editUserId">
                        <button type="button" class="btn btn-primary" onclick="updateUser()">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>

    <script>
        $(document).ready(function() {
            $("#logoutBtn").on("click", function() {
                $.ajax({
                    type: "POST",
                    url: "<?= base_url('mycontroller/logout'); ?>",
                    success: function(response) {
                        window.location.href = "<?= base_url('mycontroller/login'); ?>";
                    },
                    error: function() {
                        console.log("Logout request failed");
                    }
                });
            });
        });

        function confirmDelete(userId) {
            var confirmation = confirm("Are you sure you want to delete this record?");

            if (confirmation) {
                $.ajax({
                    type: "POST",
                    url: "<?= base_url('mycontroller/delete_user'); ?>",
                    data: {
                        id: userId
                    },
                    success: function(response) {
                        location.reload();
                    },
                    error: function() {
                        console.log("Delete request failed");
                    }
                });
            }
        }

        function openEditModal(userId, username, email) {
            $.ajax({
                type: 'POST',
                url: "<?= base_url('mycontroller/encrypt_user_id'); ?>",
                data: {
                    id: userId
                },
                success: function(encryptedUserId) {
                    $('#editUserId').val(encryptedUserId);

                    var newUrl = window.location.href.split('?')[0] + '?id=' + encryptedUserId;
                    window.history.pushState({
                        path: newUrl
                    }, '', newUrl);

                    $('#editUserId').val(userId);
                    $('#editUsername').val(username);
                    $('#editEmail').val(email);

                    $('#editModal').modal('show');
                },
                error: function(xhr, status, error) {
                    console.error('Error encrypting user ID:', error);
                }
            });
        }


        function updateUser() {
            var userId = $('#editUserId').val();
            var username = $('#editUsername').val();
            var email = $('#editEmail').val();
            var password = $('#editPassword').val();

            $.ajax({
                type: "POST",
                url: "<?= base_url('mycontroller/update_user'); ?>",
                data: {
                    id: userId,
                    username: username,
                    email: email,
                },
                success: function(response) {
                    var data = JSON.parse(response);

                    if (data.success) {
                        urlReset();
                        location.reload();
                    } else {
                        alert('Username or email already exists!');
                    }
                },
                error: function() {
                    console.log("Update request failed");
                }
            });
        }

        function urlReset() {
            window.history.replaceState({}, document.title, window.location.pathname);
        }
    </script>
</body>

</html>