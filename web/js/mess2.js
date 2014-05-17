$(document).ready(function(){

var interval = 1000;

setInterval(function(){
	$.ajax({
				type : 'get',
				url : '../../../../../chat-controllers/web/user/profile/david/display',
				dataType : 'html',
				success : function(html, textStatus){
					$('#chatDiv').html(html);
				},
				error : function(XMLHttpRequest, textStatus, errorThrown) {
					$('#chatDiv').val('false');
				}
			});
			return false;
}, interval);


var form = $('#testForm');
console.log(form);

 	form.submit(function(){

                // fetch where we want to submit the form to
                var url = '../../../../../chat-controllers/web/user/profile/david/chat';
                //$('#testForm').attr('action');
                console.log(url);

                // // fetch the data for the form
                var data = $('#testForm').serializeArray();
                console.log(data);

                // setup the ajax request
                $.ajax({
                    url: url,
                    data: data,
                    //dataType: 'json',
                    success: function(data) { 
                    	$("#content").html(data); 
						}   
                });
                return false;
            });
 	});
