<?php
require_once 'core/init.php';
?>
<?php
include 'includes/head.php';
include 'includes/navigation.php';
include 'includes/partial_header.php';

if (isset($_GET['cat'])) {
  $cat_id = sanitize($_GET['cat']);
}else {
  $cat_id = '';
}

$categoryQ = $db->query("select * from products where categories='$cat_id'");
$category = get_category($cat_id); 

?>

      <div class="container-fluid">
      <?php
         include 'includes/leftbar.php';

      ?>
         
         <!--this is going to be main content showing products-->
          <div class="col-md-8"> 
            <div class="row">
              <h2 class="text-center"><?=$category['parent'].' '. $category['child']; ?></h2>
              <?php while($products = mysqli_fetch_assoc($categoryQ)) : ?>
              <div class="col-md-3 text-center">
              
              <h4><?= $products['title']; ?></h4>
              <?php $photos = explode(',', $products['image']);?>
              <img src="<?= $photos[0]; ?>" alt="<?= $products['title']; ?>" class="img-thumb" />
              <p class="list-price text-danger">List Price: <s>Rs <?= $products['last_price']; ?></s></p>
              <p class="price">New price: Rs <?= $products['price']; ?></p>
              <button class="btn btn-sm btn-success" data-toggle="modal" onclick="detailmodal(<?= $products['id']; ?>)" data-target="#details">Details</button>
              
              </div>
            <?php endwhile; ?>
            </div>
          </div>
           <?php
           include 'includes/rightbar.php';
           ?>
      </div>
      <?php 
          include 'includes/footer.php';
      ?>
      