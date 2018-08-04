<!DOCTYPE html>
<html>
<head>
	<title></title>
	<style type="text/css">
	body {
		margin: 0 auto;
		width: 600px;
		font-family: 'Dosis';
	}
	a {
		cursor: pointer;
	}
	#editor {
		box-shadow: 0 0 2px #CCC;
		min-height: 150px;
		overflow: auto;
		padding: 1em;
		margin-top: 20px;
		resize: vertical;
		outline: none;
	}
	</style>
</head>
<body>
	<form action="savedata.php" method="post" enctype="multipart/form-data" onsubmit="return fetchData();">
		<div id="editor" contenteditable="true" style="height: 100%; width: 100%; outline: 0; overflow: auto"></div>
		<textarea name="editordata" id="editordata" style="display:none;"></textarea>
		<input type="submit" name="save_btn" value="Save Data">
		<span id="txtHint"></span>
	</form>
    <script type="text/javascript">
        document.getElementById("editor").focus();
        document.body.addEventListener("paste", function(e) {
            for (var i = 0; i < e.clipboardData.items.length; i++) {
                if (e.clipboardData.items[i].kind == "file" && e.clipboardData.items[i].type == "image/png") {
                    // get the blob
                    var imageFile = e.clipboardData.items[i].getAsFile();
                    // read the blob as a data URL
                    var fileReader = new FileReader();
                    fileReader.onloadend = function(e) {
                        // create an image
                        var image = document.createElement("IMG");
                        image.src = this.result;ajaxPostRequest(this.result);
                        // insert the image
                        var range = window.getSelection().getRangeAt(0);
                        range.insertNode(image);
                        range.collapse(false);

                        // set the selection to after the image
                        var selection = window.getSelection();
                        selection.removeAllRanges();
                        selection.addRange(range);
                    };

                    // TODO: Error Handling!
                    // fileReader.onerror = ...

                    fileReader.readAsDataURL(imageFile);

                    // prevent the default paste action
                    e.preventDefault();

                    // only paste 1 image at a time
                    break;
                }
            }
        });  
		function fetchData(){
			//console.log(document.getElementById('editor').innerHTML);
			var dt = document.getElementById('editor').innerHTML;
			document.getElementById('editordata').value=dt;
			return true;
		}
		function ajaxPostRequest(data)
		{
			var xmlhttp = new XMLHttpRequest();
			xmlhttp.onreadystatechange = function() {
				if (this.readyState == 4 && this.status == 200) {
					document.getElementById("txtHint").innerHTML = this.responseText;
				}
			};
			xmlhttp.open("post","savedata.php", true);
			xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
			xmlhttp.send("Action=UploadFile&ImgData="+data);
		}    
    </script>
</body>
</html>
