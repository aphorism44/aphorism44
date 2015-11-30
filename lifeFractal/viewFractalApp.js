

var main = function() {
	
	"use strict";
	
	getEntryHTML(-1);
	
	//navigation buttons
	$('#previousEntry').click(function() { 
		getPreviousEntry();
	});
	
	$('#nextEntry').click(function() { 
		getNextEntry();
	});
	
	
};

$(document).ready(main);


/* functions to get data*/
var getPreviousEntry = function() {
	var jsonGetPreviousEntry = {'action':'getPreviousEntry'};
	$.ajax({
		  url: 'viewFractalHandler.php',
		  type: 'GET',
		  data: jsonGetPreviousEntry,
		  dataType: "text",
		  success: function(data) {
		  	//console.log("success");
		  	//console.log(data);
		  	getEntryHTML(data);
		  },
		  error: function(e) {
		  	//console.log("fail");
			console.log(e);
		  }
	}); //end AJAX
};

var getNextEntry = function() {
	var jsonGetNextEntry = {'action':'getNextEntry'};
	$.ajax({
		  url: 'viewFractalHandler.php',
		  type: 'GET',
		  data: jsonGetNextEntry,
		  dataType: "text",
		  success: function(data) {
		  	//console.log("success");
		  	//console.log(data);
		  	getEntryHTML(data);
		  },
		  error: function(e) {
		  	//console.log("fail");
			console.log(e);
		  }
	}); //end AJAX
};

/*get enttry page*/
var getEntryHTML = function(entryId) {
	var jsonGetEntryHTML= {'action':'getEntryHTML', 'entryId' : entryId};
	$.ajax({
		  url: 'viewFractalHandler.php',
		  type: 'GET',
		  data: jsonGetEntryHTML,
		  dataType: "json",
		  success: function(data) {
		  	//console.log("success");
		  	//console.log(data.entry_text);
		  	$("main .content").empty();
		  	$("main .content").append(data.entry_text);   
		  	if  (data.entry_date.indexOf("0000") < 0) {
		  		$("main .content").append("<br><br><strong>Entry Date:</strong><br>");
		  		$("main .content").append(data.entry_date);
		  	}
		  },
		  error: function(e) {
		  	//console.log("fail");
			console.log(e);
		  }
	}); //end AJAX
};


/*get keyword page*/
var getKeywordHTML = function(keywordId) {
	var jsonGetKeywordHTML= {'action':'getKeywordHTML', 'keywordId' : keywordId };
	$.ajax({
		  url: 'viewFractalHandler.php',
		  type: 'GET',
		  data: jsonGetKeywordHTML,
		  dataType: "json",
		  success: function(data) {
		  	//console.log(data);
		  	$("main .content").empty();
		  	$.each(data, function (index, value) {
		  		$("main .content").append(value);
		  	});
		  },
		  error: function(error) {
		  	console.log("fail");
			//console.log(error);
		  }
	}); //end AJAX
};





