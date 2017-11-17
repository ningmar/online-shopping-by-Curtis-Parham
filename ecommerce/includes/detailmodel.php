<!--modal of the product in detail-->
<?php 
   require_once '../core/init.php';
   $id = $_POST['id'];
   $id = (int)$id;
   $query = "SELECT * FROM products WHERE id = '$id'";
   $result = $db->query($query);
   $product = mysqli_fetch_assoc($result);
   $brand_id = $product['brand'];
   $sql ="SELECT brand FROM brand where id = '$brand_id'";
   $bquery = $db->query($sql);
   $brand = mysqli_fetch_assoc($bquery);
   $sizes = $product['sizes'];
   $sizes = rtrim($sizes);
   $sizes_array = explode(',', $sizes);
?>
<?php ob_start(); ?>
      <div class="modal fade detail" id="details" tabindex="-1" role="dialog" aria-labelledby="detail" aria-hidden="true" data-backdrop="static" data-keyboard="false">
        <div class="modal-dialog modal-lg">
        <div class="modal-content">
          <!--modal header-->
          <div class="modal-header">
          <button class="close" type="botton" onclick="closemodal()" aria-label="close"><span aria-hidden="true">&times;</span>
          </button>
          <div class="modal-title text-center"><?= $product['title']; ?></div>
          </div>
          <!--MODAL BODY-->
          <div class="modal-body">
            <div class="container-fluid">
            <div class="row">
            <span id="modal_errors" class="bg-danger"></span>
              <div class="col-sm-6 fotorama">
                <?php $photos = explode(',', $product['image']); 
                  foreach($photos as $photo): ?>
                
                  <img src="<?= $photo; ?>" alt="<?= $product['title']; ?>" class="details img-responsive">
                
              <?php endforeach; ?>
              </div>
              <div class="col-sm-6">
                <h4>Details</h4>
                <p><?= nl2br($product['description']); ?> </p>
                <hr>
                <p>Price: Rs <?= $product['price']; ?></p>
                <p>Brand: <?= $brand['brand']; ?></p>

                <form action="addtocart.php" method="post" id="add_product_form">
                <input type="hidden" name="product_id" value="<?=$id; ?>">
                <input type="hidden" name="available" id="available" value="">
                  <div class="form-group">
                  <div class="col-xs-3">
                    <label for="quantity">Quantity:</label>
                    <input type="number" class="form-control" min="0" id="quantity" name="quantity">
                  </div>
                  
                  </div><br><br><br>
                  <div class="form-group">
                    <label for="size">Size:</label>
                    <select name="size" id="size" class="form-control">
                      <option value=""></option>
                      <?php foreach($sizes_array as $string){ 

                                  $string_array = explode(':', $string);
                                  $size = $string_array[0];
                                  $available = $string_array[1];
                                  if($available > 0) {
                                  echo '<option value="'.$size.'" data-available="'.$available.'">'.$size.' ('.$available.' Available)</option>';
                                }
                     } ?>
                      </select>
                  </div>
                </form>
              </div>
            </div>
            </div>
          </div>
          <!--MODAL FOOTER-->
          <div class="modal-footer">
            <button class="btn btn-default" onclick="closemodal()">Close</button>
            <button class="btn btn-warning" onclick="add_to_cart();return false;"><span class="glyphicon glyphicon-shopping-cart"></span> Add To Cart</button>
          </div>
        </div>
        </div>
      </div> 

      <script type="text/javascript">

      $('#size').change(function(){
        var available = $('#size option:selected').data("available");
        $('#available').val(available);
      });

$(function () {
  $('.fotorama').fotorama({'loop':true,'autoplay':true});
});

        function closemodal(){
            $('#details').modal('hide');
            setTimeout(function(){
              $('#details').remove();
              $('.modal-dropback').remove();
            },500);
        }
      </script>                             
<?php echo ob_get_clean(); ?>