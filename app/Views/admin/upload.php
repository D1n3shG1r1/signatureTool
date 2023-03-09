<?php include("header.php"); ?>

<script src="<?php echo base_url("/assets/js/pdf.js"); ?>"></script>

<div id="fileUploadMainContainer" class="fileUploadMainContainer">
    <form action="<?php echo base_url("fileupload"); ?>" method="POST" onsubmit="return uploadProcess();" enctype="multipart/form-data">
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

<script>

    function browseFile(){
        $("#fileupload").val("");
        $("#fileupload").trigger("click");
    }

    function uploadFile(evt){
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
     
    }

    function previewPdf(e, scale){
       // Loaded via <script> tag, create shortcut to access PDF.js exports.
        var pdfjsLib = window['pdfjs-dist/build/pdf'];
        // The workerSrc property shall be specified.
        //pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://mozilla.github.io/pdf.js/build/pdf.worker.js';
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



</script>
<?php include("footer.php"); ?>