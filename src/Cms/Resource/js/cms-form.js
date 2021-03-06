define('cms-form', ['jquery', 'cms-html-widget'], function($, HtmlWidget) {

	'use strict';

	var Form, FormRow;

	Form = function(form) {
		this.form = form;
		this.rows = {};
		this.form.find('.form_row').each(function(i, el) {
			this._initRow($(el));
		}.bind(this));
	};

	Form.prototype = {

		_initRow: function(row) {
			var formRow = new FormRow(row);
			this.rows[formRow.name] = formRow;
		}

	};

	FormRow = function(row) {

		this.row = row;
		this.type = row.data('type');
		this.name = row.data('name');
		this.widget = row.find('div.widget');

		switch(this.type) {
			case 'html':
				this.htmlWidget = new HtmlWidget(this.widget);
				break;
		}

	};

	return Form;

});