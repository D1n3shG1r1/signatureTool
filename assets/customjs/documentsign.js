      var canvas;
      var signaturePad;
      var TMP_USERLOCALE = {};
      
      $(function(){
        
          getUserLocale();

          $("#pdfContainer").click(function() {
            showThumbNails();
          });
          
          // preventing page from redirecting
          $("html").on("dragover", function(e) {
              e.preventDefault();
              e.stopPropagation();
              $(".upload-sign-wrap.canvas-border").addClass("uploadDragDropBorder");
              
          });
      
          $("html").on("drop", function(e) { e.preventDefault(); e.stopPropagation(); });
          
          // Drag enter
          $('.upload-area').on('dragenter', function (e) {
              e.stopPropagation();
              e.preventDefault();
              
          });
      
          // Drag over
          $('.upload-area').on('dragover', function (e) {
              e.stopPropagation();
              e.preventDefault();
              
          });
      
          // Drop
          $('.upload-area').on('drop', function (e) {
              e.stopPropagation();
              e.preventDefault();
      
              $("#dragDropHere").text("Upload");
            
              var files = e.originalEvent.dataTransfer.files;
              var file = files[0];
              
              var ftype = file.type;
              var allowedMimeTypes = ["image/bmp", "image/jpeg", "image/png"];
              var ftypeIdx = allowedMimeTypes.indexOf(ftype);
              if(ftypeIdx > -1){
                renderSignFile(file);
              }else{
                alert("Invalid file type");
              }
             
          });
      
      });


      const PDFStart = nameRoute => {
        
        let loadingTask = pdfjsLib.getDocument(nameRoute),
        pdfDoc = null,
        //canvas = document.querySelector('#cnv'),
        //ctx = canvas.getContext('2d'),
        scale = 1.5,
        numPage = 1;
        console.log('hi');

        const GeneratePDF = numPage => {
          
          var totalPages = pdfDoc.numPages;
          

          pdfDoc.getPage(numPage).then(page => {
            console.log(page);

            let viewport = page.getViewport({scale:scale});

            //canvas = document.querySelector('#cnv'),
            //ctx = canvas.getContext('2d'),

            var tmpPageNo = numPage;
            var tmpCanvas = document.createElement('canvas');
            tmpCanvas.id = "document_"+tmpPageNo;
            tmpCanvas.className = "document_canvas";
            tmpCanvas.width = viewport.width;
            tmpCanvas.height = viewport.height;

            var tmpMiniCanvas = document.createElement('canvas');
            tmpMiniCanvas.id = "documentThumbnailCanvas_"+tmpPageNo;
            tmpMiniCanvas.className = "documentThumbnailCanvas";
            tmpMiniCanvas.width = tmpCanvas.width/4;
            tmpMiniCanvas.height =  tmpCanvas.height/4;
            
            $('<div>', {
                "id": "documentPageHolder_"+tmpPageNo,
                "class": "documentPageHolder",
                /*"style": "display: table; border: 1px solid #000; margin-bottom: 4px;",*/
                "style": "display: table; margin-bottom: 4px;",
                "html":tmpCanvas
            }).appendTo('#pdfContainer');

            var canvas = document.querySelector('#document_'+tmpPageNo);
            var ctx = canvas.getContext('2d');
            //canvas.height = viewport.height;
            //canvas.width = viewport.width;

            console.log("viewport:");
            console.log(viewport);
            let renderContext = {
              canvasContext : ctx,
              viewport: viewport
            }


            page.render(renderContext);  

            /*== Generate thumbnails ==*/
          
            $('<div>', {
              "id": "documentThumbnail_"+tmpPageNo,
              "class": "documentThumbnail",
              "style": "display: table; margin-bottom: 10px;",
              "html":"<div class=\"thumbnailarea\"><div class=\"thumbCanvasHolder\" pageNum=\""+tmpPageNo+"\" pageHeight=\""+tmpCanvas.height+"\" pageWidth=\""+tmpCanvas.width+"\" onclick=\"gotopage(this);\"></div><span class=\"thumbnailmeta\"></span></div><div class=\"thumbnailText\"><span>"+tmpPageNo+"</span></div>"
            }).appendTo('.thumbnailsBox'); 
            
            $(".thumbnailsBox #documentThumbnail_"+tmpPageNo+" .thumbCanvasHolder").html(tmpMiniCanvas);
            var canvasTh = document.querySelector('#documentThumbnailCanvas_'+tmpPageNo);
            var ctxTh = canvasTh.getContext('2d');

            var scaleTh = Math.max(canvasTh.width / viewport.width, canvasTh.height / viewport.height);
            scaleTh = scaleTh*1.5;
            //page.getViewport({scale:scale});
            let renderContextTh = {
              canvasContext : ctxTh,
              viewport: page.getViewport({scale:scaleTh })
            }
            page.render(renderContextTh); 

          })


          if(numPage < totalPages){
         
            var newPage = numPage + 1;
            GeneratePDF(newPage);  

          }else{
            
            console.log("else");
            
            setTimeout(function(){
              setElementContainerDim();
            }, 500);
            
          }
          
          //document.querySelector('#npages').innerHTML = pdfDoc.numPages;
        }

        /*
        const PrevPage = () => {
          if(numPage === 1){
              return
          }

          numPage--;
          GeneratePDF(numPage);
        }

        const NextPage = () => {
          if(numPage >= pdfDoc.numPages){
              return
          }

          numPage++;
          GeneratePDF(numPage);
        }

        document.querySelector('#prev').addEventListener('click', PrevPage)
        document.querySelector('#next').addEventListener('click', NextPage)
        */

        loadingTask.promise.then(pdfDoc_ => {
          pdfDoc = pdfDoc_;

          var totalPages = pdfDoc.numPages;
          
          if(totalPages > 0){ 
            //document.querySelector('#npages').innerHTML = pdfDoc.numPages;
            GeneratePDF(numPage);
          }

        });


      }
		
      const startPdf = () => {
		    console.log("UPLOADEDFILE2:"+UPLOADEDFILE);
        PDFStart(UPLOADEDFILE);
      }

      window.addEventListener('load', startPdf);
      

      function addSignerFeilds(){
       // alert("addSignerFeilds");
        $.each(DOCUMENTDATA, function(idx, vl){
          console.log("vl:");
          console.log(vl);
          var tmpElm = vl;
          var tmp_is_readonly = tmpElm.is_readonly; 
          var tmp_is_required = tmpElm.is_required; 
          var tmp_default_value = tmpElm.default_value;
          var tmp_elmType = tmpElm.elmType;
          var tmp_font_family = tmpElm.font_family;
          var tmp_font_size = tmpElm.font_size;
          var tmp_font_style = tmpElm.font_style;
          var tmp_font_weight = tmpElm.font_weight;
          var tmp_style = tmpElm.style;
          var tmp_page = tmpElm.page;
          var tmp_pageTop = tmpElm.pageTop;
          
          var tmp_text_decoration = tmpElm.text_decoration;

          var elmData = getElementTypeG(tmpElm);
          
          var objType = tmp_elmType;
          var elm = elmData.elm;
          var elmUniqId = objType +'_'+ elmData.uniqId;
  
          $("#formElementsContainer_Group").append(elm);
          
          
          assignPageNoToElement(elmUniqId, tmp_page);
          assignPageTopToElement(elmUniqId, tmp_pageTop);
          changeFontFamily(elmUniqId);
          changeFontSize(elmUniqId);
          changeLineHeight(elmUniqId);
          changeBold(elmUniqId);
          changeItalic(elmUniqId);
          changeUnderline(elmUniqId);

          /*
          hideElementBorder();
          var objId = $(obj).attr("id");
          var objIdParts = objId.split("_");
          var objType = objIdParts[0];

          var elmData = getElementTypeG(objType);
          */

        });
          
      }

      function gotopage(obj){
        
        var pageNumb = $(obj).attr("pageNum");
        var pageNum = pageNumb - 1;
        var pageHeight = $(obj).attr("pageHeight");
        var pageWidth = $(obj).attr("pageWidth");
        
        var pgBtm = 4;
        var extraHgt = 5;
        var scrollPx = pageNum * pgBtm;
        scrollPx = scrollPx + (pageNum * extraHgt);

        pageHeight = pageHeight.replace("px","");
        pageHeight = pageNum * parseInt(pageHeight);
        scrollPx = scrollPx + pageHeight;
        
        $("#pdfContainer").animate({scrollTop: scrollPx+"px"});
        $("#formElementsContainer_SVG").animate({scrollTop: scrollPx+"px"});
       
        $(".thumbnailarea").removeClass("activeThumb");
        $("#documentThumbnail_"+pageNumb+" .thumbnailarea").addClass("activeThumb");

      }


      function setElementContainerDim(){
        
        /*
        $("#formElementsContainer").width(w);
        $("#formElementsContainer").height(h);
        */
      
        var noOfCnvs = $(".document_canvas").length; 
        
        var cw = $($(".document_canvas")[0]).width();
        var ch = $($(".document_canvas")[0]).height();

        var newWidth = cw + 2;
        var newHeight = ch + 2;
        newHeight = newHeight * noOfCnvs;
        var cnvsBttm = 4 * noOfCnvs;
        var extraHght = 3.9 * noOfCnvs;
        newHeight = newHeight + cnvsBttm;
        newHeight = newHeight + extraHght;
        var negMrg = newHeight + 6
        negMrg = 0 - negMrg;
        negMrg = negMrg - 4;
        $('<div>', {
            "id": 'formElementsContainer',
            "class": 'formElementsContainer',
            "width": newWidth,
            "height": newHeight
        //}).appendTo('#pdfContainer');
      }).insertBefore('#documentPageHolder_1');

        setTimeout(function(){
          $("#formElementsContainer").css({
              "border": "1px dashed #f2f2f2",
              "position": "absolute",
              //"position": "relative",
              //"margin-top": negMrg,
              "margin-left": "0px"
          });
        }, 100);
        

        var  contextMenu = '<div class="contextMenuParent">\
          <ul class="contextMenuUL">\
            <li id="duplicateCtxMenu" class="contextMenuLI">Duplicate</li>\
            <!--<li id="clearCtxMenu" class="contextMenuLI">Clear</li>-->\
            <li id="deleteCtxMenu" class="contextMenuLI">Delete</li>\
          </ul>\
        </div>';

        $('#formElementsContainer').append(contextMenu);


        $('<svg>', {
            "id": 'formElementsContainer_SVG',
            "class": 'formElementsContainer_SVG',
            "style": "display:flex; width:"+newWidth+"px; height:"+newHeight+"px;",
            "html":'<g id="formElementsContainer_Group" transform="scale(1)" class=""></g><g id="svg_selector"></g>',
            "onclick":"hideElementBorder();"
        }).appendTo('#formElementsContainer');
           
        setTimeout(function(){
          addSignerFeilds();
        }, 500);
        


      }

      function hideElementBorder(){
        $(".pdf-form-element").removeClass("pdf-form-element-border");
      }

      function addElement(obj){
	    	hideElementBorder();
        var objId = $(obj).attr("id");
        var objIdParts = objId.split("_");
        var objType = objIdParts[0];

        var elmData = getElementTypeG(objType);

        var elm = elmData.elm;
        var elmUniqId = objType +'_'+ elmData.uniqId;

        console.log("elm:");
        console.log(elm);

        $("#formElementsContainer_Group").append(elm);

       

        setTimeout(function(){
          
          console.log("elmUniqId");
          console.log($("#"+elmUniqId));
          
          //close context menu
          $(".formElementsContainer_SVG").click(function(){
            $(".contextMenuParent").hide();
          });
          
          $(".pdf-form-element").click(function(){
            $(".contextMenuParent").hide();
          });


          initDragElement(document.getElementById(elmUniqId));
          createContextMenu(elmUniqId);
        }, 300);

      }

      function resetFieldData(obj, DstElmId){
        
        return false; //needs to be change
        //text
        $(obj).val("");
        changeDefaultText(obj, DstElmId);

        //font family
        $("#font-family").val("CourierPrime-Regular");
        changeFontFamily(obj, DstElmId);
          
        $("").val(13);     
        changeFontSize(obj, DstElmId);



      }


      function createContextMenu(elmUniqId){
        return false; //needs to be remove
        var elmnt = document.getElementById(elmUniqId)
        
        /* if (document.addEventListener) { */
            //document.addEventListener('contextmenu', function(e) {
            elmnt.addEventListener('contextmenu', function(e) {
              
              //alert("You've tried to open context menu 1"); //here you draw your own menu
              var pos = $("#"+elmUniqId).position();
              var l = pos.left;
              var t = pos.top;
              var h = $("#"+elmUniqId).height();
              t = t + h + 2;

              
              $(".contextMenuParent").css("left",l);
              $(".contextMenuParent").css("top",t);
              
              var onclickAttr = 'deleteForm("'+elmUniqId+'");';

              $(".contextMenuParent #deleteCtxMenu").attr("onclick", onclickAttr);

              $(".contextMenuParent").show();

              e.preventDefault();
            }, false);
         /*
          } else {
            //document.attachEvent('oncontextmenu', function() {
            elmnt.attachEvent('oncontextmenu', function() {
              alert("You've tried to open context menu 2");
              window.event.returnValue = false;
            });
          }
        */
      }


      function deleteForm(elmUniqId){
        return false; //needs to be remove
        $(".contextMenuParent").hide();
        $("#"+elmUniqId).remove();

      }

      function randomStr() {
          var len = 20;
          var arr = '12Zcfcfihkdhmvt345abc755dh54vhfdsk56de67890xudfsrgkfbg';
          var ans = '';
          for (var i = len; i > 0; i--) {
              ans += 
                arr[Math.floor(Math.random() * arr.length)];
          }
          return ans;
      }

      function currentDate(format, d){

        var monthNamesArr = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];

        if(d == undefined || d == null || d == ""){
          var d = new Date();
          var month = d.getMonth()+1;
          var day = d.getDate();
          var year = d.getFullYear();
        }else{
          var dateParts = d.split("/");
          
          var month = dateParts[0];;
          var day = dateParts[1];
          var year = dateParts[2];
          
        }
        

        if(format == "dd/MM/yyyy"){
          //"dd/MM/yyyy"
          var output = ((''+day).length<2 ? '0' : '') + day + '/' + ((''+month).length<2 ? '0' : '') + month + '/' + year;

        }else if(format == "MM/dd/yyyy"){
          //"MM/dd/yyyy"
          var output = ((''+month).length<2 ? '0' : '') + month + '/' + ((''+day).length<2 ? '0' : '') + day + '/' + year;

        }else if(format == "dd-MMM-yyyy"){
          //"dd-MMM-yyyy"
          var MMM = monthNamesArr[month - 1];
          var output = ((''+day).length<2 ? '0' : '') + day + '-' + MMM + '-' + year;

        }else if(format == "MMM-dd-yyyy"){
          //"MMM-dd-yyyy"
          var MMM = monthNamesArr[month - 1];
          var output = MMM + '-' + ((''+day).length<2 ? '0' : '') + day + '-' + year;

        }else if(format == "MMM dd, yyyy"){
          //"MMM dd, yyyy"
          var MMM = monthNamesArr[month - 1];
          var output = MMM + ' ' + ((''+day).length<2 ? '0' : '') + day + ', ' + year;

        }else{
          //"dd MMM, yyyy"
          var MMM = monthNamesArr[month - 1];
          var output = ((''+day).length<2 ? '0' : '') + day + ' ' + MMM + ', ' + year;
        }

        return output;

      }

      function userName(){
        var fName = "Dinesh";
        var lName = "Kumar";
        return fName +" "+ lName;
      }

      function userEmail(){
        var email = "upkit.dineshgiri@gmail.com";
        return email;
      }

      function getElementTypeG(tmpElm){
        
        var uniqId = randomStr();

	      var tmp_is_readonly = parseInt(tmpElm.is_readonly); 
        var tmp_is_required = parseInt(tmpElm.is_required); 
        var tmp_default_user = tmpElm.default_user;
        var tmp_default_value = tmpElm.default_value;
        var tmp_elmType = tmpElm.elmType;
        var tmp_font_family = tmpElm.font_family;
        var tmp_font_size = tmpElm.font_size;
        var tmp_font_style = tmpElm.font_style;
        var tmp_font_weight = tmpElm.font_weight;
        var tmp_style = tmpElm.style;
        var tmp_text_decoration = tmpElm.text_decoration;
        var tmpUserProp = tmp_default_user.split(SEPERATOR);  
        var tmp_userName = tmpUserProp[0];
        var tmp_userEmail = tmpUserProp[1];
        var tmp_userTag = tmpUserProp[2];
        var tmp_userColor = tmpUserProp[3];
        
        var objType = tmp_elmType;
        var contEditable = '';
        var onKeyUpAttr = '';
        if(tmp_is_required == 1 && tmp_is_readonly == 0){
         // contEditable = 'contenteditable="true"';
          /*onKeyUpAttr = 'onKeyup=changeDefault'*/
        }


        var signature = '<g id="signature_'+uniqId+'" class="pdf-form-element" onclick="openSignPad(\'signature_'+uniqId+'\');" style="'+tmp_style+'" '+contEditable+'>\
                      <rect id="signature_'+uniqId+'_rect2" width="124" height="32" fill="#FDF7DB" stroke="#fdf7db"></rect>\
                      <rect id="signature_'+uniqId+'_rect1" width="4" height="32" fill="#FAEA9E" stroke="#fdf7db"></rect>\
                      <text id="signature_'+uniqId+'_text" x="4" font-size="'+tmp_font_size+'" font-family="'+tmp_font_family+'" fill="#000000" font-style="normal" font-weight="normal" text-decoration="none" xml:space="preserve" y="0" default-value="'+tmp_default_value+'" default-user="'+tmp_default_user+'" is-readonly="'+tmp_is_readonly+'" is-required="'+tmp_is_required+'">\
              <tspan style="word-break: break-word;" x="4" dy="13">'+tmp_default_value+'</tspan>\
                      </text>\
                  </g>';

        var signaturein = '<g id="signaturein_'+uniqId+'" class="pdf-form-element" onclick="openSignPad(\'signaturein_'+uniqId+'\');" style="'+tmp_style+'" '+contEditable+'>\
                          <rect id="signaturein_'+uniqId+'_rect2" width="48" height="32" fill="#FDF7DB" stroke="#fdf7db"></rect>\
                          <rect id="signaturein_'+uniqId+'_rect1" width="4" height="32" fill="#FAEA9E" stroke="#fdf7db"></rect>\
                          <text id="signaturein_'+uniqId+'_text" x="4" font-size="'+tmp_font_size+'" font-family="'+tmp_font_family+'" fill="#000000" font-style="normal" font-weight="normal" text-decoration="none" xml:space="preserve" y="0" default-value="'+tmp_default_value+'" default-user="'+tmp_default_user+'" is-readonly="'+tmp_is_readonly+'" is-required="'+tmp_is_required+'">\
						  <tspan style="word-break: break-word;" x="4" dy="13">'+tmp_default_value+'</tspan>\
						  </text>\
                       </g>';

        var textbox =  '<g id="textbox_'+uniqId+'" class="pdf-form-element" onclick="openFieldSettings(\'textbox_'+uniqId+'\');" style="'+tmp_style+'" '+contEditable+'>\
                          <rect id="textbox_'+uniqId+'_rect2" width="80" height="17" fill="#FDF7DB" stroke="transparent"></rect>\
                          <rect id="textbox_'+uniqId+'_rect1" width="4" height="17" fill="#FAEA9E" stroke="transparent"></rect>\
                          <text id="textbox_'+uniqId+'_text" x="4" font-size="'+tmp_font_size+'" font-family="'+tmp_font_family+'" fill="#000000" font-style="'+tmp_font_style+'" font-weight="'+tmp_font_weight+'" text-decoration="'+tmp_text_decoration+'" xml:space="preserve" y="0" default-value="'+tmp_default_value+'" default-user="'+tmp_default_user+'" is-readonly="'+tmp_is_readonly+'" is-required="'+tmp_is_required+'">\
                          <tspan style="word-break: break-word;" x="4" dy="13">'+tmp_default_value+'</tspan>\
                          </text>\
                       </g>';
	
		    var datepicker = '<g id="datepicker_'+uniqId+'" class="pdf-form-element" onclick="openFieldSettings(\'datepicker_'+uniqId+'\');" '+contEditable+'>\
                           <rect id="datepicker_'+uniqId+'_rect2" width="74.078125" height="17" fill="#FDF7DB" stroke="transparent"></rect>\
                           <rect id="datepicker_'+uniqId+'_rect1" width="4" height="17" fill="#FAEA9E" stroke="transparent"></rect>\
                           <text id="datepicker_'+uniqId+'_text" x="4" font-size="13px" font-family="CourierPrime-Regular" fill="#000000" font-style="normal" font-weight="normal" text-decoration="none" default-value="'+currentDate("dd/MM/yyyy")+'" date-format="dd/MM/yyyy" default-user="'+CURRENTUSERNAME_1+SEPERATOR+CURRENTUSEREMAIL_1+SEPERATOR+CURRENTUSERTAG_1+SEPERATOR+CURRENTUSERCOLOR_1+'" xml:space="preserve" y="0" is-readonly="'+tmp_is_readonly+'" is-required="'+tmp_is_required+'">\
                           <tspan style="word-break: break-word;" x="4" dy="13">'+currentDate("dd/MM/yyyy")+'</tspan>\
                           </text>\
                       </g>';
        
        var checkbox = '<g id="checkbox_'+uniqId+'" class="pdf-form-element" onclick="openFieldSettings(\'checkbox_'+uniqId+'\');">\
                          <rect id="checkbox_'+uniqId+'_backRect" width="22" height="16" fill="#FDF7DB"></rect>\
                          <rect id="checkbox_'+uniqId+'_rect1" width="2" height="16" fill="#FAEA9E"></rect>\
                          <rect id="checkbox_'+uniqId+'_rect2" x="4" y="1" width="14" height="14" fill="#ffffff" stroke="#b3bbc5" rx="2" ry="2"></rect>\
                          <path id="checkbox_'+uniqId+'_tick" fill="none" stroke="#0565ff" d="M 4 6 L 7.5 9.5 L 14.5 2.5" transform="translate(2,2)" stroke-width="2"></path>\
                       </g>';

        var  radiobutton = '<g id="radiobutton_'+uniqId+'" class="pdf-form-element" onclick="openFieldSettings(\'radiobutton_'+uniqId+'\');">\
                              <g id="radioChild_'+uniqId+'" transform="translate(245.5,2057)" class="pdf-child-form-element">\
                                 <rect id="radioChild_'+uniqId+'_backRect" width="22" height="16" fill="#FDF7DB"></rect>\
                                 <rect id="radioChild_'+uniqId+'_rect1" width="2" height="16" fill="#FAEA9E"></rect>\
                                 <circle id="radioChild_'+uniqId+'_circle" fill="#ffffff" stroke="#b3bbc5" cx="11" cy="8" r="7"></circle>\
                              </g>\
                              <g id="radioChild_'+uniqId+'" transform="translate(245.5,2081)" class="pdf-child-form-element">\
                                 <rect id="radioChild_'+uniqId+'_backRect" width="22" height="16" fill="#FDF7DB"></rect>\
                                 <rect id="radioChild_'+uniqId+'_rect1" width="2" height="16" fill="#FAEA9E"></rect>\
                                 <circle id="radioChild_'+uniqId+'_circle" fill="#ffffff" stroke="#b3bbc5" cx="11" cy="8" r="7"></circle>\
                              </g>\
                           </g>';

        var name = '<g id="name_'+uniqId+'" class="pdf-form-element" onclick="openFieldSettings(\'name_'+uniqId+'\');" '+contEditable+'>\
                       <rect id="name_'+uniqId+'_rect2" width="73.4072265625" height="17" fill="#FDF7DB" stroke="transparent"></rect>\
                       <rect id="name_'+uniqId+'_rect1" width="4" height="17" fill="#FAEA9E" stroke="transparent"></rect>\
                       <text id="name_'+uniqId+'_text" x="4" font-size="13px" font-family="CourierPrime-Regular" fill="#000000" font-style="normal" font-weight="normal" text-decoration="none" xml:space="preserve" y="0" default-value="Text" default-user="'+CURRENTUSERNAME_1+SEPERATOR+CURRENTUSEREMAIL_1+SEPERATOR+CURRENTUSERTAG_1+SEPERATOR+CURRENTUSERCOLOR_1+'" xml:space="preserve" y="0" is-readonly="'+tmp_is_readonly+'" is-required="'+tmp_is_required+'">\
                        <tspan style="word-break: break-word;" x="4" dy="13">'+userName()+'</tspan>\
                       </text>\
                    </g>'; 


        var email = '<g id="email_'+uniqId+'" class="pdf-form-element" onclick="openFieldSettings(\'email_'+uniqId+'\');" '+contEditable+'>\
                       <rect id="email_'+uniqId+'_rect2" width="168.15625" height="17" fill="#FDF7DB" stroke="transparent"></rect>\
                       <rect id="email_'+uniqId+'_rect1" width="4" height="17" fill="#FAEA9E" stroke="transparent"></rect>\
                       <text id="email_'+uniqId+'_text" x="4" font-size="13px" font-family="CourierPrime-Regular" fill="#000000" font-style="normal" font-weight="normal" text-decoration="none" xml:space="preserve" y="0" default-value="'+CURRENTUSEREMAIL_1+'" default-user="'+CURRENTUSERNAME_1+SEPERATOR+CURRENTUSEREMAIL_1+SEPERATOR+CURRENTUSERTAG_1+SEPERATOR+CURRENTUSERCOLOR_1+'" xml:space="preserve" y="0" is-readonly="'+tmp_is_readonly+'" is-required="'+tmp_is_required+'">\
                        <tspan style="word-break: break-word;" x="4" dy="13">'+userEmail()+'</tspan>\
                       </text>\
                    </g>';

        var editableDate = '<g id="editableDate_'+uniqId+'" class="pdf-form-element" onclick="openFieldSettings(\'editableDate_'+uniqId+'\');" '+contEditable+'>\
                             <rect id="editableDate_'+uniqId+'_rect2" width="95" height="17" fill="#FDF7DB" stroke="transparent"></rect>\
                             <rect id="editableDate_'+uniqId+'_rect1" width="4" height="17" fill="#FAEA9E" stroke="transparent"></rect>\
                             <text id="editableDate_'+uniqId+'_text" x="4" font-size="13px" font-family="CourierPrime-Regular" fill="#000000" font-style="normal" font-weight="normal" text-decoration="none" default-value="'+currentDate("MM/dd/yyyy")+'" default-user="'+CURRENTUSERNAME_1+SEPERATOR+CURRENTUSEREMAIL_1+SEPERATOR+CURRENTUSERTAG_1+SEPERATOR+CURRENTUSERCOLOR_1+'"  xml:space="preserve" y="0" is-readonly="'+tmp_is_readonly+'" is-required="'+tmp_is_required+'">\
                              <tspan style="word-break: break-word;" x="4" dy="13">MM/dd/yyyy</tspan>\
                             </text>\
                          </g>';

        var label = '<g id="label_'+uniqId+'" class="pdf-form-element" style="visibility: visible;" onclick="openFieldSettings(\'label_'+uniqId+'\');" '+contEditable+'>\
                       <rect id="label_'+uniqId+'_rect1" width="80" height="17" fill="#f4f5eb" stroke="transparent"></rect>\
                       <text id="label_'+uniqId+'_text" x="4" font-size="13px" font-family="CourierPrime-Regular" fill="#000000" font-style="normal" font-weight="normal" text-decoration="none" default-value="Label" default-user="'+CURRENTUSERNAME_1+SEPERATOR+CURRENTUSEREMAIL_1+SEPERATOR+CURRENTUSERTAG_1+SEPERATOR+CURRENTUSERCOLOR_1+'" xml:space="preserve" y="0" is-readonly="'+tmp_is_readonly+'" is-required="'+tmp_is_required+'">\
                        <tspan style="word-break: break-word;" x="4" dy="13">Label</tspan>\
                       </text>\
                    </g>';

        var hyperlink = '<g id="hyperlink_'+uniqId+'" class="pdf-form-element" onclick="openFieldSettings(\'hyperlink_'+uniqId+'\');" '+contEditable+'>\
                          <rect id="hyperlink_'+uniqId+'_rect1" width="90" height="20" fill="#f4f5eb" stroke="transparent"></rect>\
                          <image height="16" width="16" id="hyperlink_'+uniqId+'_hyperlinkicon" x="71.6875" src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjQiIGhlaWdodD0iMjQiIHZpZXdCb3g9IjAgMCAyNCAyNCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZmlsbC1ydWxlPSJldmVub2RkIiBjbGlwLXJ1bGU9ImV2ZW5vZGQiIGQ9Ik01IDdDNC43MzQ3OCA3IDQuNDgwNDMgNy4xMDUzNiA0LjI5Mjg5IDcuMjkyODlDNC4xMDUzNiA3LjQ4MDQzIDQgNy43MzQ3OCA0IDhWMTlDNCAxOS4yNjUyIDQuMTA1MzYgMTkuNTE5NiA0LjI5Mjg5IDE5LjcwNzFDNC40ODA0MyAxOS44OTQ2IDQuNzM0NzggMjAgNSAyMEgxNkMxNi4yNjUyIDIwIDE2LjUxOTYgMTkuODk0NiAxNi43MDcxIDE5LjcwNzFDMTYuODk0NiAxOS41MTk2IDE3IDE5LjI2NTIgMTcgMTlWMTNDMTcgMTIuNDQ3NyAxNy40NDc3IDEyIDE4IDEyQzE4LjU1MjMgMTIgMTkgMTIuNDQ3NyAxOSAxM1YxOUMxOSAxOS43OTU3IDE4LjY4MzkgMjAuNTU4NyAxOC4xMjEzIDIxLjEyMTNDMTcuNTU4NyAyMS42ODM5IDE2Ljc5NTcgMjIgMTYgMjJINUM0LjIwNDM1IDIyIDMuNDQxMjkgMjEuNjgzOSAyLjg3ODY4IDIxLjEyMTNDMi4zMTYwNyAyMC41NTg3IDIgMTkuNzk1NiAyIDE5VjhDMiA3LjIwNDM1IDIuMzE2MDcgNi40NDEyOSAyLjg3ODY4IDUuODc4NjhDMy40NDEyOSA1LjMxNjA3IDQuMjA0MzUgNSA1IDVIMTFDMTEuNTUyMyA1IDEyIDUuNDQ3NzIgMTIgNkMxMiA2LjU1MjI4IDExLjU1MjMgNyAxMSA3SDVaIiBmaWxsPSIjMzMzMzMzIi8+CjxwYXRoIGZpbGwtcnVsZT0iZXZlbm9kZCIgY2xpcC1ydWxlPSJldmVub2RkIiBkPSJNMTQgM0MxNCAyLjQ0NzcyIDE0LjQ0NzcgMiAxNSAySDIxQzIxLjU1MjMgMiAyMiAyLjQ0NzcyIDIyIDNWOUMyMiA5LjU1MjI4IDIxLjU1MjMgMTAgMjEgMTBDMjAuNDQ3NyAxMCAyMCA5LjU1MjI4IDIwIDlWNEgxNUMxNC40NDc3IDQgMTQgMy41NTIyOCAxNCAzWiIgZmlsbD0iIzMzMzMzMyIvPgo8cGF0aCBmaWxsLXJ1bGU9ImV2ZW5vZGQiIGNsaXAtcnVsZT0iZXZlbm9kZCIgZD0iTTIxLjcwNzEgMi4yOTI4OUMyMi4wOTc2IDIuNjgzNDIgMjIuMDk3NiAzLjMxNjU4IDIxLjcwNzEgMy43MDcxMUwxMC43MDcxIDE0LjcwNzFDMTAuMzE2NiAxNS4wOTc2IDkuNjgzNDIgMTUuMDk3NiA5LjI5Mjg5IDE0LjcwNzFDOC45MDIzNyAxNC4zMTY2IDguOTAyMzcgMTMuNjgzNCA5LjI5Mjg5IDEzLjI5MjlMMjAuMjkyOSAyLjI5Mjg5QzIwLjY4MzQgMS45MDIzNyAyMS4zMTY2IDEuOTAyMzcgMjEuNzA3MSAyLjI5Mjg5WiIgZmlsbD0iIzMzMzMzMyIvPgo8L3N2Zz4K" preserveAspectRatio="xMinYMid meet" y="2"></image>\
                          <text id="hyperlink_'+uniqId+'_text" x="4" font-size="13px" font-family="CourierPrime-Regular" fill="#3E60FF" font-style="normal" font-weight="normal" text-decoration="none" default-value="'+CURRENTUSEREMAIL_1+'" default-user="'+CURRENTUSERNAME_1+SEPERATOR+CURRENTUSEREMAIL_1+SEPERATOR+CURRENTUSERTAG_1+SEPERATOR+CURRENTUSERCOLOR_1+'"  xml:space="preserve" y="0" is-readonly="'+tmp_is_readonly+'" is-required="'+tmp_is_required+'">\
                           <tspan style="word-break: break-word;" x="4" dy="13">Hyperlink</tspan>\
                          </text>\
                       </g>';  

        var elemJson = {
          
          "signature":signature,
          "signaturein":signaturein,
          "textbox":textbox,
          "datepicker":datepicker,
          "checkbox":checkbox,
          "radiobutton":radiobutton,
          "name":name,
          "email":email,
          "editableDate":editableDate,
          "label":label,
          "hyperlink":hyperlink

        };


        var elm = elemJson[objType];
		
	    	return {"uniqId":uniqId, "elm":elm};

      }

		
			
      function openUsersList(){
        return false; //need to be remove
        
        $("#select-user").slideToggle("slow");
      }

      function assignUserToField(obj, DstElmId){
        return false; //need to be remove
        var userVal = $(obj).attr("data-value");
        var userValHtml = $(obj).html();
        
        $("#currentUserNameSpan").html(userValHtml);
        $("#currentUserNameHidden").val(userVal);
        
        var elmIdParts = DstElmId.split("_");
        var  elmTyp = elmIdParts[0];
        var  elmIdStr = elmIdParts[1];
        
        
        var userValArr = userVal.split(SEPERATOR);
        var tmpColor = userValArr[userValArr.length - 1];
        
        $("#"+elmTyp+"_"+elmIdStr+"_rect1").remove();
        
        //$("#"+elmTyp+"_"+elmIdStr+"_rect2").css({"border-right": "4px solid "+tmpColor, "height": "100%", "float": "left"});
        $("#"+elmTyp+"_"+elmIdStr).css({"border": "1px solid "+tmpColor});
        $("#"+elmTyp+"_"+elmIdStr).css({"border-left": "4px solid "+tmpColor});
        //$("#"+elmTyp+"_"+elmIdStr).css({"background-color": tmpColor, "opacity": "0.5"});
        $("#"+elmTyp+"_"+elmIdStr).css({"background-color": tmpColor+"80"});

        $("#"+elmTyp+"_"+elmIdStr+"_text").attr("default-user", userVal);
        
        $("#select-user").slideToggle("slow");
        
      }


      function getElementSavedAttributes(elmId){
        
        var elmIdParts = elmId.split("_");
            var elmTyp = elmIdParts[0];
            var elmIdStr = elmIdParts[1];
        
        var font_size = $("#"+elmTyp+"_"+elmIdStr+"_text").attr("font-size");
        var font_family = $("#"+elmTyp+"_"+elmIdStr+"_text").attr("font-family");
        var font_style = $("#"+elmTyp+"_"+elmIdStr+"_text").attr("font-style");
        var font_weight = $("#"+elmTyp+"_"+elmIdStr+"_text").attr("font-weight");
        var text_decoration = $("#"+elmTyp+"_"+elmIdStr+"_text").attr("text-decoration");
        var default_value = $("#"+elmTyp+"_"+elmIdStr+"_text").attr("default-value");
        var default_user = $("#"+elmTyp+"_"+elmIdStr+"_text").attr("default-user");
        var line_height = $("#"+elmTyp+"_"+elmIdStr+"_text").attr("line-height");
        var date_format = $("#"+elmTyp+"_"+elmIdStr+"_text").attr("date-format");

        var is_readonly = $("#"+elmTyp+"_"+elmIdStr+"_text").attr("is-readonly");
        var is_required = $("#"+elmTyp+"_"+elmIdStr+"_text").attr("is-required");
        
        if(isReal(font_size) == false){
          font_size = "13px";
        }else if(isReal(font_family) == false){
          font_family = "CourierPrime-Regular";
        }else if(isReal(font_style) == false){
          font_style = "normal";
        }else if(isReal(font_weight) == false){
          font_weight = "normal";
        }else if(isReal(text_decoration) == false){
          text_decoration = "none";
        }else if(isReal(default_value) == false){
          default_value = "Text";
        }else if(isReal(default_user) == false){
          default_user = CURRENTUSERNAME_1+SEPERATOR+CURRENTUSEREMAIL_1+SEPERATOR+CURRENTUSERTAG_1+SEPERATOR+CURRENTUSERCOLOR_1;
        }else if(isReal(line_height) == false){
          line_height = "15px";
        }else if(isReal(date_format) == false){
          date_format = "dd/MM/yyyy";
        }
      
      
        return {"font-size":font_size, "font-family":font_family, "font-style":font_style, "font-weight":font_weight, "text-decoration":text_decoration, "default-value":default_value, "default-user":default_user, "line-height":line_height, "date-format":date_format, "is-readonly":is_readonly, "is-required":is_required};
      
      }

      function showThumbNails(){
        $("#bs-thumbnail-prepare").show();
        $("#Advance-fields").hide();
      }

    function openFieldSettings(elmId){
 
        event.stopPropagation();
		hideElementBorder();
        $("#"+elmId).addClass("pdf-form-element-border");
        initResizeElement(document.getElementById(elmId));


        var elmIdParts = elmId.split("_");
        var  elmTyp = elmIdParts[0];
        var  elmIdStr = elmIdParts[1];

		//"font-size":font_size, "font-family":font_family, "font-style":font_style, "font-weight":font_weight, "text-decoration":text_decoration, "default-value":default_value, "default-user":default_user, "line-height":line_height, "date-format":date_format

		var fieldValuesObj = getElementSavedAttributes(elmId);
		console.log("fieldValuesObj");
		console.log(fieldValuesObj);
		
		var font_size = fieldValuesObj["font-size"];
		var font_family = fieldValuesObj["font-family"];
		var font_style = fieldValuesObj["font-style"];
		var font_weight = fieldValuesObj["font-weight"];
		var text_decoration = fieldValuesObj["text-decoration"];
		var default_value = fieldValuesObj["default-value"];
		var default_user = fieldValuesObj["default-user"];
		var line_height = fieldValuesObj["line-height"];
		var date_format = fieldValuesObj["date-format"];

		//Dynamic HTML Strings
		//Action buttons html for close & reset

		var resetButton = '<span class="reset-icon"><img src="'+BASEURL+'/assets/images/Blue-Refresh.png" /></span>';
    resetButton = '';
		var closeButton = '<a class="settingsClose" href="javascript:void(0);" onclick="showThumbNails();">X</a>';
		var okButton = '<button type="button" class="btn btn-primary" onclcik="validate();">OK</button>';	
			
		//Date format html		
		var dateFormat = '<div class="settingRow">\
              <label class="settingRowLabel">Date format</label>\
              <select class="settingRowFields borderColor textColor" id="date-format" onchange="changeDateFormat(this, \''+elmId+'\');">\
                <option value="dd/MM/yyyy">dd/MM/yyyy</option>\
                <option value="MM/dd/yyyy">MM/dd/yyyy</option>\
                <option value="dd-MMM-yyyy">dd-MMM-yyyy</option>\
                <option value="MMM-dd-yyyy">MMM-dd-yyyy</option>\
                <option value="MMM dd, yyyy">MMM dd, yyyy</option>\
                <option value="dd MMM, yyyy">dd MMM, yyyy</option>\
              </select>\
            </div>';
		
		//Font options html
		var fontOptions = '<div class="settingRow">\
              <label class="settingRowLabel">Font</label>\
              <select class="settingRowFields borderColor textColor" id="font-family" onchange="changeFontFamily(this, \''+elmId+'\');">\
                <option value="CourierPrime-Regular">Courier</option>\
                <option value="Helvetica">Helvetica</option>\
                <option value="NotoSans-Regular">Noto Sans</option>\
                <option value="Times-New-Roman">Times New Roman</option>\
              </select>\
              <ul class="settingRowFields borderColor" id="font-style">\
                <li class="settingRowFieldsLi">\
                  <span id="font-weight">\
                  <a href="javascript:void(0);" class="font-weight-button bold textColor" onclick="changeBold(this, \''+elmId+'\');">B</a>\
                  <a href="javascript:void(0);" class="font-weight-button italic textColor" onclick="changeItalic(this, \''+elmId+'\');">I</a>\
                  <a href="javascript:void(0);" class="font-weight-button underline textColor"  onclick="changeUnderline(this, \''+elmId+'\');">U</a>\
                  </span>\
				<span id="font-size">\
                    <input id="font-size-input" class="borderColor textColor" type="number" max="72" min="7" value="13" onkeyup="changeFontSize(this, \''+elmId+'\');" onchange="changeFontSize(this, \''+elmId+'\');"/>\
                      <!--<span>\
                        <a href="javascript:void(0);">+</a>\
                        <a href="javascript:void(0);">-</a>\
                    </span>-->\
                  </span>\
                </li>\
                <li class="settingRowFieldsLi">\
                  <span id="line-height">\
                    <label class="settingRowLineHeightLabel">Line Height</label>\
                    <input id="line-height-input" class="borderColor textColor" type="number" max="100" min="15" value="15" onkeyup="changeLineHeight(this, \''+elmId+'\');" onchange="changeLineHeight(this, \''+elmId+'\');"/>\
                    <!---<span>\
                      <a href="javascript:void(0);">+</a>\
                      <a href="javascript:void(0);">-</a>\
                    </span>--->\
                  </span>\
                  <span id="color-picker">\
                    <label class="settingRowFontColorLabel">Font Color</label>\
                    <span class="color-action-box borderColor textColor">\
                      <a href="javascript:void(0);" class="currentFontColor"><span></span></a>\
                      <a href="javascript:void(0);" class="selectFontColor" onclick="openColorPicker();" data-jscolor="{width: 141,position: \'right\',previewPosition: \'right\', previewSize: 40,palette: [\'#000000\', \'#7d7d7d\', \'#870014\', \'#ec1c23\', \'#ff7e26\', \'#fef100\', \'#22b14b\', \'#00a1e7\', \'#3f47cc\', \'#a349a4\', \'#ffffff\', \'#c3c3c3\', \'#b87957\', \'#feaec9\', \'#ffc80d\', \'#eee3af\', \'#b5e61d\', \'#99d9ea\', \'#7092be\', \'#c8bfe7\',]}">s-c</a>\
					</span>\
                  </span>\
                </li>\
              </ul>\
            </div>';
      fontOptions = '';
		//Assigned to users html	
		var default_userArr = default_user.split(SEPERATOR);
		var tmpCURRENTUSERNAME = default_userArr[0];
		var tmpCURRENTUSEREMAIL = default_userArr[1];
		var tmpCURRENTUSERTAG = default_userArr[2];
		var tmpCURRENTUSERCOLOR = default_userArr[3];
		var tmpUserClass = tmpCURRENTUSERCOLOR.replace("#","");
		
		var usersHtml = '<div class="settingRow">\
            <label class="settingRowLabel">Assigned to</label>\
			<span class="settingRowFields borderColor textColor">\
				<span class="currentUserNameSpanBox">\
					<span class="currentUserNameSpan" id="currentUserNameSpan">\
						<span class="userColor" style="background-color:'+tmpCURRENTUSERCOLOR+'"></span><span>'+tmpCURRENTUSERTAG+'</span>\
					</span>\
					<a href="javascript:void(0);" onclick="openUsersList();">></a>\
				</span>\
				<!--<input type="hidden" id="currentUserNameHidden" value="'+CURRENTUSERNAME_1+SEPERATOR+CURRENTUSEREMAIL_1+SEPERATOR+CURRENTUSERTAG_1+SEPERATOR+CURRENTUSERCOLOR_1+'"/>-->\
				<input type="hidden" id="currentUserNameHidden" value="'+default_user+'"/>\
			</span>\
			<ul class="settingRowFields borderColor textColor select-user" id="select-user">';

		$.each(SELECTEDUSERS, function(idx, vl){
			
			var tmpName = vl.name;
			var tmpEmail =  vl.email;
			var tmpTag = vl.tag;
			var tmpClr = vl.color;
			var tmpClass = tmpClr.replace("#","");
			usersHtml += '<li class="userLI '+tmpClass+'" data-value="'+tmpName+SEPERATOR+tmpEmail+SEPERATOR+tmpTag+SEPERATOR+tmpClr+'" onclick="assignUserToField(this,\''+elmId+'\');"><span class="userColor" style="background-color:'+tmpClr+'"></span><span>'+tmpTag+'</span></li>';
			
		});

		usersHtml += '</ul></div>';

    usersHtml = '';
		//Signature settings html
        var signatureSettings = '<div class="textSettingsConatiner">\
          <div class="textSettingsHeader borderColor">\
            <span style="margin-right: 4px; font-size: 18px;"><strong>Textbox settings</strong></span>\
			'+resetButton+'\
			'+closeButton+'\
          </div>\
          <div class="textSettingsBody">';
		  
		signatureSettings += usersHtml;
		signatureSettings += '</div>\
        </div>';

		//Signature Initials settings html
		var signatureinSettings = '<div class="textSettingsConatiner">\
          <div class="textSettingsHeader borderColor">\
            <span style="margin-right: 4px; font-size: 18px;"><strong>Textbox settings</strong></span>\
            '+resetButton+'\
            '+closeButton+'\
          </div>\
          <div class="textSettingsBody">';
		  
		signatureinSettings += usersHtml;
		signatureinSettings += '</div>\
        </div>';

		//Textbox settings html
        var textBoxSettings = '<div class="textSettingsConatiner">\
          <div class="textSettingsHeader borderColor">\
            <span style="margin-right: 4px; font-size: 18px;"><strong>Textbox settings</strong></span>\
            '+resetButton+'\
            '+closeButton+'\
          </div>\
          <div class="textSettingsBody">';
		  
		textBoxSettings += usersHtml;
		  
        textBoxSettings +=  '<div class="settingRow">\
              <label class="settingRowLabel">Enter Text</label>\
              <textarea id="default-text" class="settingRowFields borderColor textColor" onKeyup="changeDefaultText(this, \''+elmId+'\');" style="width: 242px; height: 50px; resize: none;" placeholder="Add text here..."></textarea>\
            </div>';
        textBoxSettings += fontOptions;
        textBoxSettings += '</div>\
        </div>';

		//Label settings html	
        var labelSettings = '<div class="textSettingsConatiner">\
          <div class="textSettingsHeader borderColor">\
            <span style="margin-right: 4px; font-size: 18px;"><strong>Label settings</strong></span>\
            '+resetButton+'\
            '+closeButton+'\
          </div>\
          <div class="textSettingsBody">'
		  
		labelSettings += usersHtml;
		  
        labelSettings += '<div class="settingRow">\
              <label class="settingRowLabel">Enter Text</label>\
              <input type="text" class="settingRowFields borderColor textColor" onKeyup="changeDefaultText(this, \''+elmId+'\');" placeholder="Add text here..." />\
            </div>';
            
		labelSettings += fontOptions;
		labelSettings += '</div>\
        </div>';

		//Hyperlink settings html
        var hyperlinkSettings = '<div class="textSettingsConatiner">\
          <div class="textSettingsHeader borderColor">\
            <span style="margin-right: 4px; font-size: 18px;"><strong>Hyperlink settings</strong></span>\
            '+resetButton+'\
            '+closeButton+'\
          </div>\
          <div class="textSettingsBody">';
		  
		hyperlinkSettings += usersHtml;
        hyperlinkSettings += '<div class="settingRow">\
              <label class="settingRowLabel">Text to Display*</label>\
              <input type="text" class="settingRowFields borderColor textColor" onKeyup="changeDefaultText(this, \''+elmId+'\');" placeholder="Add text here..." />\
            </div>\
            <div class="settingRow">\
              <label class="settingRowLabel">Hyperlink URL*</label>\
              <input type="text" class="settingRowFields borderColor textColor" onKeyup="changeDefaultHyperlink(this, \''+elmId+'\');" placeholder="Enter your URL here..." />\
            </div>';
           
		hyperlinkSettings += fontOptions;
		hyperlinkSettings += '</div>\
        </div>';

		//Signed Date settings html 
        var dateSignedSettings = '<div class="textSettingsConatiner">\
          <div class="textSettingsHeader borderColor">\
            <span style="margin-right: 4px; font-size: 18px;"><strong>Date format settings</strong></span>\
            '+resetButton+'\
            '+closeButton+'\
          </div>\
          <div class="textSettingsBody">';
		  
		dateSignedSettings += usersHtml;
		dateSignedSettings += dateFormat;
		dateSignedSettings += fontOptions;
        dateSignedSettings += '</div>\
        </div>';

      
		//Editable Date settings html
        var dateEditableSettings = '<div class="textSettingsConatiner">\
          <div class="textSettingsHeader borderColor">\
            <span style="margin-right: 4px; font-size: 18px;"><strong>Date format settings</strong></span>\
            '+resetButton+'\
            '+closeButton+'\
          </div>\
          <div class="textSettingsBody">';
          
		dateEditableSettings += usersHtml;
		  
		dateEditableSettings += '<div class="settingRow">\
              <label class="settingRowLabel">Set Date</label>\
              <input type="text" readonly class="settingRowFields borderColor textColor" id="datePicker" onchange="changeDate(this, \''+elmId+'\');">\
            </div>';
			
		dateEditableSettings += dateFormat;
        dateEditableSettings += fontOptions;
		dateEditableSettings += '</div>\
        </div>';


		//Name settings html
        var nameSettings = '<div class="textSettingsConatiner">\
          <div class="textSettingsHeader borderColor">\
            <span style="margin-right: 4px; font-size: 18px;"><strong>Name settings</strong></span>\
            '+resetButton+'\
            '+closeButton+'\
          </div>\
          <div class="textSettingsBody">';
		
		nameSettings += usersHtml;
		nameSettings += fontOptions;
		nameSettings += '</div>\
        </div>';
	
		//Email settings html
        var emailSettings = '<div class="textSettingsConatiner">\
          <div class="textSettingsHeader borderColor">\
            <span style="margin-right: 4px; font-size: 18px;"><strong>Email settings</strong></span>\
            '+resetButton+'\
            '+closeButton+'\
          </div>\
          <div class="textSettingsBody">';
		  
		emailSettings += usersHtml;
		emailSettings += fontOptions;
		emailSettings += '</div>\
        </div>';

	

        if(elmTyp == "signature"){
          $("#Advance-fields").html(signatureSettings);
        }else if(elmTyp == "signaturein"){
          $("#Advance-fields").html(signatureinSettings);
        }else if(elmTyp == "textbox"){
          $("#Advance-fields").html(textBoxSettings);
        }else if(elmTyp == "label"){
          $("#Advance-fields").html(labelSettings);
        }else if(elmTyp == "hyperlink"){
          $("#Advance-fields").html(hyperlinkSettings);
        }else if(elmTyp == "datepicker"){
          $("#Advance-fields").html(dateSignedSettings);
        }else if(elmTyp == "name"){
			    console.log("nameSettings2:");
			    console.log(nameSettings);
          $("#Advance-fields").html(nameSettings);
        }else if(elmTyp == "email"){
          $("#Advance-fields").html(emailSettings);
        }else if(elmTyp == "editableDate"){
          $("#Advance-fields").html(dateEditableSettings);
          setTimeout(function(){
            $("#datePicker").datepicker();  
          }, 100);
            
        }
		
        $("#bs-thumbnail-prepare").hide();
        $("#Advance-fields").show();


        setTimeout(function(){
          
          //set values for settings elements
          
          $("#default-text").val(default_value);
          $("#font-family").val(font_family);
          var tmpFontSize = font_size.replace("px","");
          var tmpLineHeight = line_height.replace("px","");
          $("#font-size-input").val(tmpFontSize);
          $("#line-height-input").val(tmpLineHeight);
          if(isReal($("#date-format")) == true){
            $("#date-format").val(date_format);	
          }
          
          //$('.userLI.'+tmpUserClass).trigger("click");
          
        }, 500);
        
      }

      function openColorPicker(){
        //$("#colorPicker").trigger("click");
        //$("#colorPicker").trigger("focus");
      }

      function changeDefaultText(obj, DstElmId){
          //return false;
          
          document.getElementById(DstElmId).style.height = 'unset';
          
          var defltVl = $(obj).val();
          var elmIdParts = DstElmId.split("_");
          var  elmTyp = elmIdParts[0];
          var  elmIdStr = elmIdParts[1];

          var defltVlArr = defltVl.split("\n");
          $("#"+elmTyp+"_"+elmIdStr+"_text").attr("default-value", defltVl);
          
          var textHtml = '';
          
          $.each(defltVlArr, function(i,v){
            textHtml += '<tspan style="word-break: break-word; width: 100%; float: left;" x="4" dy="13">'+v+'</tspan>';
          });
          var inithght = document.getElementById(DstElmId).clientHeight;
          
          if(inithght < 30){
            inithght = 30;
          }
          console.log("inithght2:"+inithght);
          var newHeight = inithght+"px";

          //$("#"+elmTyp+"_"+elmIdStr+"_text tspan").text(defltVl);
          $("#"+elmTyp+"_"+elmIdStr+"_text").html(textHtml);

          $("#"+DstElmId).css({"height":newHeight});

      }

      function changeDefaultHyperlink(obj, DstElmId){

          var defltVl = $(obj).val();
          var elmIdParts = DstElmId.split("_");
          var  elmTyp = elmIdParts[0];
          var  elmIdStr = elmIdParts[1];

          var defltVlArr = defltVl.split("\n");
          $("#"+elmTyp+"_"+elmIdStr+"_text").attr("default-Hyperlink", defltVl);
          
          //var textHtml = '';
          //$.each(defltVlArr, function(i,v){
            //textHtml += '<tspan x="4" dy="13" style="width: 100%; float: left;">'+v+'</tspan>';
          //})
          
          //$("#"+elmTyp+"_"+elmIdStr+"_text tspan").text(defltVl);
          //$("#"+elmTyp+"_"+elmIdStr+"_text").html(textHtml);
      }

      function changeDateFormat(obj, DstElmId){

          var dtFormat = $(obj).val();
          var elmIdParts = DstElmId.split("_");
          var  elmTyp = elmIdParts[0];
          var  elmIdStr = elmIdParts[1];

          var dt = $("#datePicker").val();
          var defltVl = currentDate(dtFormat, dt);
          $("#"+elmTyp+"_"+elmIdStr+"_text").attr("default-value", defltVl);
          $("#"+elmTyp+"_"+elmIdStr+"_text").attr("date-format", dtFormat);
          
          
          var textHtml = '<tspan style="word-break: break-word;" x="4" dy="13" style="width: 100%; float: left;">'+defltVl+'</tspan>';
          
          
          //$("#"+elmTyp+"_"+elmIdStr+"_text tspan").text(defltVl);
          $("#"+elmTyp+"_"+elmIdStr+"_text").html(textHtml);

      }

      function changeDate(obj, DstElmId){

          var defltVl = $(obj).val();
          var elmIdParts = DstElmId.split("_");
          var  elmTyp = elmIdParts[0];
          var  elmIdStr = elmIdParts[1];

          $("#"+elmTyp+"_"+elmIdStr+"_text").attr("default-value", defltVl);
          //$("#"+elmTyp+"_"+elmIdStr+"_text").attr("date-format", dtFormat);
          
          
          var textHtml = '<tspan style="word-break: break-word;" x="4" dy="13" style="width: 100%; float: left;">'+defltVl+'</tspan>';
          
          $("#"+elmTyp+"_"+elmIdStr+"_text").html(textHtml);

      }
      
      function changeFontFamily(DstElmId){
          
          var elmIdParts = DstElmId.split("_");
          var  elmTyp = elmIdParts[0];
          var  elmIdStr = elmIdParts[1];

          var fontFamily = $("#"+elmTyp+"_"+elmIdStr+"_text").attr("font-family");
          $("#"+elmTyp+"_"+elmIdStr+"_text tspan").css("font-family", fontFamily);
      }

      function changeFontSize(DstElmId){
        
        var elmIdParts = DstElmId.split("_");
        var  elmTyp = elmIdParts[0];
        var  elmIdStr = elmIdParts[1];

        var fontSize = $("#"+elmTyp+"_"+elmIdStr+"_text").attr("font-size");
        $("#"+elmTyp+"_"+elmIdStr+"_text tspan").css("font-size", fontSize);

      }
      
      function changeLineHeight(DstElmId){
        
        var elmIdParts = DstElmId.split("_");
        var  elmTyp = elmIdParts[0];
        var  elmIdStr = elmIdParts[1];

        var lineHeight = $("#"+elmTyp+"_"+elmIdStr+"_text").attr("line-height");
        $("#"+elmTyp+"_"+elmIdStr+"_text tspan").css("line-height", lineHeight);
      }
      
      function changeBold(DstElmId){

        var elmIdParts = DstElmId.split("_");
        var  elmTyp = elmIdParts[0];
        var  elmIdStr = elmIdParts[1];

        var fontWeight = $("#"+elmTyp+"_"+elmIdStr+"_text").attr("font-weight");
        $("#"+elmTyp+"_"+elmIdStr+"_text tspan").css("font-weight", fontWeight);
      }

      function changeItalic(DstElmId){

        var elmIdParts = DstElmId.split("_");
        var  elmTyp = elmIdParts[0];
        var  elmIdStr = elmIdParts[1];

        var fontStyle = $("#"+elmTyp+"_"+elmIdStr+"_text").attr("font-style");
        $("#"+elmTyp+"_"+elmIdStr+"_text").css("font-style", fontStyle);
      } 
      
      function changeUnderline(DstElmId){

        var elmIdParts = DstElmId.split("_");
        var  elmTyp = elmIdParts[0];
        var  elmIdStr = elmIdParts[1];
        
        var textDecoration = $("#"+elmTyp+"_"+elmIdStr+"_text").attr("text-decoration");
        $("#"+elmTyp+"_"+elmIdStr+"_text").css("text-decoration", textDecoration);
        $("#"+elmTyp+"_"+elmIdStr+"_text tspan").css("text-decoration", textDecoration);
      }

      function assignPageNoToElement(elmntId, p){
        
        $("#"+elmntId).attr("page", p);
           
      }

      function assignPageTopToElement(elmntId, pageTop){
        
        $("#"+elmntId).attr("pageTop", pageTop);
           
      }
      
      //Make the DIV element draggagle:

       function initDragElement(elmnt) {
        return false; //need to be remove
          var pos1 = 0,
            pos2 = 0,
            pos3 = 0,
            pos4 = 0;
          //var popups = document.getElementsByClassName("popup");
          //var elmnt = null;
          var currentZIndex = 100; //TODO reset z index when a threshold is passed

          //for (var i = 0; i < popups.length; i++) {
            //var popup = popups[i];
            var popup = elmnt;
            var header = getHeader(popup);
           
            
            popup.onmousedown = function() {
              this.style.zIndex = "" + ++currentZIndex;
            };
            

            if (header) {
              header.parentPopup = popup;
              header.onmousedown = dragMouseDown;
            }
          //}

          function dragMouseDown(e) {
            elmnt = this.parentPopup;
            elmnt.style.zIndex = "" + ++currentZIndex;

            e = e || window.event;
            // get the mouse cursor position at startup:
            pos3 = e.clientX;
            pos4 = e.clientY;
            document.onmouseup = closeDragElement;
            // call a function whenever the cursor moves:
            document.onmousemove = elementDrag;
          }

          function elementDrag(e) {
            if (!elmnt) {
              return;
            }

            e = e || window.event;
            // calculate the new cursor position:
            pos1 = pos3 - e.clientX;
            pos2 = pos4 - e.clientY;
            pos3 = e.clientX;
            pos4 = e.clientY;
            // set the element's new position:
            elmnt.style.top = elmnt.offsetTop - pos2 + "px";
            elmnt.style.left = elmnt.offsetLeft - pos1 + "px";
          }

          function closeDragElement() {
            /* stop moving when mouse button is released:*/
            document.onmouseup = null;
            document.onmousemove = null;
          }

          function getHeader(element) {
            
            console.log("element:");
            console.log(element);

            //var headerItems = element.getElementsByClassName("popup-header");
            var headerItems = element.getElementsByTagName("text");

            if (headerItems.length === 1) {
              return headerItems[0];
            }
            

            return null;
          }
        }


      function initResizeElement(elmnt) {
        return false; //need to be remove
        //var popups = document.getElementsByClassName("popup");
        var element = null;
        var startX, startY, startWidth, startHeight;

        //for (var i = 0; i < popups.length; i++) {
          //var p = popups[i];
          var p = elmnt;

          var right = document.createElement("div");
          right.className = "resizer-right";
          p.appendChild(right);
          right.addEventListener("mousedown", initDrag, false);
          right.parentPopup = p;

          var bottom = document.createElement("div");
          bottom.className = "resizer-bottom";
          p.appendChild(bottom);
          bottom.addEventListener("mousedown", initDrag, false);
          bottom.parentPopup = p;

          var both = document.createElement("div");
          both.className = "resizer-both";
          p.appendChild(both);
          both.addEventListener("mousedown", initDrag, false);
          both.parentPopup = p;
        //}

        function initDrag(e) {
          
          element = this.parentPopup;

          startX = e.clientX;
          startY = e.clientY;
          startWidth = parseInt(
            document.defaultView.getComputedStyle(element).width,
            10
          );
          startHeight = parseInt(
            document.defaultView.getComputedStyle(element).height,
            10
          );
          document.documentElement.addEventListener("mousemove", doDrag, false);
          document.documentElement.addEventListener("mouseup", stopDrag, false);
        }

        function doDrag(e) {
          
          //element.style.width = startWidth + e.clientX - startX + "px";
          //element.style.height = startHeight + e.clientY - startY + "px";

          console.log("startWidth:"+startWidth+",e.clientX:"+e.clientX+",startX:"+startX);
          console.log("startHeight:"+startHeight+",e.clientY:"+e.clientY+",startY:"+startY);

          element.style.width = startWidth + e.clientX - startX + "px";
          element.style.height = startHeight + e.clientY - startY + "px";
        }

        function stopDrag() {

          document.documentElement.removeEventListener("mousemove", doDrag, false);
          document.documentElement.removeEventListener("mouseup", stopDrag, false);
        }
      }


      function showCoordinates(){
        return false;  //need to be remove
        var validi = [];
        var nonValidi = [];
        
        var maxHTMLx = 892; //$('#the-canvas').width();
        var maxHTMLy = 1262 * 2; //$('#the-canvas').height();
        var paramContainerWidth = $('#parametriContainer').width();
        
        //recupera tutti i placholder validi
        $('.drag-drop.can-drop').each(function( index ) {
          var x = parseFloat($(this).data("x"));
          var y = parseFloat($(this).data("y"));
          var valore = $(this).data("valore");
          var descrizione = $(this).find(".descrizione").text();
            
          var pdfY = y * maxPDFy / maxHTMLy;
          var posizioneY = maxPDFy - offsetY - pdfY;    
          var posizioneX =  (x * maxPDFx / maxHTMLx)  - paramContainerWidth;
          
          var val = {"descrizione": descrizione, "posizioneX":posizioneX,   "posizioneY":posizioneY, "valore":valore};
          validi.push(val);
        
        });
      
        if(validi.length == 0){
           alert('No placeholder dragged into document');
        }
       else{
        alert(JSON.stringify(validi));
       }
    }
	
	function extractAndSaveGElements(){
       // return false; //need to be remove
        var tmpSaveDataObj = {};
        

        $(".pdf-form-element").each(function(idx, elm){

            var tmpStyl = $(elm).attr("style");
            console.log("tmpStyl:");
            console.log(tmpStyl);

            var elmId = $(elm).attr("id");

            var elmIdParts = elmId.split("_");
            var elmTyp = elmIdParts[0]; //element type


            var font_size = $("#"+elmId+"_text").attr("font-size");
            var font_family = $("#"+elmId+"_text").attr("font-family");
            var font_style = $("#"+elmId+"_text").attr("font-style");
            var font_weight = $("#"+elmId+"_text").attr("font-weight");
            var text_decoration = $("#"+elmId+"_text").attr("text-decoration");
            var default_value = $("#"+elmId+"_text").attr("default-value");
            var default_user = $("#"+elmId+"_text").attr("default-user");


            //var default_userParts = default_user.split(SEPERATOR);



            if(isReal(tmpSaveDataObj[default_user])){
                //nothing to do
            }else{
                tmpSaveDataObj[default_user] = [];
            }

            tmpSaveDataObj[default_user].push({"elmType":elmTyp,"elmId":elmId, "style":tmpStyl, "font_size":font_size, "font_family":font_family, "font_style":font_style, "font_weight":font_weight, "text_decoration":text_decoration, "default_value":default_value, "default_user":default_user});


            if(elmTyp == "signature"){
              
              var dataSign = $("#"+elmId+"_text").attr("data-sign");
              tmpSignDataObj[elmId] = dataSign;
            }



            console.log("tmpSaveDataObj");
            console.log(tmpSaveDataObj);
            
        });



        //post data to save in db
        var documentId = $("#documentId").val();
        var rqsturl = "send";
        var postdata = {"data":tmpSaveDataObj, "documentId":documentId};
        var rqstType = "POST";
        callAjax(rqsturl, postdata, rqstType, function(resp){
            console.log("resp");
            console.log(resp);
            if(resp.C == 100){
              
              var msg = "Document is saved and sent for signature collection.";
              var err = 0;
              showToastMsg(msg, err);

              //redirect to dashboard

            }else{
              
              var msg = "Please try again.";
              var err = 1;
              showToastMsg(msg, err);
              
            }

        });

    }


    /*==== New Code for Signature process ====*/
    function startSigning(){
     //scroll document input wise one by one for sign collection
    }

    function openSignPad(elmId){
      showThumbNails();
      event.stopPropagation();
      hideElementBorder();
          $("#"+elmId).addClass("pdf-form-element-border");
          initResizeElement(document.getElementById(elmId));
  
  
          var elmIdParts = elmId.split("_");
          var  elmTyp = elmIdParts[0];
          var  elmIdStr = elmIdParts[1];
  
      //"font-size":font_size, "font-family":font_family, "font-style":font_style, "font-weight":font_weight, "text-decoration":text_decoration, "default-value":default_value, "default-user":default_user, "line-height":line_height, "date-format":date_format
  
      var fieldValuesObj = getElementSavedAttributes(elmId);
      console.log("fieldValuesObj");
      console.log(fieldValuesObj);
      
      var font_size = fieldValuesObj["font-size"];
      var font_family = fieldValuesObj["font-family"];
      var font_style = fieldValuesObj["font-style"];
      var font_weight = fieldValuesObj["font-weight"];
      var text_decoration = fieldValuesObj["text-decoration"];
      var default_value = fieldValuesObj["default-value"];
      var default_user = fieldValuesObj["default-user"];
      var line_height = fieldValuesObj["line-height"];
      var date_format = fieldValuesObj["date-format"];

      $("#signModal").modal("show");

      //$("#signModal #signInput").trigger("onkeypress");

      
      $("#signUseBttn").attr("data-elm",elmId);
      $("#pills-type-tab").trigger("click");

    }

    function closeSignPad(){
      $("#signModal").modal("hide");
    }

    function typeSign(obj,event){
      
      var defltSize = 4;
      var ppc = 0.09;
      var pdt = 0.07;

      var signVal  = $(obj).val();

      var letrLen = signVal.length;
      if(letrLen > 0){
        convertToSentenceCase(signVal, function(letters){
          
          phraseCaseType(letters,function(caseType){
          
            console.log("caseType:"+caseType);
    
            var minusPixel = 0;
            var minFsz = 0.75;
            if(caseType == "U"){
              //upper case
              ppc = 0.19;
              minFsz = 0.39;
            }else if(caseType == "L"){
              //lower case 
              ppc = 0.07;
              minFsz = 0.75;
            }else if(caseType == "S"){
              //sentence case
              ppc = 0.097;
              //minFsz = 0.013;
              minFsz = 0.75;
            }
            minFsz = 0.75;
    
            if(letrLen > 1){
            
              defltSize = defltSize - (letrLen * ppc);
            }
    
            if(letters.length > 0 && letters.length <= 55){
              
              $(".signValue").text(letters);
              
              if(letters.length == 55){
                $(".signValue").css("font-size",minFsz+"rem");
              }else if (defltSize > minFsz) {
                $(".signValue").css("font-size", defltSize+"rem");
                $(".signValue").css("padding-top", (letrLen * pdt)+"rem");
              }
              
            }else{
              
              $(obj).val(letters.substring(0, 55));
            }
          });

        });
    
      }else{
        
        $(".signValue").text("");
      }
      
    }

    function openColorPallet(){
      $("#colorList").show();
    }
    
    function setSignColor(obj,clr){
      
      $(".pencolor").removeClass("activePenColor");
      $(".pencolor").removeClass("la");
      $(".pencolor").removeClass("la-check");
      
      $(obj).addClass("activePenColor");
      $(obj).addClass("la");
      $(obj).addClass("la-check");
      
      
      $(".signValue").removeClass("signRed");
      $(".signValue").removeClass("signGreen");
      $(".signValue").removeClass("signBlue");
      $(".signValue").removeClass("signBlack");

      if(clr == "red"){
        $(".signValue").addClass("signRed");
        
        if(signaturePad != undefined){
          signaturePad.clear();
          signaturePad.penColor = "rgb(248, 59, 59)";
        }
        
      }else if(clr == "green"){
        $(".signValue").addClass("signGreen");
        
        if(signaturePad != undefined){
          signaturePad.clear();
          signaturePad.penColor = "rgb(0, 174, 35)";
        }

      }else if(clr == "blue"){
        
        $(".signValue").addClass("signBlue");
        
        if(signaturePad != undefined){
          signaturePad.clear();
          signaturePad.penColor = "rgb(0, 87, 227)";
        }

      }else if(clr == "black"){
        $(".signValue").addClass("signBlack");

        if(signaturePad != undefined){
          signaturePad.clear();
          signaturePad.penColor = "rgb(0, 0, 0)";
        }

      }else{
        $(".signValue").addClass("signBlack");
        
        if(signaturePad != undefined){
          signaturePad.clear();
          signaturePad.penColor = "rgb(0, 0, 0)";
        }
        
      }
      
      $("#colorList").hide();
    }

    function choseSignStyle(ob){
      
      $("#signModal .pre-signwrap").removeClass("activesign");
      $("#signModal .pre-signwrap .check-mark").removeClass("la-check");
      $("#signModal .pre-signwrap .signValue").removeAttr("id");
      
      $(ob).addClass("activesign");
      
      if($(ob).hasClass("activesign")){

        $("#signModal .pre-signwrap.activesign .check-mark").addClass("la-check");
        $("#signModal .pre-signwrap.activesign .signValue").attr("id","selectedSign");
        
      }
      
    }

    function allowAlphabetsOnly(event){
      
      var inputValue = event.charCode;
    
      if(!(inputValue >= 65 && inputValue <= 90) && !(inputValue >= 97 && inputValue <= 122) && (inputValue != 32 && inputValue != 0)){
          event.preventDefault();
      }
    }

    function convertToSentenceCase(phrase, cb){
      
      var mySentence = phrase;
      var spcIdx = mySentence.indexOf(" ");
      var words = mySentence;
      
      if(spcIdx > -1){
        
        words = mySentence.split(" ");
      }else{
        var words = [mySentence];
      }
      
      for (let i = 0; i < words.length; i++) {
          if(isReal(words[i][0])){
            words[i] = words[i][0].toUpperCase() + words[i].substr(1).toLowerCase();
          }
          
      }
      
      var newSentence = "";
      $.each(words, function(i,v){
        if(i > 0){
          newSentence += " "+v;
        }else{
          newSentence += v;
        }
        
      });
      
      return cb(newSentence);
      
    }


    function phraseCaseType(letters, cb){
      
      var lowerLetters = [];
      var upperLetters = [];
      var caseType;
      
      for (var i = 0; i<letters.length; i++) {
          if (letters[i] != " " && letters[i] === letters[i].toUpperCase()) {
            upperLetters.push(letters[i]);
          }
      }
    
      for (var i = 0; i<letters.length; i++) {
          if (letters[i] != " " && letters[i] === letters[i].toLowerCase()) {
            lowerLetters.push(letters[i]);
          }
      }

      if(upperLetters.length > 0 && lowerLetters.length == 0){
        caseType = "U";
      }else if(lowerLetters.length > 0 && upperLetters.length == 0){
        caseType = "L";
      }else if(lowerLetters.length > 0 && upperLetters.length > 0){
        caseType = "S";
      }
      
      return cb(caseType);
    }



    function processSign(){
        var tmpSaveDataObj = {};
        var tmpSignDataObj = [];
        
        $(".pdf-form-element").each(function(idx, elm){

            var tmpPage = $(elm).attr("page"); 
            var tmpPageTop = $(elm).attr("pageTop"); 
            var tmpStyl = $(elm).attr("style");
            var elmId = $(elm).attr("id");

            var elmIdParts = elmId.split("_");
            var elmTyp = elmIdParts[0]; //element type

            var font_size = $("#"+elmId+"_text").attr("font-size");
            var font_family = $("#"+elmId+"_text").attr("font-family");
            var font_style = $("#"+elmId+"_text").attr("font-style");
            var font_weight = $("#"+elmId+"_text").attr("font-weight");
            var text_decoration = $("#"+elmId+"_text").attr("text-decoration");
            var default_value = $("#"+elmId+"_text").attr("default-value");
            var default_user = $("#"+elmId+"_text").attr("default-user");

            //var default_userParts = default_user.split(SEPERATOR);

            if(isReal(tmpSaveDataObj[default_user])){
                //nothing to do
            }else{
                tmpSaveDataObj[default_user] = [];
            }

            
            var dataSignType = '';
            if(elmTyp == "signature"){
          
              var dataSign = $("#"+elmId+"_text").attr("data-sign");
              tmpSignDataObj.push({"elemId":elmId, "bs64":dataSign});
            }
           
            tmpSaveDataObj[default_user].push({"elmType":elmTyp, "elmId":elmId, "page":tmpPage, "pageTop":tmpPageTop, "style":tmpStyl, "font_size":font_size, "font_family":font_family, "font_style":font_style, "font_weight":font_weight, "text_decoration":text_decoration, "default_value":default_value, "default_user":default_user});
        
          });

        
        console.log("tmpSignDataObj");
        console.log(tmpSignDataObj);

        console.log("tmpSaveDataObj");
        console.log(tmpSaveDataObj);


        //=== saving sign code
        var userDtTm = new Date().toLocaleString(); //system current date time
        userDtTm = userDtTm.replace(",",""); 
        userDtTm = userDtTm.trim();
        
        TMP_USERLOCALE["platform"] = getBrowserName();
        TMP_USERLOCALE["userDtTm"] = userDtTm;

        
        //post data to save in db
        var documentId = $("#documentId").val();
        var signerDocumentId = $("#signerDocumentId").val();
        var signImgData = $("#fullsignbs64").val();
        var signIniImgData = $("#initsignbs64").val();
        var signType = $("#signType").val();

        var rqsturl = "processsign";
        var postdata = {
          "data":tmpSaveDataObj,
          "documentId":documentId,
          "signerDocumentId":signerDocumentId,
          "initials":signIniImgData,
          "sign":signImgData,
          "signType":signType,
          "userLocale":TMP_USERLOCALE
        };
        var rqstType = "POST";
        callAjax(rqsturl, postdata, rqstType, function(resp){
            console.log("resp");
            console.log(resp);
            if(resp.C == 100){
              
              var msg = "Thankyou for signing the document.";
              var err = 0;
              showToastMsg(msg, err);

              //redirect to dashboard

            }else{
              
              var msg = "Please try again.";
              var err = 1;
              showToastMsg(msg, err);
              
            }

        }); 
        
    }

    function getBrowserName(){
      var userAgent = navigator.userAgent;
      var browserName;
        // Detect Chrome
        if (userAgent.indexOf("Chrome") > -1 && userAgent.indexOf("OPR") === -1) {
          browserName = "Google Chrome";
        }else if (userAgent.indexOf("Firefox") > -1) {
          browserName = "Mozilla Firefox";
        }
        // Detect Safari
        else if (userAgent.indexOf("Safari") > -1 && userAgent.indexOf("Chrome") === -1) {
          browserName = "Apple Safari";
        }
        // Detect Opera
        else if (userAgent.indexOf("OPR") > -1) {
          browserName = "Opera";
        }
        // Detect Edge
        else if (userAgent.indexOf("Edg") > -1) {
          browserName = "Microsoft Edge";
        }
        // Detect Internet Explorer
        else if (userAgent.indexOf("Trident") > -1) {
          browserName = "Microsoft Internet Explorer";
        }
        else {
          browserName = "Unknown";
        }

      return browserName;
    }

    function saveSignBscode(obj, ci, li){
      //no need of this function can remove this
      //tmpSignDataObj
      var encvl = obj[ci];
      if(isReal(encvl)){
        
          //var documentId = $("#documentId").val();
          var signerDocumentId = $("#signerDocumentId").val();
          
          var rqsturl = "writesigndata";
          var postdata = {"data":encvl, "signerDocumentId":signerDocumentId};
          var rqstType = "POST";
          callAjax(rqsturl, postdata, rqstType, function(resp){
            if(resp.c == 100){
              //img write ok
              console.log("file write ok");
            }else{
              //write the log for if sign is not write at server
              console.log("file write error");
            }

            
            //make next call
            var nxt = ci + 1;
            
            if(nxt <= li){

              saveSignBscode(obj, nxt, li);
              
            }else{
              //process to save other stuff
              console.log("Proceed to save other data");
            }

          });
      }else{
        //invalid data
        console.log("No sign Img available");
      }
    }

    
    function typeSignBttn(){
      var onclickAttr = 'createTypeToSign("type");';
      $("#signUseBttn").attr("onclick", onclickAttr);
    }
    
    function uploadSignBttn(){
      var onclickAttr = 'createTypeToSign("upload");';
      $("#signUseBttn").attr("onclick", onclickAttr);
    }

    function drawSignBttn(){
      
      if(! isReal(canvas)){
        canvas = document.getElementById("signatureCanvas");
      }
      
      signaturePad = new SignaturePad(canvas,{
        penColor: "rgb(0, 0, 0)"
      });
      
      $('#clear-signature').on('click', function(){
          signaturePad.clear();
      });
    

      //type draw upload
      //createTypeToSign
      var onclickAttr = 'createTypeToSign("draw");';
      $("#signUseBttn").attr("onclick", onclickAttr);
    
    }

    

    function uploadSign(){
      $("#file").val("");
      $("#file").trigger("click");
    }

    function fileUploadProcess(e){
      var files = e.target.files;
      var file = files[0];
      renderSignFile(file);
      
    }

    var tmpCropper;
    function renderSignFile(file){
      
      var fileName = file.name;
      var fileSize = file.size;
      var fileType = file.type;

      var reader = new FileReader();
      reader.onload = function(event){
        var fileBase64 = reader.result;

        $("#signImg").attr("src",fileBase64);
        $("#dragDropContainer").hide();
        $("#signImgContainer").show();

      };
    
      reader.readAsDataURL(file);
      $(".cropControls").show();
      
      setTimeout(function(){
        $("#crop-signature").trigger("click");
      }, 250);
    }
    
    function removeSign(){
      
      tmpCropper.destroy();
      $("#signImg").removeAttr("src");
      $("#signImgContainer").hide();
      $(".cropControls").hide();
      $("#dragDropContainer").show();
    }

    function showZoomSlider(){
      
      $("#rotateButton").removeClass("activeCropControll");
      $("#rotateslider").hide();

      $("#zoomslider").show();
      $("#zoomButton").addClass("activeCropControll");
       

      $("#sliderLabel").html("Zoom Image");
    }

    function showRotateSlider(){
      $("#rotateslider").show();
      $("#rotateButton").addClass("activeCropControll");

      $("#zoomslider").hide();
      $("#zoomButton").removeClass("activeCropControll");

      $("#sliderLabel").html("Rotate Image");
    }
    
    function cropSign(){
      
      var imgElm = document.getElementById("signImg");
      tmpCropper = new Cropper(imgElm,{
        //viewMode:1,
        dragMode:"none",
        aspectRatio:NaN,
        minContainerWidth:616,
        minContainerHeight:180,
        movable: true,
        rotatable: true,
        center:true,
        scalable: true,
        guides: true,
        zoomable: true,
        zoomOnWheel: true,
        cropBoxMovable: true,
        cropBoxResizable: true,
        autoCrop: true,
        autoCropArea: 0.8,
        ready: function(){

        },
      });

      setTimeout(function(){
        $( "#zoomslider" ).slider({
          min: 0.1,
          max: 4,
          step: 0.01,
          value: 1,
          slide: function( event, ui ) {
            
            var ratio = ui.value;
            tmpCropper.zoomTo(ratio);
          }
        }); 
        $( "#rotateslider" ).slider({
          min: 0,
          max: 360,
          value: 0,
          step: 1,
          slide: function( event, ui ) {
             
            var degree = ui.value;
            tmpCropper.rotateTo(degree);
            $(".cropper-hide").css({"width":"540px", "height":"180px"});
          }
        });
      }, 500);

      
    }

    function setCroppedSign(){
      var croppedBs64 = tmpCropper.getCroppedCanvas({
        width:616
      }).toDataURL();


      console.log("croppedBs64");
      console.log(croppedBs64);
    }

    function createTypeToSign(signTyp){
        
      if(signTyp == "type"){
        
        var elmId = $("#signUseBttn").attr("data-elm");
       // $("#"+elmId+" img").remove();
  
        html2canvas(document.getElementById("selectedSign"),
        {
          allowTaint: true,
          useCORS: true,
          backgroundColor:null
        }).then(function (canvas) {
          
          var signImgData = canvas.toDataURL("image/png");
          

          $fullsignElements = $('g[id^=signature_]');
          $signInitElements = $('g[id^=signaturein_]');

          $("#fullsignbs64").val(signImgData);
          $("#initsignbs64").val(signImgData);
          $("#signType").val(signTyp);

          if($fullsignElements.length > 0){
            
            $($fullsignElements).each(function(i,e){

              var tmpElmId = $(e).attr("id");
              
              $("#"+tmpElmId+" img").remove();

              var txtElm = tmpElmId+"_text";
              //$("#"+txtElm).attr("data-sign",signImgData);
              
              $("#"+txtElm).css({"opacity":0,"height":"0px", "width":"0px", "position":"absolute"});
              var signImg = "<img src=\""+signImgData+"\" style=\"width:100%; height: 100%;\">";
      
              $("#"+tmpElmId).append(signImg);
              
            });
          
          }

          if($signInitElements.length > 0){
            $($signInitElements).each(function(i,e){

              var tmpElmId = $(e).attr("id");
              
              $("#"+tmpElmId+" img").remove();

              var txtElm = tmpElmId+"_text";
              //$("#"+txtElm).attr("data-sign",signImgData);
              $("#"+txtElm).attr("data-sign-type",signTyp);
              $("#"+txtElm).css({"opacity":0,"height":"0px", "width":"0px", "position":"absolute"});
              var signImg = "<img src=\""+signImgData+"\" style=\"width:100%; height: 100%;\">";
      
              $("#"+tmpElmId).append(signImg);
              
            });
          }
  
        });

      }else if(signTyp == "draw"){
        
        var elmId = $("#signUseBttn").attr("data-elm");
        //$("#"+elmId+" img").remove();
  
        var signImgData = signaturePad.toDataURL(); // save image as PNG
        
        $fullsignElements = $('g[id^=signature_]');
        $signInitElements = $('g[id^=signaturein_]');

        $("#fullsignbs64").val(signImgData);
        $("#initsignbs64").val(signImgData);
        $("#signType").val(signTyp);
          
          if($fullsignElements.length > 0){
            
            $($fullsignElements).each(function(i,e){

              var tmpElmId = $(e).attr("id");
              
              $("#"+tmpElmId+" img").remove();

              var txtElm = tmpElmId+"_text";
              //$("#"+txtElm).attr("data-sign",signImgData);
              
              $("#"+txtElm).css({"opacity":0,"height":"0px", "width":"0px", "position":"absolute"});
              var signImg = "<img src=\""+signImgData+"\" style=\"width:100%; height: 100%;\">";
      
              $("#"+tmpElmId).append(signImg);
              
            });
          
          }

          if($signInitElements.length > 0){
            $($signInitElements).each(function(i,e){

              var tmpElmId = $(e).attr("id");
              
              $("#"+tmpElmId+" img").remove();

              var txtElm = tmpElmId+"_text";
              //$("#"+txtElm).attr("data-sign",signImgData);
              $("#"+txtElm).attr("data-sign-type",signTyp);
              $("#"+txtElm).css({"opacity":0,"height":"0px", "width":"0px", "position":"absolute"});
              var signImg = "<img src=\""+signImgData+"\" style=\"width:100%; height: 100%;\">";
      
              $("#"+tmpElmId).append(signImg);
              
            });
          }
  
      }else if(signTyp == "upload"){

        var elmId = $("#signUseBttn").attr("data-elm");
        //$("#"+elmId+" img").remove();

        var signImgData = tmpCropper.getCroppedCanvas({
          width:616
        }).toDataURL();
      
        $fullsignElements = $('g[id^=signature_]');
        $signInitElements = $('g[id^=signaturein_]');
        
        $("#fullsignbs64").val(signImgData);
        $("#initsignbs64").val(signImgData);
        $("#signType").val(signTyp);
          if($fullsignElements.length > 0){
            
            $($fullsignElements).each(function(i,e){

              var tmpElmId = $(e).attr("id");
              
              $("#"+tmpElmId+" img").remove();

              var txtElm = tmpElmId+"_text";
              //$("#"+txtElm).attr("data-sign",signImgData);
              
              $("#"+txtElm).css({"opacity":0,"height":"0px", "width":"0px", "position":"absolute"});
              var signImg = "<img src=\""+signImgData+"\" style=\"width:100%; height: 100%;\">";
      
              $("#"+tmpElmId).append(signImg);
              
            });
          
          }

          if($signInitElements.length > 0){
            $($signInitElements).each(function(i,e){

              var tmpElmId = $(e).attr("id");
              
              $("#"+tmpElmId+" img").remove();

              var txtElm = tmpElmId+"_text";
              //$("#"+txtElm).attr("data-sign",signImgData);
                
              $("#"+txtElm).css({"opacity":0,"height":"0px", "width":"0px", "position":"absolute"});
              var signImg = "<img src=\""+signImgData+"\" style=\"width:100%; height: 100%;\">";
      
              $("#"+tmpElmId).append(signImg);
              
            });
          }
      
          
      }

    
    }

    
    function getUserLocale(){
      
      $.getJSON('https://json.geoiplookup.io/?callback=?', function(data) {


      TMP_USERLOCALE["country_code"] = data.country_code;
      TMP_USERLOCALE["city"] = data.city;
      TMP_USERLOCALE["district"] = data.district;
      TMP_USERLOCALE["ip"] = data.ip;

      });
    
    }
    
    /*
    console.log(s);

    var tzRe = /\(([\w\s]+)\)/; // Look for "(", any words (\w) or spaces (\s), and ")"
    var d = new Date().toString();
    var tz = tzRe.exec(d)[1]; // timezone, i.e. "Pacific Daylight Time"
    console.log(tz);
    */
  