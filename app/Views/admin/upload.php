<?php include("header.php"); ?>

<script src="<?php echo base_url("/assets/js/pdf.js"); ?>"></script>
<!---
<div id="fileUploadMainContainer" class="fileUploadMainContainer">
    <form action="<?php //echo base_url("fileupload"); ?>" method="POST" onsubmit="return uploadProcess();" enctype="multipart/form-data">
        <div>
            <div class="previewMainBox">
                <div class="browseBox">
                    <span onclick="browseFile();">Browse File</span>
                    <input type="file" id="fileupload" name="fileupload" onchange="uploadFile(event);" placeholder="Browse your file" style="height:1px; width:1px; opacity:0;"> 
                </div>
                <div class="previewBox">
                    <canvas id="pdfViewer"></canvas>
                <div>
            </div>
            
        </div>  
        <div>
            <button onclick="uploadProcess();">Save</button>
        </div>
    </form>
</div>
--->
<main>
<form id="documentForm" action="<?php echo site_url("fileupload"); ?>" method="POST" onsubmit="return next();" enctype="multipart/form-data">
    <div id="mainPageHeader" class="container-fluid" style="margin-bottom: 70px;">
          <input type="hidden" id="documentId" value="<?php echo $documentId; ?>">
        <div class="top-menu">
            <figure class="logo-wrap">
                <!--<span class="appName"><img src="<?php //echo base_url("/assets/images/boldsign_sitelogo.svg"); ?>" /></span>-->
                <span class="appName"><img src="<?php echo base_url("/assets/images/logocl.png"); ?>" /></span>
            </figure>
            <div class="">
              <span class="documentNameContainer conf-fields"><i class="la la-angle-left"></i> Prepare document for signing <small>(
                Step 1/2)
              </small></span>
              
            </div>
            <ul class="top-right-btns list-unstyled other-page-top-btns">
               
                  <li>
                    <button type="submit" value="Submit" class="btn btn-primary">Next</button>
                  </li>
                  <li>
                    <a class="btn-cross" href="javascript:void(0);">X</a>
                  </li>
               
              </ul>
        </div>
      
    </div>


<section class="upload-docs-fullbody">
    <div class="container">
        <div class="box">
            <div class="box-heading">
                <h4 class="mb-4"><b>Add file</b></h4>
            </div>     
            <div class="box-body">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="file-upload-wrap">
                            <p class="file-upload-text"><b>Choose from computer</b></p> 
                            <div>
                                <a href="javascript:void(0);" class="browseBttn btn btn-outline-primary" onclick="browseFile();">Browse</a>
                                <input type="file" id="fileupload" name="fileupload" onchange="uploadFile(event);" placeholder="Browse your file" style="height:1px; width:1px; opacity:0;" accept="application/pdf"> 
                            </div>
                            <p id="prefferedFormatTxt">Preferred format: PDF</p>
                        </div>
                    </div>
                    <!---
                    <div class="col-lg-5">
                        <div class="cloud-icons-wrap">
                            <p><b>Choose from cloud</b></p>
                            <div>
                                <a href="#"><img src="<?php //echo base_url("/assets/images/onedrive.png"); ?>" width="75px"></a>
                                <a href="#"><img src="<?php //echo base_url("/assets/images/dropbox.png"); ?>" width="75px"></a>
                                <a href="#"><img src="<?php //echo base_url("/assets/images/google-drive.png"); ?>" width="75px"></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="choose-temp-wrap">
                            <p><b>Choose from cloud</b></p>
                            <div><button class="btn btn-outline-primary">Browse</button></div>
                        </div>
                    </div>
                    --->
                    <div class="col-12 mt-4">
                        <table class="table file-list-table" id="uploadedFilesRowsBox">
                        
                            
                        <!--
                            <tr>
                                <td>1</td>
                                <td width="70px"><img src="assets/images/pdf-icon.png" width="50px"></td>
                                <td>
                                    <h5><b>Track Consignment</b></h5>
                                    <p>2 Pages</p>
                                </td>
                                <td class="uploadFileMsg">Upload Successfully</td>
                                <td class="fileActionBox">
                                    <a href="#" class="file-remove-a"><i class="la la-trash-alt"></i> Remove</a>
                                </td>
                            </tr>
                            -->
                        </table>    
                    </div>
                </div>
            </div>
        </div>

        <div class="box mt-4">
            <div class="box-heading">
                <h4 class="mb-4"><b>Add Recipients</b></h4>
            </div>     
            <div class="box-body">
                <table class="table add-recipient-table" id="recipientsContainer">
                    <tr id="recipient_1" class="recipientRow">
                        <td class="srno"><span class="recipient-srno" style="background-color:#C0C0C0;">1</span></td>
                        <td>
                            <input type="text" class="form-control RecipientName" name="RecipientName[1]" placeholder="Recipient name*">
                        </td>
                        <td>
                            <input type="text" class="form-control RecipientEmail" name="RecipientEmail[1]" placeholder="Recipient email*">
                        </td>
                        <td>
                            <div class="dropdown">
                            <button disabled class="btn btn-outline-primary" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="las la-signature"></i> Signer
                            </button>
                            <!--
                                <button disabled class="btn btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Signer
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="javascript:void(0);">Signer</a></li>
                                    <li><a class="dropdown-item" href="javascript:void(0);">Reviewer</a></li>
                                    <li><a class="dropdown-item" href="javascript:void(0);">In-Person Signer</a></li>
                                </ul>
                            -->
                            </div>
                        </td>
                        <td>
                            <!--<a href="#"><i class="la la-trash-alt"></i></a>-->
                        </td>
                    </tr>

                    <!-- Recipient Settings -->
                    <tr id="recipientSettingRow_1" class="recipientSettingRow">
                        <td></td>
                        <td colspan="4">
                            <a href="javascript:void(0);" id="recipientSettingBttn_1" class="recipientSettingBttn" onclick="toggleOpt(1);">Show Settings <i class="la la-angle-down"></i></a>
                            
                            <div id="recipientSettingOpt_1" class="hideElement">
                            <h5 class="mt-3 authHead"><b>Authentication </b><i data-placement="top" data-toggle="authtooltip" title="Recipients will be required to enter a system generated one time password or a sender specified access code to view and sign the document." class="las la-info-circle"></i>
                                <!--<div class="toggle-btn">-->
                                    <input type="checkbox" id="recipientAuthBttn_1" class="cb-value" onchange="toggleBttn(1);" value="0"/>
                                    <input type="hidden" class="recipientAuthInputBttn" name="recipientAuthInputBttn[1]" id="recipientAuthInputBttn_1" value="0"/>
                                   <!-- <span class="round-btn"></span> 
                                </div>-->
                            </h5>
                            <div class="authenticateOptions" id="authenticateOptions_1">
                                <div class="mt-3">
                                    <input id="accessOtpRadio_1" type="radio" name="accessRadio_1" onchange="authTyp(1,1);">
                                    <label for="accessOtpRadio_1">Email OTP (One Time Password)</label>
                                </div>
                                <div class="mt-3">
                                    <input id="accessCodeRadio_1" type="radio" name="accessRadio_1" onchange="authTyp(1,2);">
                                    <label for="accessCodeRadio_1">Access Code</label>
                                </div>
                                <input type="hidden" class="accessCodeOtpOpt" name="accessCodeOtpOpt[1]" id="accessCodeOtpVal_1" value="0">
                                <div class="accesscodeProperties" id="accesscodeProperties_1">
                                <div class="mt-3">
                                    <input type="text" class="form-control accessCode" id="accessCode_1" name="accessCode[1]" placeholder="Access Code">
                                    <span class="text-danger"><small>This field is required when access code is enabled</small></span>
                                </div>
                                <div>Note: You must communicate this access code to the recipient directly</div>
                                </div>
                            </div>
                            <!--
                            <h5 class="mt-4"><b>Private Message </b></h5>
                            <div class="mt-3">
                                <textarea class="form-control pri-msg" rows="3"></textarea>
                            </div>
                            -->
                            </div>
                        </td>
                    </tr>
                    <!-- Recipient Settings -->



                </table>
                <div class="mt-4">
                    <table class="table add-recipient-table-2">
                        <td width="52px"></td>
                        <td>
                            <a class="add-recipient-bttn w-100 btn btn-outline-primary" onclick="addRecipient();">+ Add Recipient</a>        
                        </td>
                        <td width="90px">
                            <!--<a href="#"><i class="la la-ellipsis-v"></i></a>-->
                        </td>
                    </table>
                    
                </div>
            </div>
        </div>
        
        <div class="box mt-4">
            <div class="box-heading">
                <h4 class="mb-4"><b>Document</b></h4>
            </div> 
            <div class="box-body">
                <div class="row">
                    <div class="col-12 mb-4">
                        <label>Title <a href="javascript:void(0);"><i data-placement="top" data-toggle="doctitletooltip" title="This is the document name that will be shown within the user interface and also in the signature request email." class="las la-info-circle"></i></a></label>
                        <input type="text" id="documentTitle" name="documentTitle" class="form-control" placeholder="Enter document title*">
                    </div>
                    <div class="col-12 mb-4">
                        <label>Message</label>
                        <textarea class="form-control" rows="3" id="recipientMessage" name="recipientMessage" placeholder="Enter message for all recipients"></textarea>
                    </div>
                </div>
            </div>
        </div>

        <div class="box mt-4">
            <div class="box-heading">
                <h4 class="mb-4"><b>Document Settings</b></h4>
            </div> 
            <div class="box-body">
                <div class="row">
                    <div class="col-4">
                    <!--    
                        <div class="mb-4">
                            <label>Tags</label>
                            <textarea rows="3" class="form-control"></textarea>
                        </div>
                        <div class="mb-4">
                            <p>Allow Signers to re-assign</p>
                        </div>
                    -->
                        <div class="mb-4 input-number-wrap">
                            <label>Expires in (Days)</label>

                            <div class="input-group">
                                <input type="text" class="form-control" id="expiresInDays" name="expiresInDays" value="10" oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*)\./g, '$1');" min="1" max="180" aria-label="Username" aria-describedby="basic-addon1">
                                <span class="input-group-text" id="basic-addon1">
                                    <a href="javascript:void(0);" onclick="dayplus();"><i class="la la-angle-up"></i></a>
                                    <a href="javascript:void(0);" onclick="dayminus();"><i class="la la-angle-down"></i></a>
                                </span>
                            </div>
                            <small id="expiryDateTxt">23-May-2023 11:59:59 PM IST</small>
                            <input type="hidden" id="expiryDate" name="expiryDate">
                            
                        </div>
                        <div class="mb-4 custom-checkbox">
                            <input type="checkbox" name="alertOneDyBfrExp" id="alertOneDyBfrExp" onchange="setalertOneDyBfrExp('alertOneDyBfrExp');" value="0"> <label>Alert 1 day before expiry</label>
                        </div>
                        <!--
                            <div class="mb-4">
                            <label>Auto reminder</label>
                        </div>
                        --->
                    </div>
                    <!--
                    <div class="w-100"></div>
                    
                    <div class="col-lg-6 col-12">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="input-number-wrap">
                                    <label>Remind Every</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" value="60" aria-label="Username" aria-describedby="basic-addon1">
                                        <span class="input-group-text" id="basic-addon1">
                                            <a href="javascript:void(0);"><i class="la la-angle-up"></i></a>
                                            <a href="javascript:void(0);"><i class="la la-angle-down"></i></a>
                                        </span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="input-number-wrap">
                                    <label>Max. reminders (Upto 5)</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" value="60" aria-label="Username" aria-describedby="basic-addon1">
                                        <span class="input-group-text" id="basic-addon1">
                                            <a href="javascript:void(0);"><i class="la la-angle-up"></i></a>
                                            <a href="javascript:void(0);"><i class="la la-angle-down"></i></a>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
              -->

                </div>
            </div>
        </div>

        
    </div>
</section>
</form>

<!-- Doc Preview Modal -->
<div class="modal fade" id="DocPreviewModal" tabindex="-1" aria-labelledby="DocPreviewModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="DocPreviewModalLabel"></h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div id="previewBox" class="previewBox"></div>
      </div>
      <div class="modal-footer">
        <!--<button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>-->
      </div>
    </div>
  </div>
</div>
<!-- Doc Preview Modal -->

</main>

<script>
    var TMPFILEEVENT;
    var COLORSINDXARR = <?php echo json_encode($COLORSINDXARR); ?>;
    var MONTHNAMESARR = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Nov", "Dec"];
    $(function(){
        setTimeout(function(){
            dayplus(1);
            
            $('[data-toggle="authtooltip"]').tooltip();
            $('[data-toggle="doctitletooltip"]').tooltip();
            
        },1000);
    });

    function browseFile(){
        $("#prefferedFormatTxt").removeClass("errorMsg");
        $("#fileupload").val("");
        $("#fileupload").trigger("click");
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

    function uploadFile(evt){
        $("#previewBox").html("");
        $(".file-upload-wrap").removeClass("errorBorder");

        var preLoadHtml = `<tr>
                                <td width="22px"><div class="animate-bg"></div></td>
                                <td width="70px"><div class="animate-bg col-sm-11" style="height: 60px; width: 55px;"></div></td>
                                <td width="375px">
                                    <h5><div class="animate-bg col-sm-11"></div></h5>
                                    <div class="animate-bg col-sm-5"></div>
                                </td>
                                <td width="375px" class="uploadFileMsg"><div class="animate-bg col-sm-5"></div></td>
                                <td class="fileActionBox">
                                    <a href="javascript:void(0);" class="file-preview-a"><div class="animate-bg col-sm-5"></div></a> 
                                    <a href="javascript:void(0);" class="file-remove-a"><div class="animate-bg col-sm-5"></div></a>
                                </td>
                            </tr>`;
        
        $("#uploadedFilesRowsBox").html(preLoadHtml);



        TMPFILEEVENT = evt;
        var file = evt.target.files[0]; // FileList object
        var fileName = file.name;
        
        if(file.type == "application/pdf"){
            var fileReader = new FileReader();  
            fileReader.onload = function() {
                var pdfData = new Uint8Array(this.result);

                // Using DocumentInitParameters object to load binary data.
                var loadingTask = pdfjsLib.getDocument({data: pdfData});
                loadingTask.promise.then(function(pdf) {
                    
                    var tmpDocPages = pdf._pdfInfo.numPages;
                    var elmId = randomStr();
                    
                        var rowHtml = '<tr id="rw_'+elmId+'">\
                            <td>1</td>\
                            <td width="70px"><img src="<?php echo base_url("assets/images/pdf-icon.png"); ?>" width="50px"></td>\
                            <td>\
                                <h5 class="no-padding"><b>'+fileName+'</b></h5>\
                                <p class="no-padding">'+tmpDocPages+' Pages</p>\
                            </td>\
                            <td class="uploadFileMsg">Upload Successfully</td>\
                            <td class="fileActionBox">\
                            <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#DocPreviewModal" class="file-preview-a">Preview</a>\
                            <a href="javascript:void(0);" onclick="removeUploadedFile(\'rw_'+elmId+'\');" class="file-remove-a"><i class="la la-trash-alt"></i> Remove</a>\
                            </td>\
                        </tr>';
                        
                        setTimeout(function(){$("#uploadedFilesRowsBox").html(rowHtml);},1000);

                }, function (reason) {
                    // PDF loading error
                    console.log("reason");
                    console.error(reason);
                    });
                };
                fileReader.readAsArrayBuffer(file);

                previewPdf();

        }else{
            $("#prefferedFormatTxt").addClass("errorMsg");
        }

    }

    
        const PDFStart = nameRoute => {
        
        let loadingTask = pdfjsLib.getDocument(nameRoute),
        pdfDoc = null,
        scale = 1.5,
        numPage = 1;
        
        const GeneratePDF = numPage => {
          
          var totalPages = pdfDoc.numPages;
          TOTALPDFPAGES = totalPages; //total no of pages

          pdfDoc.getPage(numPage).then(page => {
            
            let viewport = page.getViewport({scale:scale});
            
            var tmpPageNo = numPage;
            var tmpCanvas = document.createElement('canvas');
            tmpCanvas.id = "document_"+tmpPageNo;
            tmpCanvas.className = "document_canvas";
            tmpCanvas.width = viewport.width;
            tmpCanvas.height = viewport.height;
            
            $('<div>', {
                "id": "documentPageHolder_"+tmpPageNo,
                "class": "documentPageHolder",
                "style": "display: table; margin-bottom: 4px;",
                "html":tmpCanvas
            }).appendTo('#previewBox');
          
            
            
            var canvas = document.querySelector('#document_'+tmpPageNo);
            var ctx = canvas.getContext('2d');
            
            let renderContext = {
              canvasContext : ctx,
              viewport: viewport
            }
            
            page.render(renderContext); 
        
          })


          if(numPage < totalPages){
            
            var newPage = numPage + 1;
            GeneratePDF(newPage);  

          }
          
        }


        loadingTask.promise.then(pdfDoc_ => {
          pdfDoc = pdfDoc_;

          var totalPages = pdfDoc.numPages;
          
          if(totalPages > 0){ 
            GeneratePDF(numPage);
          }

        });


      }
		
      
      
    function previewPdf(){
        
        const startPdf = () => {
            var filett = TMPFILEEVENT.target.files[0]; // FileList object
		    $("#DocPreviewModalLabel").html(filett.name);
            if(filett.type == "application/pdf"){
                var fileReader = new FileReader();  
                fileReader.onload = function() {
                    var pdfData = new Uint8Array(this.result);
                    var pdfDataObj = {data:pdfData};
                    PDFStart(pdfDataObj);
                };
                
                fileReader.readAsArrayBuffer(filett);
            }
            
        }

        startPdf();

    }

    function removeUploadedFile(rwId){
        $("#fileupload").val("");
        $("#previewBox").html("");
        $("#"+rwId).remove();
    }

    function removeRecipientRow(rwId){
        $("#recipient_"+rwId).remove();
        $("#recipientSettingRow_"+rwId).remove();

        var totalRows = $(".recipientRow .srno .recipient-srno");
        $(totalRows).each(function(i,e){
            $(e).html(i+1);
        });

    }

    function addRecipient(){

            var totalRows = $(".recipientRow").length;

            if(totalRows < 50){
                var rcpntClr = COLORSINDXARR[totalRows];
                var newCount = totalRows + 1;
                var newRwId = Date.now();
                
                var rcpntHtml = '<tr id="recipient_'+newRwId+'" class="recipientRow">\
                    <td class="srno"><span class="recipient-srno" style="background-color:'+rcpntClr+';">'+newCount+'</span></td>\
                    <td>\
                        <input type="text" class="form-control RecipientName" name="RecipientName['+newRwId+']" placeholder="Recipient name*">\
                    </td>\
                    <td>\
                        <input type="text" class="form-control RecipientEmail" name="RecipientEmail['+newRwId+']" placeholder="Recipient email*">\
                    </td>\
                    <td>\
                        <div class="dropdown">\
                            <button disabled class="btn btn-outline-primary" type="button" data-bs-toggle="dropdown" aria-expanded="false">\
                            <i class="las la-signature"></i> Signer\
                            </button>\
                            <!--\
                                <button disabled class="btn btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">\
                                    Signer\
                                </button>\
                                <ul class="dropdown-menu">\
                                    <li><a class="dropdown-item" href="javascript:void(0);">Signer</a></li>\
                                    <li><a class="dropdown-item" href="javascript:void(0);">Reviewer</a></li>\
                                    <li><a class="dropdown-item" href="javascript:void(0);">In-Person Signer</a></li>\
                                </ul>\
                            -->\
                        </div>\
                    </td>\
                    <td>\
                        <a href="javascript:void(0);" onclick="removeRecipientRow('+newRwId+');"><i class="la la-trash-alt"></i></a>\
                    </td>\
                </tr>';
                
                var rcpntSttngHtml = '<!-- Recipient Settings -->\
                        <tr id="recipientSettingRow_'+newRwId+'" class="recipientSettingRow">\
                            <td></td>\
                            <td colspan="4">\
                                <a href="javascript:void(0);" id="recipientSettingBttn_'+newRwId+'" class="recipientSettingBttn" onclick="toggleOpt('+newRwId+');">Show Settings <i class="la la-angle-down"></i></a>\
                                <div id="recipientSettingOpt_'+newRwId+'" class="hideElement">\
                                <h5 class="mt-3 authHead"><b>Authentication </b><i data-placement="top" data-toggle="authtooltip" title="Recipients will be required to enter a system generated one time password or a sender specified access code to view and sign the document." class="las la-info-circle"></i>\
                                    <!--<div class="toggle-btn">-->\
                                    <input type="checkbox" id="recipientAuthBttn_'+newRwId+'" class="cb-value" onchange="toggleBttn('+newRwId+');" value="0"/>\
                                    <input type="hidden" class="recipientAuthInputBttn" name="recipientAuthInputBttn['+newRwId+']" id="recipientAuthInputBttn_'+newRwId+'" value="0"/>\
                                   <!-- <span class="round-btn"></span>\
                                </div>-->\
                                </h5>\
                                <div class="authenticateOptions" id="authenticateOptions_'+newRwId+'">\
                                <div class="mt-3">\
                                    <input id="accessOtpRadio_'+newRwId+'" type="radio" name="accessRadio_'+newRwId+'" onchange="authTyp('+newRwId+',1);">\
                                    <label for="accessOtpRadio_'+newRwId+'">Email OTP (One Time Password)</label>\
                                </div>\
                                <div class="mt-3">\
                                    <input id="accessCodeRadio_'+newRwId+'" type="radio" name="accessRadio_'+newRwId+'" onchange="authTyp('+newRwId+',2);">\
                                    <label for="accessCodeRadio_'+newRwId+'">Access Code</label>\
                                </div>\
                                <input type="hidden" class="accessCodeOtpOpt" name="accessCodeOtpOpt['+newRwId+']" id="accessCodeOtpVal_'+newRwId+'" value="0">\
                                <div class="accesscodeProperties" id="accesscodeProperties_'+newRwId+'">\
                                <div class="mt-3">\
                                    <input type="text" class="form-control accessCode" id="accessCode_'+newRwId+'" name="accessCode['+newRwId+']" placeholder="Access Code">\
                                    <span class="text-danger"><small>This field is required when access code is enabled</small></span>\
                                </div>\
                                <div>Note: You must communicate this access code to the recipient directly</div>\
                                </div>\
                                </div>\
                                <!--\
                                <h5 class="mt-4"><b>Private Message </b></h5>\
                                <div class="mt-3">\
                                    <textarea class="form-control pri-msg" rows="3"></textarea>\
                                </div>\
                                -->\
                                </div>\
                            </td>\
                        </tr>\
                        <!-- Recipient Settings -->';

                $("#recipientsContainer").append(rcpntHtml+rcpntSttngHtml);
                $('[data-toggle="authtooltip"]').tooltip();

            }else{
                var msg = "Maximum recipient count should be less than or equal to 50";
                var err = 1;
                showToastMsg(msg, err);
            }

        }

    
    function uploadProcess(){
       
        //submit form
       var fileuploadVal = $("#fileupload").val();

        if(isReal(fileuploadVal) == false){

            var msg = "Please upload the pdf document.";
            var err = 1;
            showToastMsg(msg, err);
            return false;

        }else{
            return true;
        }

    }

    function toggleOpt(rwNum){
        
        if($("#recipientSettingOpt_"+rwNum).hasClass("hideElement")){
            $("#recipientSettingOpt_"+rwNum).removeClass("hideElement");
            $("#recipientSettingBttn_"+rwNum).html('Hide settings <i class="la la-angle-up"></i>');
            
        }else{
            $("#recipientSettingOpt_"+rwNum).addClass("hideElement");
            $("#recipientSettingBttn_"+rwNum).html('Show settings <i class="la la-angle-down"></i>');
        }
    }

    function authTyp(cnt,vl){
        
        $("#accessCodeOtpVal_"+cnt).val(vl);
        
        if(vl == 2){
            $("#accesscodeProperties_"+cnt).show();
        }else{
            $("#accesscodeProperties_"+cnt).hide();
        }

        $("#authenticateOptions_"+cnt).removeClass("errorBorder");
        $("#authenticateOptions_"+cnt).removeClass("solidBorder");
    }

    function toggleBttn(rw){
        
        //var mainParent = $("#recipientAuthBttn_"+rw).parent('.toggle-btn');
        if($("#recipientAuthBttn_"+rw).is(':checked')) {
            //$(mainParent).removeClass('active');
            $("#authenticateOptions_"+rw).fadeIn("slow");
            $("#recipientAuthBttn_"+rw).val(1);
            $("#recipientAuthInputBttn_"+rw).val(1);
        } else {
            //$(mainParent).addClass('active');
            $("#authenticateOptions_"+rw).fadeOut("slow");
            $("#recipientAuthBttn_"+rw).val(0);
            $("#recipientAuthInputBttn_"+rw).val(0);
            $("#accessCodeOtpVal_"+rw).val(0);
        }

    }

    
    function dayplus(init){
        var days = $("#expiresInDays").val();
        days = parseInt(days);
        if(days < 180){
            
            if(!isReal(init)){
                days = days + 1;
            }
            
            $("#expiresInDays").val(days);

            var date = new Date(); // Now
            date.setDate(date.getDate() + days);
        
            var dtStr = (date.getDate()) +'-'+ MONTHNAMESARR[date.getMonth()] +'-'+ date.getFullYear()+' 11:59:59 PM';
            
            $("#expiryDateTxt").html(dtStr);
            $("#expiryDate").val(dtStr); 
        }
    }
    
    function dayminus(init){
        var days = $("#expiresInDays").val();
        days = parseInt(days);
        if(days > 1){
            if(!isReal(init)){
                days = days - 1;
            }
            
            $("#expiresInDays").val(days);

            var date = new Date(); // Now
            date.setDate(date.getDate() + days);
        
            var dtStr = (date.getDate()) +'-'+ MONTHNAMESARR[date.getMonth()] +'-'+ date.getFullYear()+' 11:59:59 PM';
            
            $("#expiryDateTxt").html(dtStr);
            $("#expiryDate").val(dtStr); 

        }
        
    }

    function setalertOneDyBfrExp(elmId){
        if($("#"+elmId).is(":checked")){
            $("#"+elmId).val(1);
        }else{
            $("#"+elmId).val(0);
        }
    }


    function next(){
        //validations
        var err = 0;
        var duplctEml = 0;
        var tmpEmailsArr = [];

        var fileuploadVal = $("#fileupload").val();

        if(!isReal(fileuploadVal)){
            err = 1;
            $(".file-upload-wrap").addClass("errorBorder");
        }
        
        $(".RecipientName").each(function(i,e){
            var tmpEVal = $(e).val();
            if(!isReal(tmpEVal)){
                err = 1;
                $(e).addClass("errorBorder");
                $(e).keyup(function(){
                    $(e).removeClass("errorBorder");
                });
            }
        });

        $(".RecipientEmail").each(function(i,e){
            var tmpEVal = $(e).val();
            if(!isReal(tmpEVal)){
                err = 1;
                $(e).addClass("errorBorder");
                $(e).keyup(function(){
                    $(e).removeClass("errorBorder");
                });
            }

            var emlIdx = tmpEmailsArr.indexOf(tmpEVal);
            if(emlIdx > -1){
                err = 1;
                duplctEml = 1;
                $(e).addClass("errorBorder");
                $(e).keyup(function(){
                    $(e).removeClass("errorBorder");
                });
            }else{
                tmpEmailsArr.push(tmpEVal);
            }
            
        });

        $(".recipientAuthInputBttn").each(function(i,e){
            var tmpEVal = $(e).val();
            var tmpEId = $(e).attr("id");
            var tmpEIdParts = tmpEId.split("_");
            var tmpElmUnqNum = tmpEIdParts[1];
            
           var tmpRcptAthInpt = $("#recipientAuthInputBttn_"+tmpElmUnqNum).val();
           var tmpAccssCodeOpt = $("#accessCodeOtpVal_"+tmpElmUnqNum).val();
           var tmpAccssCode = $("#accessCode_"+tmpElmUnqNum).val();

            if(tmpRcptAthInpt == 1){
                //Auth checked
                if(tmpAccssCodeOpt > 0){
                    
                    if(tmpAccssCodeOpt == 2 && !isReal(tmpAccssCode)){
                        //Empty Access Code
                        err = 1;
                        $("#accessCode_"+tmpElmUnqNum).addClass("errorBorder");
                        $("#accessCode_"+tmpElmUnqNum).keyup(function(){
                            $("#accessCode_"+tmpElmUnqNum).removeClass("errorBorder");
                        });

                        //open recipent settings
                        $("#recipientSettingOpt_"+tmpElmUnqNum).removeClass("hideElement");
                        $("#recipientSettingBttn_"+tmpElmUnqNum).html('Hide settings <i class="la la-angle-up"></i>');
                        console.log("if1:");
                    }

                }else{
                    //Not selected any option otp/access code
                    err = 1;
                    $("#authenticateOptions_"+tmpElmUnqNum).addClass("solidBorder");
                    $("#authenticateOptions_"+tmpElmUnqNum).addClass("errorBorder");
                    
                    //open recipent settings
                    $("#recipientSettingOpt_"+tmpElmUnqNum).removeClass("hideElement");
                    $("#recipientSettingBttn_"+tmpElmUnqNum).html('Hide settings <i class="la la-angle-up"></i>');
                    
                }
            }

            if(!isReal(tmpEVal)){
                err = 1;
                $(e).addClass("errorBorder");
                $(e).keyup(function(){
                    $(e).removeClass("errorBorder");
                });
            }
        });

        /*
        $(".accessCodeOtpOpt").each(function(i,e){

        });

        $(".accessCode").each(function(i,e){

        });
        */

        //validate Document
        var documentTitle = $("#documentTitle").val();
        var recipientMessage = $("#recipientMessage").val();
        if(!isReal(documentTitle)){
            $("#documentTitle").addClass("errorBorder");
            $("#documentTitle").keyup(function(){
                $("#documentTitle").removeClass("errorBorder");
            });
        }

        //validate document settings
        var expiresInDays = $("#expiresInDays").val();
        var expiryDate = $("#expiryDate").val();
        var alertOneDyBfrExp = $("#alertOneDyBfrExp").val();

        if(err > 0){
            
            var msg = "Please fill the required fields.";
            
            if(duplctEml > 0){
                msg = "Please fill the required fields and remove the duplicate emails.";
            }
            
            var errr = 1;
            showToastMsg(msg, errr);      
            return false;                                

        }else{
            return true;
        }

        /*
        var formData = $("#documentForm").serialize();
        console.log("formData:");
        console.log(formData);
        */
    }


</script>
<?php include("footer.php"); ?>