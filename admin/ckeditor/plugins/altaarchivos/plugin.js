
var tmp_editor;

function altaarchivosDialog(editor){
	tmp_editor = editor;
    dialog = editor.openDialog('iframeAltaArchivosDialog');
};


CKEDITOR.plugins.add('altaarchivos',
{
    init: function(editor)
    {       
		
		box = editor.name;
		CKEDITOR.dialog.addIframe('iframeAltaArchivosDialog', 'Upload file',   'file_upload.php?box='+box, 700, 250 );
		var cmd = editor.addCommand('altaarchivos', {exec: altaarchivosDialog});
		//plugin code goes here
        editor.ui.addButton('altaarchivos',
            {
                label: 'Upload file',
                command: 'altaarchivos',
                icon: CKEDITOR.plugins.getPath('altaarchivos') + 'img.png'
            });
    }
});


function poner_valor_altaarchivos(vReturnValue, leyenda){	

		if (vReturnValue==null) { }
		else{
			editor = tmp_editor;

			arr_ext = vReturnValue.split(".");
			ext = arr_ext[arr_ext.length-1].toUpperCase()

			if (ext == "JPG" || ext == "GIF" || ext == "PNG") {
				html = '<img src="' + vReturnValue + '" class="img-fluid" alt="' + leyenda + '"/>';
			}
			else if (ext == "DOC" || ext == "DOCX" || ext == "XLS" || ext == "XLSX" || ext == "PDF" || ext == "ZIP") {

				if (leyenda=="")
				{
					archivo_split = vReturnValue.split("/");
					leyenda = archivo_split[archivo_split.length-1];	
				}
				
				html = '<a href="' + vReturnValue + '" target="_blank" class="enlace" title="' + leyenda + '">' + leyenda + '</a>';
			}
			else if (ext == "MP4") {
				html = '<div class="embed-responsive embed-responsive-16by9">';
				html+= '<video controls="" poster="" class="embed-responsive-item">';
				html+= '<source src="' + vReturnValue + '" type="video/mp4" id="mp4video">';
				html+= '</video>';
				html+= '</div>';			
			}

			var newElement = CKEDITOR.dom.element.createFromHtml(html, editor.document);
			editor.insertElement(newElement);
		}

		CKEDITOR.dialog.getCurrent().hide();
}
function ocultar_pie_altaarchivos(){
		document.querySelector('.cke_dialog_footer').style.display = "none";
}