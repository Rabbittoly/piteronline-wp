function mediaLibraryOrganizerTinyMCERegisterPlugin(e){var $;$=jQuery,tinymce.PluginManager.add(e.prefix+"_"+e.name.replaceAll("-","_"),(function(i,a){i.addButton(e.prefix+"_"+e.name.replaceAll("-","_"),{title:e.title,image:e.icon,cmd:"media_categories_module_"+e.name.replaceAll("-","_")}),i.addCommand(e.prefix+"_"+e.name.replaceAll("-","_"),(function(){i.windowManager.open({id:"wpzinc-tinymce-modal",title:e.title,width:e.modal.width,height:e.modal.height,buttons:[{text:media_categories_module_tinymce.labels.cancel,classes:"cancel"},{text:media_categories_module_tinymce.labels.insert,subtype:"primary",classes:"insert"}]}),$.post(ajaxurl,{action:"media_categories_module_tinymce_output_modal",nonce:media_categories_module_tinymce.nonce,editor_type:"tinymce",shortcode:e.name},(function(e){jQuery("#wpzinc-tinymce-modal-body").html(e),wp_zinc_tabs_init(),mediaLibraryOrganizerSelectizeInit()}))}))}))}