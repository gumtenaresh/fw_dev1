$('#la').hide();
$('#ra').hide();
 
var newURL = window.location.protocol+'//'+window.location.host+'/';
var path=window.location.pathname;
  if(newURL=='http://localhost/')
     {
        newURL +="fanwire/"; 
     }
(function() {
    function $id(id) {
        
      
    //  alert(document.getElementById('upload'));
        return document.getElementById(id);
        
    }
    // output information
    function Output(msg) {
        var m = $id("messages");
        m.innerHTML = msg + m.innerHTML;
    }
    // file drag hover
    function FileDragHover(e) {
        e.stopPropagation();
        e.preventDefault();
       // e.target.className = (e.type == "dragover" ? "hover" : "");
    }
    // file selection
    function FileSelectHandler(e) {
        // cancel event and hover styling
        FileDragHover(e);
        // fetch FileList object
        var files = e.target.files || e.dataTransfer.files;
        // process all File objects
        for (var i = 0, f; f = files[i]; i++) {
            ParseFile(f);
            UploadFile(f);
        }

    }
 
    function ParseFile(file) {
        if (file.type.indexOf("image") == 0) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#messages').show();
                $.ajax({
                    url:newURL+'admin/fanwires/ajaxImageDrag',
                    method:'post',
                    success:function(res){
                        $('#messages').css('display:visible');
                        $('#la').show();
                        $('#ra').show();                       
                        $('#messages').html(res);
                    }
                });
            }
            reader.readAsDataURL(file);
        }

        // display text
        if (file.type.indexOf("text") == 0) {
            var reader = new FileReader();
            reader.onload = function(e) {
                Output(
                     "<p><strong>" + file.name + ":</strong></p>\n\
                     <pre>" +e.target.result.replace(/</g, "&lt;").replace(/>/g, "&gt;") +"</pre>"
                    );
            }
            reader.readAsText(file);
        }

    }

    // upload JPEG files
    function UploadFile(file) {
     
        var xhr = new XMLHttpRequest();
 
        if (xhr.upload && (file.type == "image/jpeg"||file.type == "image/gif"||file.type == "image/png"||file.type == "image/bmp"||file.type == "image/JPEG"||file.type == "image/GIF"||file.type == "image/PNG"||file.type == "image/BMP"||file.type == "image/jpg"||file.type == "image/JPG") && file.size <= $id("MAX_FILE_SIZE").value) {
   
            xhr.onreadystatechange = function(e) {
                if (xhr.readyState == 4) {
                   
                //progress.className = (xhr.status == 200 ? "success" : "failure");
                }
            }; 
            // start upload
            xhr.open("POST", $id("upload").action, true);
            xhr.setRequestHeader("X_FILENAME", file.name);
            xhr.send(file);

        }
    }
    // initialize
    function Init() {

        var fileselect = $id("fileselect"),
        filedrag = $id("filedrag"),
        submitbutton = $id("submitbutton");

        // file select
        fileselect.addEventListener("change", FileSelectHandler, false);

        // is XHR2 available?
        var xhr = new XMLHttpRequest();
        if (xhr.upload) {

            // file drop
            filedrag.addEventListener("dragover", FileDragHover, false);
            filedrag.addEventListener("dragleave", FileDragHover, false);
            filedrag.addEventListener("drop", FileSelectHandler, false);
            filedrag.style.display = "block";
        }
    }
    // call initialization file
    if (window.File && window.FileList && window.FileReader) {
        Init();
    }
})();