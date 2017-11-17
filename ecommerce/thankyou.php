<?php 

require_once 'core/init.php';

   $fullname = sanitize($_POST['full_name']);
   $email = sanitize($_POST['email']);
   $phone = sanitize($_POST['phone']);
   $street1 = sanitize($_POST['street1']);
   $street2 = sanitize($_POST['street2']);
   $city = sanitize($_POST['city']);
   $tax = sanitize($_POST['tax']);
   $sub_total = sanitize($_POST['sub_total']);
   $grand_total = sanitize($_POST['grand_total']);
   $cart_id = sanitize($_POST['cart_id']);
   $description = sanitize($_POST['description']);
  // $charge_amount = number_format($grand_total,2) * 100;
   $metadata = array(
   	'cart_id'    =>  $cart_id,
   	'tax'        =>  $tax,
   	'sub_total'  =>  $sub_total,
   );

//update inventory
   $itemQ = $db->query("select * from cart where id='{$cart_id}'");
   $iresults = mysqli_fetch_assoc($itemQ);
   $items = json_decode($iresults['items'], true);
   foreach ($items as $item) {
      $newSizes = array();
      $item_id = $item['id'];
      $productQ = $db->query("select sizes from products where id = '{$item_id}'");
      $product = mysqli_fetch_assoc($productQ);
      $sizes = sizesToArray($product['sizes']);
      foreach ($sizes as $size) {
         if ($size['size'] == $item['size']) {
            $q = $size['quantity'] - $item['quantity'];
            $newSizes[] = array('size' => $size['size'],'quantity' => $q);
         } else {
            $newSizes[] = array('size' => $size['size'],'quantity' => $size['quantity']);
         }
      }
      $sizeString = sizesToString($newSizes);
      $db->query("update products set sizes = '{$sizeString}' where id = '{$item_id}'");
   }
//updating cart 
   $db->query("update cart set paid=1 where id = '{$cart_id}'");
   $db->query("insert into transactions
      (cart_id,full_name,email,phone,street1,street2,city,sub_total,tax,grand_total,description) values 
      ('$cart_id','$fullname','$email','$phone','$street1','$street2','$city','$sub_total','$tax','$grand_total','$description')");
   
   $domain = ($_SERVER['HTTP_HOST'] != 'localhost')? '.'.$_SERVER['HTTP_HOST']:false;
   setcookie(CART_COOKIE,'',1,'/',$domain,false);
 
   include 'includes/head.php';
   include 'includes/navigation.php';
   include 'includes/partial_header.php';


?>

  <h1 class="text-center text-success">Thank You!</h1>
  <p> Your cart has been successfully charged <?=money($grand_total);?>. You have been mailed and </p>
  <p>Your receipt number is:<strong><?=$cart_id;?></strong></p>
  <p>Your order will be shipped to the address below:</p>
  <address>
     <?=$fullname;?> <br>
     <?=$street1;?> <br>
     <?=(($street2 != '')?$street2.'<br>':''); ?>
     <?=$city;?>
  </address>

<?php include 'includes/footer.php';
?>