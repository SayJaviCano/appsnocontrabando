
var tmp_editor;

function youtubeDialog(editor){
	tmp_editor = editor;
    dialog = editor.openDialog('iframeYouTubeDialog');
};



CKEDITOR.plugins.add('youtube',
{
    init: function(editor)
    {   
       CKEDITOR.dialog.addIframe('iframeYouTubeDialog', 'Vídeo embbed',   CKEDITOR.plugins.getPath('youtube')+'video_youtube.php', 500, 200 );

		var cmd = editor.addCommand('youtube', {exec: youtubeDialog});

		//plugin code goes here
        editor.ui.addButton('youtube',
            {
                label: 'Vídeo embbed',
                command: 'youtube',
                icon: CKEDITOR.plugins.getPath('youtube') + 'img.png'
            });
    }
});


function poner_valor_iframe(url, vReturnValue){		

		if (vReturnValue==null) { }
		else{
			editor = tmp_editor;
			arr_video = vReturnValue.split("/");
			video = arr_video[arr_video.length-1];
			html = "";

			var canal = url.indexOf("youtu");
			if (canal > 0) {
				html = '<div class="embed-responsive embed-responsive-16by9">';
				html+= '<iframe class="embed-responsive-item" src="https://www.youtube.com/embed/' + video + '" frameborder="0" allowfullscreen></iframe>';
				html+= '</div>';
			} else {
				var canal = url.indexOf("vimeo");
				if (canal > 0) {
					html = '<div class="embed-responsive embed-responsive-16by9">';
					html+= '<iframe class="embed-responsive-item" src="https://player.vimeo.com/video/' + video + '" frameborder="0" allowfullscreen></iframe>';
					html+= '</div>';
				}				
			}			

			if (html != "") {
				var newElement = CKEDITOR.dom.element.createFromHtml(html, editor.document);
				editor.insertElement(newElement);
			}
		}
		CKEDITOR.dialog.getCurrent().hide();
}

function ocultar_pie_youtubeDialog(){
	document.querySelector('.cke_dialog_footer').style.display = "none";
}