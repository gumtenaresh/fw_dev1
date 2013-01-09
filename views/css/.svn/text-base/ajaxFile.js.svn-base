 function addFan(id){
                //	alert(id);
                var dataString ='id='+id;
        
                $.ajax({
                    type: "POST",
                    url:"{/literal}{$smarty.const.SITE_URL}{literal}addFanwire",
                    data: dataString,
                    success: function(response) {
                        if(response==1){
                            /*location.reload();		
                            $('#message').html(response)
                            .hide()
                            .fadeIn(1500)
                            .fadeOut(1500)*/
                            //alert($("#n"+id).val());
                            $("#n"+id).removeClass('fan');
                        	$("#n"+id).addClass('unfan');
							$("#n"+id).val('- unfan');
							$("#n"+id).removeAttr( 'onClick');
							$("#n"+id).attr( 'onClick', 'unFan("'+id+'")');
                        }else{
                            $('#message').html(response)
                            .hide()
                            .fadeIn(1500)
                            .fadeOut(1500)
                        }
                    }
                });
                return false;
            }
            function unFan(id){
    	
                var dataString ='id='+id;
        
                $.ajax({
                    type: "POST",
                    url:"{/literal}{$smarty.const.SITE_URL}{literal}unFanwire",
                    data: dataString,
                    success: function(response) {
                        if(response==1){
                            /*location.reload();
                        
                            $('#message').html(reponse)
                            .hide()
                            .fadeIn(1500)
                            .fadeOut(1500)*/
                        	//$("#n"+id).toggle();
                            //alert($("#n"+id).val());
                            //$("#n"+id).hasClass();
                            $("#n"+id).removeClass('unfan');
                        	$("#n"+id).addClass('fan');
                        	$("#n"+id).val('+ fan');
                        	//$("#n"+id).hide();
                        	//$("#n"+id).hasClass();
                        	$("#n"+id).removeAttr( 'onClick');
							$("#n"+id).attr( 'onClick', 'addFan("'+id+'")');
					
                        }else{
                            $('#message').html(response)
                            .hide()
                            .fadeIn(1500)
                            .fadeOut(1500)
                        }
                    }
                });
                return false;
            }
            function checkMinThreeFan(){
            	
                var dataString ='session_id='+'{/literal}{$smarty.session.id}{literal}';
        
                $.ajax({
                    type: "POST",
                    url:"{/literal}{$smarty.const.SITE_URL}{literal}checkMinThreeFan",
                    data: dataString,
                    success: function(response) {
                    	 
                        if(response>=3){
                            //location.reload();
                            
                            window.location.href="{/literal}{$smarty.const.SITE_URL}{literal}socialMedia";
                        }else{
                            $('#message').html( "<h2>Must be fan of three fanwires</h2>")
                            .hide()
                            .fadeIn(3000)
                            .fadeOut(3000)
                        }
                    }
                });
                return false;
            }