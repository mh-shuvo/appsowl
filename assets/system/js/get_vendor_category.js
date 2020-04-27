
$(document).ready(function() {

    $('select[name="vendor_id"]').on('change', function(){
        var vendor_id = $(this).val();
        if(vendor_id) {
            $.ajax({
                url: '/kj/admin/vendor_category/'+vendor_id,
                type:"GET",
                dataType:"json",
                beforeSend: function(){
                    $('#loader').css("visibility", "visible");
                },

                success:function(data) {

                    $('select[name="vendor_category"]').empty();
                 /*data receive without array*/
                    
                           $.each(data, function(key, value){

                        $('select[name="vendor_category"]').append('<option value="'+ key +'">' + value + '</option>');

                    }); 
                },               
                complete: function(){
                    $('#loader').css("visibility", "hidden");
                }
            });
        } else {
            $('select[name="vendor_category"]');
        }

    });

});