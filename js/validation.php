
<script type="text/javascript" language="javascript">
jQuery(document).ready(function(){
	jQuery("#frmAddGallery").submit(function(){
		var alphabetReg = /^[a-zA-Z ]*$/;
		if (jQuery("#title").val() == '') {
			alert("Title <?php echo CANTEMPTY; ?>");
			jQuery( "#title" ).focus();
			return false;
		}
		if(!alphabetReg.test(jQuery("#title").val())) {
			alert("Title <?php echo ALPHABETSVALIDATION; ?>");
			jQuery( "#title" ).focus();
			return false;
		}
		if (jQuery("#type").val() == 0) {
			alert("Type <?php echo CANTEMPTY; ?>");
			return false;
		}
		if (jQuery("#uploadimage").val() == '') {
			alert("Upload image <?php echo CANTEMPTY; ?>");
			return false;
		}
		if (jQuery("#uploadimage").val() != '') {
			var file_size = $('#uploadimage')[0].files[0].size;
			if(file_size>2097152) {
				alert("File size is greater than 2MB");
				return false;
			} 
		}
		var ext = $('#uploadimage').val().split('.').pop().toLowerCase();
		if($.inArray(ext, ['jpg','JPG','jpeg','JPEG','bmp','BMP','PNG','png','gif','GIF']) == -1) {
			alert('invalid file extension!');
			return false;
		}
	});
});
</script>