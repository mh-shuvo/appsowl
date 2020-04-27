// $("#soft_package").change(function(){
	// var pk= $('#soft_package').val();
	// var price = $('#software_price').val();
	// $("#total1").html(pk*price);
	// $("#total2").val(pk*price);
// });
/**
	* Validate and update the category form.
*/

$("#app_reg form").validate({
    rules: {
        soft_package: {
            required: true
		},
		subscribe_payment: {
            required: true
		},
		payment_type: {
            required: true
		}
	},	
    submitHandler: function(form) {
		AS.Http.post(SoftwareSubmit(form), function (result) {
			swal(result.postmsg, "", "success");
			$('.confirm').click(function () {
				location.replace('subscribe-pos-setting');
			});
		}); 
		
	}
});

function SoftwareSubmit(form) {
    return {
        action: "softwareSubscribe",
        data: {
            software_id: form['software_id'].value,
            soft_package: form['soft_package'].value,
            subscribe_payment: form['subscribe_payment'].value,
            payment_type: form['payment_type'].value
		}
	};
}

function getcheckdomain() {
	var domainname = $("#domain_name").val();
	domainname = domainname.replace(/\s/g, '');
	
	jQuery.ajax({
		url: "ajax/",
		data: {
			action	 : "GetDomainCheck",
			domain_name  : domainname
		},
		type: "POST",
		success:function(data){
			$(".domainstatus").html(data);
		},
		error:function (){}
	});
}
function seturl(){
	var company_name = $("#company_name").val();
	company_name = company_name.replace(/\s/g, '');
	$("#domain_name").val(company_name.toLowerCase());
	getcheckdomain();
}



$("#vat_type").change(function(){
	if($("#vat_type").val()=='global'){
		$("#var_area").show();
	}
	else{
		$("#var_area").hide();
	}
});

function getcountrycode(){
	var countrycode = $("#country_code").val();
	$("#reg_phone").val(countrycode);
	
}

$('#admin_account_update form').validate({
	rules:{
		first_name: {
            required: true
		},
		last_name: {
            required: true
		},
		user_mobile:{
			required: true
		},
		user_email:{
			email: true
		},
		user_address:{
			required: true
		},
	},
	submitHandler: function(form) {
        AS.Http.submit(form, admin_account_update(form), false, function (response) {
            AS.Util.displaySuccessMessage($(form), response.message);
		});
		
	}
});
$('.agentform form').validate({
	rules:{
		agent_name: {
            required: true
		},
		agent_phone: {
            required: true
		},
		agent_present_address:{
			required: true
		},
		agent_email:{
			email: true
		},
		agent_nid:{
			required: true
		},
		agent_zone:{
			required: true
		},
		agent_area:{
			required: true
		},
		agent_password:{
			required: true,
			minlength: 6
		},
		confirm_password:{
			required: true,
			minlength: 6
		},
		
	},
	submitHandler: function(form) {
        AS.Http.PostSubmit(form, {action: "agent_registration"}, false, function (response) {
			  swal(response.message, "", response.status);
					$('.confirm').click(function () {
						location.href='/';
					});
		});
		
	}
});

function admin_account_update(form) {
	return {
        action: "admin_account_update",
        data: {
			first_name: form['first_name'].value,
			last_name: form['last_name'].value,
			phone: form['user_mobile'].value,
			email: form['user_email'].value,
			address: form['user_address'].value
		}
	};
}

$('#account_update form').validate({
	rules:{
		first_name: {
            required: true
		},
		last_name: {
            required: true
		},
		user_mobile:{
			required: true
		},
		user_email:{
			email: true
		},
		user_address:{
			required: true
		},
	},
	submitHandler: function(form) {
        AS.Http.submit(form, account_update(form), false, function (response) {
            AS.Util.displaySuccessMessage($(form), response.message);
		});
		
	}
});
function account_update(form) {
	return {
        action: "account_update",
        data: {
			first_name: form['first_name'].value,
			last_name: form['last_name'].value,
			phone: form['user_mobile'].value,
			email: form['user_email'].value,
			address: form['user_address'].value
		}
	};
}

$("#change-password-form form").validate({
	rules: {
        old_password: "required",
        new_password: {
			required: true,
			minlength: 6
		},
        new_password_confirmation: "required"
	},
	submitHandler: function(form) {
        AS.Http.submit(form, getChangePasswordFormData(form), false, function () {
			AS.Util.displaySuccessMessage($(form), $_lang.password_updated_successfully);
		});
	}
});

function getChangePasswordFormData(form) {
	return {
        action: "updatePassword",
        old_password: AS.Util.hash(form['old_password'].value),
        new_password: AS.Util.hash(form['new_password'].value),
        new_password_confirmation: AS.Util.hash(form['new_password_confirmation'].value)
	};
}
$("#change-withdrawal-password form").validate({
	rules: {
        old_password: "required",
        new_password: {
			required: true,
			minlength: 6
		},
        new_password_confirmation: "required"
	},
	submitHandler: function(form) {
        AS.Http.submit(form, getChangeWithdrawalPasswordFormData(form), false, function () {
			AS.Util.displaySuccessMessage($(form), $_lang.password_updated_successfully);
		});
	}
});

function getChangeWithdrawalPasswordFormData(form) {
	return {
        action: "updateWithdrawalPassword",
        old_password: AS.Util.hash(form['old_password'].value),
        new_password: AS.Util.hash(form['new_password'].value),
        new_password_confirmation: AS.Util.hash(form['new_password_confirmation'].value)
	};
}
$("#withdrawal-password form").validate({
	rules: {
        new_password: {
			required: true,
			minlength: 6
		},
        new_password_confirmation: "required"
	},
	submitHandler: function(form) {
        AS.Http.submit(form, getWithdrawalPasswordFormData(form), false, function (result) {
			AS.Util.displaySuccessMessage($(form), $_lang.password_updated_successfully);
		});
	}
});

function getWithdrawalPasswordFormData(form) {
	return {
        action: "withdrawalPassword",
        new_password: AS.Util.hash(form['new_password'].value),
        new_password_confirmation: AS.Util.hash(form['new_password_confirmation'].value)
	};
}

$("#admin-change-password-form form").validate({
	rules: {
        old_password: "required",
        new_password: {
			required: true,
			minlength: 6
		},
        new_password_confirmation: "required"
	},
	submitHandler: function(form) {
        AS.Http.submit(form, getAdminChangePasswordFormData(form), false, function () {
			AS.Util.displaySuccessMessage($(form), $_lang.password_updated_successfully);
		});
	}
});

function getAdminChangePasswordFormData(form) {
	return {
        action: "updateAdminPassword",
        old_password: AS.Util.hash(form['old_password'].value),
        new_password: AS.Util.hash(form['new_password'].value),
        new_password_confirmation: AS.Util.hash(form['new_password_confirmation'].value)
	};
}

$("#user-change-password-form form").validate({
	rules: {
        new_password: {
			required: true,
			minlength: 6
		},
        new_password_confirmation: "required"
	},
	submitHandler: function(form) {
        AS.Http.submit(form, getChangePasswordFormData(form), false, function () {
			AS.Util.displaySuccessMessage($(form), $_lang.password_updated_successfully);
		});
	}
});

function getChangePasswordFormData(form) {
	return {
        action: "updateAdminUserPassword",
		user_id: form['user_id'].value,
        new_password: AS.Util.hash(form['new_password'].value),
        new_password_confirmation: AS.Util.hash(form['new_password_confirmation'].value)
	};
}

$('#manage_user form').validate({
    rules: {
        first_name: {
            required: true
		},
		last_name: {
            required: true
		},
        user_mobile: {
			required: true
		},		
		user_address: {
            required: true
		},
        user_password: {
			required: true
		},
        confirm_password: {
			required: true
		}
	},	
    submitHandler: function(form) {
        AS.Http.submit(form, getUserSubmit(form), false, function (response) {
            AS.Util.displaySuccessMessage($(form), response.message);
		});		
	}
});

function getUserSubmit(form) {
    return {
        action: "UserSubmit",
        data: {
            first_name: form['first_name'].value,
            user_id: "null",
            last_name: form['last_name'].value,
            user_mobile: form['user_mobile'].value,
            user_email: form['user_email'].value,
            user_address: form['user_address'].value,
            user_password: AS.Util.hash(form['user_password'].value),
            confirm_password: AS.Util.hash(form['confirm_password'].value),
            pos_sale: form['pos_sale'].value,
            pos_category: form['pos_category'].value,
            pos_unit: form['pos_unit'].value,
            pos_product: form['pos_product'].value,
            pos_supplier: form['pos_supplier'].value,
            pos_stock: form['pos_stock'].value,
            pos_customer: form['pos_customer'].value,
            pos_return: form['pos_return'].value,
            pos_damage: form['pos_damage'].value,
            pos_report: form['pos_report'].value
		}
	};
}

$('#manage_update_user form').validate({
    rules: {
        first_name: {
            required: true
		},
		last_name: {
            required: true
		},
        user_mobile: {
			required: true
		},		
		user_address: {
            required: true
		}
	},	
    submitHandler: function(form) {
        AS.Http.submit(form, getUpdateUserSubmit(form), false, function (response) {
            AS.Util.displaySuccessMessage($(form), response.message);
		});
		
	}
});

function getUpdateUserSubmit(form) {
    return {
        action: "UserSubmit",
        data: {
            first_name: form['first_name'].value,
            user_id: form['user_id'].value,
            last_name: form['last_name'].value,
            user_mobile: form['user_mobile'].value,
            user_email: form['user_email'].value,
            user_address: form['user_address'].value,
            pos_sale: form['pos_sale'].value,
            pos_category: form['pos_category'].value,
            pos_unit: form['pos_unit'].value,
            pos_product: form['pos_product'].value,
            pos_supplier: form['pos_supplier'].value,
            pos_stock: form['pos_stock'].value,
            pos_customer: form['pos_customer'].value,
            pos_return: form['pos_return'].value,
            pos_damage: form['pos_damage'].value,
            pos_report: form['pos_report'].value
		}
	};
}

$('.user_delete').click(function () {
	var Id = $(this).attr("user_id");
	swal({
		title: $_lang.are_you_sure,
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
			$.ajax({
				url: "ajax/",
				data: {
					action	: "GetDeleteUser",
					id	: Id,
				},
				type: "POST",
				success:function(data){
					
					swal($_lang.deleted, "", "success");
					$('.confirm').click(function () {
						location.reload();
					});
				},
				error:function (){}
			});
			
			} else {
			swal($_lang.cancelled, "", "error"); /*"বাতিল করা হয়েছে"*/
		}
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

$("#create_user form").validate({
    rules: {
        country_code: {
            required: true
		},
		reg_gender: {
            required: true
		},
		reg_birthday: {
            required: true
		},
		reg_birthmonth: {
			required: true
		},
		reg_birthyear: {
			required: true
		},
		reg_first_name: {
			required: true
		},
		reg_last_name: {
            required: true
		},
        reg_phone: "required",
        password: {
            required: true,
            minlength: 6
		},
		password_confirmation: {
            required: true
		}
	},
    submitHandler: function(form) {
        AS.Http.submit(form, getRegisterFormData(form), false, function (result) {
            swal({
				title: 'Success',
				text: result.page,
				type: "success",
				confirmButtonText: "OK"
			},
			function (isConfirm) {
				if (isConfirm) {
					location.replace('agent/user-info');
				} 
			});
		});
	}
});

/**
	* Get registration form data as JSON.
	* @param form
*/
function getRegisterFormData(form) {
    return {
        action: "registerUser",
        user: {
            reg_first_name: form['reg_first_name'].value,
            reg_last_name: form['reg_last_name'].value,
            reg_gender: form['reg_gender'].value,
            reg_birthday: form['reg_birthday'].value,
            reg_birthmonth: form['reg_birthmonth'].value,
            reg_birthyear: form['reg_birthyear'].value,
            reg_email: form['reg_email'].value,
            country_code: form['country_code'].value,
            reg_phone: form['reg_phone'].value,
            password: AS.Util.hash(form['password'].value),
            password_confirmation: AS.Util.hash(form['password_confirmation'].value)
		}
	};
}

$('.user_subs_soft').click(function () {
	var Id = $(this).attr("id");
	
	jQuery.ajax({
		url: "ajax/",
		data: {
			action	: "usersubscrib",
			id	: Id,
		},
		type: "POST",
		success:function(data){
			$("#usersublist").html(data);
			$('#user_soft_view').modal('show');
		},
		error:function (){}
	});
	
});




function GetAgentIdCard(id)
{
	var agent_id = $(id).attr('agent_id');
		$.ajax({
				url: "ajax/",
				data: {
					action	: "GetAgentIdCard",
					id	: agent_id,
				},
				type: "POST",
				success:function(data){
					$(".last_receipt_view").html(data);		
					$.print("#pos-print");
					
				},
				error:function (){}
			});
}
$('#reg_terms').click(function(){
	var flag = $(this).prop('checked');
	$('#reg_submit').prop( "disabled", true );
	if(flag==true){
		$('#reg_submit').prop( "disabled", false );
	}
	else{
		$('#reg_submit').prop( "disabled", true );
	}
});
