 
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
      // alert(id);
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
        e.target.className = (e.type == "dragover" ? "hover" : "");
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

        //		Output(
        //			"<p>File information: <strong>" + file.name +
        //			"</strong> type: <strong>" + file.type +
        //			"</strong> size: <strong>" + file.size +
        //			"</strong> bytes</p>"
        //		);

        // display an image
        
     
        if (file.type.indexOf("image") == 0) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#messages').show();
               
                
                $.ajax({
                    url:newURL+'photos/ajaxImageDrag',
                    method:'post',
                    success:function(res){
   
                        $('#messages').css('display:visible');
                        $('#la').show();
                        $('#ra').show();
                       
                        $('#messages').html(res);
                      
                             

       
                    }
                });
                   
            //Output(
            //"<p><strong>" + file.name + ":</strong><br />" +
            //'<div class="inimage" id="'+file.name+'"><img src="' + e.target.result + '" height="127px" width="156px" /><p><a href="javascript:void(0);"><i>caption'+file.name+'</i></a><input type="radio" name="mainimage" /> Main Image </p><div class="close_btn_drag"><a href="javascript:void(0);"  onclick="return cancelPic(\''+file.name+'\');"><img src="views/images/upload_image_close_btn.png" /></a></div></div>'
            //);
            }
            reader.readAsDataURL(file);
        }

        // display text
        if (file.type.indexOf("text") == 0) {
            var reader = new FileReader();
            reader.onload = function(e) {
                Output(
                    //"<p><strong>" + file.name + ":</strong></p><pre>" +
                    //e.target.result.replace(/</g, "&lt;").replace(/>/g, "&gt;") //+
                    //"</pre>"
                    );
            }
            reader.readAsText(file);
        }

    }
    

    


     

    // upload JPEG files
    function UploadFile(file) {

        // following line is not necessary: prevents running on SitePoint servers
        //if (location.host.indexOf("sitepointstatic") >= 0) return

        var xhr = new XMLHttpRequest();
        if (xhr.upload && (file.type == "image/jpeg"||file.type == "image/gif"||file.type == "image/png"||file.type == "image/bmp"||file.type == "image/JPEG"||file.type == "image/GIF"||file.type == "image/PNG"||file.type == "image/BMP"||file.type == "image/jpg"||file.type == "image/JPG") && file.size <= $id("MAX_FILE_SIZE").value) {

            // create progress bar
            /*var o = $id("progress");
			var progress = o.appendChild(document.createElement("p"));
			progress.appendChild(document.createTextNode("upload " + file.name));*/


            // progress bar
            /*xhr.upload.addEventListener("progress", function(e) {
				var pc = parseInt(100 - (e.loaded / e.total * 100));
				progress.style.backgroundPosition = pc + "% 0";
			}, false);*/

            // file received/failed
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
        //appending javascript file dynamically
        var head = document.getElementsByTagName('head')[0];
        var script = document.createElement('script');
        script.type = 'text/javascript';
        script.src = newURL+'fanwire/views/scripts/jquery.tools.min.js';
        head.appendChild(script);
        //appending end
                

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

            // remove submit button
            submitbutton.style.display = "visible";
        }

    }

    // call initialization file
    if (window.File && window.FileList && window.FileReader) {
        Init();
       
    }


})();