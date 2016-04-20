jQuery(document).ready(function($){
			$("#rep").hide();
			$("#textatrad").on('input', function(){
				var textatrad = $("#textatrad").val();
				var source = $("#source option:selected").val();
				var target = $("#target option:selected").val();
				
				if (source == target){
					alert("la langue source et la langue cible doivent être différentes");
				}
				else if (textatrad == ""){
					$("#rep").hide();
				}
				else {
					$.post(ajaxScript.ajax_url, {q: textatrad, source: source, target: target, action: "moduletrad_ajax_handler",} 					
					function (data){
					$("#rep").show().empty().append(data);
					}
				)}
				
			return false;
			});
			
		});