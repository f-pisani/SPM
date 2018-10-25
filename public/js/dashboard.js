var init_form = false;

function showNewBoardModal()
{
	let navbar_height = $("#navbar").height();

	$("#modal-newproject").css('display', 'block');
	$("#modal-newproject").css('top', $(window).height());

	$("#modal-newproject").css('width', $(window).width());
	$("#modal-newproject").css('height', $(window).height()-navbar_height);

	$("#modal-newproject").animate({top: navbar_height+2}, 250, function(e) {});

	if(!init_form)
	{
		var colorpicker = new ColorPicker("project-colorpicker"); // Initialize color picker

		$('#form-newproject button[type="submit"]').on('click', (e) => {
			validateNewBoardForm();
			e.preventDefault();
		});

		init_form = true;
	}
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
			data = $.parseJSON(data);
			// Title field error
			if(data.errors.title != undefined)
			{
				$("#form-newproject [name='title']").after($('<div class="form-error">'+data.errors.title+'</div>'));
			}

			// Form validate, redirect to the created board
			if(data.success == true && data.redirect != undefined)
				window.location.replace(data.redirect);
		}
	});
}
