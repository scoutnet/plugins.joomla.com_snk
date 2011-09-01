window.addEvent('domready', function() {
		document.formvalidator.setHandler('time',
			function (value) {
			regex=/^(([01]|)[0-9]|2[0-4]):[0-5][0-9]$/;
			return regex.test(value);
			});
		});
