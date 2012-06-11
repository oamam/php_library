<!DOCTYPE html>
<html lang="ja">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="chrome=1">
<link rel="stylesheet" href="./js/jquery.plupload.queue/css/jquery.plupload.queue.css" type="text/css">
<link rel="stylesheet" href="./jquery-ui-1.8.21.custom/css/ui-lightness/jquery-ui-1.8.21.custom.css" type="text/css">
<style type="text/css">
body {
font-size: 12px;
}
</style>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.8.18/jquery-ui.min.js"></script>
<script type="text/javascript" src="http://bp.yahooapis.com/2.4.21/browserplus-min.js"></script>
<script type="text/javascript" src="./js/plupload.js"></script>
<script type="text/javascript" src="./js/plupload.gears.js"></script>
<script type="text/javascript" src="./js/plupload.silverlight.js"></script>
<script type="text/javascript" src="./js/plupload.flash.js"></script>
<script type="text/javascript" src="./js/plupload.browserplus.js"></script>
<script type="text/javascript" src="./js/plupload.html4.js"></script>
<script type="text/javascript" src="./js/plupload.html5.js"></script>
<!--
<script type="text/javascript" src="./plupload/js/plupload.full.js"></script>
-->
</head>
<body>
	<section id="imageupload">
		<h2>写真アップロード</h2>
		<div id="container" >
			<div id="filelist">
			<ul id="uploader_filelist" class="plupload_filelist">
			</ul>
			</div>
			<br />
			<a id="pickfiles" href="javascript:void(0);" class="plupload_button plupload_add" style="margin: 10px 0; float: left;">写真の選択</a>
			<a id="uploadfiles" href="javascript:void(0);" class="plupload_button plupload_start plupload_disable" style="margin: 10px; float: left; display: none">アップロード</a>
		</div>

		<script type="text/javascript">
		<!--//
		$(function() {
			$('#ui_dialog_upload').dialog({
				autoOpen: false,  // hide dialog
				bgiframe: true,   // for IE6
				height: 'auto',
				modal: true,
				buttons: {
					 "閉じる": function(event) {
						$('#pickfiles').css('display', '');
						$(this).dialog("close");
					 },
				 }
			});

			/*
			 * pluploader
			 */
			function startMe(id) {
				return document.getElementById(id);
			};

			var uploader = new plupload.Uploader({
				runtimes : 'gears,html5,flash,silverlight,browserplus',
				browse_button : 'pickfiles',
				container: 'container',
				max_file_size : '10mb',
				url : './upload.php',
				resize : {width : 1600, height : 1200, quality : 100},
				flash_swf_url : './js/plupload.flash.swf',
				silverlight_xap_url : './js/plupload.silverlight.xap',
				filters : [
					{title : "Image files", extensions : "jpg,gif,png"},
					{title : "Zip files", extensions : "zip"}
				]
			});

			uploader.bind('Init', function(up, params) {
				startMe('filelist').innerHTML += '<div id="photoselectanounce" style="text-align: center; border: 1px solid #DDDDDD; padding: 10px;">写真を選択してください。</div>';
			});


			uploader.bind('FilesAdded', function(up, files) {
				$('#uploader_filelist').css('border-top', '1px solid #DDDDDD');
				$('#uploader_filelist').css('border-left', '1px solid #DDDDDD');
				$('#uploader_filelist').css('border-right', '1px solid #DDDDDD');
				 var button = $('.ui-dialog-buttonpane').find('button:contains("閉じる")');
				 	button.attr('disabled','disabled');
				 	button.removeClass('ui-state-disable');
				 	button.addClass('ui-state-disabled');
				for (var i in files) {
					startMe('uploader_filelist').innerHTML += '<li id="' + files[i].id + '" class="plupload_delete">' +
					'<div class="plupload_file_name"><span>' + files[i].name + '</span></div>' +
					'<div class="plupload_file_action"><a href="javascript:void(0);" style="display: block;"></a></div>' +
					'<div class="plupload_file_status"><b>0</b>%</div><div class="plupload_file_size">' + plupload.formatSize(files[i].size) + '</div>' +
					'<div class="plupload_clearer">&nbsp;</div>';
					'</li>';
					//startMe('filelist').innerHTML += '<div id="' + files[i].id + '">' + files[i].name + ' (' + plupload.formatSize(files[i].size) + ') <b></b></div>';
				}
				$('#photoselectanounce').remove();
				$('.ui-dialog-content').css('overflow', 'hidden');
				$('#uploadfiles').css('display', '');
			});

			uploader.bind('StateChanged', function(up) {
				if(up.state === plupload.STARTED) {
					$('#pickfiles').css('display', 'none');
					$('#uploadfiles').css('display', 'none');
					$('.plupload_delete div.plupload_file_action a').css('display', 'none');
				}
			});

			uploader.bind('UploadProgress', function(up, file) {
				startMe(file.id).getElementsByTagName('b')[0].innerHTML = '<span>' + file.percent + "</span>";
				if(file.percent === 100) {
					$('li#' + file.id).attr('class', 'plupload_done');
					$('li#' + file.id + ' div.plupload_file_action a').css('display', 'block');
				}
			});

			uploader.bind('UploadComplete', function(up, files) {
				    var button = $('.ui-dialog-buttonpane').find('button:contains("閉じる")');
				    button.removeAttr('disabled');
				    button.removeClass('ui-state-disabled');
				    button.addClass('ui-state-disable');
				    $('#ui_dialog_upload').dialog('open');
			});

			uploader.bind('Error', function(up, error) {
				alert(error.message);
			});

			startMe('uploadfiles').onclick = function() {
				uploader.start();
				return false;
			};

			$('.plupload_delete a').live('click', function() {
				var id = $(this).parent().parent().attr('id');
				var file = uploader.getFile(id);
				uploader.removeFile(file);
				$('li#' + id).remove();
				$('.ui-dialog-content').css('overflow', 'hidden');
			});
			uploader.init();
		});
		//-->
		</script>
	</section>
	<div id="ui_dialog_upload" title="upload" style="display: none;">
		<p>complete!</p>
	</div>
</body>
</html>
