<?php include('header.php'); 

  $signerDataId = $signersData["id"];
  $parentDocument = $signersData["parentDocument"];
  $signerDocumentId = $signersData["documentId"];
  $signerEmail = $signersData["signerEmail"];
  $signerName = $signersData["signerName"];
  $signerId = $signersData["signerId"];
  $internalUser = $signersData["internalUser"];
  $document_status = $signersData["document_status"];
  $document_data = $signersData["document_data"];
  $accessCode = $signersData["accessCode"];
  $accessCodeMedia = $signersData["accessCodeMedia"];
  $documentExpiry = $signersData["documentExpiry"];
  $documentExpired = $signersData["documentExpired"];
  $lastReminder = $signersData["lastReminder"];
  $documentSentDate = $signersData["documentSentDate"];
  $created_at = $signersData["created_at"];
  $updated_at = $signersData["updated_at"];
?>

<script>

var UPLOADEDFILE = BASEURL + '<?php echo $document; ?>';
var DOCUMENTDATA = <?php echo  $document_data; ?>;

var SIGNERNAME_GLB = "<?php echo $signerName; ?>";
var SIGNEREMAIL_GLB = "<?php echo $signerEmail; ?>";
var SIGNERID_GLB = "<?php echo $signerId; ?>";

</script>
<script src="<?php echo base_url("/assets/js/pdf.min.js");?>"></script>
<script src="<?php echo base_url("/assets/js/html2canvas.js");?>"></script>
<script src="<?php echo base_url("/assets/js/signature_pad.min.js");?>"></script>

<script src="<?php echo base_url("assets/cropper-js/cropper.min.js"); ?>" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<link rel="stylesheet" href="<?php echo base_url("assets/css/signpage.css"); ?>" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="<?php echo base_url("assets/cropper-js/cropper.css"); ?>" crossorigin="anonymous" referrerpolicy="no-referrer" />
<script src="<?php echo base_url("assets/cropper-js/cropper.js"); ?>" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/cropper/2.3.4/cropper.js" crossorigin="anonymous" referrerpolicy="no-referrer"></script>-->

<script src="<?php echo base_url("/assets/customjs/documentsign.js");?>"></script>
<script>
$(function(){
  
  setTimeout(function(){
    $("#termsModal").modal("show");
  }, 500);
});  

function acceptTerms(elmId){
  if($("#"+elmId).is(":checked")){
    $("#continueBttn").removeClass("button-disabled");
    $("#continueBttn").addClass("btn-primary");
    $("#continueBttn").removeClass("btn-outline-primary");
  }else{
    $("#continueBttn").addClass("button-disabled");
    $("#continueBttn").addClass("btn-outline-primary");
    $("#continueBttn").removeClass("btn-primary");
  }
}
</script>
<main>

    <div id="mainPageHeader" class="container-fluid" style="margin-bottom: 70px;">
          <input type="hidden" id="documentId" value="<?php echo $documentId; ?>">
          <input type="hidden" id="signerDocumentId" value="<?php echo $signerDocumentId; ?>">
          <input type="hidden" id="fullsignbs64" value="">
          <input type="hidden" id="initsignbs64" value="">
          <input type="hidden" id="signType" value="">
          
          
        <div class="top-menu">
            <figure class="logo-wrap">
                <span class="appName"><img src="<?php echo base_url("/assets/images/logocl.png"); ?>" /></span>
            </figure>
            <div class="">
              <span class="documentNameContainer conf-fields" style="display:none;">Configure fields</span>
              <span class="documentNameContainer">Samplepdf.pdf</span>
            </div>
            <ul class="top-right-btns list-unstyled">
                  
                  <!---
                  <li>
                    <button class="btn btn-primary">Preview</button>
                  </li>
                  --->
                  <li>
                    <button class="btn btn-primary" onclick="startSigning();">Start signing</button>
                  </li>
                  
                  <li>
                    <!--<button class="button-disabled" onclick="processSign();">Continue</button>-->
                    <button id="finishBttn" class="finishBttn btn btn-warning" onclick="processSign();">Finish</button>
                  </li>
                  
               
              </ul>
        </div>
      
    </div>

    <div id="mainPdfBody" class="main-pdf-body">
     <div id="fieldsContainer">
        <!--
        <div class="bs-form-field-container" >
            <div class="bs-form-field-row">
              <div id="signature_parent" onclick="addElement(this);" class="bs-form-field bs-form-left-field" aria-grabbed="true">
                <div class="bs-form-field-icon">
                  <span class="sf-icon-NeedstoSign">
                    <img class="fields-icon" src="<?php echo base_url("/assets/images/sign.png"); ?>" />
                  </span>
                </div>
                <div class="bs-form-field-text">
                  <span>Signature</span>
                </div>
              </div>
              <div id="signaturein_parent" onclick="addElement(this);" class="bs-form-field bs-form-right-field">
                <div class="bs-form-field-icon">
                  <span class="sf-icon-Initial">
                    <img class="fields-icon" src="<?php echo base_url("/assets/images/in.png"); ?>" /> 
                  </span>
                </div>
                <div class="bs-form-field-text">
                  <span>Initials</span>
                </div>
              </div>
            </div>
            <div class="bs-form-field-row">
              <div id="textbox_parent" onclick="addElement(this);" class="bs-form-field bs-form-left-field" aria-grabbed="false">
                <div class="bs-form-field-icon">
                  <span class="sf-icon-TypeField">
                    <img class="fields-icon" src="<?php echo base_url("/assets/images/textbox.png"); ?>" /> 
                  </span>
                </div>
                <div class="bs-form-field-text">
                  <span>Textbox</span>
                </div>
              </div>
              <div id="datepicker_parent" onclick="addElement(this);" class="bs-form-field bs-form-right-field">
                <div class="bs-form-field-icon">
                  <span class="sf-icon-Calendar">
                    <img class="fields-icon" src="<?php echo base_url("/assets/images/Date.png"); ?>" /> 
                  </span>
                </div>
                <div class="bs-form-field-text">
                  <span>Date signed</span>
                </div>
              </div>
            </div>
            <div class="bs-form-field-row">
              <div id="checkbox_parent" onclick="addElement(this);" class="bs-form-field bs-form-left-field">
                <div class="bs-form-field-icon">
                  <span class="sf-icon-Checkbox">
                    <img class="fields-icon" src="<?php echo base_url("/assets/images/checkbox.png"); ?>" /> 
                  </span>
                </div>
                <div class="bs-form-field-text">
                  <span>Checkbox</span>
                </div>
              </div>
              <div id="radiobutton_parent" onclick="addElement(this);" class="bs-form-field bs-form-right-field">
                <div class="bs-form-field-icon">
                  <span class="sf-icon-Radiobutton">
                    <img class="fields-icon" src="<?php echo base_url("/assets/images/radio.png"); ?>" /> 
                  </span>
                </div>
                <div class="bs-form-field-text">
                  <span>Radio</span>
                </div>
              </div>
            </div>
            <div class="bs-form-field-row">
              <div id="name_parent" onclick="addElement(this);" class="bs-form-field bs-form-left-field">
                <div class="bs-form-field-icon">
                  <span class="sf-icon-User">
                    <img class="fields-icon" src="<?php echo base_url("/assets/images/user.png"); ?>" /> 
                  </span>
                </div>
                <div class="bs-form-field-text">
                  <span>Name</span>
                </div>
              </div>
              <div id="email_parent" onclick="addElement(this);" class="bs-form-field bs-form-right-field">
                <div class="bs-form-field-icon">
                  <span class="sf-icon-Mail">
                    <img class="fields-icon" src="<?php echo base_url("/assets/images/email.png"); ?>" /> 
                  </span>
                </div>
                <div class="bs-form-field-text">
                  <span>Email</span>
                </div>
              </div>
            </div>
            <div class="bs-form-field-row">
              <div id="editableDate_parent" onclick="addElement(this);" class="bs-form-field bs-form-left-field">
                <div class="bs-form-field-icon">
                  <span class="sf-icon-editable-date">
                    <img class="fields-icon" src="<?php echo base_url("/assets/images/Date Editable.png"); ?>" /> 
                  </span>
                </div>
                <div class="bs-form-field-text">
                  <span>Editable Date</span>
                </div>
              </div>
              <div id="image_parent" onclick="addElement(this);" class="bs-form-field bs-form-right-field">
                <div class="bs-form-field-icon">
                  <span class="cs-icon-Image">
                    <img class="fields-icon" src="<?php echo base_url("/assets/images/Photo.png"); ?>" /> 
                  </span>
                </div>
                <div class="bs-form-field-text">
                  <span>Image</span>
                </div>
              </div>
            </div>
            <div class="bs-form-field-row">
              <div id="label_parent" onclick="addElement(this);" class="bs-form-field bs-form-left-field">
                <div class="bs-form-field-icon">
                  <span class="sf-icon-IncreaseFontSize">
                    <img class="fields-icon" src="<?php echo base_url("/assets/images/Label.png"); ?>" /> 
                  </span>
                </div>
                <div class="bs-form-field-text">
                  <span>Label</span>
                </div>
              </div>
              <div id="hyperlink_parent" onclick="addElement(this);" class="bs-form-field bs-form-right-field">
                <div class="bs-form-field-icon">
                  <span class="sf-icon-hyperlink">
                    <img class="fields-icon" src="<?php echo base_url("/assets/images/Link.png"); ?>" /> 
                  </span>
                </div>
                <div class="bs-form-field-text">
                  <span>Hyperlink</span>
                </div>
              </div>
            </div>
          </div>
        -->
    </div>

    <div id="pdfContainer">
        <div id="pdfContainerHolder"></div>
    </div>

    <div id="settingsContainer">
      <div id="bs-thumbnail-prepare">
        <h3>Thumbnails</h3>
        <h5></h5>
        <div class="thumbnailsBox"></div>
      </div>
      <div id="Advance-fields" class="textColor"></div>
    </div>

  </div>
  
</main>


<!--- Terms of use --->
<div class="modal fade terms-modal" id="termsModal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-body">
        <div class="terms-box col-10">
            <span class="terms-tick-box-span"><input type="checkbox" class="terms-tick-box" id="terms-tick-box" onchange="acceptTerms('terms-tick-box');"></span>
            <span for="terms-tick-box">I have read and agree to the <a href="javascript:void(0);">Electronic Signature Disclosure Terms</a> and <a href="javascript:void(0);">ScipSign's Terms of Use</a></span>
          </div>
          <div class="action-box col-2">
            <button id="continueBttn" class="continueBttn btn btn-outline-primary button-disabled" data-bs-dismiss="modal">Continue</button>
          </div>
        </div>
    </div>
  </div>
</div>
<!--- /- Terms of use --->

<!--- Signature pad --->
<div class="modal fade sign-modal" id="signModal" tabindex="-1" aria-labelledby="signModalLabel" aria-hidden="true">
  <div class="modal-dialog  modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="signModalLabel">Signature</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">


        <!-- tabs start -->
         <ul class="nav nav-pills mb-3 sign-tabs" id="pills-tab" role="tablist">
            <li class="nav-item" role="presentation">
              <button class="nav-link active" id="pills-type-tab" data-bs-toggle="pill" data-bs-target="#pills-type" type="button" role="tab" aria-controls="pills-type" aria-selected="true" onclick="typeSignBttn();">Type</button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link" id="pills-draw-tab" data-bs-toggle="pill" data-bs-target="#pills-draw" type="button" role="tab" aria-controls="pills-draw" aria-selected="false" onclick="drawSignBttn();">Draw</button>
            </li>
            <li class="nav-item" role="presentation">
              <button class="nav-link" id="pills-upload-tab" data-bs-toggle="pill" data-bs-target="#pills-upload" type="button" role="tab" aria-controls="pills-upload" aria-selected="false" onclick="uploadSignBttn();">Upload</button>
            </li>
          </ul>


          <!-- tab-content start here -->

          <div class="tab-content" id="pills-tabContent">
            
            <div class="tab-pane fade show active" id="pills-type" role="tabpanel" aria-labelledby="pills-type-tab" tabindex="0">
                <div class="row">
                  <div class="col-12">
                      <div class="form-group name-input">
                          <label>Your Name</label>
                          <input id="signInput" type="text" placeholder="Enter Name..." value="<?php echo $signerName; ?>" maxlength="55" onpaste="typeSign(this,event);"; onkeypress="allowAlphabetsOnly(event);" onkeyup="typeSign(this,event);" class="form-control">
                      </div>
                  </div>
                  <div class="w-100 mt-4"></div>
                  <div class="col-lg-6">
                      <div class="pre-signwrap activesign" onclick="choseSignStyle(this);">
                          <span id="selectedSign" class="signValue font-1"><?php echo $signerName; ?></span>
                          <div class="check-mark la la-check"></div>
                      </div>
                  </div>
                  <div class="col-lg-6">
                      <div class="pre-signwrap" onclick="choseSignStyle(this);">
                          <span class="signValue font-2"><?php echo $signerName; ?></span>
                          <div class="check-mark la"></div>
                      </div>
                  </div>
                  <div class="col-lg-6 mb-0">
                      <div class="pre-signwrap" onclick="choseSignStyle(this);">
                          <span class="signValue font-3"><?php echo $signerName; ?></span>
                          <div class="check-mark la"></div>
                      </div>
                  </div>
                  <div class="col-lg-6 mb-0">
                      <div class="pre-signwrap" onclick="choseSignStyle(this);">
                          <span class="signValue font-4"><?php echo $signerName; ?></span>
                          <div class="check-mark la"></div>
                      </div>
                  </div>
                </div>
            </div>

            <div class="tab-pane fade" id="pills-draw" role="tabpanel" aria-labelledby="pills-draw-tab" tabindex="0">

               <div class="btn-clear-wrap">
                <button id="clear-signature" class="clear-signature">Clear</button></div>
                <div class="custom-sign-wrap canvas-border">
                  <span>
                    <canvas id="signatureCanvas" width="616px" height="283px"></canvas>
                  </span>
                </div>
            </div>

            
            <div class="tab-pane fade" id="pills-upload" role="tabpanel" aria-labelledby="pills-upload-tab" tabindex="0">
                <div class="btn-clear-wrap cropControls"> 
                <button id="crop-signature" class="btn-crop la la-crop" onclick="cropSign();"></button> 
                 
                <button id="remove-signature" class="remove-signature" onclick="removeSign();">Remove</button>
                </div>
                
                <div id="dragDropContainer" class="upload-sign-wrap canvas-border">
                <input type="file" name="file" id="file" onchange="fileUploadProcess(event);" accept="image/png, image/jpeg, image/bmp" style="height:1px; width:1px; opacity:0; position:absolute; left:-1000px">
                  <div class="uploadContentBox upload-area" id="uploadfile">
                   <span id="dragDropHere">Drop signature files here...</span>
                   <span>
                    <button class="btn btn-outline-primary" id="browseSignBttn" onclick="uploadSign();">Browse</button>
                   </span>
                   <span>Supported formats: PNG, JPG, BMP</span>
                  </div>
                  <div class="afterBeforeCorners"></div>
                </div>
                
                <div id="signImgContainer" class="signImgContainer upload-sign-wrap canvas-border">
                  <img id="signImg" class="signImg"> 
                   <!--
                    <div id="cropBox" class="cropBox">
                      <img id="cropBoxImg">
                   </div>
                  -->


                  <div class="afterBeforeCorners"></div>
                </div>
                
                <div class="zoomControls cropControls">
                    <div class="row align-items-center">
                        <div class="col-lg-4">
                            <button id="zoomButton" onclick="showZoomSlider();"  class="btn-zoom la la-search-plus"></button>
                            <button id="rotateButton" onclick="showRotateSlider();" class="btn-rotate la la-sync-alt"></button>
                            <span class="img-action-label" id="sliderLabel"></span>
                        </div>
                        <div class="col-lg-8">
                            <div id="zoomslider" class="rangeSlider"></div>
                            <div id="rotateslider" class="rangeSlider"></div>  
                        </div>
                    </div>
                  </div>
            </div>
          </div>

          <!-- tabs end -->
      </div>
      <div class="modal-footer position-relative">
        <div class="sign-disclaimer-box col-12">  
          <p class="sign-disclaimer">I understand that this is a legal representation of my signature</p>
        </div>
        <div class="col-12">
          <div class="color-listing">
            <button class="btn" onclick="openColorPallet();"><i class="la la-pencil"></i></button>
            <ul id="colorList" class="list-unstyled m-0" style="display:none">
                <li>
                    <a href="javascript:void(0);" class="circle-red pencolor" onclick="setSignColor(this,'red');"></a>
                </li>
                <li>
                    <a href="javascript:void(0);" class="circle-green pencolor" onclick="setSignColor(this,'green');"></a>
                </li>
                <li>
                    <a href="javascript:void(0);" class="circle-blue pencolor" onclick="setSignColor(this,'blue');"></a>
                </li>
                <li>
                    <a href="javascript:void(0);" class="circle-black pencolor" onclick="setSignColor(this,'black');"></a>
                </li>
            </ul>
         </div>
         <button id="signUseBttn" type="button" class="btn btn-primary" style="float:right;" onclick="createTypeToSign();">Accept & Use</button>
         <button type="button" class="btn btn-secondary"  style="float:right; margin-right: 10px;" data-bs-dismiss="modal">Cancel</button>
         
        </div>
      </div>
    </div>
  </div>
</div>
  <!--- /-Signature pad --->

  <!--- Document signed modal --->
  
<div class="modal fade sign-success-modal" id="sign-success-modal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-body">
        <div class="content-box">
          <div class="templateHeader">
            <div style="margin-right: 24px;">
              <img src="<?php echo base_url("/assets/images/successtick.svg"); ?>" alt="" width="40" height="40">
            </div>
            <div class="text-content">Document has been downloaded successfully.</div>
          </div>
        </div>
      </div>
      <div class="modal-footer"> 
        <button type="button" id="dwnld-bttn" class="btn btn-primary" onclick="downloadDocument();">Download</button>
      </div>
    </div>
  </div>
</div>
<!--- / Document signed modal --->

<?php include('footer.php'); ?>