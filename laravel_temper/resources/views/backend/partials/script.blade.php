<script>
	function deleteData(dt){
		if (confirm("Are you sure you want to delete this data?")) {
			$.ajax({
				type:"POST",
				url:$(dt).data("url"),
				data: {
					"_token"  : "{{ csrf_token() }}",
                    "_method" : "delete"
				},				
				success:function(response){
					if(response.status){
						location.reload();
					}
				},
                error: function(response){
                    console.log(response);
                }
			});
		}
		return false;
	}
	
	$(document).ready(function() {
		$('body').on('click', '.btn-view', function() {
			$(".btn-view").colorbox({
				'width'				: '600px',
				'maxWidth'			: '90%',
				'maxHeight'			: '90%',
				'transition'		: 'elastic',
				'scrolling'			: true,
			});
		});
	
		$.fn.dataTable.ext.errMode = 'none';
		$(document).ajaxError(function(event, jqxhr, settings, exception) {
			if (jqxhr.status == 200) {
				alert('Session expired. Please relogin');
				window.location.reload(true);
			} else 
			if (jqxhr.status == 500) {
				alert('Program error. Please contact IT Dept');
			}
		});
	})
</script>