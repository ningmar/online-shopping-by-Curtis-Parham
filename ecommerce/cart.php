<?php
  require_once 'core/init.php';
  include 'includes/head.php';
  include 'includes/navigation.php';
  include 'includes/partial_header.php';

  if ($cart_id != '') {
  	$cartQuery = $db->query("select * from cart where id = '{$cart_id}'");
  	$result = mysqli_fetch_assoc($cartQuery);
  	$items = json_decode($result['items'],true);
  	$i = 1;
  	$sub_total = 0;
  	$item_count = 0;
  }
?>

<div class="col-md-12">
	<div class="row">
		<h2 class="text-center">My Shopping Cart</h2><hr>
		<?php if ($cart_id == ''): ?>
              <div class="bg-danger">
              	<p class="text-center text-danger">
              		Your shopping cart is empty!
              	</p>
              </div>
		<?php else: ?>
		<table class="table table-bordered table-condensed table-striped">
			<thead>
				<th>#</th><th>Items</th><th>Price</th><th>Quantity</th><th>Size</th><th>Sub Total</th>
			</thead>
			<tbody>
				<?php foreach($items as $item) {
					$product_id = $item['id'];
					$productQ = $db->query("select * from products where id = '{$product_id}'");
					$product = mysqli_fetch_assoc($productQ);
					$sArray = explode(',',$product['sizes']);
					foreach ($sArray as $sizeString) {
						$s = explode(':',$sizeString);
						if ($s[0] == $item['size']) {
							$available = $s[1];
						}
					}
					?>
					<tr>
						<td><?=$i; ?></td>
						<td><?=$product['title']; ?></td>
						<td><?=money($product['price']); ?></td>
						<td>
						    <button class="btn btn-xs btn-default" onclick="update_cart('removeone','<?=$product['id'];?>','<?=$item['size'];?>')">-</button>
							<?=$item['quantity']; ?>
							<?php if($item['quantity'] < $available): ?>
							<button class="btn btn-xs btn-default" onclick="update_cart('addone','<?=$product['id'];?>','<?=$item['size'];?>')">+</button>
						<?php else: ?>
							<span class="text-danger">Maximum</span>
						<?php endif; ?>
						</td>
						<td><?=$item['size']; ?></td>
						<td><?=money($item['quantity'] * $product['price']); ?></td>
					</tr>
					<?php 
					$i++;
					$item_count += $item['quantity'];
					$sub_total += ($product['price'] * $item['quantity']);
				}
				$tax = TAXRATE * $sub_total;
				//$tax = number_format($tax,2);
				$grand_total = $tax + $sub_total;
				?>				
			</tbody>
		</table>					
				<table class="table table-bordered table-condensed text-right">
				<legend>Totals</legend>
					<thead class="totals-table-header">
						<th>Total Items</th><th>Sub Total</th><th>Tax</th><th>Grand Total</th>
					</thead>
					<tbody>
						<tr>
							<td><?=$item_count; ?></td>
							<td><?=money($sub_total); ?></td>
							<td><?=money($tax); ?></td>
							<td class="bg-success"><?=money($grand_total); ?></td>
						</tr>
					</tbody>
				</table>

<!-- Check Out Button trigger modal -->
<button type="button" class="btn btn-primary btn-md pull-right" data-toggle="modal" data-target="#checkoutModal">
  Check Out >>
</button>

<!-- Modal -->
<div class="modal fade" id="checkoutModal" tabindex="-1" role="dialog" aria-labelledby="checkoutModalLabel">
  <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="myModalLabel">Shipping Address</h4>
      </div>
      <div class="modal-body">
        <div class="row">
        	<form action="thankyou.php" id="thankyou-form" method="post">
        	<span class="bg-danger" id="payment-errors"></span>
          <input type="hidden" name="tax" value="<?=$tax;?>">
          <input type="hidden" name="sub_total" value="<?=$sub_total;?>">
          <input type="hidden" name="grand_total" value="<?=$grand_total;?>">
          <input type="hidden" name="cart_id" value="<?=$cart_id;?>">
          <input type="hidden" name="description" value="<?=$item_count.' Item'.(($item_count>1)?'s':'').' from wenepa shop.';?>">
        		<div id="step1" style="display: block;">
        			<div class="form-group col-md-6">
        				<label for="full_name">Full Name:</label>
        				<input type="text" name="full_name" id="full_name" class="form-control">
        			</div>
        			<div class="form-group col-md-6">
        				<label for="email">Email:</label>
        				<input type="email" name="email" id="email" class="form-control">
        			</div>
        			<div class="form-group col-md-6">
        				<label for="phone">Phone:</label>
        				<input type="text" name="phone" id="phone" class="form-control">
        			</div>
        			<div class="form-group col-md-6">
        				<label for="street1">Street 1:</label>
        				<input type="text" name="street1" id="street1" class="form-control">
        			</div>
        			<div class="form-group col-md-6">
        				<label for="street2">Street 2:</label>
        				<input type="text" name="street2" id="street2" class="form-control">
        			</div>
        			<div class="form-group col-md-6">
        				<label for="city">City:</label>
        				<input type="text" name="city" id="city" class="form-control">
        			</div>
        			
        		</div>
        		<div id="step2" style="display: none;">
        			<div class="form-group col-md-3">
        				<label for="name">Name on Card:</label>
        				<input type="text" name="name" id="name" class="form-control">
        			</div>
        			<div class="form-group col-md-3">
        				<label for="name">Card Number:</label>
        				<input type="text" name="cnumber" id="number" class="form-control">
        			</div>
        			<div class="form-group col-md-2">
        				<label for="cvc">CVC:</label>
        				<input type="text" name="" id="cvc" class="form-control">
        			</div>
        			<div class="form-group col-md-2">
        				<label for="exp_month">Expire Month:</label>
        				<select id="exp_month" class="form-control">
        					<option value=""></option>
        					<?php for ($i=1; $i < 13; $i++): ?> 
        						<option value="<?=$i;?>"><?=$i;?></option>
        					<?php endfor; ?>
        				</select>
        			</div>
        			<div class="form-group col-md-2">
        				<label for="exp_year">Expire Year:</label>
        				<select id="exp_year" class="form-control">
        					<option value=""></option>
        					<?php $yr = date("y"); ?>
        					<?php for ($i=0; $i < 11; $i++): ?> 
        						<option value="<?=$yr+$i;?>"><?=$yr+$i;?></option>
        					<?php endfor; ?>
        				</select>
        			</div>
        		</div>
        	
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" onclick="check_address();" id="next_button">Next >></button>
        <button type="button" class="btn btn-primary" onclick="back_address();" id="back_button" style="display: none;"><< Back</button>
        <button type="submit" class="btn btn-primary" id="check_out_button" style="display: none;">Check Out >></button>
        </form>
      </div>
    </div>
  </div>
</div>

	<?php endif; ?>
	</div>
</div>
</br></br></br></br></br>
<script>

    function back_address() {
    	$('#payment-errors').html("");
					$('#step1').css({display: "block"});
					$('#step2').css({display:"none"});
					$('#next_button').css({display: "inline-block"});
					$('#back_button').css({display:"none"});
					$('#check_out_button').css({display:"none"});
					$('#myModalLabel').html("Shipping Address");
    }

	function check_address() {
		var data = {
			'full_name' : $('#full_name').val(),
			'email' : $('#email').val(),
			'phone' : $('#phone').val(),
			'street1' : $('#street1').val(),
			'street2' : $('#street2').val(),
			'city' : $('#city').val(),
		};
		$.ajax({
			url : '/ecommerce/admin/parsers/check_address.php',
			method : "post",
			data : data,
			success : function(data){
				if (data != 'passed') {
					$('#payment-errors').html(data);

				}
				if (data == 'passed') {
					$('#payment-errors').html("");
					$('#step1').css({display: "none"});
					$('#step2').css({display:"block"});
					$('#next_button').css({display: "none"});
					$('#back_button').css({display:"inline-block"});
					$('#check_out_button').css({display:"inline-block"});
					$('#myModalLabel').html("Enter your card detail");
				}
			},
			error : function(){
        alert('Something went wrong');},
		});
	}

/*  $(function($)) {
    var $form = $(this);
  }*/
</script>
<?php include 'includes/footer.php'; ?>