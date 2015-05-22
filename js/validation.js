jQuery(document).ready(function() {
		
		jQuery('input[type="text"]').focus(function() {
			jQuery(this).removeClass('error');
		});
		 
		jQuery('input[type="text"]').blur(function() {
			jQuery(this).addClass('error');
		});
		jQuery('#message').focus(function() {
			jQuery(this).removeClass('error');
		});
		 
		jQuery('#message').blur(function() {
			jQuery(this).addClass('error');
		});
		
    jQuery("#submit").click(function(){
	var name = jQuery("#name").val();
	var email = jQuery("#email").val();
	var number = jQuery("#number").val();
	var message = jQuery("#message").val();
	var captcha = jQuery("#captcha").val();
	var reg = /^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,4})$/;
	var pattern = /^\d{10}$/;

		 

		if (jQuery("#name").val()==0) 
		{
				jQuery("#name").addClass("error");
				var ok = false;
		}
		else
		{
				jQuery("#name").removeClass("error");
				var ok = true;
		}

		if (!reg.test(email)) 
		{
				jQuery("#email").addClass("error");
				var ok1 = false;
		}
		else
		{
				jQuery("#email").removeClass("error");
				var ok1 = true;
		}
		  
		
		if (!pattern.test(number)) 
		{
			jQuery("#number").addClass("error");
			var ok2 = false;
		}
		else
		{
			jQuery("#number").removeClass("error");
			var ok2 = true;
		}
		
		if (jQuery("#message").val() == "") 
		{
			jQuery("#message").addClass("error");
			var ok3 = false;	
		}
		else
		{
			jQuery("#message").removeClass("error");
			var ok3 = true;
		}
		
		if (jQuery("#captcha").val() == "<?php echo $security_code; ?>") 
		{
			jQuery("#captcha").removeClass("error");
			var ok4 = true;
			
		}
		else
		{
			jQuery("#captcha").addClass("error");
			var ok4 = false;	
		}
		
		
		if(ok == true && ok1 == true && ok2 == true && ok3 == true && ok4 == true)
		{
			
			jQuery.ajax({
				
                url: "<?php echo plugins_url(); ?>/sliding-enquiry-form/mail.php",
                type: "POST",
                contentType: false,
                processData: false,
                data: function() {
                    var data = new FormData();
                    data.append("name", jQuery("#name").val());
					data.append("email", jQuery("#email").val());
					data.append("number", jQuery("#number").val());
					data.append("message", jQuery("#message").val());
					data.append("captcha", jQuery("#captcha").val());
					data.append("serveremailid", jQuery("#serveremailid").val());
					
                    return data;
                }(),
                error: function(_, textStatus, errorThrown) {
                    
                    console.log(textStatus, errorThrown);
                },
                success: function(response, textStatus) {
					
                    if(response==0)
					{
						jQuery("#enquiryform").hide();
						jQuery("#enquirymsg").show();
					}
					else if(responce==1)
					{
						alert("send not");
					}
                    console.log(response, textStatus);
               }
            });
			return true;
		}
		else
		{
			return false;
		}
		
	

    });
   });