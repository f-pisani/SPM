function showNewBoardModal()
{
	let navbar_height = $("#navbar").height();

	$("#modal-newproject").css('display', 'block');
	$("#modal-newproject").css('top', $(document).height());

	$("#modal-newproject").css('width', $(document).width());
	$("#modal-newproject").css('height', $(document).height()-navbar_height);

	$("#modal-newproject").animate({top: navbar_height+2}, 250, function(e) {});

	let colorpicker = new ColorPicker("project-colorpicker"); // Initialize color picker

	$('#form-newproject button[type="submit"]').on('click', (e) => {
		validateNewBoardForm();
		e.preventDefault();
	});
}

function hideNewBoardModal()
{
	$("#modal-newproject").animate({top: $(document).height()}, 250, function(e){
		$("#modal-newproject").css('display', 'none');
	});
}

function validateNewBoardForm()
{
	// Remove older errors msgs
	$("#form-newproject .form-error").remove();

	var form = $("#form-newproject");
    var url = form.attr('action');

    $.ajax({
		type: "POST",
		url: url,
		data: form.serialize(),
		success: function(data)
		{
			alert(data);
			data = $.parseJSON(data);
			if(data.errors.title != undefined)
			{
				$("#form-newproject [name='title']").after($('<div class="form-error">'+data.errors.title+'</div>'));
			}

			if(data.errors.colorpicker != undefined)
			{
				$("#form-newproject #project-colorpicker").after($('<div class="form-error">'+data.errors.colorpicker+'</div>'));
			}

			if(data.success == true && data.redirect != undefined)
				window.location.replace(data.redirect);
		}
	});
}
