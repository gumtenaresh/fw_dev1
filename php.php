 


<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>


        <script LANGUAGE="JavaScript">
                           
            var seltext = null;
            var repltext = null;
            function replaceit() 
            {
                               
                seltext = (document.all)? document.selection.createRange() : document.getSelection();
                var selit = (document.all)? document.selection.createRange().text : document.getSelection();
                alert(selit)
                if (selit.length>=1){
                    if (seltext) {
                        repltext= prompt('Please enter the word to replace:', ' '); 
                        if ((repltext==' ')||(repltext==null)) repltext=seltext.text;
                        seltext.text = repltext;
                        window.focus()
                    }
                }
            }
            function replacePiece(str, start, end) {
                var component = str.substr(start, end - start);
                var newstr = str.substr(0,13) + "<b>" + component + "</b>"
                    + str.substr(end);
                return newstr;
            }

            $("#Str").html(replacePiece($("#Str").text(), 13, 16));
                            
        </script>
    </head>
    <body>
        <form name="f">
            <textarea cols='40' rows='10' name='msg'></textarea>

            <input type="button" name="b" value="Replace" onClick="replaceit();">
        </form>
    </body>
</html>

