
$(document).ready(function() {

    $('select[name="productCategory"]').on('change', function(){
        var productCategory = $(this).val();
        if(productCategory) {
            $.ajax({
                url: '/kj/sub_category/'+productCategory,
                type:"GET",
                dataType:"json",
                beforeSend: function(){
                    $('#loader').css("visibility", "visible");
                },

                success:function(data) {

                    $('select[name="productSubCategory"]').empty();
                 /*data receive without array*/
                    
                           $.each(data, function(key, value){

                        $('select[name="productSubCategory"]').append('<option value="'+ key +'">' + value + '</option>');

                    }); 
                },               
                complete: function(){
                    $('#loader').css("visibility", "hidden");
                }
            });
        } else {
            $('select[name="productSubCategory"]');
        }

    });

});