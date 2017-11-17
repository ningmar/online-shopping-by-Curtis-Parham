<?php
require_once 'core/init.php';
?>
<?php
include 'includes/head.php';
include 'includes/navigation.php';
include 'includes/header.php';


$sql = "SELECT * FROM products WhERE featured = 1";
$featured = $db->query($sql);
?>

      <div class="container-fluid">
      <?php
         include 'includes/leftbar.php';

      ?>
         
         <!--this is going to be main content showing products-->
          <div class="col-md-8"> 
            <div class="row">
              <h2 class="text-center">Features products</h2>
              <?php while($products = mysqli_fetch_assoc($featured)) : ?>
              <div class="col-md-3 text-center">
              
              <h4><?= $products['title']; ?></h4>
              <?php $photos = explode(',', $products['image']); ?>
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
      