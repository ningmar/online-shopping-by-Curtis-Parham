
      <div><footer class="text-center jumbotron" id="footer">&copy; Copyright 2016 wenepa nepal online shop</footer></div>

         <script type="text/javascript">
   	$(window).scroll(function(){
   		var vscroll = $(this).scrollTop();
   		$('#logotext').css({
   			"transform" : "translate(0px, "+vscroll/2+"px)"
   		});
   	});
   	function detailmodal(id){
   		var data = {"id" :id};
   		$.ajax({
   			url: '/ecommerce/includes/detailmodel.php',
   			method: "post",
   			data: data,
   			success: function(data){
   				$('body').append(data);
   				$('#details').modal('toggle');
   			},
   			error: function(){
   				alert('Something went wrong here');
   			}
   		});
   	}

      function update_cart(mode,edit_id,edit_size){ //extra pains 
         var data = {"mode": mode, "edit_id" : edit_id, "edit_size" : edit_size};
         $.ajax({
            url : '/ecommerce/admin/parsers/update_cart.php',
            method : "post",
            data : data,
            success : function(){
               location.reload();
            },
            error : function(){alert("Something went wrong")},
         });
      }

      function add_to_cart(){
         $('#modal_errors').html("");
         var size = $('#size').val();
         var quantity = $('#quantity').val();
         var available = $('#available').val();
         var error = '';
         var data = $('#add_product_form').serialize();
         if (size == '' || quantity =='' || quantity == 0) {
            error = '<p class="text-danger text-center">You must choose a size and quantity.</p>';
            $('#modal_errors').html(error);
            return;
         } else if (quantity > available) {
            error += '<p class="text-center text-danger"> There are only '+available+' available.</p>';
            $('#modal_errors').html(error);
            return;
         }else {
            $.ajax({
               url : '/ecommerce/admin/parsers/addtocart.php',
               method : "post",
               data : data,
               success : function(){
                  location.reload();
               },
               error :function(){alert("Something went wrong");}
            });
         }
      }
   </script>
</body>
</html>