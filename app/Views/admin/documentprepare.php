<?php include('header.php'); 
  
?>

<link rel="stylesheet" type="text/css" href="<?php echo base_url("/assets/js/spectrum.min.css");?>">
<script>
/*
var FCPATH = "<?php //echo $_SERVER["DOCUMENT_ROOT"]; //FCPATH; ?>";
var SITEURL = "<?php //echo site_url(); ?>";
var BASEURL = "<?php //echo base_url(); ?>";
var SERVICEURL = "<?php //echo site_url(); ?>";
var CURRENTUSERINITIALS_1 = "<?php //echo $CURRENTUSERINITIALS_1; ?>";
var CURRENTUSERNAME_1 = "<?php //echo $CURRENTUSERNAME_1; ?>";
var CURRENTUSEREMAIL_1 = "<?php //echo $CURRENTUSEREMAIL_1; ?>";
var CURRENTUSERTAG_1 = "<?php //echo $CURRENTUSERTAG_1; ?>";
var CURRENTUSERCOLOR_1 = "<?php //echo $CURRENTUSERCOLOR_1; ?>";

var SELECTEDUSERS = <?php //echo json_encode($SELECTEDUSERS); ?>;
var SEPERATOR = '#DK#';	
*/
//var UPLOADEDFILE = BASEURL + '/userassets/uploads/samplepdf.pdf';
var UPLOADEDFILE = BASEURL + '<?php echo $document; ?>';

</script>
<script src="<?php echo base_url("/assets/js/pdf.min.js");?>"></script>
<script src="<?php echo base_url("/assets/js/spectrum.min.js");?>"></script>
<script src="<?php echo base_url("/assets/customjs/documentprepare.js");?>"></script>
<main>


      <div id="mainPageHeader" class="container-fluid" style="margin-bottom: 70px;">
          <input type="hidden" id="documentId" value="<?php echo $documentId; ?>">
        <div class="top-menu">
            <figure class="logo-wrap">
                <!--<span class="appName"><img src="<?php //echo base_url("/assets/images/boldsign_sitelogo.svg"); ?>" /></span>-->
                <span class="appName"><img src="<?php echo base_url("/assets/images/logocl.png"); ?>" /></span>
            </figure>
            <div class="">
              <span class="documentNameContainer conf-fields">Configure fields</span>
              <span class="documentNameContainer">Samplepdf.pdf</span>
            </div>
            <ul class="top-right-btns list-unstyled">
               
                  <li>
                    <button class="btn btn-primary">Preview</button>
                  </li>
                  <li>
                    <button class="btn btn-warning" onclick="extractAndSaveGElements();">Send</button>
                  </li>
                  <li>
                    <a class="btn-cross" href="javascript:void(0);">X</a>
                  </li>
               
              </ul>
        </div>
      
    </div>
   <div class="main-pdf-body">
      <div id="fieldsContainer">
        <h4 class="left-panel-title">Fields</h4>
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
            <!---->
            <!---<div style="border-bottom: 1px solid rgb(210, 215, 222); margin-top: 16px;"></div>--->
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
<?php include('footer.php'); ?>