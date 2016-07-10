String.prototype.format = String.prototype.f = function() {
    var s = this,
        i = arguments.length;

    while (i--) {
        s = s.replace(new RegExp('\\{' + i + '\\}', 'gm'), arguments[i]);
    }
    return s;
};

$(document).ready(function(){
	DayRating.init();
});

var DayRating = {
	init: function(){
		this.loadOldValues();
		var timeout = null;
		$('#slider').on('change', function(event) {
			if (timeout !== null){
				clearTimeout(timeout);
			}
			timeout = setTimeout(function(){
				DayRating.submitAjax($('#slider').val(), $('#comment').val());
			}, 100);
		});
		$('#comment').on('keyup', function(event) {
			if (timeout !== null){
				clearTimeout(timeout);
			}
			timeout = setTimeout(function(){
				DayRating.submitAjax($('#slider').val(), $('#comment').val());
			}, 100);
		});
	},

	loadOldValues: function(){
		$.ajax({
			url: 'current.php',
			type: 'GET',
			success: function(response){
				$('#slider').val(response.rating);
				$('#slider').slider('refresh');
				$('#comment').html(response.comment).text();
				$('#comment').textinput('refresh');
			}
		});

		// table data
		$.ajax({
			url: 'current.php',
			type: 'GET',
			data: 'allData=1',
			success: function(response){
				$.each(response, function(k, el) {
					$('#tableData').append(
						DayRating.formatTable(el)
					);
				});
			}
		});
	},

	formatTable: function(el){
		var rowTemplate = "<tr><td>{0}</td><td>{1}</td><td>{2}</td><td>{3}</td></tr>";
		var weekSeperatorTemplate = "<tr style=\"background-color:#B8FFD9;\"><td>{0}</td><td>{1}</td><td>{2}</td><td>{3}</td></tr>";

		if (el.isMonday){
			return weekSeperatorTemplate.format(el.id, el.rating, el.comment, el.timestamp);
		}else{
			return rowTemplate.format(el.id, el.rating, el.comment, el.timestamp);
		}
	},

	submitAjax: function(value, comment){
		$.ajax({
			url: 'server.php',
			type: 'POST',
			dataType: 'json',
			data: {rating: value, comment: comment},
			success: function(response){
				console.log(response);
			}
		});
	}
};