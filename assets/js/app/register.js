/**
	* Validate and submit the registration form.
*/
$("#create form").validate({
    rules: {
        country_code: {
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
        password_confirmation: "required"
	},
    submitHandler: function(form) {
        AS.Http.submit(form, getRegisterFormData(form), false, function (result) {
            // AS.Util.displaySuccessMessage($(form), response.message);
			window.location = result.page;
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
            reg_email: form['reg_email'].value,
            country_code: form['country_code'].value,
            reg_phone: form['reg_phone'].value,
            password: AS.Util.hash(form['password'].value),
            password_confirmation: AS.Util.hash(form['password_confirmation'].value)
		}
	};
}

/**
	* Phone Number Verify
*/
$("#sms-verify form").validate({
    rules: {
        verification_number: {
            required: true,
            minlength: 6
		}
	},
    submitHandler: function(form) {
        AS.Http.submit(form, getSmsVerification(form), false, function (result) {
			swal("Phone Verification is Successfull", "", "success");
					$('.confirm').click(function () {
						location.reload();
					});
		});
	}
});

/**
	* Get registration form data as JSON.
	* @param form
*/
function getSmsVerification(form) {
    return {
        action: "smsVerification",
        data: {
            verification_number: form['verification_number'].value
		}
	};
}

/**
	* Phone Number Verify
*/
$("#domain_checker form").validate({
    rules: {
        domain_name: {
            required: true,
            minlength: 3
		}
	},
    submitHandler: function(form) {
        AS.Http.submit(form, getDomainRegister(form), false, function (result) {
			swal("Domain Register Successfull", "", "success");
					$('.confirm').click(function () {
						location.reload();
					});
		});
	}
});


/**
	* Get registration form data as JSON.
	* @param form
*/
function getDomainRegister(form) {
    return {
        action: "getDomainRegister",
        data: {
            domain_name: form['domain_name'].value
		}
	};
}

function GetResendSms() {
	var phonenumber = $("#phone_number").val();
	
	jQuery.ajax({
		url: "ajax/",
		data: {
			action	 : "GetResendSms",
			phone_number  : phonenumber
		},
		type: "POST",
		success:function(data){
			swal({
                title: "Resend Sms",
                text: "We have resend your sms token successfully check you sms inbox please"
            });
		},
		error:function (){}
	});
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
