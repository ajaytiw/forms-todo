<?php

include('includes/DB.php');
$db = new DB();

?>

<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link href="https://cdn.jsdelivr.net/npm/toastr/build/toastr.min.css" rel="stylesheet">
     <!-- toastr css -->
     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">


    <title>Dashboard</title>
  </head>

  <style>
  .error {
    color: red;
    font-size: 12px;
    font-weight: bold;
}
</style>

  <body> 

<div class="container mt-5 d-flex justify-content-between align-items-center">
    <div>
        <h5>Team</h5>
        <h6>Dashboard</h6>
    </div>
    <button type="button" class="btn btn-success add_user_modal" data-toggle="modal" data-target="#exampleModal">Add</button>


    
</div>

<div class="container">
<div class="container mt-5 d-flex justify-content-between align-items-center">
   
   <div class="table-responsive">
       <table class="table table-dark" id="userTable">
           <caption>List of users</caption>
           <thead>
               <tr>
                   <th scope="col">Name</th>
                   <th scope="col">Mobile</th>
                   <th scope="col">Email</th>
                   <th scope="col">Role</th>
                   <th scope="col">Designation</th>
                   <th scope="col">Photo</th>
                   <th scope="col">Status</th>
                   <th scope="col">Action</th>
               </tr>
           </thead>
           <tbody>
            <?php 
        //    $sql = "SELECT * FROM users";

        // $sql = "SELECT users.* , user_images.file_path FROM users LEFT JOIN  user_images ON users.id = user_images.user_id GROUP BY users.id";

        $sql = "SELECT users.*, GROUP_CONCAT(user_images.file_path SEPARATOR ',') AS photos FROM users 
        LEFT JOIN user_images ON users.id = user_images.user_id GROUP BY users.id";

           $users = $db->select($sql);

           if(!empty($users)){
            foreach($users as $user) { ?>
               <tr id="user_list_<?php echo $user['id'] ?>" class="text text-dark">
                   <td><?php echo $user['name'] ?></th>
                   <td><?php echo $user['phone'] ?></td>
                   <td><?php echo $user['email'] ?></td>
                   <td><?php echo $user['role'] ?></td>
                   <td><?php echo $user['designation'] ?></td>
                   <td> 
                    <?php
                        if(!empty($user['photos'])){
                            $photos = explode(',', $user['photos']);
                            foreach($photos as $photo){ ?>
                                <img src = "<?php echo $photo ?>" alt="pics" width="50px" height="50px"/>
                            <?php }
                        }else{
                            echo 'No pics';
                        } ?>                  
                   </td>
                   <td><?php 
                        if($user['status'] == 1) { ?>
                            <span class="badge badge-success"><?php echo 'Active' ; ?></span>
                        <?php } else{ ?>
                            <span class="badge badge-danger"><?php echo 'Inactive' ; ?></span>
                        <?php }?>
                   </td>
                   <td>
                      <button class="btn btn-action bg-secondary btn-sm edit_user" data-target="#exampleModal" 
                      data-user='<?php echo json_encode($user); ?>'><span class="fa fa-pencil"></span></button>
                      
                      <button class="btn btn-action btn-sm bg-danger text-white" onclick="delete_user(<?php echo $user['id'] ?>)"><span class="fa fa-trash"></span> </button>
                   </td>
               </tr> 
               <?php } }else{
                echo '<h4 class="text text-danger"> No records found <h4>';
            } ?>
           </tbody>
       </table>
   </div>
</div>
</div>



 
<!-- Add Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog bd-example-modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title modal_heading" id="exampleModalLabel">Create User</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="POST" enctype="multipart/form-data" id="add_user">
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="fullName">Full Name*</label>
                    <input type="text" class="form-control" id="name" name="name" placeholder="Enter Full Name">
                </div>
                <div class="form-group col-md-6">
                    <label for="mobileNo">Mobile No*</label>
                    <input type="text" class="form-control" id="phone" name="phone" placeholder="Enter Mobile No">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="emailId">Email Id* </label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Enter Email Id">
                </div>
                <div class="form-group col-md-6">
                    <label for="address">Address*</label>
                    <input type="text" class="form-control" id="address" name="address" placeholder="Enter Address">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="role">Role*</label>
                    <select class="form-control" id="role" name="role" required>
                        <option>Select Role</option>
                        <option value="admin">Admin</option>
                        <option value="developer">Developer</option>
                    </select>
                </div>

              

                <div class="form-group col-md-4">
                    <label for="married">Marital Status*</label>
                    <div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="married" id="marriedYes" value="married">
                            <label class="form-check-label" for="marriedYes">Married</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="married" id="marriedNo" value="unmarried">
                            <label class="form-check-label" for="marriedNo">Un-married</label>
                        </div>
                    </div>
                </div>

                <div class="form-group col-md-4">
                    <label for="designation">Designation*</label>
                    <input type="text" class="form-control" id="designation" name="designation" placeholder="Enter Designation">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="gender">Gender*</label>
                    <select class="form-control" id="gender" name="gender">
                        <option>Select Gender</option>
                        <option value="m">Male</option>
                        <option value="f">Female</option>
                        <option value="o">Other</option>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label for="uploadLogo">Upload Logo</label>
                    <input type="file" class="form-control-file" id="uploadLogo" name="uploadLogo[]" accept="image/*" multiple>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="date" class="col-form-label">DOB</label>
                    <input type="date" class="form-control" id="dob" name="dob" required>
                </div>
            </div>
            <div class="modal-footer">
                <input type="hidden" name="action" id="action" value="add_user">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-success action_btn">Add</button>
            </div>
        </form>
      </div>
     
    </div>
  </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="edit_exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog bd-example-modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title modal_heading" id="exampleModalLabel">Update User</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form method="POST" enctype="multipart/form-data" id="edit_user">
            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="fullName">Full Name*</label>
                    <input type="text" class="form-control" id="ename" name="ename" placeholder="Enter Full Name">
                </div>
                <div class="form-group col-md-6">
                    <label for="mobileNo">Mobile No*</label>
                    <input type="text" class="form-control" id="ephone" name="ephone" placeholder="Enter Mobile No">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="emailId">Email Id* </label>
                    <input type="email" class="form-control" id="eemail" name="eemail" placeholder="Enter Email Id">
                </div>
                <div class="form-group col-md-6">
                    <label for="address">Address*</label>
                    <input type="text" class="form-control" id="eaddress" name="eaddress" placeholder="Enter Address">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-4">
                    <label for="role">Role*</label>
                    <select class="form-control" id="erole" name="erole" required>
                        <option>Select Role</option>
                        <option value="admin">Admin</option>
                        <option value="developer">Developer</option>
                    </select>
                </div>

              

                <div class="form-group col-md-4">
                    <label for="married">Marital Status*</label>
                    <div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="emarried" id="marriedYes" value="married">
                            <label class="form-check-label" for="marriedYes">Married</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="emarried" id="marriedNo" value="unmarried">
                            <label class="form-check-label" for="marriedNo">Un-married</label>
                        </div>
                    </div>
                </div>

                <div class="form-group col-md-4">
                    <label for="designation">Designation*</label>
                    <input type="text" class="form-control" id="edesignation" name="edesignation" placeholder="Enter Designation">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-6">
                    <label for="gender">Gender*</label>
                    <select class="form-control" id="egender" name="egender">
                        <option>Select Gender</option>
                        <option value="m">Male</option>
                        <option value="f">Female</option>
                        <option value="o">Other</option>
                    </select>
                </div>
                <div class="form-group col-md-6">
                    <label for="uploadLogo">Upload Logo</label>

                    <div id="edit_upload_logo" class="file-display-container">
                            <!-- Photos will be displayed here -->
                        </div>
                    <input type="file" class="form-control-file" id="euploadLogo" name="euploadLogo[]" accept="image/*" multiple>
                    

                </div>
            </div>

            <div class="form-row">
               
                <div class="form-group col-md-6">
                    <label for="date" class="col-form-label">DOB</label>
                    <input type="date" class="form-control" id="edob" name="edob" required>
                </div>
            </div>
            <div class="modal-footer">
                <input type="hidden" name="update_id" id="update_id" value="">
                <input type="hidden" name="action" id="action" value="update_user">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="submit" class="btn btn-success action_btn">Update</button>
            </div>
        </form>
      </div>
    </div>
  </div>
</div>
    
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery-validation@1.19.5/dist/jquery.validate.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.14.7/dist/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
<script type="text/javascript" src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>

   <script>
        $(document).ready(function() {
            $('#userTable').DataTable();

                $('#add_user').validate({
                    rules: {
                        name: {
                            required: true,
                            minlength: 3
                        },
                        phone: {
                            required: true,
                            digits: true,
                            minlength: 10,
                            maxlength: 10
                        },
                        email: {
                            required: true,
                            email: true
                        },
                        address: {
                            required: true,
                            minlength: 10
                        },
                        role: {
                            required: true
                        },
                        designation: {
                            required: true
                        },
                        gender: {
                            required: true
                        },
                        upload_logo: {
                            required: true,
                            extension: "jpg|jpeg|png"
                        },
                        married: {
                            required: true
                        },
                        dob: {
                            required: true,
                            date: true
                        }
                    },
                    messages: {
                        name: {
                            required: "Required field",
                            minlength: "3 characters or more"
                        },
                        phone: {
                            required: "Required field",
                            digits: "Must be numeric",
                            minlength: "Minimum 10 digits",
                            maxlength: "Maximum 10 digits"
                        },
                        email: {
                            required: "Please enter your email",
                            email: "Please enter a valid email address"
                        },
                        address: {
                            required: "Please enter your address",
                            minlength: "Address must be at least 10 characters long"
                        },
                        role: {
                            required: "Please select a role"
                        },
                        designation: {
                            required: "Please enter your designation"
                        },
                        gender: {
                            required: "Please select your gender"
                        },
                        upload_logo: {
                            required: "Please upload a logo",
                            extension: "Only jpg, jpeg, or png files are allowed"
                        },
                        married: {
                            required: "Please select your marital status"
                        },
                        dob: {
                            required: "Please select your date of birth",
                            date: "Please enter a valid date"
                        }
                    },
                    submitHandler: function (form) {
                        // var formData = $(form).serialize();

                        var formData = new FormData(form);

                        // let action = 'add_user';

                        let action = $('#action').val();

                        if(action == 'add_user'){
                            $.ajax({
                                url: "ajax.php",
                                type: "POST",
                                data: formData,
                                processData: false,
                                contentType: false, 
                                dataType: "json",
                                action: 'add_user',

                                success: function(response) {
                                    if (response.code == 1) {
                                        alert(response.message);

                                        $('#exampleModal').modal('hide');
                                        $('#add_user')[0].reset();
                                        setTimeout(function() {
                                            location.reload();
                                        }, 1000);
                                    } else {
                                        alert(response.message);
                                    }
                                },
                                error: function() {
                                    alert("something went wrong");

                                }
                            });
                        } 
                        // ////else part
                        // else{

                        //     $.ajax({
                        //         url: "ajax.php",
                        //         type: "POST",
                        //         data: formData,
                        //         processData: false,
                        //         contentType: false, 
                        //         dataType: "json",
                        //         action: 'update_user',

                        //         success: function(response) {
                        //             if (response.code == 1) {
                        //                 alert(response.message);

                        //                 $('#exampleModal').modal('hide');
                        //                 $('#add_user')[0].reset();
                        //                 setTimeout(function() {
                        //                     location.reload();
                        //                 }, 1000);
                        //             } else {
                        //                 alert(response.message);
                        //             }
                        //         },
                        //         error: function() {
                        //             alert("something went wrong");

                        //         }
                        //     });
                        // }

                        ////edn else part
                }
                });  
            });

        function delete_user(id){
        
            if(confirm('Are you sure you want to delete'))
            {

                let action = 'user_delete'
                $.post('ajax.php', {
                id,
                action

            }, function(data) {
                data = JSON.parse(data);
                if (data.code == 1) {

                    alert('User deleted successfully')
                    $('#user_list_' + id).hide('slow');

                } else {
                    alert("Something went wrong");
                }
            })

            }
        }

        $(document).on('click', '.edit_user', function(e) {
            e.preventDefault();
            let user = $(this).data('user');
            console.log(user);

            $('#update_id').val(user.id);
            $('#ename').val(user.name);
            $('#ephone').val(user.phone);
            $('#eemail').val(user.email);
            $('#eaddress').val(user.address);
            $('#erole').val(user.role);
            $('#edesignation').val(user.designation);
            $('#egender').val(user.gender);
            $('#ephone').val(user.phone);
            $('#estatus').val(user.status);
            $('input[name="emarried"][value="' + user.marital_status + '"]').prop('checked', true);
            $('#edob').val(user.dob);
            $('#action').val('update_user');
            $('#edit_exampleModal').modal('show');

            let files = user.photos.split(',');
            let fileContainer = $('#edit_upload_logo');
            fileContainer.empty();

            files.forEach(function(filePath) {
                let fileName = filePath.split('/').pop();
                let fileDiv = $('<div>').addClass('file-name').text(fileName);
                fileContainer.append(fileDiv);
            });   
        });



        $('#edit_user').validate({
        rules: {
            ename: {
                required: true,
                minlength: 3
            },
            ephone: {
                required: true,
                digits: true,
                minlength: 10,
                maxlength: 10
            },
            eemail: {
                required: true,
                email: true
            },
            eaddress: {
                required: true,
                minlength: 10
            },
            erole: {
                required: true
            },
            edesignation: {
                required: true
            },
            egender: {
                required: true
            },
            emarried: {
                required: true
            },
            edob: {
                required: true,
                date: true
            }
        },
        messages: {
            ename: {
                required: "Required field",
                minlength: "3 characters or more"
            },
            ephone: {
                required: "Required field",
                digits: "Must be numeric",
                minlength: "Minimum 10 digits",
                maxlength: "Maximum 10 digits"
            },
            eemail: {
                required: "Please enter your email",
                email: "Please enter a valid email address"
            },
            eaddress: {
                required: "Please enter your address",
                minlength: "Address must be at least 10 characters long"
            },
            erole: {
                required: "Please select a role"
            },
            edesignation: {
                required: "Please enter your designation"
            },
            egender: {
                required: "Please select your gender"
            },
            emarried: {
                required: "Please select your marital status"
            },
            edob: {
                required: "Please select your date of birth",
                date: "Please enter a valid date"
            }
        },
        submitHandler: function(form) {
            var formData = new FormData(form);
            let action = $('#action').val();

            if (action == 'update_user') {
                $.ajax({
                    url: "ajax.php",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    dataType: "json",
                    success: function(response) {
                        if (response.code == 1) {
                            alert(response.message);
                            $('#edit_exampleModal').modal('hide');
                            $('#edit_user')[0].reset();
                            setTimeout(function() {
                                location.reload();
                            }, 1000);
                        } else {
                            alert(response.message);
                        }
                    },
                    error: function() {
                        alert("something went wrong");
                    }
                });
            }
        }
    });

    </script>

</body>
</html>
