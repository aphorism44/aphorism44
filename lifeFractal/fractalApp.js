


tinymce.init({
	mode: "none",
    selector: "textarea",
    width: 750,
    height: 300,
    plugins: [
        "advlist autolink lists link image charmap print preview anchor",
        "searchreplace visualblocks fullscreen",
        "insertdatetime media table contextmenu paste"
    ],
    toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"
});


var tab1HTML = "<br><br><form id='tab1'>"
			   + "Title:"
			   + "<br><br><p><input type = 'text' id = 'entryTitle'></p>"
			   + "<br><br>"
			   + "<button type='button' id='grabSelectedText'>Turn Selected Text into Keyword</button>"
			   + "<br><br><textarea id='entry' id='entry' class='tinymce'></textarea>"
               + "<br><br>Date:"
               + "<br><br><p><input type = 'date' id = 'entryDate'></p>"
               + "<br><br><p><button type='button' id='saveEntry'>Save</button></p>"
               + "</form>";

var tab2HTML = "<br><br><form id='tab2'>"
			   + "<br><br><p><button type='button' id='createEntry'>Create New Entry</button></p>"
			   + "<br><br>Current Entries:"
			   + "<br><br><p><select id = 'entryList' size = '10'><select></p>"
			   + "<br><br><p><button type='button' id='switchEntry'>Switch to Selected Entry</button></p>"
			   + "<br><br><p><button type='button' id='deleteEntry'>Delete Selected Entry</button></p>"
               + "</form>";
               
 var tab3HTML = "<br><br><form id='tab3'>"
 			   + "<table class='keyTable'>"
 			   + "<tr><td>Unconnected Keywords</td>"
			   + "<td>Keywords Attached to This Entry</td>"
			   + "</tr><tr>"
			   + "<td><select id = 'keywordsUnused' size = '10'><select></td>"
			   + "<td><select id = 'keywordsUsed' size = '10'><select></td>"
               + "</tr><tr><td>How Selected Keyword Relates to This Entry</td>"
               + "<td><textarea id='keywordDesc'  rows='4' cols='50'></textarea></td></tr>"
               + "<tr><td><button type='button' id='addKeyword'>Add New Keyword</button></td>"
               + "<td><button type='button' id='saveKeyword'>Save Keyword Entry Description</button></td></tr>"
               + "<tr><td><input type = 'text' id = 'newKeyword'></td>"
               + "<td><button type='button' id='removeKeyword'>Disconnect Selected Keyword from Entry</button></td></tr>"
              // + "<tr><td><button type='button' id='deleteKeyword'>Delete Unused Keyword</button></td></tr>"
               + "</table></form>";

var main = function() {
	
	"use strict";

	var tabNo;
	
	//add form elements depending on which tab you click
	for (tabNo = 1; tabNo <= 3; tabNo++) {
		
		var tabSelector = ".tabs a:nth-child(" + tabNo + ") span"; 
		$(tabSelector).on("click", function() {
			
			//the below tries to save the entry text if you click away
			var lastTabId = $(".tabs span.active").attr("id");
			var currentTabId = this.id;
			if (lastTabId === "1" && currentTabId !== "1" && $('#entryTitle').val() !== undefined) {
				//on this tab, only entryTitle is required
				if($('#entryTitle').val().trim())
					updateEntry();
			}
			
			var $content;
			
			$(".tabs span").removeClass("active");
			$(this).addClass("active");
			$("main .content").empty();
			
			if ($(this).parent().is(":nth-child(1)")) {
				$content = $(tab1HTML);
			} else if ($(this).parent().is(":nth-child(2)")) {
				$content = $(tab2HTML);
			} else if ($(this).parent().is(":nth-child(3)")) {
				$content = $(tab3HTML);
			}
			
			//below 3-line hack needed to handle TinyMCE
			tinyMCE.execCommand('mceRemoveEditor', false, 'entry');
			$("main .content").append($content);
			tinyMCE.execCommand('mceAddEditor', false, 'entry');
			
			//handling tab 1
			if($(this).parent().is(":nth-child(1)")) {
				
				//if there's an existing entryId, grab the associated data and populate
				//populate entry fields
				//console.log("in jquery, about to call ajax");
				populateEntryData();
				
				//click saveEntry button
				$('#saveEntry').click(function() {
					var proceed = true;
					//on this tab, only entryTitle is required
					if(!$('#entryTitle').val().trim()){
						alert ('Please provide an entry name.');
					} else {
						updateEntry();
					}
				});
				
				//grab selected text in TinyMCE and turn into unused keyword
				$('#grabSelectedText').click(function() {
					var selected = tinyMCE.activeEditor.selection.getContent({format : 'text'}).trim();
					if (selected.length > 0 && selected.length < 50) {
						if (confirm("Do you want to turn the word '" + selected + "' into a keyword?")) {
							var inDB;
							var jsonCheckKeyword = {'action' : 'checkKeyword', 'newKeyword' : selected};
							$.ajax({
							      url: 'fractalFormHandler.php',
							      type: 'GET',
							      dataType: "text",
							      data: jsonCheckKeyword,
							      success: function(output) {
							      	if (output == 1) {
							      		alert ('You have already entered that keyword.');
							      	} else {
							      		addNewKeyword(selected);
							      	}
			                  	  },
			                  	  failure: function(output) {
			                      	console.log(e.message);
			                  	}
							  }); // end ajax call  
						}
						
					}
					
					
				});
				
			}
			
			//handling tab 2
			if($(this).parent().is(":nth-child(2)")) {
				
				//create new entry
				$('#createEntry').click(function() { 
					//set the entryId to -1 via P
					var jsonNewEntry = {'action' : 'newEntry'};
					$.ajax({
					      url: 'fractalFormHandler.php',
					      type: 'POST',
					      dataType: "json",
					      data: jsonNewEntry,
					      success: function(output) {
	                  	  },
	                  	  failure: function(output) {
	                      	console.log(e.message);
	                  	}
					  }); // end ajax call  
					
				});
				
				//fill the listbox with all this user's entries
				loadEntryDropdown();
				
				//change the current entry if the user clicks the button
				$('#switchEntry').click(function() { 
					//set the entryId to the setting in the listbox
					var selectedEntry = $('#entryList').val();
					//alert(selectedEntry);
					if (selectedEntry != -1) {
						var jsonSwitchEntry = {'action' : 'switchEntry', 'switchEntryId' : selectedEntry};
						$.ajax({
						      url: 'fractalFormHandler.php',
						      type: 'POST',
						      dataType: "json",
						      data: jsonSwitchEntry,
						      success: function(output) {
						      	
		                  	  },
		                  	  failure: function(output) {
		                      	console.log(e.message);
		                  	}
						  }); // end ajax call  
					}
					
					
				});
				
				//delete entry
				$('#deleteEntry').click(function() { 
					if ($('#entryList').val() == null ) {
						alert("Please select the entry you want to delete.");
					} else {
						if (confirm("Are you certain you want to delete this entry?"))
							deleteEntry();
					}
					
				});
			}
			
			
			//handling tab 3
			if($(this).parent().is(":nth-child(3)")) {
				
				
				//can only choose a used or unused keyword, not both
				//also, if you select an attached keyword, its data is placed in textbox
				
				$('#keywordsUsed').on("click", function() {
					$('#keywordsUnused').removeAttr("selected");
				    getKeyData($('#keywordsUsed').val());
				});
				
				$('#keywordsUnused').on("click", function() {
					$('#keywordDesc').val("");
				    $('#keywordsUsed').removeAttr("selected");
				});
				
				//get list of keywords attached to current entry
				loadUsedKeywordDropdown();
				
				//get list of keywords not attached to current entry
				updateUnusedKeywordDropdown();
			
				//add new keyword
				$('#addKeyword').click(function() { 
					//set the entryId to the setting in the listbox
					if(!$('#newKeyword').val().trim()){
						alert ('Please enter a new keyword.');
					} else {
						var inDB;
						var jsonCheckKeyword = {'action' : 'checkKeyword', 'newKeyword' : $('#newKeyword').val()};
						$.ajax({
						      url: 'fractalFormHandler.php',
						      type: 'GET',
						      dataType: "text",
						      data: jsonCheckKeyword,
						      success: function(output) {
						      	if (output == 1) {
						      		alert ('You have already entered that keyword.');
						      	} else {
						      		addNewKeyword($('#newKeyword').val());
						      		$('#newKeyword').val("");
						      	}
		                  	  },
		                  	  failure: function(output) {
		                      	console.log(e.message);
		                  	}
						  }); // end ajax call  
					}
				});
				
				//save data to keyword
				$('#saveKeyword').click(function() { 
					//must select at least one select and have text in the box
					//furthermore, that keyword must occur at least once in the entry text
					if ( ($('#keywordsUsed').val() == null && $('#keywordsUnused').val() == null) || !$('#keywordDesc').val().trim() ) {
						alert("To add keyword data to an entry, you must choose at least one keyword from a list and enter some text in the box.");
					} else {
						getKeyInEntry();
					}
				
				});
				
				//disconnect keyword
				$('#removeKeyword').click(function() { 
					if ($('#keywordsUsed').val() == null ) {
						alert("Please select the attached keyword you want to remove.");
					} else {
						removeKeywordData();
					}
					
				});
				
				//disconnect keyword
				$('#deleteKeyword').click(function() { 
					if ($('#keywordsUnused').val() == null ) {
						alert("Please select the unused keyword you want to delete.");
					} else {
						deleteUnusedKeyword();
					}
					
				});
			}
			
			
			
			return false;
		});
	}
};

$(document).ready(main);


/* populating fields functions*/


/*populate entry data*/
var populateEntryData = function() {
	var jsonGetEntries = {'action':'getEntryData'};
	$.ajax({
		  url: 'fractalFormHandler.php',
		  type: 'GET',
		  data: jsonGetEntries,
		  dataType: "json",
		  success: function(data) {
			$('#entryTitle').val(data.entry_title); 
			tinyMCE.activeEditor.setContent(data.entry_text);
			$('#entryDate').val(data.entry_date); 
		  },
		  error: function(e) {
			console.log(e.responseText);
		  }
		});
};

var loadUsedKeywordDropdown = function() {
	var jsonKeywordList = {'action':'getKeywordList'};
	$.ajax({
		url: 'fractalFormHandler.php',
		type: 'GET',
		data: jsonKeywordList,
		dataType: "json",
		success: function(data) {						
			var keywordList;
			$.each(data, function(key, value){
				    keywordList += '<option value=' + key + '>' + value + '</option>';
				});
			$('#keywordsUsed').append(keywordList);
		  },
		  error: function(e) {
			console.log(e.message);
		  }
		});		
};

var loadEntryDropdown = function() {
	var jsonEntryList = {'action':'getEntryList'};
	$.ajax({
		url: 'fractalFormHandler.php',
		type: 'GET',
		data: jsonEntryList,
		dataType: "json",
		success: function(data) {						
			var entryList;
			$.each(data, function(key, value){
				    entryList += '<option value=' + key + '>' + value + '</option>';
				});
				
			$('#entryList').append(entryList);
			//$('#entryList').val(currentEntryId);
		  },
		  error: function(e) {
			console.log(e.message);
		  }
		});	
};

var getKeyData = function(keywordId) {
	var jsonGetKeyData = {'action' : 'getKeyData', 'keywordId' :keywordId};
	$.ajax({
		url: 'fractalFormHandler.php',
		type: 'GET',
		data: jsonGetKeyData,
		dataType: "text",
		success: function(data) {						
			//console.log(data);
			 $('#keywordDesc').val($.trim(data));
		  },
		  error: function(e) {
			console.log(e.message);
		  }
		});
};

/* update functions */

var updateEntry = function() {
	 var mceEntry =  tinyMCE.activeEditor.getContent({format : 'raw'});			
	 var jsonEntry = {
						'action' : 'entryPost'
						,'entry_title' : $('#entryTitle').val()
						, 'entry_text' : mceEntry
						, 'entry_date': $('#entryDate').val() 
						};
	 $.ajax({
	      url: 'fractalFormHandler.php',
	      type: 'POST',
	      dataType: "text",
	      data: jsonEntry,
	      success: function(output) {
	      	//console.log('success');
	      	//console.log(output);
	  	  },
	  	  failure: function(output) {
	      	//console.log('fail');
	      	//console.log(output);
	  	}
	  }); // end ajax call  
};


//delete unused keyword
var deleteEntry = function() {
	var entryIdDelete= $('#entryList option:selected').val();
	var deleteEntryData = {'action' : 'deleteEntry', 'entryId' :entryIdDelete};
	$.ajax({
	      url: 'fractalFormHandler.php',
	      type: 'POST',
	      dataType: "json",
	      data: deleteEntryData,
	      success: function(output) {
	      	$("#entryList option[value='" + entryIdDelete + "']").remove();
	       },
      	  failure: function(output) {
          	console.log(e.message);
      	}
	  }); // end ajax call
	
};

var updateUnusedKeywordDropdown = function() {
	var jsonUnusedKeywordList = {'action':'getUnusedKeywordList'};
	$.ajax({
		url: 'fractalFormHandler.php',
		type: 'GET',
		data: jsonUnusedKeywordList,
		dataType: "json",
		success: function(data) {						
			var unusedKeywordList;
			$.each(data, function(key, value){
				    unusedKeywordList += '<option value=' + key + '>' + value + '</option>';
				});
			$('#keywordsUnused').append(unusedKeywordList);
		  },
		  error: function(e) {
			console.log(e.message);
		  }
		});
};

//add a new keyword (an unused one)
var addNewKeyword = function(keyword) {
  	var jsonAddKeyword = {'action' : 'addKeyword', 'newKeyword' :keyword};
	  $.ajax({
	      url: 'fractalFormHandler.php',
	      type: 'POST',
	      dataType: "text",
	      data: jsonAddKeyword,
	      success: function(output) {
	      	$('#keywordsUnused').append('<option value=' + output + '>' + keyword + '</option>');	
      	  },
      	  failure: function(output) {
          	console.log(e.message);
      	}
	  }); // end ajax call  

};

//connect/update the connection between a keyword and entry
var updateKeywordData = function() {
	var keywordIdUnused = $('#keywordsUnused option:selected').val();
	var keywordIdUsed = $('#keywordsUsed option:selected').val();
	var keywordUnused = $('#keywordsUnused option:selected').text();
	var keywordUsed = $('#keywordsUsed option:selected').text();
	
	var keywordData = $('#keywordDesc').val();
	var keywordId;
	var keyword;
	var keyConnected;
	
	if (keywordIdUnused == null) {
		keywordId = keywordIdUsed;
		keyword = keywordUsed;
		keyConnected = 1;
	} else {
		keywordId = keywordIdUnused;
		keyword = keywordUnused;
		keyConnected = 0;
	}
	
  	var jsonUpdateKeywordData = {'action' : 'updateKeywordData', 'keywordId' :keywordId, 'keyConnected' : keyConnected, 'keywordData' : keywordData};
	  
	  $.ajax({
	      url: 'fractalFormHandler.php',
	      type: 'POST',
	      dataType: "json",
	      data: jsonUpdateKeywordData,
	      success: function(output) {
	      	if (keyConnected == 0) {
	      		$("#keywordsUnused option[value='" + keywordId + "']").remove();
	      		$('#keywordsUsed').append('<option value=' + keywordId + '>' + keyword + '</option>');	
	      		$('#keywordsUsed').val(keywordId);
	      	}
	      
	       },
      	  failure: function(output) {
          	console.log(e.message);
      	}
	  }); // end ajax call  
	
};

//remove a keyword from an entry
var removeKeywordData = function() {
	var keywordIdRemove= $('#keywordsUsed option:selected').val();
	var keywordRemove = $('#keywordsUsed option:selected').text();
	var removeKeywordData = {'action' : 'removeKeywordData', 'keywordId' :keywordIdRemove};
	$.ajax({
	      url: 'fractalFormHandler.php',
	      type: 'POST',
	      dataType: "json",
	      data: removeKeywordData,
	      success: function(output) {
	      		$("#keywordsUsed option[value='" + keywordIdRemove + "']").remove();
	      		$('#keywordsUnused').append('<option value=' + keywordIdRemove + '>' + keywordRemove + '</option>');	
	       },
      	  failure: function(output) {
          	console.log(e.message);
      	}
	  }); // end ajax call 
	
};

//delete unused keyword
var deleteUnusedKeyword = function() {
	var keywordIdDelete= $('#keywordsUnused option:selected').val();
	var deleteKeywordData = {'action' : 'deleteUnusedKeyword', 'keywordId' :keywordIdDelete};
	$.ajax({
	      url: 'fractalFormHandler.php',
	      type: 'POST',
	      dataType: "json",
	      data: deleteKeywordData,
	      success: function(output) {
	      	$("#keywordsUnused option[value='" + keywordIdDelete + "']").remove();
	       },
      	  failure: function(output) {
          	console.log(e.message);
      	}
	  }); // end ajax call
};
	
var getKeyInEntry = function() {
	var keywordUnused = $('#keywordsUnused option:selected').text();
	var getKeyInEntry = {'action' : 'getKeyInEntry', 'keyword' :keywordUnused};
	var isValidKeyword = "false";
	$.ajax({ 
	      url: 'fractalFormHandler.php',
	      type: 'GET',
	      dataType: "text",
	      data: getKeyInEntry,
	      success: function(output) {
	      	if (output === 'true')
				updateKeywordData();
			else
				alert("To attach a keyword, it must appear at least once in your entry text.");
	       },
      	  failure: function(output) {
          	console.log(e.message);
      	}
	  }); // end ajax call
};















