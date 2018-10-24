class ColorPicker
{
	constructor(id)
	{
		this.id = id;
		this.colors = $(`#${this.id}`).attr('data-colorpicker').split(',');

		if($(`#${this.id}`).attr('data-colorpicker') == undefined)
		{
			this.colors = undefined;
			console.log("ColorPicker::ctor() : #"+this.id+" attr 'data-colorpicker' is missing.");
		}

		// Hidden input for the selected color value
		$(`#${this.id}`).append($('<input type="hidden" name="colorpicker" value="'+this.colors[0]+'">'));

		// Create selector for every colors
		this.colors.forEach((color) => {
			var color_selector = $('<div class="color" style="background-color: #'+color+';" data-colorvalue="'+color+'"></div>');

			// When a selector is clicked, mark him as selected and update hidden input
			color_selector.on('click', () => {
				$(`#${this.id} [name="colorpicker"]`).val(color);
				$(`#${this.id} ` + '[data-colorvalue].selected').removeClass('selected');
				$(`#${this.id} ` + '[data-colorvalue="'+color+'"]').addClass('selected');
			});

			// Append to the container
			$(`#${this.id}`).append(color_selector);

			// First color is selected by default
			if(this.colors[0] == color)
				color_selector.click();
		});
	}
}
