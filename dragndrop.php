<script type="text/javascript">
				$(document).ready(function(){
		

    $(".block-Accepted").draggable({helper: 'clone'});
	$(".drop-Pending").droppable({
		accept: ".block-Accepted",
		activeClass: 'droppable-active',
		hoverClass: 'droppable-hover',
		drop: function(ev, ui) {
			
			var tmp=ui.draggable.clone();
			var tthis=$(this);
			ui.draggable.fadeTo('10','0.33');

			$.ajax( {
				type:"POST",
				url: "<?= bloginfo('wpurl')?>/wp-admin/admin-ajax.php", 
				data : "action=BARL_request&wtf=toPending&id="+encodeURIComponent(tmp.find('.id').html())+"&cookie="+encodeURIComponent(document.cookie),
				success : function(str){
		
							tmp.removeClass('block-Accepted');
							tmp.addClass('block-Pending').draggable({helper:'clone'});;
							ui.draggable.fadeOut("slow");
							ui.draggable.remove();
							tthis.append(tmp);
							tmp.hide();
							tmp.fadeIn("slow");
						},
				error: function (XMLHttpRequest, textStatus, errorThrown) {
							ui.draggable.fadeTo('10','1');
						}
			});

			
		}
	});
	
	$(".block-Pending").draggable({helper: 'clone'});
	$(".drop-Accepted").droppable({
		accept: ".block-Pending",
		activeClass: 'droppable-active',
		hoverClass: 'droppable-hover',
		drop: function(ev, ui) {
			
			var tmp=ui.draggable.clone();
			var tthis=$(this);
			ui.draggable.fadeTo('10','0.33');
		//	ui.draggable.fadeOut("slow");
		//	ui.draggable.remove();
			$.ajax( {
				type:"POST",
				url: "<?= bloginfo('wpurl')?>/wp-admin/admin-ajax.php", 
				data : "action=BARL_request&wtf=toApproved&id="+encodeURIComponent(tmp.find('.id').html())+"&cookie="+encodeURIComponent(document.cookie),
				success : function(str){
		
							tmp.removeClass('block-Pending');
							tmp.addClass('block-Accepted').draggable({helper:'clone'});;
							ui.draggable.fadeOut("slow");
							ui.draggable.remove();
							tthis.append(tmp);
							tmp.hide();
							tmp.fadeIn("slow");
						},
				error: function (XMLHttpRequest, textStatus, errorThrown) {
							ui.draggable.fadeTo('10','1');
						}
			});
					

			
		}
	});
	
	// DELETE 
	$(".drop-Delete").droppable({
		accept: ".block-Pending , .block-Accepted",
		activeClass: 'droppable-active',
		hoverClass: 'droppable-hover',
		tolerance: "touch", 
		drop: function(ev, ui) {
			
			var tmp=ui.draggable.clone();
			var tthis=$(this);
			ui.draggable.fadeTo('10','0.33');
		//	ui.draggable.fadeOut("slow");
		//	ui.draggable.remove();
			$.ajax( {
				type:"POST",
				url: "<?= bloginfo('wpurl')?>/wp-admin/admin-ajax.php", 
				data : "action=BARL_request&wtf=toDelete&id="+encodeURIComponent(tmp.find('.id').html())+"&cookie="+encodeURIComponent(document.cookie),
				success : function(str){		
							ui.draggable.fadeOut("slow");
							ui.draggable.remove();
						},
				error: function (XMLHttpRequest, textStatus, errorThrown) {
							ui.draggable.fadeTo('10','1');
						}
			});
					

			
		}
	});

  });
  </script>
  <style>
  .block-Accepted {
	border: 3px single #00cc00;
	background-color: #00cc66;
	z-index:100;
}
	.block-Pending {
		border: 3px single #ff9900;
		background-color: #ff9966;
	}
	.drop-Accepted { 
		opacity: 0.7;
	}
	.drop-Pending {
		opacity: 0.7;
	}
	.drop-Delete {
		opacity: 0.8;
	}
   
.droppable-active {
	opacity: 1.0;
}
.droppable-hover {
	outline: 2px dotted black;
}
</style>