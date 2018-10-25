/***********************************************************************************************************************
 * Toggle between short description and full description
 */
var toggleDesc = false;
var shortDesc = '';
function toggleBoardDesc()
{
	if(!toggleDesc)
	{
		shortDesc = $(".header .infos .desc").html();
		$(".header .infos .desc").html($(".header .infos .desc").attr('data-fulldesc'));
		$(".header .infos .desc").append($('<i class="fas fa-caret-up" onClick="toggleBoardDesc()"></i>'));

		toggleDesc = true;
	}
	else
	{
		$(".header .infos .desc").html(shortDesc);

		toggleDesc = false;
	}
}


/***********************************************************************************************************************
 * Controls for board settings modal
 */
var init_form = false;
function showBoardSettingsModal()
{
	if($("#modal-settings").css('display') == 'none')
	{
		if(toggleDesc)
			toggleBoardDesc(); // Show short desc if full description is displayed

		let offset_top = $("#navbar").outerHeight()+$("#board .header").outerHeight();
		$("#modal-settings").css('display', 'block');
		$("#modal-settings").css('top', $(window).height());
		$("#modal-settings").css('width', $(window).width());
		$("#modal-settings").css('min-height', $(window).height()-offset_top);
		$("#modal-settings").animate({top: offset_top+2}, 250);

		if(!init_form)
		{
			// Form initialization must occur only once
			var colorpicker = new ColorPicker("project-colorpicker"); // Initialize color picker

			// Form is submitted using AJAX
			$('#form-edit button[type="submit"]').on('click', (e) => {
				validateBoardSettingsForm();
				e.preventDefault(); // Avoid submit default behavior
			});

			// When colorpicker selected color changes, apply color to the header (preview)
			$('#form-edit input[name="colorpicker"]').on('change', (e) => {
				$("#board .header").css('background-color', '#'+$('#form-edit input[name="colorpicker"]').val());
			});

			init_form = true; // Form as been initialized
		}
	}
	else
		hideBoardSettingsModal(); // Toggle modal if already showed
}

function hideBoardSettingsModal()
{
	$("#modal-settings").animate({top: $(window).height()}, 250, (e) => {
		$("#modal-settings").css('display', 'none'); // Once animation complete, display: none;
	});
}

function validateBoardSettingsForm()
{
	// Remove older errors msgs
	$("#form-edit .form-error").remove();

	var form = $("#form-edit");
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
				$("#form-edit [name='title']").after($('<div class="form-error">'+data.errors.title+'</div>'));
			}

			if(data.success == true)
				window.location.reload(true); // If board has been updated, refresh the current page
		}
	});
}
