/**
	* Put button to a loading state.
	* @param {Object} button Button to be putted.
	* @param {string} loadingText Text that will be displayed while loading.
*/
AS.Util.loadingButton = function(button, loadingText) {
    button.data("original-content", button.html())
	.text(loadingText)
	.addClass("disabled")
	.attr('disabled', "disabled");
};

/**
	* Returns button from loading state to normal state.
	* @param {Object} button Button object.
*/
AS.Util.removeLoadingButton = function (button) {
    button.html(button.data("original-content"))
	.removeClass("disabled")
	.removeAttr("disabled")
	.removeAttr("rel");
};


/**
	* Put button to a loading state.
	* @param {Object} button Button to be putted.
	* @param {string} loadingText Text that will be displayed while loading.
*/
AS.Util.loadingIDButton = function(button, loadingText) {
    button.data("original-content", button.html())
	.text(loadingText)
	.addClass("disabled")
	.attr('disabled', "disabled");
};

/**
	* Returns button from loadin state to normal state.
	* @param {Object} button Button object.
*/
AS.Util.removeLoadingIDButton = function (button) {
    button.html(button.data("original-content"))
	.removeClass("disabled")
	.removeAttr("disabled")
	.removeAttr("rel");
};

/**
	* Append success message to provided parent element.
	* @param {Object} parentElement Parent element where message will be appended.
	* @param {String} message Message to be displayed.
*/
AS.Util.displaySuccessMessage = function (parentElement, message) {
    $(".alert-success").remove();
    var div = ("<div class='alert alert-success mb-3'>"+message+"</div>");
    parentElement.prepend(div);
};


/**
	* Append error message to an input element. If message is omitted, it will be set to empty string.
	* @param {Object} element Input element on which error message will be appended.
	* @param {String} message Message to be displayed.
*/
AS.Util.displayErrorMessage = function(element, message) {
    element.addClass('is-invalid').removeClass('is-valid');
    if(typeof message !== "undefined") {
        element.after(
			$("<em class='invalid-feedback' style='color:red;'>"+message+"</em>")
		);
	}
};


/**
	* Removes all error messages from all input fields.
*/
AS.Util.removeErrorMessages = function () {
    $("form input").removeClass('is-invalid').removeClass('is-valid');
    $(".invalid-feedback").remove();
};



AS.Util.ShowErrorByElement = function (element, message) {
    $("#"+element).addClass('is-invalid').removeClass('is-valid');
	if(typeof message !== "undefined") {
		$("#"+element).after(
			$("<em class='invalid-feedback' style='color:red;'>"+message+"</em>")
		);
	}
};

/**
	* Get an parameter from URL.
	* @param {string} name Parameter name.
	* @returns {string} Value of parameter with given name.
*/
AS.Util.urlParam = function(name) {
    return decodeURIComponent((new RegExp('[?|&]' + name + '=' + '([^&;]+?)(&|#|;|$)')
	.exec(location.search)||[,""])[1]
	.replace(/\+/g, '%20'))||null;
};


/**
	* Show errors received from the server.
	* @param form
	* @param error
*/
AS.Util.showFormErrors = function (form, error) {
	$.each(error.responseJSON.errors, function (key, error) {
		if (document.getElementById(key)) {
			AS.Util.displayErrorMessage($(form).find(":input[id="+key+"]"), error);
			}else{
			AS.Util.displayErrorMessage($(form).find(":input[name="+key+"]"), error);
		}
	});
};

/**
	* Hash a given value using SHA512 hashing algorithm.
	* @param value
	* @returns {string}
*/
AS.Util.hash = function (value) {
    return value.length ? CryptoJS.SHA512(value).toString() : "";
};

AS.Http.submit = function (form, data, URL, success, error, complete) {
    AS.Util.removeErrorMessages();
	
	var $submitBtnActive = $(form).find("button[type=submit].active");
	var $submitBtn = $(form).find("button[type=submit]");
	
	if($submitBtn.hasClass( "active" )){
		AS.Util.loadingButton($submitBtnActive, $submitBtnActive.data('loading-text') || $_lang.working);
		}else if ($submitBtn) {
		AS.Util.loadingButton($submitBtn, $submitBtn.data('loading-text') || $_lang.working);
	}
	
	$.ajax({
		url: URL || "ajax/",
		type: "POST",
		dataType: "json",
		data: data,
		success: function (response) {
			
			if (typeof success === "function") {
				success(response);
			}
		},
		error: error || function (errorResponse) {
			AS.Util.showFormErrors(form, errorResponse);
		},
		complete: complete || function () {
			if($submitBtn.hasClass( "active" )){
				AS.Util.removeLoadingButton($submitBtnActive);
				}else if ($submitBtn) {
				AS.Util.removeLoadingButton($submitBtn);
			}
		}
	});
};

AS.Http.PostSubmit = function (form, extra , URL, success, error, complete) {
	AS.Util.removeErrorMessages();
	var $formData = new FormData(form);
	var $submitBtnActive = $(form).find("button[type=submit].active");
	var $submitBtn = $(form).find("button[type=submit]");
	
	if($submitBtn.hasClass( "active" )){
		AS.Util.loadingButton($submitBtnActive, $submitBtnActive.data('loading-text') || $_lang.working);
		}else if ($submitBtn) {
        AS.Util.loadingButton($submitBtn, $submitBtn.data('loading-text') || $_lang.working);
	}
	
	if($submitBtnActive.val()){
		$formData.append('submit', $submitBtnActive.val());
	}
	
	$formData.append(csrf_token, csrf_data);
	for (let [key, value] of Object.entries(extra)) {
		$formData.append(key, value);
	}
	
	$.ajax({
        url: URL || "ajax/",
        type: "POST",
        dataType: "json",
		data: $formData,
		contentType:false,
		cache:false,
		processData:false,
		success: function (response) {
			
			if (typeof success === "function") {
				success(response);
			}
		},
		error: error || function (errorResponse) {
			AS.Util.showFormErrors(form, errorResponse);
		},
		complete: complete || function () {
			if($submitBtn.hasClass( "active" )){
				AS.Util.removeLoadingButton($submitBtnActive);
				}else if ($submitBtn) {
				AS.Util.removeLoadingButton($submitBtn);
			}
		}
	});
};

AS.Http.GetDataTable = function (className,COLUMNS,DATA,URL,EVENT_LISENER=false,FOOTER=[],ORDER,LENGTH = 10,RowCondition = []){
	
	var CompanyName = $('#company_name').data('company-name') || "AppsOwl";
	var CompanyAddress = $('#company_address').data('company-address') || "Nikunjo, Dhaka";
	var TableTitle = $(className).data('title') || "Table Data";
	var TableDescription = $(className).data('description') || "";
	var startdate = DATA.from_data || "";
	var enddate = DATA.to_data || "";
	var text_to = "";
	if(DATA.from_data){
		text_to = " To ";
	}
	
	if(EVENT_LISENER){
		$(className).DataTable().destroy();
	}
	$(className).DataTable({
		"processing": true,
		"serverSide": true,
		"ajax": {
			url: URL || "filter/",
			type: "POST",
			data: DATA,
		},
		columns: COLUMNS,
		error: function (errorResponse) {
			console.log(errorResponse);
		},
		order: ORDER || [[0, "desc"]],
		pageLength: LENGTH,
		responsive: {
            details: {
                type: 'column',
                target: -1
			}
		},
        columnDefs: [ {
            className: 'control',
            orderable: false,
            targets:   -1
		} ],
		dom: '<"html5buttons"B>lTfgitp',
		buttons: [
			{extend: 'excel', title: CompanyName+'\n'+CompanyAddress+'\n'+TableTitle+'\n'+TableDescription+" "+startdate+text_to+enddate,  footer:true,
				exportOptions:{
					columns: ":not(.not-show)"
				},
			},
			{extend: 'pdf', title: CompanyName+'\n'+CompanyAddress+'\n'+TableTitle+'\n'+TableDescription+" "+startdate+text_to+enddate, orientation: 'landscape', pageSize: 'LEGAL', footer:true,
				exportOptions:{
					charset: "utf-8",
					columns: ":not(.not-show)"
				},
				customize: function (doc) {
					doc.content[1].table.widths = 
					Array(doc.content[1].table.body[0].length + 1).join('*').split('');
					// doc.styles.tableFooter.background = '';
					doc.styles.tableFooter.alignment = 'center';
					doc.styles.tableBodyEven.alignment = 'center';
					doc.styles.tableBodyOdd.alignment = 'center'; 
				}
			},
			{extend: 'print', title: CompanyName+'</br><small>'+CompanyAddress+'</small></br>'+TableTitle+'</small></br>'+TableDescription+" "+startdate+text_to+enddate, orientation: 'landscape', pageSize: 'A4', footer:true,
				exportOptions:{
					columns: ":not(.not-show)",
				},
				customize: function (win){
					$(win.document.body).find('h1').css('text-align', 'center');
					$(win.document.body).addClass('white-bg');
					$(win.document.body).css('font-size', '10px');
					
					$(win.document.body).find('table')
					.addClass('compact')
					.css('font-size', 'inherit')
					.css('text-align', 'center');
					$(win.document.body).find('th')
					.addClass('text-center');
					$(win.document.body).find('thead')
					.css('background','#FFA500');
					doc.styles.tableBodyEven.alignment = 'center';
					doc.styles.tableBodyOdd.alignment = 'center'; 
				}
			},			
			
		],
		language: {
			"decimal":        "",
			"emptyTable":     $_lang.no_data_available_in_table,
			"info":            $_lang.showing+" _START_ "+ $_lang.too +" _END_ "+ $_lang.off +" _TOTAL_ "+$_lang.entries,
			"infoEmpty":      $_lang.showing+" _START_ "+ $_lang.too +" _END_ "+ $_lang.off +" _TOTAL_ "+$_lang.entries,
			"infoFiltered":   "(filtered from _MAX_ total entries)",
			"infoPostFix":    "",
			"thousands":      ",",
			"lengthMenu":     $_lang.entries+" _MENU_ "+$_lang.show,
			"loadingRecords": "Loading...",
			"processing":     "Processing...",
			"search":         $_lang.search+":",
			"zeroRecords":    "No matching records found",
			"paginate": {
				"first":      $_lang.first,
				"last":       $_lang.last,
				"next":       $_lang.next,
				"previous":   $_lang.previous
			},
			"aria": {
				"sortAscending":  ": activate to sort column ascending",
				"sortDescending": ": activate to sort column descending"
			}
		},
		footerCallback: function(row, data, start, end, display) {
			var api = this.api();
			$.each(FOOTER, function( index, value ) {
				
				api.columns('.'+value, {
					page: 'all'
					}).every(function() {
					var sum = this
					.data()
					.reduce(function(a, b) {
						
						if(!Number(a) && a != 0){
							a = a.replace(/\,/g,'');
						}
						
						if(!Number(b) && b != 0){
							b = b.replace(/\,/g,'');
						}
						var x = parseFloat(a) || 0;
						var y = parseFloat(b) || 0;
						return x + y;
					}, 0);
					
					$(this.footer()).html(formatNumber(parseFloat(sum).toFixed(2)));
				});
			});
			
		},
		rowCallback: function( row, data, index ) {
			for (let [key, value] of Object.entries(RowCondition)) {
				if (data[key] == value) {
					$(row).hide();
				}
			}
		}
	});
}


AS.Http.post = function (data, URL, success, error, complete) {
	$.ajax({
		url: URL || "ajax/",
		type: "POST",
		dataType: "json",
		data: data,
		success: success || function () {},
		error: error || function () {},
		complete: complete || function () {}
	});
};


AS.Http.posthtml = function (data, URL, success, error, complete) {
	$.ajax({
		url: URL || "ajax/",
		type: "POST",
		dataType: "html",
		data: data,
		success: success || function () {},
		error: error || function () {},
		complete: complete || function () {}
	});
};


function ucwords(str){
	return (str + '').replace(/^(.)|\s+(.)/g, function ($1) {
		return $1.toUpperCase()
	})
}

function formatNumber(num) {
	return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
}
