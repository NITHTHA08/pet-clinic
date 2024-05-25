<?php

require_once("../components/database.php");

if(isset($_POST['submit'])){
    $name = $_POST['name'];
    $name = filter_var($name, FILTER_SANITIZE_STRING);
    $price = $_POST['price'];
    $price = filter_var($price, FILTER_SANITIZE_STRING);

    $image = $_FILES['image']['name'];
    $image = filter_var($image, FILTER_SANITIZE_STRING);
    $image_size = $_FILES['image']['size'];
    $image_temp_name = $_FILES['image']['tmp_name'];
    $image_folder = 'product_img/'.$image;

    $select_products = $conn->prepare("SELECT * FROM `product` WHERE name = ?");
    $select_products->bind_param("s", $name); // Binding parameters
    $select_products->execute(); 

    $select_products->store_result(); // Store result to get num_rows
    if($select_products->num_rows > 0){
       $message[] = 'Product already exists'; 
    } else {
        if($image_size > 5000000){
            $message[] = 'Image size exceeds the limit';
        } else {
            move_uploaded_file($image_temp_name, $image_folder);
            $add_product = $conn->prepare("INSERT INTO `product` (name, price, image) VALUES (?, ?, ?)");
            $add_product->bind_param("sss", $name, $price, $image); // Binding parameters
            $add_product->execute(); 

            $message[] = 'Successfully added';
        }
    } 
}

?>



<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>dashboard</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="../css/admin_style.css">

</head>
<body> 
    <?php include("../components/admin_header.php");?>


  <section class="add-products">

        <form action="" method="POST" enctype="multipart/form-data">
        <h3>add product</h3>
        <input type="text" required placeholder="enter product name" name="name" maxlength="100" class="box">
        <input type="number" min="0" max="9999999999" required placeholder="enter product price" name="price" onkeypress="if(this.value.length == 10) return false;" class="box">
        <input type="file" name="image" class="box" accept="image/jpg, image/jpeg, image/png, image/webp" required>
        <input type="submit" value="add product" name="submit" class="btn">
        </form>

  </section>

</body>