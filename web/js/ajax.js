// Establish the XML HTTP Request
// This is what allows JavaScript to access our PHP file
if(window.XMLHttpRequest)
{
   // For modern browsers
   ajax = new XMLHttpRequest();
}
else
{
   // For IE6 and IE5 browsers
   ajax = new ActiveXObject('Microsoft.XMLHTTP');
}

// Sends request to PHP file
// You can pass query string name/value pairs here if you want
// Example: data.php?name1=value1&name2=value2, etc
ajax.open('get', '../../../db.php', true);
ajax.send(null);

// Runs function when state of our request variable changes
ajax.onreadystatechange = function()
{
   // 4 means done; the data has been retrieved from data.php
   if(ajax.readyState == 4)
   {
      // Put that data into the DIV element
      document.getElementById('chatDiv').innerHTML = ajax.responseText;
   }
}

