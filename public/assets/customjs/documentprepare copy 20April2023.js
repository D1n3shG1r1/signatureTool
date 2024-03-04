var TOTALPDFPAGES = 0;
var FORMELEMENTSCONTAINER_HEIGHT = 0;
var FORMELEMENTSCONTAINER_WIDTH = 0;
var PDFPAGESAREA = [];

$(function(){

  // preventing page from redirecting
  $("html").on("dragover", function(e) {
      e.preventDefault();
      e.stopPropagation();
      //$(".upload-sign-wrap.canvas-border").addClass("uploadDragDropBorder");
      
  });

  $("html").on("drop", function(e) { e.preventDefault(); e.stopPropagation(); });

  // Drag enter
  $('.formElementsContainer_SVG').on('dragenter', function (e) {
      e.stopPropagation();
      e.preventDefault();
      
  });

  // Drag over
  $('.formElementsContainer_SVG').on('dragover', function (e) {
      e.stopPropagation();
      e.preventDefault();
      
  });

  // Drop
  $('.formElementsContainer_SVG').on('drop', function (e) {
      e.stopPropagation();
      e.preventDefault();


      console.log("e:");
      console.log(e);


      //create virtual element


      /*$("#dragDropHere").text("Upload");
    
      var files = e.originalEvent.dataTransfer.files;
      var file = files[0];
      
      var ftype = file.type;
      var allowedMimeTypes = ["image/bmp", "image/jpeg", "image/png"];
      var ftypeIdx = allowedMimeTypes.indexOf(ftype);
      if(ftypeIdx > -1){
        renderSignFile(file);
      }else{
        alert("Invalid file type");
      }*/
    
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
          TOTALPDFPAGES = totalPages; //total no of pages

          pdfDoc.getPage(numPage).then(page => {
            console.log(page);

            let viewport = page.getViewport({scale:scale});
            console.log("viewport");
            console.log(viewport);
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

            let renderContext = {
              canvasContext : ctx,
              viewport: viewport
            }
            
            page.render(renderContext); 
            //canvas.width = "150px";
            
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
            
            console.log("if");

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
      
//---


//--

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
            "height": newHeight,
            //"onmousedown":"svgMouseDown()"
        //}).appendTo('#pdfContainer');
      }).insertBefore('#documentPageHolder_1');
        
        setTimeout(function(){
          $("#formElementsContainer").css({
              "border": "1px solid #f2f2f2",
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

        FORMELEMENTSCONTAINER_HEIGHT = newHeight;
        FORMELEMENTSCONTAINER_WIDTH = newWidth;

        $('<svg>', {
            "id": 'formElementsContainer_SVG',
            "class": 'formElementsContainer_SVG',
            "style": "display:flex; width:"+newWidth+"px; height:"+newHeight+"px;",
            "html":'<g id="formElementsContainer_Group" transform="scale(1)" class=""></g><g id="svg_selector"></g>',
            "onclick":"hideElementBorder();"
        }).appendTo('#formElementsContainer');


        /*--- set page area (page no , page start px , page end px)---*/
        var p = 0;
        var tmpPerPageHeight = FORMELEMENTSCONTAINER_HEIGHT / TOTALPDFPAGES;
        for(p = 1; p <= TOTALPDFPAGES; p++){
          var tmpPage = p;
          var tmpPageEnd = tmpPage * tmpPerPageHeight;
          var tmpPageStart = tmpPageEnd - tmpPerPageHeight;
          
          PDFPAGESAREA.push({"page":tmpPage, "top":tmpPageStart, "bottom":tmpPageEnd, "perPageHeight":tmpPerPageHeight});
        
        }


      }

      function hideElementBorder(){
        $(".pdf-form-element").removeClass("pdf-form-element-border");
        $("#bs-thumbnail-prepare").show();
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

          $("#"+elmUniqId).attr("pageTop", 0);
          $("#"+elmUniqId).attr("page", 1);

          //close context menu
          $(".formElementsContainer_SVG").click(function(){
            $(".contextMenuParent").hide();
          });
          
          $(".pdf-form-element").click(function(){
            $(".contextMenuParent").hide();
          });


          initDragElement(document.getElementById(elmUniqId));
          // svgMouseDown(document.getElementById(elmUniqId));
          createContextMenu(elmUniqId);
        }, 300);

      }

      function resetFieldData(obj, DstElmId){
        

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

              var onclickAttr = 'duplicateForm("'+elmUniqId+'");'; 
              $(".contextMenuParent #duplicateCtxMenu").attr("onclick", onclickAttr);
              
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

        $(".contextMenuParent").hide();
        $("#"+elmUniqId).remove();

      }

      function duplicateForm(elmUniqId){
        var elmTop = document.getElementById(elmUniqId).style.top;
        var cloneElm = $("#"+elmUniqId).clone();
        var tmpHgt = cloneElm.attr("pagetop");
        var tmpHgtNum = tmpHgt.replace("px", "");
        var newTop = parseInt(tmpHgtNum) + 100;
        var newPageTop = newTop+"px";
        cloneElm.attr("pagetop", newPageTop);
        
        //console.log("cloneElm.style.top");
        //console.log(elmTop);

        var tmpelmTopNum = elmTop.replace("px", "");
        var newTop = parseInt(tmpelmTopNum) + 100;
        newTop = newTop+"px";
       
        cloneElm.css("top", newTop );

        console.log("cloneElm");
        console.log(cloneElm);

        var idArr = elmUniqId.split("_");
        var elmTyp = idArr[0];
        var unqId = randomStr();
        
        var tmpId = elmTyp+"_"+unqId;
        var onclickAttr =  "openFieldSettings('"+tmpId+"');";
        

        cloneElm.attr("id",tmpId);
        cloneElm.attr("onclick",onclickAttr);

        $("#formElementsContainer_SVG #formElementsContainer_Group").append(cloneElm);
        $("#"+tmpId+" #"+elmUniqId+"_rect2").attr("id", tmpId+"_rect2");
        $("#"+tmpId+" #"+elmUniqId+"_text").attr("id", tmpId+"_text");
        
        //---
        
        //close context menu
        $(".formElementsContainer_SVG").click(function(){
          $(".contextMenuParent").hide();
        });
        
        $(".pdf-form-element").click(function(){
          $(".contextMenuParent").hide();
        });


        initDragElement(document.getElementById(tmpId));
        // svgMouseDown(document.getElementById(tmpId));
        createContextMenu(tmpId);
        $(".contextMenuParent").hide();

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

      function getElementTypeG(objType){
        
        var uniqId = randomStr();

		    var signature = '<g id="signature_'+uniqId+'" class="pdf-form-element" onclick="openFieldSettings(\'signature_'+uniqId+'\');" style="height:30px; width:90px; left:0px; top:0px;" page="1" pagetop="0px">\
                  <rect id="signature_'+uniqId+'_rect2" width="124" height="32" fill="#FDF7DB" stroke="#fdf7db"></rect>\
                  <rect id="signature_'+uniqId+'_rect1" width="4" height="32" fill="#FAEA9E" stroke="#fdf7db"></rect>\
                  <text id="signature_'+uniqId+'_text" x="4" font-size="13px" font-family="CourierPrime-Regular" fill="#000000" font-style="normal" font-weight="normal" text-decoration="none" xml:space="preserve" y="0" default-value="Signature of '+CURRENTUSERNAME_1+'" default-user="'+CURRENTUSERNAME_1+SEPERATOR+CURRENTUSEREMAIL_1+SEPERATOR+CURRENTUSERTAG_1+SEPERATOR+CURRENTUSERCOLOR_1+'" is-required="0" is-readonly="0" placeholder-hint="Sign Here">\
				  <tspan style="word-break: break-word;" x="4" dy="13">Signature</tspan>\
                  </text>\
               </g>'; 

        var signaturein = '<g id="signaturein_'+uniqId+'" class="pdf-form-element" onclick="openFieldSettings(\'signaturein_'+uniqId+'\');" style="height:30px; width:90px; left:0px; top:0px;" page="1" pagetop="0px">\
                          <rect id="signaturein_'+uniqId+'_rect2" width="48" height="32" fill="#FDF7DB" stroke="#fdf7db"></rect>\
                          <rect id="signaturein_'+uniqId+'_rect1" width="4" height="32" fill="#FAEA9E" stroke="#fdf7db"></rect>\
                          <text id="signaturein_'+uniqId+'_text" x="4" font-size="13px" font-family="CourierPrime-Regular" fill="#000000" font-style="normal" font-weight="normal" text-decoration="none" xml:space="preserve" y="0" default-value="'+CURRENTUSERINITIALS_1+'" default-user="'+CURRENTUSERNAME_1+SEPERATOR+CURRENTUSEREMAIL_1+SEPERATOR+CURRENTUSERTAG_1+SEPERATOR+CURRENTUSERCOLOR_1+'" is-required="0" is-readonly="0" placeholder-hint="Sign Here">\
						  <tspan style="word-break: break-word;" x="4" dy="13">Initials</tspan>\
						  </text>\
                       </g>';

        var textbox =  '<g id="textbox_'+uniqId+'" class="pdf-form-element" onclick="openFieldSettings(\'textbox_'+uniqId+'\');" style="height:30px; width:90px; left:0px; top:0px;" page="1" pagetop="0px">\
                          <rect id="textbox_'+uniqId+'_rect2" width="80" height="17" fill="#FDF7DB" stroke="transparent"></rect>\
                          <rect id="textbox_'+uniqId+'_rect1" width="4" height="17" fill="#FAEA9E" stroke="transparent"></rect>\
                          <text id="textbox_'+uniqId+'_text" x="4" font-size="13px" font-family="CourierPrime-Regular" fill="#000000" font-style="normal" font-weight="normal" text-decoration="none" xml:space="preserve" y="0" default-value="Text" default-user="'+CURRENTUSERNAME_1+SEPERATOR+CURRENTUSEREMAIL_1+SEPERATOR+CURRENTUSERTAG_1+SEPERATOR+CURRENTUSERCOLOR_1+'" is-required="0" is-readonly="0" placeholder-hint="Text..." data-textcolor-rgb="0,0,0" data-textcolor-hex="#000">\
                          <tspan style="word-break: break-word;" x="4" dy="13">Text</tspan>\
                          </text>\
                       </g>';
	
	    	var datepicker = '<g id="datepicker_'+uniqId+'" class="pdf-form-element" onclick="openFieldSettings(\'datepicker_'+uniqId+'\');" style="height:30px; width:90px; left:0px; top:0px;" page="1" pagetop="0px">\
                           <rect id="datepicker_'+uniqId+'_rect2" width="74.078125" height="17" fill="#FDF7DB" stroke="transparent"></rect>\
                           <rect id="datepicker_'+uniqId+'_rect1" width="4" height="17" fill="#FAEA9E" stroke="transparent"></rect>\
                           <text id="datepicker_'+uniqId+'_text" x="4" font-size="13px" font-family="CourierPrime-Regular" fill="#000000" font-style="normal" font-weight="normal" text-decoration="none" default-value="'+currentDate("dd/MM/yyyy")+'" date-format="dd/MM/yyyy" default-user="'+CURRENTUSERNAME_1+SEPERATOR+CURRENTUSEREMAIL_1+SEPERATOR+CURRENTUSERTAG_1+SEPERATOR+CURRENTUSERCOLOR_1+'" xml:space="preserve" y="0" is-required="0" is-readonly="0" placeholder-hint="DD/MM/YYYY" data-textcolor-rgb="0,0,0" data-textcolor-hex="#000">\
                           <tspan style="word-break: break-word;" x="4" dy="13">'+currentDate("dd/MM/yyyy")+'</tspan>\
                           </text>\
                       </g>';
        
        var checkbox = '<g id="checkbox_'+uniqId+'" class="pdf-form-element" onclick="openFieldSettings(\'checkbox_'+uniqId+'\');" style="height:30px; width:90px; left:0px; top:0px;" page="1" pagetop="0px">\
                          <rect id="checkbox_'+uniqId+'_backRect" width="22" height="16" fill="#FDF7DB"></rect>\
                          <rect id="checkbox_'+uniqId+'_rect1" width="2" height="16" fill="#FAEA9E"></rect>\
                          <rect id="checkbox_'+uniqId+'_rect2" x="4" y="1" width="14" height="14" fill="#ffffff" stroke="#b3bbc5" rx="2" ry="2"></rect>\
                          <path id="checkbox_'+uniqId+'_tick" fill="none" stroke="#0565ff" d="M 4 6 L 7.5 9.5 L 14.5 2.5" transform="translate(2,2)" stroke-width="2"></path>\
                       </g>';

        var  radiobutton = '<g id="radiobutton_'+uniqId+'" class="pdf-form-element" onclick="openFieldSettings(\'radiobutton_'+uniqId+'\');" style="height:30px; width:90px; left:0px; top:0px;" page="1" pagetop="0px">\
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

        var name = '<g id="name_'+uniqId+'" class="pdf-form-element" onclick="openFieldSettings(\'name_'+uniqId+'\');" style="height:30px; width:90px; left:0px; top:0px;" page="1" pagetop="0px">\
                       <rect id="name_'+uniqId+'_rect2" width="73.4072265625" height="17" fill="#FDF7DB" stroke="transparent"></rect>\
                       <rect id="name_'+uniqId+'_rect1" width="4" height="17" fill="#FAEA9E" stroke="transparent"></rect>\
                       <text id="name_'+uniqId+'_text" x="4" font-size="13px" font-family="CourierPrime-Regular" fill="#000000" font-style="normal" font-weight="normal" text-decoration="none" xml:space="preserve" y="0" default-value="Text" default-user="'+CURRENTUSERNAME_1+SEPERATOR+CURRENTUSEREMAIL_1+SEPERATOR+CURRENTUSERTAG_1+SEPERATOR+CURRENTUSERCOLOR_1+'" xml:space="preserve" y="0" is-required="0" is-readonly="0" placeholder-hint="Name" data-textcolor-rgb="0,0,0" data-textcolor-hex="#000">\
                        <tspan style="word-break: break-word;" x="4" dy="13">'+userName()+'</tspan>\
                       </text>\
                    </g>'; 


        var email = '<g id="email_'+uniqId+'" class="pdf-form-element" onclick="openFieldSettings(\'email_'+uniqId+'\');" style="height:30px; width:90px; left:0px; top:0px;" page="1" pagetop="0px">\
                       <rect id="email_'+uniqId+'_rect2" width="168.15625" height="17" fill="#FDF7DB" stroke="transparent"></rect>\
                       <rect id="email_'+uniqId+'_rect1" width="4" height="17" fill="#FAEA9E" stroke="transparent"></rect>\
                       <text id="email_'+uniqId+'_text" x="4" font-size="13px" font-family="CourierPrime-Regular" fill="#000000" font-style="normal" font-weight="normal" text-decoration="none" xml:space="preserve" y="0" default-value="'+CURRENTUSEREMAIL_1+'" default-user="'+CURRENTUSERNAME_1+SEPERATOR+CURRENTUSEREMAIL_1+SEPERATOR+CURRENTUSERTAG_1+SEPERATOR+CURRENTUSERCOLOR_1+'" xml:space="preserve" y="0" is-required="0" is-readonly="0" placeholder-hint="Email" data-textcolor-rgb="0,0,0" data-textcolor-hex="#000">\
                        <tspan style="word-break: break-word;" x="4" dy="13">'+userEmail()+'</tspan>\
                       </text>\
                    </g>';

        var editableDate = '<g id="editableDate_'+uniqId+'" class="pdf-form-element" onclick="openFieldSettings(\'editableDate_'+uniqId+'\');" style="height:30px; width:90px; left:0px; top:0px;" page="1" pagetop="0px">\
                             <rect id="editableDate_'+uniqId+'_rect2" width="95" height="17" fill="#FDF7DB" stroke="transparent"></rect>\
                             <rect id="editableDate_'+uniqId+'_rect1" width="4" height="17" fill="#FAEA9E" stroke="transparent"></rect>\
                             <text id="editableDate_'+uniqId+'_text" x="4" font-size="13px" font-family="CourierPrime-Regular" fill="#000000" font-style="normal" font-weight="normal" text-decoration="none" default-value="'+currentDate("MM/dd/yyyy")+'" default-user="'+CURRENTUSERNAME_1+SEPERATOR+CURRENTUSEREMAIL_1+SEPERATOR+CURRENTUSERTAG_1+SEPERATOR+CURRENTUSERCOLOR_1+'"  xml:space="preserve" y="0" is-required="0" is-readonly="0" placeholder-hint="MM/DD/YYYY" data-textcolor-rgb="0,0,0" data-textcolor-hex="#000">\
                              <tspan style="word-break: break-word;" x="4" dy="13">MM/DD/YYYY</tspan>\
                             </text>\
                          </g>';

        var label = '<g id="label_'+uniqId+'" class="pdf-form-element" style="visibility: visible;" onclick="openFieldSettings(\'label_'+uniqId+'\');" style="height:30px; width:90px; left:0px; top:0px;" page="1" pagetop="0px">\
                       <rect id="label_'+uniqId+'_rect1" width="80" height="17" fill="#f4f5eb" stroke="transparent"></rect>\
                       <text id="label_'+uniqId+'_text" x="4" font-size="13px" font-family="CourierPrime-Regular" fill="#000000" font-style="normal" font-weight="normal" text-decoration="none" default-value="Label" default-user="'+CURRENTUSERNAME_1+SEPERATOR+CURRENTUSEREMAIL_1+SEPERATOR+CURRENTUSERTAG_1+SEPERATOR+CURRENTUSERCOLOR_1+'" xml:space="preserve" y="0" is-required="0" is-readonly="0" placeholder-hint="Label" data-textcolor-rgb="0,0,0" data-textcolor-hex="#000">\
                        <tspan style="word-break: break-word;" x="4" dy="13">Label</tspan>\
                       </text>\
                    </g>';

        var hyperlink = '<g id="hyperlink_'+uniqId+'" class="pdf-form-element" onclick="openFieldSettings(\'hyperlink_'+uniqId+'\');" style="height:30px; width:90px; left:0px; top:0px;" page="1" pagetop="0px">\
                          <rect id="hyperlink_'+uniqId+'_rect1" width="90" height="20" fill="#f4f5eb" stroke="transparent"></rect>\
                          <image height="16" width="16" id="hyperlink_'+uniqId+'_hyperlinkicon" x="71.6875" src="data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjQiIGhlaWdodD0iMjQiIHZpZXdCb3g9IjAgMCAyNCAyNCIgZmlsbD0ibm9uZSIgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIj4KPHBhdGggZmlsbC1ydWxlPSJldmVub2RkIiBjbGlwLXJ1bGU9ImV2ZW5vZGQiIGQ9Ik01IDdDNC43MzQ3OCA3IDQuNDgwNDMgNy4xMDUzNiA0LjI5Mjg5IDcuMjkyODlDNC4xMDUzNiA3LjQ4MDQzIDQgNy43MzQ3OCA0IDhWMTlDNCAxOS4yNjUyIDQuMTA1MzYgMTkuNTE5NiA0LjI5Mjg5IDE5LjcwNzFDNC40ODA0MyAxOS44OTQ2IDQuNzM0NzggMjAgNSAyMEgxNkMxNi4yNjUyIDIwIDE2LjUxOTYgMTkuODk0NiAxNi43MDcxIDE5LjcwNzFDMTYuODk0NiAxOS41MTk2IDE3IDE5LjI2NTIgMTcgMTlWMTNDMTcgMTIuNDQ3NyAxNy40NDc3IDEyIDE4IDEyQzE4LjU1MjMgMTIgMTkgMTIuNDQ3NyAxOSAxM1YxOUMxOSAxOS43OTU3IDE4LjY4MzkgMjAuNTU4NyAxOC4xMjEzIDIxLjEyMTNDMTcuNTU4NyAyMS42ODM5IDE2Ljc5NTcgMjIgMTYgMjJINUM0LjIwNDM1IDIyIDMuNDQxMjkgMjEuNjgzOSAyLjg3ODY4IDIxLjEyMTNDMi4zMTYwNyAyMC41NTg3IDIgMTkuNzk1NiAyIDE5VjhDMiA3LjIwNDM1IDIuMzE2MDcgNi40NDEyOSAyLjg3ODY4IDUuODc4NjhDMy40NDEyOSA1LjMxNjA3IDQuMjA0MzUgNSA1IDVIMTFDMTEuNTUyMyA1IDEyIDUuNDQ3NzIgMTIgNkMxMiA2LjU1MjI4IDExLjU1MjMgNyAxMSA3SDVaIiBmaWxsPSIjMzMzMzMzIi8+CjxwYXRoIGZpbGwtcnVsZT0iZXZlbm9kZCIgY2xpcC1ydWxlPSJldmVub2RkIiBkPSJNMTQgM0MxNCAyLjQ0NzcyIDE0LjQ0NzcgMiAxNSAySDIxQzIxLjU1MjMgMiAyMiAyLjQ0NzcyIDIyIDNWOUMyMiA5LjU1MjI4IDIxLjU1MjMgMTAgMjEgMTBDMjAuNDQ3NyAxMCAyMCA5LjU1MjI4IDIwIDlWNEgxNUMxNC40NDc3IDQgMTQgMy41NTIyOCAxNCAzWiIgZmlsbD0iIzMzMzMzMyIvPgo8cGF0aCBmaWxsLXJ1bGU9ImV2ZW5vZGQiIGNsaXAtcnVsZT0iZXZlbm9kZCIgZD0iTTIxLjcwNzEgMi4yOTI4OUMyMi4wOTc2IDIuNjgzNDIgMjIuMDk3NiAzLjMxNjU4IDIxLjcwNzEgMy43MDcxMUwxMC43MDcxIDE0LjcwNzFDMTAuMzE2NiAxNS4wOTc2IDkuNjgzNDIgMTUuMDk3NiA5LjI5Mjg5IDE0LjcwNzFDOC45MDIzNyAxNC4zMTY2IDguOTAyMzcgMTMuNjgzNCA5LjI5Mjg5IDEzLjI5MjlMMjAuMjkyOSAyLjI5Mjg5QzIwLjY4MzQgMS45MDIzNyAyMS4zMTY2IDEuOTAyMzcgMjEuNzA3MSAyLjI5Mjg5WiIgZmlsbD0iIzMzMzMzMyIvPgo8L3N2Zz4K" preserveAspectRatio="xMinYMid meet" y="2"></image>\
                          <text id="hyperlink_'+uniqId+'_text" x="4" font-size="13px" font-family="CourierPrime-Regular" fill="#3E60FF" font-style="normal" font-weight="normal" text-decoration="none" default-value="'+CURRENTUSEREMAIL_1+'" default-user="'+CURRENTUSERNAME_1+SEPERATOR+CURRENTUSEREMAIL_1+SEPERATOR+CURRENTUSERTAG_1+SEPERATOR+CURRENTUSERCOLOR_1+'"  xml:space="preserve" y="0" is-required="0" is-readonly="0" placeholder-hint="Hyperlink" data-textcolor-rgb="0,0,0" data-textcolor-hex="#000">\
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
        $("#select-user").slideToggle("slow");
      }
      
      function setRequiredField(obj, DstElmId){
        var isRequired = 0;
        if($(obj).is(":checked")){
          isRequired = 1;
        }else{
          isRequired = 0;
        }

        $(obj).val(isRequired);

        var elmIdParts = DstElmId.split("_");
        var  elmTyp = elmIdParts[0];
        var  elmIdStr = elmIdParts[1];
       
        $("#"+elmTyp+"_"+elmIdStr+"_text").attr("is-required", isRequired);
        
      }

      function setReadOnlyField(obj, DstElmId){
        var isReadonly = 0;
        if($(obj).is(":checked")){
          isReadonly = 1;
        }else{
          isReadonly = 0;
        }

        $(obj).val(isReadonly);

        var elmIdParts = DstElmId.split("_");
        var  elmTyp = elmIdParts[0];
        var  elmIdStr = elmIdParts[1];
       
        $("#"+elmTyp+"_"+elmIdStr+"_text").attr("is-readonly", isReadonly);
        
      }

      function assignUserToField(obj, DstElmId){
        
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

        var is_required = $("#"+elmTyp+"_"+elmIdStr+"_text").attr("is-required");
        var is_readonly = $("#"+elmTyp+"_"+elmIdStr+"_text").attr("is-readonly");
        var data_textcolor_rgb = $("#"+elmTyp+"_"+elmIdStr+"_text").attr("data-textcolor-rgb");
        var data_textcolor_hex = $("#"+elmTyp+"_"+elmIdStr+"_text").attr("data-textcolor-hex");
      
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
      
        return {"font-size":font_size, "font-family":font_family, "font-style":font_style, "font-weight":font_weight, "text-decoration":text_decoration, "default-value":default_value, "default-user":default_user, "line-height":line_height, "date-format":date_format, "is_required":is_required, "is_readonly":is_readonly, "data_textcolor_rgb":data_textcolor_rgb, "data_textcolor_hex":data_textcolor_hex};
      
      }

    function openFieldSettings(elmId){
 
 
        event.stopPropagation();
		    //hideElementBorder();
        $("#bs-thumbnail-prepare").hide();
        
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

		var resetButton = '<a href="#"><i class="la la-redo-alt"></i></a>';
		var closeButton = '<a class="settingsClose" href="javascript:void(0);"><i class="la la-times"></i></a>';
			
			
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
              \
              <div class="row" id="font-style">\
                  <div class="col-lg-8">\
                      <ul class="list-unstyled font-weight" id="font-weight">\
                          <li><a href="javascript:void(0);" class="font-weight-button bold textColor" onclick="changeBold(this, \''+elmId+'\');">B</a>\</li>\
                          <li><a href="javascript:void(0);" class="font-weight-button italic textColor" onclick="changeItalic(this, \''+elmId+'\');">I</a>\</li>\
                          <li><a href="javascript:void(0);" class="font-weight-button underline textColor"  onclick="changeUnderline(this, \''+elmId+'\');">U</a>\</li>\
                      </ul>\
                  </div>\
                  <div class="col-lg-4">\
                      <div id="font-size">\
                        <input id="font-size-input" class="borderColor textColor form-control" type="number" max="72" min="7" value="13" onkeyup="changeFontSize(this, \''+elmId+'\');" onchange="changeFontSize(this, \''+elmId+'\');"/>\
                      </div>\
                  </div>\
                  <div class="w-100 mt-3"></div>\
                  <div class="col-lg-8">\
                    <span id="line-height">\
                      <label class="settingRowLineHeightLabel">Line Height</label>\
                      <input id="line-height-input" class="form-control borderColor textColor" type="number" max="100" min="15" value="15" onkeyup="changeLineHeight(this, \''+elmId+'\');" onchange="changeLineHeight(this, \''+elmId+'\');"/>\
                      <!---<span>\
                        <a href="javascript:void(0);">+</a>\
                        <a href="javascript:void(0);">-</a>\
                      </span>--->\
                    </span>\
                  </div>\
                  <div class="col-lg-4">\
                    <span id="color-picker">\
                        <label class="settingRowFontColorLabel">Font Color</label>\
                        <span class="color-action-box borderColor textColor">\
                          <input type="text" id="colorPicker" value="#000">\
                        </span>\
                    </span>\
                  </div>\
              </div>\
              <!-- <ul class="settingRowFields borderColor">\
                <li class="settingRowFieldsLi">\
                  <span >\
                  </span>\<span >\
                    </span>\
                </li>\
                <li class="settingRowFieldsLi">\
                  </li>\
              </ul>-->\
            </div>';
		
		//Assigned to users html	
		var default_userArr = default_user.split(SEPERATOR);
		var tmpCURRENTUSERNAME = default_userArr[0];
		var tmpCURRENTUSEREMAIL = default_userArr[1];
		var tmpCURRENTUSERTAG = default_userArr[2];
		var tmpCURRENTUSERCOLOR = default_userArr[3];
		var tmpUserClass = tmpCURRENTUSERCOLOR.replace("#","");
		
		var usersHtml = '<div class="settingRow">\
            <label class="settingRowLabel">Assigned to</label>\
            <div class="dropdown assigned-to-dropdown">\
              <button id="currentUserNameSpan" class="btn btn-outline-secondary dropdown-toggle" onclick="openUsersList();" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="color-cirle"  style="background-color:'+tmpCURRENTUSERCOLOR+'"></i>'+tmpCURRENTUSERTAG+'</button>\
              <!--<input type="hidden" id="currentUserNameHidden" value="'+CURRENTUSERNAME_1+SEPERATOR+CURRENTUSEREMAIL_1+SEPERATOR+CURRENTUSERTAG_1+SEPERATOR+CURRENTUSERCOLOR_1+'"/>-->\
				    <input type="hidden" id="currentUserNameHidden" value="'+default_user+'"/>\
        <!--<input type="hidden" id="currentUserNameHidden" value="'+CURRENTUSERNAME_1+SEPERATOR+CURRENTUSEREMAIL_1+SEPERATOR+CURRENTUSERTAG_1+SEPERATOR+CURRENTUSERCOLOR_1+'"/>-->\
				<input type="hidden" id="currentUserNameHidden" value="'+default_user+'"/>\
			<ul class="settingRowFields borderColor textColor select-user" id="select-user">';

		$.each(SELECTEDUSERS, function(idx, vl){
			
			var tmpName = vl.name;
			var tmpEmail =  vl.email;
			var tmpTag = vl.tag;
			var tmpClr = vl.color;
			var tmpClass = tmpClr.replace("#","");
			usersHtml += '<li class="userLI '+tmpClass+'" data-value="'+tmpName+SEPERATOR+tmpEmail+SEPERATOR+tmpTag+SEPERATOR+tmpClr+'" onclick="assignUserToField(this,\''+elmId+'\');"><i class="userColor" style="background-color:'+tmpClr+'"></i>'+tmpTag+'</li>';
			
		});

		usersHtml += '</ul></div></div>';
    
    var readOnlyRequiredHtml = '<div class="readonlyRequiredContainer"><ul class="readonlyRequiredUl list-unstyled"><li class="readonlyRequiredLi custom-checkbox"><input onchange="setRequiredField(this,\''+elmId+'\');" type="checkbox" id="requiredCheck" /><label for="requiredCheck">Required</label></li><li class="readonlyRequiredLi custom-checkbox"><input onchange="setReadOnlyField(this,\''+elmId+'\');" type="checkbox" id="readOnlyCheck" /><label for="readOnlyCheck">Read Only</label></li></ul></div>';


		//Signature settings html
        var signatureSettings = '<div class="textSettingsConatiner">\
          <div class="textSettingsHeader borderColor">\
            <span style="margin-right: 4px; font-size: 18px;"><strong>Textbox settings</strong></span>\
			'+resetButton+'\
			'+closeButton+'\
          </div>\
          <div class="textSettingsBody">';
		  
		signatureSettings += usersHtml+readOnlyRequiredHtml;
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
		  
		signatureinSettings += usersHtml+readOnlyRequiredHtml;
		signatureinSettings += '</div>\
        </div>';

		//Textbox settings html
        var textBoxSettings = '<div class="textSettingsConatiner">\
          <div class="textSettingsHeader borderColor">\
            <strong>Textbox settings</strong>\
            <ul class="list-unstyled">\
              <li>'+resetButton+'</li>\
              <li>'+closeButton+'</li>\
            </ul>\
          </div>\
          <div class="textSettingsBody">';
		  
		textBoxSettings += usersHtml+readOnlyRequiredHtml;
		  
        textBoxSettings +=  '<div class="settingRow">\
              <label class="settingRowLabel">Default Text</label>\
              <textarea id="default-text" class="settingRowFields borderColor textColor" onKeyup="changeDefaultText(this, \''+elmId+'\');" style="resize: none;" placeholder="Add text here..."></textarea>\
            </div>\
            <div class="settingRow">\
              <label class="settingRowLabel">Hint text(optional)</label>\
              <input type="text" id="default-text-placeholder" class="settingRowFields borderColor textColor" onKeyup="changeDefaultPLaceholder(this, \''+elmId+'\');" placeholder="Add placeholder here...">\
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
		  
		labelSettings += usersHtml+readOnlyRequiredHtml;
		  
        labelSettings += '<div class="settingRow">\
              <label class="settingRowLabel">Default Text</label>\
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
		  
		hyperlinkSettings += usersHtml+readOnlyRequiredHtml;
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
		  
		dateSignedSettings += usersHtml+readOnlyRequiredHtml;
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
          
		dateEditableSettings += usersHtml+readOnlyRequiredHtml;
		  
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
		
		nameSettings += usersHtml+readOnlyRequiredHtml;
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
		  
		emailSettings += usersHtml+readOnlyRequiredHtml;
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
			    $("#Advance-fields").html(nameSettings);
        }else if(elmTyp == "email"){
          $("#Advance-fields").html(emailSettings);
        }else if(elmTyp == "editableDate"){
          $("#Advance-fields").html(dateEditableSettings);
          setTimeout(function(){
            $("#datePicker").datepicker();  
          }, 100);
            
        }
		
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
			
			$('.userLI.'+tmpUserClass).trigger("click");
			
      if($('#colorPicker').length > 0){
        openColorPicker(elmId);
      }
		}, 500);
        
      }

      function openColorPicker(elmId){
        
        $('#colorPicker').spectrum({
          type: "color",
          showInput: true,
          showAlpha: false
        });


        setTimeout(function(){
          
          $('.sp-container .sp-choose').text("Apply");
          $('.sp-container .sp-choose').attr("onclick", "setColorValues('"+elmId+"');");

        },100);

      }

      function setColorValues(elmId){
        var clr = $('.sp-container .sp-input').val();
        var rgbBgClr = $(".sp-preview-inner").css("background-color");
        
        rgbBgClr = rgbBgClr.replace("(","");
        rgbBgClr = rgbBgClr.replace(")","");
        rgbBgClr = rgbBgClr.replace("rgb","");
        rgbBgClr = rgbBgClr.replace(" ","");
        $("#colorPicker").val(clr);
        $("#colorPicker").attr("data-rgb",rgbBgClr);
        $("#"+elmId+"_text").attr("data-textcolor-rgb",rgbBgClr);
        $("#"+elmId+"_text").attr("data-textcolor-hex",clr);
        $("#"+elmId).css({"color":clr});

        console.log("rgb:"+rgbBgClr);
        console.log("clr:"+clr);


      }

      function changeDefaultPLaceholder(obj, DstElmId){
        
        var defltVl = $(obj).val();
        var elmIdParts = DstElmId.split("_");
        var  elmTyp = elmIdParts[0];
        var  elmIdStr = elmIdParts[1];
        
        $("#"+elmTyp+"_"+elmIdStr+"_text").attr("placeholder-hint", defltVl);

        /*
        document.getElementById(elmTyp+"_"+elmIdStr).style.height = 'unset';
        var tmpDefaultValue = $("#"+elmTyp+"_"+elmIdStr+"_text").attr("default-value");
        
        if(!isReal(defltVl) && isReal(tmpDefaultValue)){
          defltVl = tmpDefaultValue;
        }else if(isReal(defltVl) && !isReal(tmpDefaultValue)){
          //defltVl = "Text...";
        }else{
          defltVl = "Text...";
        }
        
        $("#"+elmTyp+"_"+elmIdStr+"_text").attr("placeholder-hint", defltVl);
        
        var textHtml = '<tspan style="word-break: break-word; width: 100%; float: left;" x="4" dy="13">'+defltVl+'</tspan>';
        $("#"+elmTyp+"_"+elmIdStr+"_text").html(textHtml);

        var inithght = document.getElementById(elmTyp+"_"+elmIdStr).clientHeight;

        if(inithght < 30){
          inithght = 30;
        }
        
        var newHeight = inithght+"px";
        $("#"+elmTyp+"_"+elmIdStr).css({"height":newHeight});
        */
      }

      function changeDefaultText(obj, DstElmId){
        
          var defltVl = $(obj).val();
          var elmIdParts = DstElmId.split("_");
          var  elmTyp = elmIdParts[0];
          var  elmIdStr = elmIdParts[1];

          document.getElementById(elmTyp+"_"+elmIdStr).style.height = 'unset';
          if(!isReal(defltVl)){
            defltVl = "";
          }

          $("#"+elmTyp+"_"+elmIdStr+"_text").attr("default-value", defltVl);

          if(defltVl != "" && defltVl != null && defltVl != undefined){
            var defltVlArr = defltVl.split("\n");
            
            var textHtml = '';
            $.each(defltVlArr, function(i,v){
              textHtml += '<tspan style="word-break: break-word; width: 100%; float: left;" x="4" dy="13">'+v+'</tspan>';
            })
            
            //$("#"+elmTyp+"_"+elmIdStr+"_text tspan").text(defltVl);
            $("#"+elmTyp+"_"+elmIdStr+"_text").html(textHtml);
          }else{
            var tmpPlcHldr = $("#"+elmTyp+"_"+elmIdStr+"_text").attr("placeholder-hint");
            var textHtml = '<tspan style="word-break: break-word; width: 100%; float: left;" x="4" dy="13">'+tmpPlcHldr+'</tspan>';
            if(tmpPlcHldr != "" && tmpPlcHldr != null && tmpPlcHldr != undefined){
              $("#"+elmTyp+"_"+elmIdStr+"_text").html(textHtml);
            }
          }
          
          
          var inithght = document.getElementById(elmTyp+"_"+elmIdStr).clientHeight;

          if(inithght < 30){
            inithght = 30;
          }
          
          var newHeight = inithght+"px";
          $("#"+elmTyp+"_"+elmIdStr).css({"height":newHeight});

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

      function changeFontFamily(obj, DstElmId){
          
          var defltVl = $(obj).val();
          var elmIdParts = DstElmId.split("_");
          var  elmTyp = elmIdParts[0];
          var  elmIdStr = elmIdParts[1];

          $("#"+elmTyp+"_"+elmIdStr+"_text").attr("font-family", defltVl);
          $("#"+elmTyp+"_"+elmIdStr+"_text tspan").css("font-family", defltVl);
      }

      function changeFontSize(obj, DstElmId){

        var defltVl = $(obj).val();
        
        if(defltVl < 7){
          $(obj).val(13);
          setTimeout(function(){
            changeFontSize(obj, DstElmId);
          },100);
          
        }

        var elmIdParts = DstElmId.split("_");
        var  elmTyp = elmIdParts[0];
        var  elmIdStr = elmIdParts[1];

        $("#"+elmTyp+"_"+elmIdStr+"_text").attr("font-size", defltVl+"px");
        $("#"+elmTyp+"_"+elmIdStr+"_text tspan").css("font-size", defltVl+"px");
      }
      

      function changeLineHeight(obj, DstElmId){
        
        var defltVl = $(obj).val();

        if(defltVl < 15){
          $(obj).val(15);
          setTimeout(function(){
            changeLineHeight(obj, DstElmId);
          },100);
        }

        var elmIdParts = DstElmId.split("_");
        var  elmTyp = elmIdParts[0];
        var  elmIdStr = elmIdParts[1];

        $("#"+elmTyp+"_"+elmIdStr+"_text").attr("line-height", defltVl+"px");
        $("#"+elmTyp+"_"+elmIdStr+"_text tspan").css("line-height", defltVl+"px");
      }

      
      function changeBold(obj, DstElmId){

        if($(obj).hasClass("selected")){
          $(obj).removeClass("selected");
          var defltVl = "normal";
        }else{
          $(obj).addClass("selected");
          var defltVl = "bold";
        }

        var elmIdParts = DstElmId.split("_");
        var  elmTyp = elmIdParts[0];
        var  elmIdStr = elmIdParts[1];

        $("#"+elmTyp+"_"+elmIdStr+"_text").attr("font-weight", defltVl);
        $("#"+elmTyp+"_"+elmIdStr+"_text tspan").css("font-weight", defltVl);
      }

      function changeItalic(obj, DstElmId){

        if($(obj).hasClass("selected")){
          $(obj).removeClass("selected");
          var defltVl = "normal";
        }else{
          $(obj).addClass("selected");
          var defltVl = "italic";
        }

        var elmIdParts = DstElmId.split("_");
        var  elmTyp = elmIdParts[0];
        var  elmIdStr = elmIdParts[1];

        $("#"+elmTyp+"_"+elmIdStr+"_text").attr("font-style", defltVl);
        $("#"+elmTyp+"_"+elmIdStr+"_text").css("font-style", defltVl);
      }

      function changeUnderline(obj, DstElmId){

        if($(obj).hasClass("selected")){
          $(obj).removeClass("selected");
          var defltVl = "none";
        }else{
          $(obj).addClass("selected");
          var defltVl = "underline";  
        }


        var elmIdParts = DstElmId.split("_");
        var  elmTyp = elmIdParts[0];
        var  elmIdStr = elmIdParts[1];
        

        $("#"+elmTyp+"_"+elmIdStr+"_text").attr("text-decoration", defltVl);
        $("#"+elmTyp+"_"+elmIdStr+"_text").css("text-decoration", defltVl);
        $("#"+elmTyp+"_"+elmIdStr+"_text tspan").css("text-decoration", defltVl);
      }


      //Make the DIV element draggagle:

      function svgMouseDown(){
        /*    
        $(document).on("mousedown", ".pdf-form-element", function(){
          initDragElement(this);
        });
        */
      }

       function initDragElement(elmnt) {
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

           var svgID = parseInt($("#formElementsContainer_SVG")[0].style.width);
           var svgIDHeight = parseInt($("#formElementsContainer_SVG")[0].style.height);

            var offsetTop = elmnt.offsetTop;
            var offsetLeft = elmnt.offsetLeft;
            // set the element's new position:
            if (parseInt(elmnt.offsetTop) < 0) {
              offsetTop = 1;
            }
            
            if (parseInt(elmnt.offsetLeft) < 0) {
              offsetLeft = 1;
            }else if(parseInt(elmnt.offsetLeft) > svgID - 92){
              offsetLeft = svgID - 92;
            }else if(parseInt(elmnt.offsetTop) > svgIDHeight - 40){
              offsetTop = svgIDHeight - 40;
            }

            elmnt.style.top = offsetTop - pos2 + "px";
            elmnt.style.left = offsetLeft - pos1 + "px";

            assignPageNoToElement(elmnt, elmnt.style.top);
            
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


      function assignPageNoToElement(elmnt, elmntTop){
        
        $.each(PDFPAGESAREA, function(k, v){
          var p = v.page;
          var t = v.top;
          var b = v.bottom;
          var pph = v.perPageHeight;

          var elmtTopac = elmntTop.replace("px", "");
          if(elmtTopac >= t && elmtTopac <= b){
            var elemPageTop = 0;
            if(p > 1){
              
              var prevPages = p - 1;
              var minusTop = pph * prevPages;
              elemPageTop = elmtTopac - minusTop;
              
              if(elemPageTop >= 5){
                elemPageTop = elemPageTop - 5;
              }else{
                elemPageTop = 0;
              }
              
            }else{
              elemPageTop = elmtTopac;
            }
            
            $(elmnt).attr("pageTop", elemPageTop);
            $(elmnt).attr("page", p);

            resetThumbnailFieldCount();
           }
        });
      }
      
      function resetThumbnailFieldCount(){
        var pageElmArr = {};
        $("#formElementsContainer_Group .pdf-form-element").each(function(idx, vl){
          var tmpPage = $(vl).attr("page");
          if(isReal(pageElmArr["page_"+tmpPage])){
            pageElmArr["page_"+tmpPage] = pageElmArr["page_"+tmpPage] + 1;
          }else{
            pageElmArr["page_"+tmpPage] = 1;
          }
          
        });
        
        $(".thumbCanvasHolder .thumbnailmeta").html(""); 
        $(".thumbCanvasHolder .thumbnailmeta").removeClass("bgClr");
        
        if(isReal(pageElmArr)){
          
          $.each(pageElmArr, function(i,v){
            
            var iparts = i.split("_");
            var tmpPgNum = iparts[1];
            var htmlTxt = "";
            
            if(v > 1){
              htmlTxt = v + " fields added";
            }else{
              htmlTxt = v + " field added";
            }
            
            $("#documentThumbnail_"+tmpPgNum+" .thumbnailmeta").addClass("bgClr");
            $("#documentThumbnail_"+tmpPgNum+" .thumbnailmeta").html(htmlTxt); 

            
          });
        }

      }

      function initResizeElement(elmnt) {
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


        showLoader("sendBttn");


        var tmpSaveDataObj = {};

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
            var is_required = $("#"+elmId+"_text").attr("is-required");
            var is_readonly = $("#"+elmId+"_text").attr("is-readonly");
            var placeholder_hint = $("#"+elmId+"_text").attr("placeholder-hint");
            
            var data_textcolor_rgb = $("#"+elmId+"_text").attr("data-textcolor-rgb");
            var data_textcolor_hex = $("#"+elmId+"_text").attr("data-textcolor-hex");
            
            //var default_userParts = default_user.split(SEPERATOR);

            if(isReal(tmpSaveDataObj[default_user])){
                //nothing to do
            }else{
                tmpSaveDataObj[default_user] = [];
            }

            tmpSaveDataObj[default_user].push({"elmType":elmTyp, "page":tmpPage, "pageTop":tmpPageTop, "style":tmpStyl, "font_size":font_size, "font_family":font_family, "font_style":font_style, "font_weight":font_weight, "text_decoration":text_decoration, "default_value":default_value, "default_user":default_user, "is_required":is_required, "is_readonly":is_readonly, "placeholder_hint":placeholder_hint, "data_textcolor_rgb":data_textcolor_rgb, "data_textcolor_hex": data_textcolor_hex});

            //console.log("tmpSaveDataObj");
            //console.log(tmpSaveDataObj);


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
              $("#prepare-success-modal").modal("show");
              $("#sendBttn").addClass("button-disabled");
              $("#sendBttn").removeAttr("onclick");
            }else{
              
              var msg = "Please try again.";
              var err = 1;
              showToastMsg(msg, err);
              
            }

            hideLoader("sendBttn", "Send");

        });

    }

    function gotoDashboard(){
      window.location.href= SERVICEURL+"/dashboard";
    }
    
    function createNewDocument(){
      window.location.href= SERVICEURL+"/upload";
    }
    