<?php include("header.php"); ?>
<style>
.bs-signing-page .access-content-overlay, .bs-signing-page .sb-content-overlay {
    height: 100%;
    top: 0;
}

.access-content-overlay, .v-sb-content-overlay {
    background-color: #fff;
    border: none;
    border-radius: 8px;
    bottom: 0;
    margin: auto;
    min-width: 48px;
    top: 56px;
    z-index: 10000;
    display: block;
    position: absolute;
    left: 0;
    right: 0;
    width: 100%;
    height: calc(100% - 56px);
}

.bs-access-denied-document .bs-page-not-found-header {
    position: absolute;
    width: 250px;
    height: 60px;
    left: calc(50% - 140px);
    top: 0;
}

.bs-access-denied-document .bs-page-not-found-body {
    position: absolute;
    width: 100%;
    height: 260px;
    top: 60px;
    left: 0;
}

.bs-access-denied-document .bs-page-not-found-footer {
    position: absolute;
    width: auto;
    max-width: 755px;
    padding: 0 10px;
    height: 60px;
    top: 320px;
    left: 0;
    right: 0;
    margin: 0 auto;
}

.bs-access-denied-document .bs-page-not-found-footer span {
    display: inline-block;
    width: 100%;
}

.bs-page-not-found-footer {
    position: absolute;
    width: 290px;
    height: 60px;
    bottom: 100px;
    left: calc(50% - 145px);
    text-align: center;
}
    </style>

<main>
<div class="access-content-overlay" style="display: block;"><div class="bs-access-denied-document bs-page-not-found" style="width: 100%; height: calc(100% - 380px); position: absolute; top: calc(50% - 190px);"><div class="bs-page-not-found-header"><img src="<?php echo base_url('/assets/images/logocl.png'); ?>" alt="BoldSign Logo" style="width: 100%; height: 100%;"></div> <div class="bs-page-not-found-body"><img src="https://static.boldsign.com/202303291303-106469/img/190ef6d.svg" style="width: 240px; height: 180px; position: absolute; left: calc(50% - 120px); top: calc(50% - 90px);"></div> <div class="bs-page-not-found-footer"><span class="e-control" style="height: 30px; font-weight: bold; font-size: 20px; color: rgb(51, 56, 66);">
Access denied
</span> <span class="e-control" style="height: 20px; font-size: 14px; margin-top: 10px; color: rgb(51, 56, 66);">
The provided document id seems to be invalid
</span></div></div></div>
</main>    
<?php include("footer.php"); ?>