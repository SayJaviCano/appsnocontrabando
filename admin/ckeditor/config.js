/**	
 * @license Copyright (c) 2003-2016, CKSource - Frederico Knabben. All rights reserved.
 * For licensing, see LICENSE.md or http://ckeditor.com/license
 */

CKEDITOR.editorConfig = function( config ) {

	config.toolbar = [
		{ name: 'document', items: [ 'Source', '-'] },
		{ name: 'styles', items: ['Styles'] },			
		{ name: 'clipboard', items: ['PasteText', 'PasteFromWord'] },
		{ name: 'basicstyles', items: [ 'Bold', 'Italic', 'Underline', '-', 'CopyFormatting', 'RemoveFormat' ] },
		{ name: 'paragraph', items: [ 'NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'JustifyLeft', 'JustifyCenter', 'JustifyRight', 'JustifyBlock'] },
		{ name: 'links', items: [ 'Link', 'Unlink' ] },
		{ name: 'insert', items: [ 'altaarchivos', 'galeria', 'Image', 'youtube', 'Iframe'] },
		{ name: 'tools', items: [ 'Maximize' ] }		
	];

	config.extraPlugins = 'altaarchivos,galeria,youtube,iframedialog,scayt';
	config.language = 'es';
	config.entities_latin = false;
	config.removeDialogTabs = 'image:advanced;link:advanced';
	config.allowedContent = true;
};
