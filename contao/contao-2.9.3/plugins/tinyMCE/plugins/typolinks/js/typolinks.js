/* Contao Open Source CMS :: Copyright (C) 2005-2010 Leo Feyer :: LGPL license */
tinyMCEPopup.requireLangPack();var LinkDialog={preInit:function(){var a;if(a=tinyMCEPopup.getParam("external_link_list_url")){document.write('<script language="javascript" type="text/javascript" src="'+tinyMCEPopup.editor.documentBaseURI.toAbsolute(a)+'"><\/script>')}},init:function(){var b=document.forms[0],a=tinyMCEPopup.editor;document.getElementById("hrefbrowsercontainer").innerHTML=getBrowserHTML("hrefbrowser","href","file","theme_advanced_link");
if(isVisible("hrefbrowser")){document.getElementById("href").style.width="180px"}this.fillFileList("link_list","tinyMCELinkList");this.fillRelList("rel_list");this.fillTargetList("target_list");this.fillClassList("class_list");if(e=a.dom.getParent(a.selection.getNode(),"A")){b.href.value=a.dom.getAttrib(e,"href");b.linktitle.value=a.dom.getAttrib(e,"title");b.insert.value=a.getLang("update");selectByValue(b,"link_list",b.href.value);
if(/window.open\(this.href\);/.test(a.dom.getAttrib(e,"onclick"))){selectByValue(b,"target_list","_blank")}else{selectByValue(b,"target_list",a.dom.getAttrib(e,"target"))}selectByValue(b,"rel_list",a.dom.getAttrib(e,"rel"),true);selectByValue(b,"class_list",a.dom.getAttrib(e,"class"),true)}TinyMCE_EditableSelects.init()},update:function(){var g=document.forms[0],c=tinyMCEPopup.editor,h,a;tinyMCEPopup.restoreSelection();
h=c.dom.getParent(c.selection.getNode(),"A");if(!g.href.value){if(h){tinyMCEPopup.execCommand("mceBeginUndoLevel");a=c.selection.getBookmark();c.dom.remove(h,1);c.selection.moveToBookmark(a);tinyMCEPopup.execCommand("mceEndUndoLevel");tinyMCEPopup.close();return}}tinyMCEPopup.execCommand("mceBeginUndoLevel");if(h==null){c.getDoc().execCommand("unlink",false,null);tinyMCEPopup.execCommand("CreateLink",false,"#mce_temp_url#",{skip_undo:1});
var d=this;tinymce.each(c.dom.select("a"),function(b){if(c.dom.getAttrib(b,"href")=="#mce_temp_url#"){h=b;c.dom.setAttribs(h,{href:g.href.value,title:g.linktitle.value,rel:g.rel_list?getSelectValue(g,"rel_list"):null,"class":g.class_list?getSelectValue(g,"class_list"):null});d.fixIssues(c,h,g)}})}else{c.dom.setAttribs(h,{href:g.href.value,title:g.linktitle.value,rel:g.rel_list?getSelectValue(g,"rel_list"):null,"class":g.class_list?getSelectValue(g,"class_list"):null});
this.fixIssues(c,h,g)}if(h.childNodes.length!=1||h.firstChild.nodeName!="IMG"){c.focus();c.selection.select(h);c.selection.collapse(0);tinyMCEPopup.storeSelection()}tinyMCEPopup.execCommand("mceEndUndoLevel");tinyMCEPopup.close()},fixIssues:function(a,c,b){var d=a.dom.getAttrib(c,"onclick");if(getSelectValue(b,"target_list")=="_blank"&&!/window.open\(this.href\);/.test(d)){a.dom.setAttrib(c,"onclick",tinymce.trim(d+" window.open(this.href); return false;"))
}else{if(d){a.dom.setAttrib(c,"onclick",tinymce.trim(d.replace("window.open(this.href); return false;","")))}}if(b.href.value+"/"==a.settings.document_base_url){b.href.value+="/"}if(b.href.value==a.settings.document_base_url){c.setAttribute("mce_href",b.href.value)}},checkPrefix:function(a){if(a.value&&Validator.isEmail(a)&&!/^\s*mailto:/i.test(a.value)&&confirm(tinyMCEPopup.getLang("typolinks_dlg.link_is_email"))){a.value="mailto:"+a.value
}if(/^\s*www\./i.test(a.value)&&confirm(tinyMCEPopup.getLang("typolinks_dlg.link_is_external"))){a.value="http://"+a.value}if(a.value&&/^#/.test(a.value)){a.value="{{env::request}}"+a.value}},fillFileList:function(g,c){var f=tinyMCEPopup.dom,a=f.get(g),d,b;c=window[c];if(c&&c.length>0){a.options[a.options.length]=new Option("","");tinymce.each(c,function(h){a.options[a.options.length]=new Option(h[0],h[1])
})}else{f.remove(f.getParent(g,"tr"))}},fillRelList:function(d){var c=tinyMCEPopup.dom,a=c.get(d),b;a.options[a.options.length]=new Option(tinyMCEPopup.getLang("not_set"),"");a.options[a.options.length]=new Option(tinyMCEPopup.getLang("typolinks_dlg.image_rel_single"),"lightbox");a.options[a.options.length]=new Option(tinyMCEPopup.getLang("typolinks_dlg.image_rel_multi"),"lightbox[multi]")},fillTargetList:function(d){var c=tinyMCEPopup.dom,a=c.get(d),b;
a.options[a.options.length]=new Option(tinyMCEPopup.getLang("not_set"),"");a.options[a.options.length]=new Option(tinyMCEPopup.getLang("typolinks_dlg.link_target_blank"),"_blank");if(b=tinyMCEPopup.getParam("theme_advanced_link_targets")){tinymce.each(b.split(","),function(f){f=f.split("=");a.options[a.options.length]=new Option(f[0],f[1])})}},fillClassList:function(f){var d=tinyMCEPopup.dom,a=d.get(f),c,b;
if(c=tinyMCEPopup.getParam("theme_advanced_styles")){b=[];tinymce.each(c.split(";"),function(g){var h=g.split("=");b.push({title:h[0],"class":h[1]})})}else{b=tinyMCEPopup.editor.dom.getClasses()}a.options[a.options.length]=new Option(tinyMCEPopup.getLang("not_set"),"");if(b.length>0){tinymce.each(b,function(g){a.options[a.options.length]=new Option(g.title||g["class"],g["class"])})}}};LinkDialog.preInit();
tinyMCEPopup.onInit.add(LinkDialog.init,LinkDialog);