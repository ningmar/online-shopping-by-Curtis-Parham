
      <footer class="text-center jumbotron" id="footer">&copy; Copyright 2016 wenepa nepal online shop</footer>
      <script type="text/javascript">
      
      //function updatesizes in the button
      function updateSizes(){
      	var sizeString = '';
      	for (var i = 1; i <= 12; i++) {
      		if ($('#size'+i).val() != ''){
      			sizeString += $('#size'+i).val()+':'+$('#qty'+i).val()+':'+$('#threshold'+i).val()+','; 
      		}
      	}
      	$('#sizes').val(sizeString);
      }

      //launch modal on click by calling this function
            function get_child_options(selected) {
                  if (typeof selected == 'undefined') {
                     var selected = '';
                  }
                  
                  var parentId = $('#parent').val();
                  $.ajax({   //jQuery.ajax
                   url: '/ecommerce/admin/parsers/child_categories.php',
                   type: 'POST',
                   data: {parentId : parentId, selected : selected},
                   success: function(data){
                      $('#child').html(data);
                   },
                   errors: function(){
                        alert('Something terribly went wrong with child option.')
                   },
            });
      }
      $('select[name="parent"]').change(function(){
            get_child_options();
      });

    </script>
      </body>
      </hetml>