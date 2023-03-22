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
                    <button class="btn btn-primary">Next</button>
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
                                <button class="browseBttn btn btn-outline-primary" onclick="browseFile();">Browse</button>
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
                        <td>1</td>
                        <td>
                            <input type="text" class="form-control" placeholder="Recipient name*">
                        </td>
                        <td>
                            <input type="text" class="form-control" placeholder="Recipient email*">
                        </td>
                        <td>
                            <div class="dropdown">
                                <button disabled class="btn btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    Signer
                                </button>
                                <ul class="dropdown-menu">
                                    <li><a class="dropdown-item" href="javascript:void(0);">Signer</a></li>
                                    <li><a class="dropdown-item" href="javascript:void(0);">Reviewer</a></li>
                                    <li><a class="dropdown-item" href="javascript:void(0);">In-Person Signer</a></li>
                                </ul>
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
                            <h5 class="mt-3"><b>Authentication </b>
                                <!--<div class="toggle-btn">-->
                                    <input type="checkbox" id="recipientAuthBttn_1" class="cb-value" onclick="toggleBttn(1);"/>
                                   <!-- <span class="round-btn"></span> 
                                </div>-->
                            </h5>
                            <div class="mt-3">
                                <input id="accessOtpRadio_1" type="radio" name="accessRadio" onchange="authTyp(1,1);">
                                <label for="accessOtpRadio_1">Email OTP (One Time Password)</label>
                            </div>
                            <div class="mt-3">
                                <input id="accessCodeRadio_1" type="radio" name="accessRadio" onchange="authTyp(1,2);">
                                <label for="accessCodeRadio_1">Access Code</label>
                            </div>
                            <input type="hidden" id="accessCodeOtpVal_1" value="0">
                            <div class="mt-3">
                                <input type="text" class="form-control" placeholder="Access Code">
                                <span class="text-danger"><small>This field is required when access code is enabled</small></span>
                            </div>
                            <div>Note: You must communicate this access code to the recipient directly</div>
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
                            <button class="add-recipient-bttn w-100 btn btn-outline-primary" onclick="addRecipient();">+ Add Recipient</button>        
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
                        <label>Title <a href="#"><i class="la la-info-circle"></i></a></label>
                        <input type="text" class="form-control" placeholder="Enter document title*">
                    </div>
                    <div class="col-12 mb-4">
                        <label>Message</label>
                        <textarea class="form-control" rows="3" placeholder="Enter message for all recipients"></textarea>
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
                        <div class="mb-4">
                            <label>Tags</label>
                            <textarea rows="3" class="form-control"></textarea>
                        </div>
                        <div class="mb-4">
                            <p>Allow Signers to re-assign</p>
                        </div>
                        <div class="mb-4 input-number-wrap">
                            <label>Expires in</label>

                            <div class="input-group">
                                <input type="text" class="form-control" value="60" aria-label="Username" aria-describedby="basic-addon1">
                                <span class="input-group-text" id="basic-addon1">
                                    <a href="#"><i class="la la-angle-up"></i></a>
                                    <a href="#"><i class="la la-angle-down"></i></a>
                                </span>
                            </div>
                            <small>23-May-2023 11:59:59 PM IST</small>
                            
                        </div>
                        <div class="mb-4 custom-checkbox">
                            <input type="checkbox"> <label>Alert 1 day before expiry</label>
                        </div>
                        <div class="mb-4">
                            <label>Auto reminder</label>
                        </div>
                    </div>

                    <div class="w-100"></div>
                    
                    <div class="col-lg-6 col-12">
                        <div class="row">
                            <div class="col-lg-6">
                                <div class="input-number-wrap">
                                    <label>Remind Every</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" value="60" aria-label="Username" aria-describedby="basic-addon1">
                                        <span class="input-group-text" id="basic-addon1">
                                            <a href="#"><i class="la la-angle-up"></i></a>
                                            <a href="#"><i class="la la-angle-down"></i></a>
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
                                            <a href="#"><i class="la la-angle-up"></i></a>
                                            <a href="#"><i class="la la-angle-down"></i></a>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        
    </div>
</section>
</main>

<script>

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

        var file = evt.target.files[0]; // FileList object
        
        console.log("file:");
        console.log(file);

        var fileName = file.name;
        
        var pdfjsLib = window['pdfjs-dist/build/pdf'];
        pdfjsLib.GlobalWorkerOptions.workerSrc = '<?php echo base_url("/assets/js/pdf.worker.js"); ?>';

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
                                <h5><b>'+fileName+'</b></h5>\
                                <p>'+tmpDocPages+' Pages</p>\
                            </td>\
                            <td class="uploadFileMsg">Upload Successfully</td>\
                            <td class="fileActionBox">\
                                <a href="javascript:void(0);" onclick="removeUploadedFile(\'rw_'+elmId+'\');" class="file-remove-a"><i class="la la-trash-alt"></i> Remove</a>\
                            </td>\
                        </tr>';
                        
                        $("#uploadedFilesRowsBox").html(rowHtml);

                }, function (reason) {
                    // PDF loading error
                    //console.log("reason");
                    //console.error(reason);
                    });
                };
                fileReader.readAsArrayBuffer(file);
        }else{
            $("#prefferedFormatTxt").addClass("errorMsg");
        }



      /* //Create thumbnail
        var file = evt.target.files[0]; // FileList object

        console.log("file:");
        console.log(file);
        var scale = 0.5;
        previewPdf(evt, scale);
        
        if(file.type == "application/pdf"){
            var fileReader = new FileReader();  
            fileReader.onload = function() {
                var pdfData = new Uint8Array(this.result);
                console.log("pdfData:");
                console.log(pdfData);
                
            };
            fileReader.readAsArrayBuffer(file);
        }
     */
    }

    function previewPdf(e, scale){
       // Loaded via <script> tag, create shortcut to access PDF.js exports.
        var pdfjsLib = window['pdfjs-dist/build/pdf'];
        // The workerSrc property shall be specified.
        pdfjsLib.GlobalWorkerOptions.workerSrc = '<?php echo base_url("/assets/js/pdf.worker.js"); ?>';

       // $("#myPdf").on("change", function(e){
            var file = e.target.files[0]
            if(file.type == "application/pdf"){
                var fileReader = new FileReader();  
                fileReader.onload = function() {
                    var pdfData = new Uint8Array(this.result);
                    // Using DocumentInitParameters object to load binary data.
                    var loadingTask = pdfjsLib.getDocument({data: pdfData});
                    loadingTask.promise.then(function(pdf) {
                    console.log('PDF loaded');
                    
                    // Fetch the first page
                    var pageNumber = 1;
                    pdf.getPage(pageNumber).then(function(page) {
                        console.log('Page loaded');
                        
                        //var scale = 1.5;
                       // var scale = 0.5;
                        var viewport = page.getViewport({scale: scale});

                        // Prepare canvas using PDF page dimensions
                        var canvas = $("#pdfViewer")[0];
                        var context = canvas.getContext('2d');
                        canvas.height = viewport.height;
                        canvas.width = viewport.width;

                        // Render PDF page into canvas context
                        var renderContext = {
                        canvasContext: context,
                        viewport: viewport
                        };
                        var renderTask = page.render(renderContext);
                        renderTask.promise.then(function () {
                        console.log('Page rendered');
                        });
                    });
                    }, function (reason) {
                    // PDF loading error
                    console.error(reason);
                    });
                };
                fileReader.readAsArrayBuffer(file);
            }
        //});
    }

    function removeUploadedFile(rwId){
        $("#"+rwId).remove();
    }

    function removeRecipientRow(rwId){
        $("#recipient_"+rwId).remove();
        $("#recipientSettingRow_"+rwId).remove();
    }

    function addRecipient(){
        var totalRows = $(".recipientRow").length;
        var newCount = totalRows + 1;
            
        var rcpntHtml = '<tr id="recipient_'+newCount+'" class="recipientRow">\
                <td>'+newCount+'</td>\
                <td>\
                    <input type="text" class="form-control" placeholder="Recipient name*">\
                </td>\
                <td>\
                    <input type="text" class="form-control" placeholder="Recipient email*">\
                </td>\
                <td>\
                    <div class="dropdown">\
                        <button disabled class="btn btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">\
                            Signer\
                        </button>\
                        <ul class="dropdown-menu">\
                            <li><a class="dropdown-item" href="javascript:void(0);">Signer</a></li>\
                            <li><a class="dropdown-item" href="javascript:void(0);">Reviewer</a></li>\
                            <li><a class="dropdown-item" href="javascript:void(0);">In-Person Signer</a></li>\
                        </ul>\
                    </div>\
                </td>\
                <td>\
                    <a href="javascript:void(0);" onclick="removeRecipientRow('+newCount+');"><i class="la la-trash-alt"></i></a>\
                </td>\
            </tr>';
    
            var rcpntSttngHtml = '<!-- Recipient Settings -->\
                    <tr id="recipientSettingRow_'+newCount+'" class="recipientSettingRow">\
                        <td></td>\
                        <td colspan="4">\
                            <a href="javascript:void(0);" id="recipientSettingBttn_'+newCount+'" class="recipientSettingBttn" onclick="toggleOpt('+newCount+');">Show Settings <i class="la la-angle-down"></i></a>\
                            \
                            <div id="recipientSettingOpt_'+newCount+'" class="hideElement">\
                            <h5 class="mt-3"><b>Authentication </b></h5>\
                            <div class="mt-3">\
                                <input id="accessOtpRadio_'+newCount+'" type="radio" name="accessRadio" onchange="authTyp('+newCount+',1);">\
                                <label for="accessOtpRadio_'+newCount+'">Email OTP (One Time Password)</label>\
                            </div>\
                            <div class="mt-3">\
                                <input id="accessCodeRadio_'+newCount+'" type="radio" name="accessRadio" onchange="authTyp('+newCount+',2);">\
                                <label for="accessCodeRadio_'+newCount+'">Access Code</label>\
                            </div>\
                            <input type="hidden" id="accessCodeOtpVal_'+newCount+'" value="0">\
                            <div class="mt-3">\
                                <input type="text" class="form-control" placeholder="Access Code">\
                                <span class="text-danger"><small>This field is required when access code is enabled</small></span>\
                            </div>\
                            <div>Note: You must communicate this access code to the recipient directly</div>\
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
    }


    //toggleBttn(rw){recipientAuthBttn_1}

    function toggleBttn(rw){
        //rw

        //var mainParent = $("#recipientAuthBttn_"+rw).parent('.toggle-btn');
        if($("#recipientAuthBttn_"+rw).is(':checked')) {
            //$(mainParent).addClass('active');
            $("#recipientAuthBttn_"+rw).val(0);
        } else {
            //$(mainParent).removeClass('active');
            $("#recipientAuthBttn_"+rw).val(1);
        }

        /*
        $('.cb-value').click(function() {
            var mainParent = $(this).parent('.toggle-btn');
            if($(mainParent).find('input.cb-value').is(':checked')) {
                $(mainParent).addClass('active');
            } else {
                $(mainParent).removeClass('active');
            }

            })
        */
    }

</script>
<?php include("footer.php"); ?>