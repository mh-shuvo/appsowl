/*----- Pos Sales Start---------*/

function AddSalesProductList(productCode,SerialNo = 'no_serial'){
	AS.Http.post({"action" : "GetSalesProductList","product_code" : productCode,"serial_no_check" : SerialNo}, "pos/ajax/", function (response) {
		if(response.product_status == 'found'){
			var product_quantity = $("#product_quantity_"+response.sub_product_id).val() || 0;
			if(response.product_current_stock > product_quantity || response.product_stock == 'enable'){
				if(response.product_serial == 'enable' && response.product_search_type == 'product_code' ){
					
					AS.Http.posthtml({"action" : "GetSerialModal","product_code" : productCode}, "pos/modal/", function (data) {
						$(".serial_modal_status").html(data);
						$(".show_modal").modal("show");
					});
					}else{
					AddSalesRow(response);
				}
				
				AddSalesRowExtraLoad(response.sub_product_id,response);
				
				if(response.product_current_stock == response.alert_quantity){
					swal ( "Oops" ,  $_lang.product_limit+"!" ,  "error" );
					}
				
				}else{
				swal ( "Oops" ,  $_lang.out_of_stock+"!" ,  "error" );
			}
			
			}else if(response.product_status == "multi_product_serial"){
				AS.Http.posthtml({"action" : "GetMultiSerialModal","product_code" : productCode}, "pos/modal/", function (data) {
						$(".serial_modal_status").html(data);
						$(".show_modal").modal("show");
					});
			}else{
			swal ( "Oops" ,  $_lang.no_product_found+"!" ,  "error" );
		}
	});
}

function IDGenerator() {
	
	this.length = 10;
	this.timestamp = +new Date;
	
	var _getRandomInt = function( min, max ) {
		return Math.floor( Math.random() * ( max - min + 1 ) ) + min;
	}
	
	this.generate = function() {
		var ts = this.timestamp.toString();
		var parts = ts.split( "" ).reverse();
		var id = "";
		
		for( var i = 0; i < this.length; ++i ) {
			var index = _getRandomInt( 0, parts.length - 1 );
			id += parts[index];	 
		}
		
		return id;
	}
}

function GetCustomerReceiptViews(el) {
	var id = $(el).attr('sales_id');
	// alert(id);
	$("#pos-print").removeClass('hidden');
	jQuery.ajax({
		url: "pos/ajax/",
		data: {
			action: "GetCustomerReceiptViews",
			sales_id: id,
		},
		type: "POST",
		success:function(data){
			$(".last_receipt_view").html(data);		
			$.print("#pos-print");
			
		},
		error:function (){}
	});
	
}

/*----- Pos Sales End---------*/



$(document).on("click",".change_lang", function(){
	var Id = $(this).attr("id");
	$.get(Id, function(){ 
		location.reload();
	});
});
function GoInFullscreen(element) {
	if(element.requestFullscreen)
	element.requestFullscreen();
	else if(element.mozRequestFullScreen)
	element.mozRequestFullScreen();
	else if(element.webkitRequestFullscreen)
	element.webkitRequestFullscreen();
	else if(element.msRequestFullscreen)
	element.msRequestFullscreen();
}

/* Get out of full screen */
function GoOutFullscreen() {
	if(document.exitFullscreen)
	document.exitFullscreen();
	else if(document.mozCancelFullScreen)
	document.mozCancelFullScreen();
	else if(document.webkitExitFullscreen)
	document.webkitExitFullscreen();
	else if(document.msExitFullscreen)
	document.msExitFullscreen();
}

/* Is currently in full screen or not */
function IsFullScreenCurrently() {
	var full_screen_element = document.fullscreenElement || document.webkitFullscreenElement || document.mozFullScreenElement || document.msFullscreenElement || null;
	
	// If no element is in full-screen
	if(full_screen_element === null)
	return false;
	else
	return true;
}

$(".toggle-fullscreen").on('click', function() {
	if(IsFullScreenCurrently())
	GoOutFullscreen();
	else
	GoInFullscreen($("#wrapper").get(0));
});

/****************************
	
	DELETE CONTACT 
	
***********************/






$('.account_lock').click(function (){
	
	jQuery.ajax({
		url: "pos/ajax/",
		data: {
			action	: "account_lock",
			lock_status	: "locked"
		},
		success:function(){
			location.reload();
		},
	});
	
});

function logout_confirmation()
{
	swal({
		title: $_lang.logout,
		text: "",
		type: "warning",
		showCancelButton: true,
		confirmButtonColor: "#DD6B55",
		confirmButtonText: $_lang.yes,/*"Yes!"*/
		cancelButtonText:$_lang.no ,/*"No!"*/
		closeOnConfirm: false,
	closeOnCancel: false },
	function (isConfirm) {
		if (isConfirm) {
			location.href='logout/';
			
			} else {
			swal(
			/*$_lang.cancelled, "", "error"*/
			{
				title: $_lang.cancelled,
				text: "",
				type: "warning",
				confirmButtonColor: "#DD6B55",
				confirmButtonText: $_lang.ok,/*"Yes!"*/
			}
			); /*"বাতিল করা হয়েছে"*/
		}
	});
}

function CheckUnite(){
	
	var unite = $("#product_unit_name").val();
	AS.Http.post({
		action: "GetCheckUnite",
		unite_name: unite
		},"pos/ajax/", function (result) {
		if(result.status == '1'){
			$("#unit_id").val(result.unit_id);
		}
		else{
			$("#unit_id").val(result.unit_id);
		}
	});
}

function CheckCategory(){
	var category = $("#product_category").val();
	AS.Http.post({
		action: "GetCheckCategory",
		category_name: category
		},"pos/ajax/", function (result) {
		if(result.status == '1'){
			$("#category_id").val(result.category_id);
		}
		else{
			$("#category_id").val(result.category_id);
		}
	});
}


function CheckBrand(){
	var brand = $("#product_brand").val();
	AS.Http.post({
		action: "GetCheckBrand",
		brand_name: brand
		},"pos/ajax/", function (result) {
		if(result.status == '1'){
			$("#brand_id").val(result.brand_id);
		}
		else{
			$("#brand_id").val(result.brand_id);
		}
	});
}


$(document).ready(function() {
    $("#show_hide_password a").on('click', function(event) {
        event.preventDefault();
        if($('#show_hide_password input').attr("type") == "text"){
            $('#show_hide_password input').attr('type', 'password');
            $('#show_hide_password i').addClass( "fa-eye-slash" );
            $('#show_hide_password i').removeClass( "fa-eye" );
			}else if($('#show_hide_password input').attr("type") == "password"){
            $('#show_hide_password input').attr('type', 'text');
            $('#show_hide_password i').removeClass( "fa-eye-slash" );
            $('#show_hide_password i').addClass( "fa-eye" );
		}
	});
});

