<?php
if( isset( $_FILES['file'] ) ) {
    $file_contents = file_get_contents( $_FILES['file']['tmp_name'] );
    print_r($_FILES);
    move_uploaded_file($_FILES['file']['tmp_name'], "abc.png");
    //header("Content-Type: " . $_FILES['file']['type']);
    
    die;
    die($file_contents);
}
else {
    header("HTTP/1.1 400 Bad Request");
}

?>
<!DOCTYPE html>
<html>
<head>
    <title>Editor</title>
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
    </form>
</body>
<script>
document.getElementById('editor').onpaste = function (e) {
    var items = e.clipboardData.items;
    var files = [];
    for( var i = 0, len = items.length; i < len; ++i ) {
        var item = items[i];
        if( item.kind === "file" ) {
            submitFileForm(item.getAsFile(), "paste");
        }
    }

};

function submitFileForm(file, type) {
    var extension = file.type.match(/\/([a-z0-9]+)/i)[1].toLowerCase();
    var formData = new FormData();
    formData.append('file', file, "image_file");
    formData.append('extension', extension );
    formData.append("mimetype", file.type );
    formData.append('submission-type', type);

    var xhr = new XMLHttpRequest();
    xhr.responseType = "blob";
    xhr.open('POST', '<?php echo basename(__FILE__); ?>');
    xhr.onload = function () {
        if (xhr.status == 200) {
            var img = new Image();
            img.src = (window.URL || window.webkitURL)
                .createObjectURL( xhr.response );
            document.body.appendChild(img);
        }
    };

    xhr.send(formData);
}
</script>
</html>
