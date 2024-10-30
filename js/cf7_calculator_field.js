jQuery(document).ready(function($) {
	if ( jQuery( ".wpcf7-form" ).length ) {
		cf7_formulas_called();
		jQuery("body").on("change",".wpcf7 input,.wpcf7 select",function(e){
			cf7_formulas_called();
		})
		jQuery(".cf7-hide").closest('p').css('display', 'none');
		function cf7_formulas_called(){
		   var total = 0;
	       var match;
	       var reg =[]; 
	       jQuery("form.wpcf7-form input").each(function () { 
	       		if( jQuery(this).attr("type") == "checkbox" || jQuery(this).attr("type") == "radio"  ) {
	       			var name = jQuery(this).attr("name").replace("[]", "");
	       			reg.push(name);
	       		}else{
	       			reg.push(jQuery(this).attr("name"));
	       		}
	       		
	       })
	       jQuery("form.wpcf7-form select").each(function () { 
	       		reg.push(jQuery(this).attr("name"));
	       })
	       
	       reg = remove_duplicates_ctf7called(reg);
	       var field_regexp = new RegExp( '('+reg.join("|")+')');
	       
	       jQuery( ".calculator_field-total" ).each(function( index ) {
	       		var eq = jQuery(this).data('equation');
				var test = eq;
				eq = '(' + eq + ')';
				while ( match = field_regexp.exec( eq ) ){
					var type = jQuery("input[name="+match[0]+"]").attr("type");
					if( type === undefined ) {
						var type = jQuery("input[name='"+match[0]+"[]']").attr("type");
					}
					if( type =="checkbox" ){
						var vl = 0;
						jQuery("input[name='"+match[0]+"[]']:checked").each(function () {
								 vl += new Number(jQuery(this).val());
						});
						
					}else if( type == "radio"){
						var vl = jQuery("input[name='"+match[0]+"']:checked").val();

					}else if( type === undefined ){
						var vl = jQuery("select[name="+match[0]+"]").val();	
					}else{
						var vl = jQuery("input[name="+match[0]+"]").val();
					}
					if(!$.isNumeric(vl)){
						vl = 0;
					}
					test = test.replace( match[0], vl );
					eq = eq.replace( match[0], vl ); 
				}
				try{
					var r = eval( eq ); // Evaluate the final equation
					total = r;
				}
				catch(e)
				{
					alert( "Error:" + eq );
				}
				
				
				
				
				jQuery(this).val(total);
				jQuery(this).parent().find('.cf7-calculated-name').html(total);
	       });

			
		}
	}
	
	function remove_duplicates_ctf7called(arr) {
	    var obj = {};
	    var ret_arr = [];
	    for (var i = 0; i < arr.length; i++) {
	        obj[arr[i]] = true;
	    }
	    for (var key in obj) {
	    	if("_wpcf7" == key || "_wpcf7_version" == key  || "_wpcf7_locale" == key  || "_wpcf7_unit_tag" == key || "_wpnonce" == key || "undefined" == key  || "_wpcf7_container_post" == key || "_wpcf7_nonce" == key  ){

	    	}else {
	    		ret_arr.push(key);
	    	}
	    }
	    return ret_arr;
	}
});