<?php
include('includes/DB.php');
$db = new DB();

if (isset($_POST['action']) && $_POST['action'] == 'add_user') {

    $name = $db->validate_data($_POST['name']);
    $phone = $db->validate_data($_POST['phone']);
    $email = $db->validate_data($_POST['email']);
    $address = $db->validate_data($_POST['address']);
    $role = $db->validate_data($_POST['role']);
    $designation = $db->validate_data($_POST['designation']);
    $gender = $db->validate_data($_POST['gender']);
    $married_status = $db->validate_data($_POST['married']);
    $dob = $db->validate_data($_POST['dob']);

    $photos = $_FILES['uploadLogo'];


    $sql = "INSERT INTO `users`(`name`, `email`, `phone`, `address`, `role`, `designation`, `gender`,  `marital_status`, `dob`) 
                        VALUES ('$name','$email','$phone','$address','$role','$designation','$gender','$married_status','$dob')";        

        if ($user_id = $db->insert($sql)) {
            $uploaded_images = [];

                if (isset($photos['name'][0]) && !empty($photos['name'][0])) {


                    if (!file_exists('uploads')) {
                        mkdir('uploads', 0755, true);
                    }                    

                    for ($i = 0; $i < count($photos['name']); $i++) {
                        $tmpName = $photos['tmp_name'][$i];
                        $fileName = uniqid() . "_" . basename($photos['name'][$i]);
                        $filePath = 'uploads/' . $fileName;

                        if (move_uploaded_file($tmpName, $filePath)) {

                            $tmpName = $db->validate_data($tmpName);
                            $filePath = $db->validate_data($filePath);

                            $sql = "INSERT INTO user_images (user_id, filename, file_path) VALUES ($user_id, '$tmpName', '$filePath')";
                            $db->insert($sql);
                        }
                    }
            }
           
            $return_array = array(
                'code' => 1,
                'message' => 'User added successfully'
            );
        } else {
            $return_array = array(
                'code' => 0,
                'message' => 'Error occurred while adding user'
            );
        }

        echo json_encode($return_array);
        exit;

}




if (isset($_POST['id']) &&  $_POST['action'] == 'user_delete') {

    $id = $_POST['id'];

    $sql = "SELECT file_path FROM user_images WHERE user_id = $id";
    $images =   $db->select($sql);

    if(!empty($images)){
        foreach($images as $image){
            $image_path = $image['file_path'];
            if(file_exists($image_path)){
                unlink($image_path);
            }
        }
    }

    $sql = "DELETE FROM `users` WHERE id='$id'";

    if ($db->delete($sql)) {
        $return_array =  array(
            'code' => 1,
            'message' => 'Deleted Successfully'
        );
    } else {
        $return_array =  array(
            'code' => 0,
            'message' => 'Something went wrong'
        );
    }

    echo json_encode($return_array);
    exit;
}

if(isset($_POST['update_id']) && $_POST['action'] == 'update_user') {

    $id = $_POST['update_id'];
    $name = $db->validate_data($_POST['ename']);
    $phone = $db->validate_data($_POST['ephone']);
    $email = $db->validate_data($_POST['eemail']);
    $address = $db->validate_data($_POST['eaddress']);
    $role = $db->validate_data($_POST['erole']);
    $designation = $db->validate_data($_POST['edesignation']);
    $gender = $db->validate_data($_POST['egender']);
    $married_status = $db->validate_data($_POST['emarried']);
    $dob = $db->validate_data($_POST['edob']);

    $photos = $_FILES['euploadLogo'];
    
    $sql =  "UPDATE `users` SET `name`='$name',`email`='$email',`phone`='$phone',`address`='$address',`role`='$role',
        `designation`='$designation',`gender`='$gender',`marital_status`='$married_status',`dob`='$dob' WHERE id = $id";


    if ($db->update($sql)) {
        $user_id = $id;

        if (isset($photos['name'][0]) && !empty($photos['name'][0])) {

            for ($i = 0; $i < count($photos['name']); $i++) {
                $tmpName = $photos['tmp_name'][$i];
                $fileName = uniqid() . "_" . basename($photos['name'][$i]);
                $filePath = 'uploads/' . $fileName;

                if (move_uploaded_file($tmpName, $filePath)) {

                    $tmpName = $db->validate_data($tmpName);
                    $filePath = $db->validate_data($filePath);

                    $sql = "INSERT INTO user_images (user_id, filename, file_path) VALUES ($user_id, '$tmpName', '$filePath')";
                    $db->insert($sql);
                }
            }
        }

        $return_array =  array(
            'code' => 1,
            'message' => 'Updated Successfully'
        );
    } else {
        $return_array =  array(
            'code' => 0,
            'message' => 'Something went wrong'
        );
    }

    echo json_encode($return_array);
    exit;

}

if(isset($_POST['action']) && $_POST['action']=='remove-file'){
    $filePath = $_POST['file_Path'];
    echo($filePath);
}