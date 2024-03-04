<?php

include('header.php'); 

/*

Array
(
    [page_tilte] => Document Prepare
    [document] => /userassets/uploads/1673874254153097/1680260575625909.pdf
    [documentId] => 1680260575625909
    [fileName] => SSHA_VRPL.pdf
    [documentTitle] => SSHA VRPL AGREEMENT
    [recipients] => Array
        (
            [0] => Array
                (
                    [name] => Dinesh
                    [email] => upkit.dineshgiri@gmail.com
                )

            [1] => Array
                (
                    [name] => Rashika
                    [email] => upkit.rashikasapru@gmail.com
                )

            [2] => Array
                (
                    [name] => Kishan
                    [email] => upkit.pamposhdhar@gmail.com
                )

        )

)

$SELECTEDUSERS = array(
  array("name" => $CURRENTUSERNAME_1, "initials" => $CURRENTUSERINITIALS_1, "email" => $CURRENTUSEREMAIL_1, "tag" => $CURRENTUSERTAG_1, "color" => $CURRENTUSERCOLOR_1),
  array("name" => $CURRENTUSERNAME_2, "initials" => $CURRENTUSERINITIALS_2, "email" => $CURRENTUSEREMAIL_2, "tag" => $CURRENTUSERTAG_2, "color" => $CURRENTUSERCOLOR_2),
  array("name" => $CURRENTUSERNAME_3, "initials" => $CURRENTUSERINITIALS_3, "email" => $CURRENTUSEREMAIL_3, "tag" => $CURRENTUSERTAG_3, "color" => $CURRENTUSERCOLOR_3),
);
*/

?>

<link rel="stylesheet" type="text/css" href="<?php echo base_url("/assets/js/spectrum.min.css");?>">
<script>

var FCPATH = "<?php echo $_SERVER["DOCUMENT_ROOT"]; //FCPATH; ?>";
var SITEURL = "<?php echo site_url(); ?>";
var BASEURL = "<?php echo base_url(); ?>";
var SERVICEURL = "<?php echo site_url(); ?>";
/*var CURRENTUSERINITIALS_1 = "<?php //echo $CURRENTUSERINITIALS_1; ?>";
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
                <span class="appName"><img src="<?php echo base_url("/assets/images/logocl.png"); ?>" /></span>
            </figure>
            <div class="">
              <span class="documentNameContainer conf-fields">Configure fields <small>(Step 2/2)</small></span>
              <span class="documentNameContainer"><?php echo $fileName; ?></span>
            </div>
            <ul class="top-right-btns list-unstyled">
                  <!--
                  <li>
                    <button class="btn btn-primary">Preview</button>
                  </li>-->
                  <li>
                    <button class="btn btn-warning" id="sendBttn" onclick="extractAndSaveGElements();">Send</button>
                  </li>
                  <li>
                    <!--<a class="btn-cross" href="javascript:void(0);">X</a>-->
                    <!--<a class="btn btn-outline-warning" href="<?php //echo site_url("dashboard"); ?>">Back</a>-->
                    <a class="btn btn-outline-warning" href="<?php echo site_url("edit/".$documentId); ?>">Back</a>
                    
                  </li>
               
              </ul>
        </div>
      
    </div>
   <div class="main-pdf-body">
      <div id="fieldsContainer">
        <h4 class="left-panel-title">Fields</h4>
      <div class="bs-form-field-container" >
            <div class="bs-form-field-row">
      
            <div id="signature_parent" ondrag="grabDragElementId(this);" class="bs-form-field bs-form-left-field" aria-grabbed="true" draggable="true">
                <div class="bs-form-field-icon">
                  <span class="sf-icon-NeedstoSign">
                    <img class="fields-icon" src="<?php echo base_url("/assets/images/sign.png"); ?>" draggable="false"/>
                  </span>
                </div>
                <div class="bs-form-field-text">
                  <span>Signature</span>
                </div>
              </div>
            
              <div id="signaturein_parent" ondrag="grabDragElementId(this);" class="bs-form-field bs-form-right-field" aria-grabbed="true" draggable="true">
                <div class="bs-form-field-icon">
                  <span class="sf-icon-Initial">
                    <img class="fields-icon" src="<?php echo base_url("/assets/images/in.png"); ?>" draggable="false"/> 
                  </span>
                </div>
                <div class="bs-form-field-text">
                  <span>Initials</span>
                </div>
              </div>
            </div>
            <div class="bs-form-field-row">
              <div id="textbox_parent" ondrag="grabDragElementId(this);" class="bs-form-field bs-form-left-field" aria-grabbed="true" draggable="true">
                <div class="bs-form-field-icon">
                  <span class="sf-icon-TypeField">
                    <img class="fields-icon" src="<?php echo base_url("/assets/images/textbox.png"); ?>" draggable="false"/> 
                  </span>
                </div>
                <div class="bs-form-field-text">
                  <span>Textbox</span>
                </div>
              </div>

              <div id="label_parent" ondrag="grabDragElementId(this);" class="bs-form-field bs-form-right-field" aria-grabbed="true" draggable="true">
                <div class="bs-form-field-icon">
                  <span class="sf-icon-IncreaseFontSize">
                    <img class="fields-icon" src="<?php echo base_url("/assets/images/Label.png"); ?>" draggable="false"/> 
                  </span>
                </div>
                <div class="bs-form-field-text">
                  <span>Label</span>
                </div>
              </div>

            </div>
            <!--
            <div class="bs-form-field-row">
              <div id="checkbox_parent" ondrag="grabDragElementId(this);" class="bs-form-field bs-form-left-field" aria-grabbed="true" draggable="true">
                <div class="bs-form-field-icon">
                  <span class="sf-icon-Checkbox">
                    <img class="fields-icon" src="<?php //echo base_url("/assets/images/checkbox.png"); ?>" draggable="false"/> 
                  </span>
                </div>
                <div class="bs-form-field-text">
                  <span>Checkbox</span>
                </div>
              </div>
              <div id="radiobutton_parent" ondrag="grabDragElementId(this);" class="bs-form-field bs-form-right-field" aria-grabbed="true" draggable="true">
                <div class="bs-form-field-icon">
                  <span class="sf-icon-Radiobutton">
                    <img class="fields-icon" src="<?php //echo base_url("/assets/images/radio.png"); ?>" draggable="false"/> 
                  </span>
                </div>
                <div class="bs-form-field-text">
                  <span>Radio</span>
                </div>
              </div>
            </div>
            -->
            <div class="bs-form-field-row">
              <div id="name_parent" ondrag="grabDragElementId(this);" class="bs-form-field bs-form-left-field" aria-grabbed="true" draggable="true">
                <div class="bs-form-field-icon">
                  <span class="sf-icon-User">
                    <img class="fields-icon" src="<?php echo base_url("/assets/images/user.png"); ?>" draggable="false"/> 
                  </span>
                </div>
                <div class="bs-form-field-text">
                  <span>Name</span>
                </div>
              </div>
              <div id="email_parent" ondrag="grabDragElementId(this);" class="bs-form-field bs-form-right-field" aria-grabbed="true" draggable="true">
                <div class="bs-form-field-icon">
                  <span class="sf-icon-Mail">
                    <img class="fields-icon" src="<?php echo base_url("/assets/images/email.png"); ?>" draggable="false"/> 
                  </span>
                </div>
                <div class="bs-form-field-text">
                  <span>Email</span>
                </div>
              </div>
            </div>
            <div class="bs-form-field-row">
              <div id="editableDate_parent" ondrag="grabDragElementId(this);" class="bs-form-field bs-form-left-field" aria-grabbed="true" draggable="true">
                <div class="bs-form-field-icon">
                  <span class="sf-icon-editable-date">
                    <img class="fields-icon" src="<?php echo base_url("/assets/images/Date Editable.png"); ?>" draggable="false"/> 
                  </span>
                </div>
                <div class="bs-form-field-text">
                  <span>Editable Date</span>
                </div>
              </div>
              <div id="datepicker_parent" ondrag="grabDragElementId(this);" class="bs-form-field bs-form-right-field" aria-grabbed="true" draggable="true">
                <div class="bs-form-field-icon">
                  <span class="sf-icon-Calendar">
                    <img class="fields-icon" src="<?php echo base_url("/assets/images/Date.png"); ?>" draggable="false"/> 
                  </span>
                </div>
                <div class="bs-form-field-text">
                  <span>Date signed</span>
                </div>
              </div>
              <!--
              <div id="image_parent" ondrag="grabDragElementId(this);" class="bs-form-field bs-form-right-field" aria-grabbed="true" draggable="true">
                <div class="bs-form-field-icon">
                  <span class="cs-icon-Image">
                    <img class="fields-icon" src="<?php //echo base_url("/assets/images/Photo.png"); ?>" draggable="false"/> 
                  </span>
                </div>
                <div class="bs-form-field-text">
                  <span>Image</span>
                </div>
              </div>
              -->
            </div>
            <!---->
            <!---<div style="border-bottom: 1px solid rgb(210, 215, 222); margin-top: 16px;"></div>--->
            <div class="bs-form-field-row">
              
              <div id="hyperlink_parent" ondrag="grabDragElementId(this);" class="bs-form-field bs-form-left-field" aria-grabbed="true" draggable="true">
                <div class="bs-form-field-icon">
                  <span class="sf-icon-hyperlink">
                    <img class="fields-icon" src="<?php echo base_url("/assets/images/Link.png"); ?>" draggable="false"/> 
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

<div class="modal fade prepare-success-modal" id="prepare-success-modal" tabindex="-1" aria-hidden="true" data-bs-backdrop="static" data-bs-keyboard="false">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-body">
        <div class="content-box">
          <div class="templateHeader">
            <div style="margin-right: 24px;">
              <img src="<?php echo base_url("/assets/images/successtick.svg"); ?>" alt="" width="40" height="40">
            </div>
            <div class="text-content">Document has been sent successfully.</div>
          </div>
        </div>
      </div>
      <div class="modal-footer"> 
        <button type="button" class="btn btn-outline-primary" onclick="gotoDashboard();">Go to dashboard</button>
        <button type="button" class="btn btn-primary" onclick="createNewDocument();">Create new document</button>
      </div>
    </div>
  </div>
</div>

<?php include('footer.php'); ?>