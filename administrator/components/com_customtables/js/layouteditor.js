var codemirror_editors=[];
var codemirror_active_index=0;
var temp_params_tag="";
var websiteroot='';

var layout_tags=[];
var layout_tags_loaded=false;

var tags_box_obj=null;
var tagsets=[];

var current_layout_type=0;

function updateTagsParameters()
{
    if(type_obj == null)
        return ;

    current_layout_type=parseInt(type_obj.value);
    if(isNaN(current_layout_type))
        current_layout_type=0;

    var t1=findTagSets(current_layout_type,1);
    var t2=findTagSets(current_layout_type,2);
    var t3=findTagSets(current_layout_type,3);
    var t4=findTagSets(current_layout_type,4);

    tagsets=t1.concat(t2,t3,t4);


    if(tagsets.length>0)
    {
        render_current_TagSets();
    }
    else
        tags_box_obj.innerHTML='<p class="msg_error">Unknown Field Type</p>';

    updateFieldsBox();
}

function findTagSet(tagsetname)
{
    for(var i=0;i<layout_tags.length;i++)
    {
        var a=layout_tags[i]["@attributes"];

        var n="";
        if (typeof(a.name)!= "undefined")
            n=layout_tags[i]["@attributes"].name;

        if(n==tagsetname)
        {
            return layout_tags[i];
        }
    }
    return [];
}

function findTagSets(layouttypeid,priority)
{
    var tagsets_=[];
    for(var i=0;i<layout_tags.length;i++)
    {
        var a=layout_tags[i]["@attributes"];

        var p=0;
        if (typeof(a.priority)!= "undefined")
            p=layout_tags[i]["@attributes"].priority;

        if(p==priority)
        {
            var layouttypes="";
            if (typeof(a.layouttypes)!= "undefined")
                layouttypes=a.layouttypes;

            var lta=layouttypes.split(',');

            if(layouttypes=="" || lta.indexOf(layouttypeid+"")!=-1)
            {
                tagsets_.push(layout_tags[i]);
            }
        }

    }
    return tagsets_;
}

function loadTagParams(type_id,tags_box)
{
    //type_obj_id=type_id;

    current_params_count=0;

    type_obj=document.getElementById(type_id);
    tags_box_obj=document.getElementById(tags_box);

    if(!layout_tags_loaded)
    {
        loadTags(type_id,tags_box);
    }
    else
    {
        updateTagsParameters();
    }

}

function loadTags(type_id,tags_box)
{
    type_obj=document.getElementById(type_id);
    tags_box_obj=document.getElementById(tags_box);

    tags_box_obj.innerHTML='Loading...';

    var url=websiteroot+"/administrator/components/com_customtables/xml/tags.xml";

    var http = null;
    var params = "";

    if (!http)
    {
        http = CreateHTTPRequestObject ();   // defined in ajax.js
    }

    if (http)
    {
        http.open("GET", url, true);
        http.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        http.onreadystatechange = function()
        {

            if (http.readyState == 4)
            {
                var res=http.response;

                var parser = new DOMParser();
                var xmlDoc = parser.parseFromString(res,"text/xml");

                if(xmlDoc.getElementsByTagName('parsererror').length)
                {
                    tags_box_obj.innerHTML='<p class="msg_error">Error: '+(new XMLSerializer()).serializeToString(xmlDoc)+'</p>';
                    return;
                }
                tags_box_obj.innerHTML='Loaded.';
                var s=Array.from(xmlToJson(xmlDoc));
                layout_tags=s[0].layouts.tagset;

                layout_tags_loaded=true;
                loadTagParams(type_id,tags_box);

            }
        };
        http.send(params);
    }
    else
    {
        //error
        tags_box_obj.innerHTML='<p class="msg_error">Cannot connect to the server</p>';
    }
}


function showModal()
{


            // Get the modal
            var modal = document.getElementById('layouteditor_Modal');

            // Get the <span> element that closes the modal
            var span = document.getElementsByClassName("layouteditor_close")[0];

            // When the user clicks on <span> (x), close the modal
            span.onclick = function() {
                modal.style.display = "none";
            };

            // When the user clicks anywhere outside of the modal, close it
            window.onclick = function(event) {
                if (event.target == modal) {
                    modal.style.display = "none";
                }
            };

            var box=document.getElementById("layouteditor_modalbox");



            modal.style.display = "block";

            var d = document;
            e = d.documentElement;

            var doc_w=e.clientWidth;
            var doc_h=e.clientHeight;

            var w=box.offsetWidth;
            var h=box.offsetHeight;

            //var x=left-w/2;
            var x= (doc_w/2)-w/2;
            if(x<10)
                x=10;

            if(x+w+10>doc_w)
                x=doc_w-w-10;

            //var y=top-h/2;
            var y=(doc_h/2)-h/2;


            if(y<50)
                y=50;


            if(y+h+50>doc_h)
            {
                y=doc_h-h-50;
            }

            box.style.left=x+'px';
            box.style.top=y+'px';
}

function showModalForm(tagstartchar,tagendchar,tag,top,left,line,positions,isnew)
{
    //detect tag type first
    //var tag_pair=parseQuote(tag,':',false);
    if(tagstartchar==='{')
    {
        //tags
        showModalTagForm(tagstartchar,tagendchar,tag,top,left,line,positions,isnew);
    }
    else
    {
        //field tags
        showModalFieldTagForm(tagstartchar,tagendchar,tag,top,left,line,positions,isnew);
    }


}

function showModalTagForm(tagstartchar,tagendchar,tag,top,left,line,positions,isnew)
{
    var tag_pair=parseQuote(tag,[':','='],false);

    temp_params_tag=tag_pair[0];

    var tagobject=findTagObjectByName(tagstartchar,tagendchar,temp_params_tag);
    if(tagobject==null || typeof tagobject !== 'object')
        return null;

    var param_array=getParamOptions(tagobject.params,'param');
    var countparams=param_array.length;

    var paramvaluestring="";
    if(tag_pair.length==2)
        paramvaluestring=tag_pair[1];

    var form_content=getParamEditForm(tagobject,line,positions,isnew,countparams,tagstartchar,tagendchar,paramvaluestring);
    if(form_content==null)
        return false;

    var obj=document.getElementById("layouteditor_modal_content_box");
    obj.innerHTML=form_content;

    jQuery(function($)
    {
        //container ||
        $(obj).find(".hasPopover").popover({"html": true,"trigger": "hover focus","layouteditor_modal_content_box": "body"});
    });

    updateParamString("fieldtype_param_",1,countparams,"current_tagparameter",null);

    showModal();
 }

function addTag(index_unused,tagstartchar,tagendchar,tag,param_count)
{

    var index=0;
    if(param_count>0)
    {
        var cm=codemirror_editors[index];
        var cr=cm.getCursor();

        var positions=[cr.ch,cr.ch];
        var mousepos=cm.cursorCoords(cr,"window");

        showModalTagForm(tagstartchar,tagendchar,atob(tag),mousepos.top,mousepos.left,cr.line,positions,1);
    }
    else
        updateCodeMirror(tagstartchar+atob(tag)+tagendchar);
}

function updateCodeMirror(text)
{
    var editor = codemirror_editors[codemirror_active_index];

    var doc = editor.getDoc();
    var cursor = doc.getCursor();
    doc.replaceRange(text, cursor);
}



    function textarea_findindex(code)
    {
        for(var i=0;i<text_areas.length;i++)
        {
        	if(text_areas[i][0]==code)
        		return text_areas[i][1];
        }
        return -1;
    }



    function findTagInLine(ch,str)
        {
            var start_pos=-1;
            var end_pos=-1;
            var level=1;
            var startchar='';
            var endchar='';

            for(var i=ch;i>-1;i--)
            {

                if((str[i]==']' || str[i]=='}') && i!=ch)
                    level++;


                if(str[i]=='[' || str[i]=='{')
                {
                    if(startchar=='')
                        startchar=str[i];

                    level--;
                    if(level==0)
                    {
                        start_pos=i;
                        break;
                    }
                }


            }
            if(start_pos==-1)
                return null;

            level=1;
            for(var i2=ch;i2<str.length;i2++)
            {
                if(str[i2]=='[' || str[i2]=='{')
                    level++;

                if(str[i2]==']' || str[i2]=='}')
                {
                    if(endchar=='')
                        endchar=str[i2];

                    level--;
                    if(level==0)
                    {
                        end_pos=i2;
                        break;
                    }
                }


            }

            if(end_pos==-1)
                return null;


            if(start_pos<=ch && end_pos>=ch)
                return [start_pos,end_pos+1];// +1 because position should end after the tag

            return null;
        }


    function findTagObjectByName(tagstartchar,tagendchar,lookfor_tag)
    {
        for(var s=0;s<tagsets.length;s++)
        {
            var tagset=tagsets[s];
            var tags=getParamOptions(tagset,'tag');

            for(var i=0;i<tags.length;i++)
            {
                var tag=tags[i];
                var a=tag["@attributes"];
                if(a.name==lookfor_tag && a.startchar==tagstartchar && a.endchar==tagendchar)
                    return tag;
            }

        }
        return null;
    }


    //function getParamEditForm(tagobjecttagstartchar,tagendchar,tag,line,positions,isnew)
    function getParamEditForm(tagobject,line,positions,isnew,countparams,tagstartchar,tagendchar,paramvaluestring)
    {
        var att=tagobject["@attributes"];

        var result="";
        var separator=":";

        if (typeof(att.separator)!== "undefined")
            separator=att.separator;

        result+=renderParamBox(tagobject,"current_tagparameter",paramvaluestring);

        result+='<hr/><div class="dynamic_values"><span class="dynamic_values_label">Tag with parameter:</span> '+tagstartchar+temp_params_tag;
        result+=separator+'<span id="current_tagparameter" style="">'+paramvaluestring+'</span>';
        result+=tagendchar+'</div><hr/>';


        result+='<div style="text-align:center;">';
        result+='<button id="clsave" onclick=\'return saveParams(event,'+countparams+','+line+','+positions[0]+','+positions[1]+','+isnew+',"'+tagstartchar+'","'+tagendchar+'","'+separator+'");\' class="btn btn-small button-apply btn-success">Save</button>';
        result+=' <button id="clclose" onclick=\'return closeModal(event);\' class="btn btn-small button-cancel btn-danger">Cancel</button>';
        result+='</div>';


        return result;
    }

    function saveParams(e,countparams,line_number,pos1,pos2,isnew,tagstartchar,tagendchar,separator)
    {
        updateParamString("fieldtype_param_",1,countparams,"current_tagparameter",null);

        e.preventDefault();
        var result='';
        var tmp_params=document.getElementById('current_tagparameter').innerHTML;

            result=tagstartchar+temp_params_tag;

            if(tmp_params!="")
                result+=separator+tmp_params;//{tag:par1,par2} where ":" is separator

            result+=tagendchar;


        var cursor_from = {line:line_number,ch:pos1};
        var cursor_to = {line:line_number,ch:pos2};

        var editor = codemirror_editors[codemirror_active_index];

        var doc = editor.getDoc();
        doc.replaceRange(result, cursor_from,cursor_to,"");


        var modal = document.getElementById('layouteditor_Modal');
        modal.style.display = "none";
        return false;
    }

    function closeModal(e)
    {
        e.preventDefault();

        var modal = document.getElementById('layouteditor_Modal');
        modal.style.display = "none";
        return false;
    }

    function define_cmLayoutEditor()
    {

        define_cmLayoutEditor1('layouteditor','text/html');
        //define_cmLayoutEditor2();
    }

    function define_cmLayoutEditor1(modename,nextmodename)
    {
        CodeMirror.defineMode(modename, function(config, parserConfig)
        {
            var layouteditorOverlay =
            {
                token: function(stream, state)
                {

                    if (stream.match("["))
                    {
                        var hasParameters=false;
                        var level=1;
                        var ch="";
                        while ((ch = stream.next()) != null)
                        {
                            if (ch == "[" )
                            {
                                level++;
                            }

                            if (ch == "]" )
                            {
                                level-=1;
                                if(level==0)
                                {
                                    stream.eat("]");

                                    if(hasParameters)
                                        return "ct_tag_withparams";
                                    else
                                        return "ct_tag";
                                }
                            }

                            if(ch==':' && level==1)
                            {
                                hasParameters=true;
                            }
                        }
                    }
                    else if (stream.match("{"))
                    {
                        var hasParameters2=false;
                        var level2=1;
                        var ch2="";
                        while ((ch2 = stream.next()) != null)
                        {
                            if (ch2 == "{" )
                            {
                                level2++;
                            }

                            if (ch2 == "}" )
                            {
                                level2-=1;
                                if(level2==0)
                                {
                                    stream.eat("}");

                                    if(hasParameters2)
                                        return "ct_curvy_tag_withparams";
                                    else
                                        return "ct_curvy_tag";
                                }
                            }

                            if(ch2==':' && 2==1)
                            {
                                hasParameters2=true;
                            }
                        }
                    }
                    while (stream.next() != null && !(stream.match("[", false) ||  stream.match("{", false) ) ) {}//|| stream.match("{")
                    return null;
                }
            };


            return CodeMirror.overlayMode(CodeMirror.getMode(config, parserConfig.backdrop || nextmodename), layouteditorOverlay);
        });
    }


    /*
    function renderTagSets()
    {
        layouttypeid=type_obj.value;
    }
    */
function render_current_TagSets()
{
    layouttypeid=type_obj.value;


    var result_li='';
    var result_div='';
    var index=0;
    for(var i=0;i<tagsets.length;i++)
    {
        var tagset=tagsets[i];
        var a=tagset["@attributes"];

        var c="";
        if(i==0)
            c="active";

        if (proversion || typeof(a.proversion) === "undefined" || a.proversion==="0")
        {
            result_li+='<li class="'+c+'"><a href="#layouteditor_tags'+index+'_'+i+'" data-toggle="tab">'+a.label+'</a></li>';
            result_div+='<div id="layouteditor_tags'+index+'_'+i+'" class="tab-pane '+c+'"><div class="FieldTagWizard"><p>'+a.description+'</p>'+renderTags(index,tagset)+'</div></div>';
        }
    }

    var result='<ul class="nav nav-tabs" >'+result_li+'</ul>';

    result+='<div class="tab-content" id="layouteditor_tagsContent'+index+'">'+result_div+'</div>';

    tags_box_obj.innerHTML=result;

}

function renderTags(index,tagset)
{
    var tags=getParamOptions(tagset,'tag');

    var result='<div class="dynamic_values">';
    for(var i=0;i<tags.length;i++)
    {
        var tag_object=tags[i];
        var tag=tag_object["@attributes"];

        //if (typeof(tag)!== "undefined" && typeof(tag['proversion']) !== "undefined")
        if (proversion || typeof(tag.proversion) === "undefined" || tag.proversion==="0")
        {
            var t="";
            //var params=[];
            //if(typeof(tag_object.params) != "undefined")
            var params=getParamOptions(tag_object.params,'param');

            if(params.length==0)
                t=tag.startchar+tag.name+tag.endchar;
            else
                t=tag.startchar+tag.name+':<span>Params</span>'+tag.endchar;

            result+='<div style="vertical-align:top;">';
                result+='<div style="display:inline-block;"><a href=\'javascript:addTag("0","'+tag.startchar+'","'+tag.endchar+'","'+btoa(tag.name)+'",'+params.length+');\' class="btn">'+t+'</a></div> ';
                result+='<div style="display:inline-block;">'+tag.description+'</div>';
            result+='</div>';
        }
    }

    result+='</div>';

    return result;
}


function addExtraEvents()
{
                    var index=0;

                    setTimeout(function()
                               {
                                    codemirror_active_index=index;
                                    var cm=codemirror_editors[index];
                                    cm.refresh();

                                    cm.on('dblclick', function()
                                    {
                                        var cr=cm.getCursor();
                                        var line=cm.getLine(cr.line);

                                        var positions=findTagInLine(cr.ch,line);

                                        if(positions!=null)
                                        {
                                            var startchar=line.substring(positions[0],positions[0]+1); //+1 to have 1 character
                                            var endchar=line.substring(positions[1]-1,positions[1]-1+1);//-1 because position ends after the tag
                                            var tag=line.substring(positions[0]+1, positions[1]-1);//-1 because position ends after the tag

                                            var mousepos=cm.cursorCoords(cr,"window");

                                            showModalForm(startchar,endchar,tag,mousepos.top,mousepos.left,cr.line,positions,0);

                                        }

                                    },true);

                               }, 100);
	}

function htmlDecode2(input)
{
  var doc = new DOMParser().parseFromString(input, "text/html");
  return doc.documentElement.textContent;
}
