<?php 
  $COLORSARR = array(
    "blue"=>"#007bff",
    "indigo"=>"#6610f2",
    "purple"=>"#6f42c1",
    "pink"=>"#e83e8c",
    "red"=>"#dc3545",
    "orange"=>"#fd7e14",
    "yellow"=>"#ffc107",
    "green"=>"#28a745",
    "teal"=>"#20c997",
    "cyan"=>"#17a2b8",
    "white"=>"#fff",
    "gray"=>"#6c757d",
    "gray-dark"=>"#343a40",
    "primary"=>"#007bff",
    "secondary"=>"#6c757d",
    "success"=>"#28a745",
    "info"=>"#17a2b8",
    "warning"=>"#ffc107",
    "danger"=>"#dc3545",
    "light"=>"#f8f9fa",
    "dark"=>"#343a40",
	"night-blue"=>"#151B54",
	"silk-blue"=>"#488AC7",
	"blue-angel"=>"#B7CEEC",
	"lavender-blue"=>"#E3E4FA",
	"electric-blue"=>"#9AFEFF",	
	"tiffany-blue"=>"#81D8D0",
	"sea-turtle-green"=>"#438D80",
	"olive"=>"#808000",
	"green-leaves"=>"#3A5F0B",	
	"moss-green"=>"#8A9A5B",	
	"acid-green"=>"#B0BF1A",	
	"organic-brown"=>"#E3F9A6",	
	"parchment"=>"#FFFFC2",	
	"mint-cream"=>"#F5FFFA",	
	"champagne"=>"#F7E7CE",	
	"coral-peach"=>"#FBD5AB", 
	"macaroni-and-cheese"=>"#F2BB66",	
	"sage"=>"#BCB88A",	
	"caramel"=>"#C68E17",	
	"dark-beige"=>"#9F8C76",	
	"old-burgundy"=>"#43302E",	
	"coral-brown"=>"#9E4638",
	"tomato"=>"#FF6347",	
	"purple-lily"=>"#550A35",	
	"tulip-pink"=>"#C25A7C",	
	"pink-plum"=>"#B93B8F",	
	"blush"=>"#FFE6E8",	
	"chocolate"=>"#D2691E",	
	"metallic-green"=>"#7C9D8E",
	"papaya-whip" => "#FFEFD5"
	);

	$COLORSINDXARR = array("#C0C0C0","#C9C0BB","#E5E4E2","#98AFC7","#737CA1","#728FCE","#87AFC7","#87CEFA","#B7CEEC","#BDEDFF","#B0E0E6","#E3E4FA","#EBF4FA","#CCFFFF","#8EEBEC","#66CDAA","#40E0D0","#3B9C9C","#848B79","#6AA121","#8A9A5B","#73A16C","#99C68E","#9CB071","#98FF98","#E3F9A6","#C2E5D3","#E8F1D4","#FFFFC2","#F5F5DC","#F7E7CE","#FAEBD7","#FFE4B5","#FBE7A1","#E8E4C9","#F2BB66","#C19A6B","#E6BF83","#C2B280","#BCB88A","#B5A642","#D4A017","#AB784E","#835C3B","#A0522D","#C34A2C","#F9966B","#F98B88","#7F525D","#E8ADAA");

	
$SELECTEDUSERS = array();
if(!$recipients){
	$recipients = array();
}
if(!empty($recipients)){
	foreach($recipients as $k=> $tmpRcp){
  
		$tag = $tmpRcp["name"];
		if(strtolower($tmpRcp["email"]) == strtolower($loginEmail)){
		  $tag = "Me";
		}
	  
		$SELECTEDUSERS[] = array(
		  "name" => $tmpRcp["name"],
		  "initials" => ucwords($tmpRcp["name"]),
		  "email" => $tmpRcp["email"],
		  "tag" => ucfirst($tag),
		  "color" => $COLORSINDXARR[$k]
		);
	}
}

  //$hostUrl = "http://localhost/esigntool/";
  if(!empty($SELECTEDUSERS)){
	$CURRENTUSERNAME_1 = $SELECTEDUSERS[0]["name"];
	$CURRENTUSERINITIALS_1 = $SELECTEDUSERS[0]["initials"];
	$CURRENTUSEREMAIL_1 = $SELECTEDUSERS[0]["email"];
	$CURRENTUSERTAG_1 = $SELECTEDUSERS[0]["tag"];
	$CURRENTUSERCOLOR_1 = $SELECTEDUSERS[0]["color"];
  }else{
	$CURRENTUSERNAME_1 = '';
	$CURRENTUSERINITIALS_1 = '';
	$CURRENTUSEREMAIL_1 = '';
	$CURRENTUSERTAG_1 = '';
	$CURRENTUSERCOLOR_1 = '';
  }
  
/*
  $CURRENTUSERNAME_2 = "Kishan Rathore";
  $CURRENTUSERINITIALS_2 = "KR";
  $CURRENTUSEREMAIL_2 = "upkit.rashikasapru@gmail.com";
  $CURRENTUSERTAG_2 = "Kishan Rathore";
  $CURRENTUSERCOLOR_2 = $COLORSARR["pink"];

  $CURRENTUSERNAME_3 = "Pamposh Dhar";
  $CURRENTUSERINITIALS_3 = "PD";
  $CURRENTUSEREMAIL_3 = "upkit.pamposhdhar@gmail.com";
  $CURRENTUSERTAG_3 = "Pamposh";
  $CURRENTUSERCOLOR_3 = $COLORSARR["orange"];
*/
  /*$SELECTEDUSERS = array(
    array("name" => $CURRENTUSERNAME_1, "initials" => $CURRENTUSERINITIALS_1, "email" => $CURRENTUSEREMAIL_1, "tag" => $CURRENTUSERTAG_1, "color" => $CURRENTUSERCOLOR_1),
    array("name" => $CURRENTUSERNAME_2, "initials" => $CURRENTUSERINITIALS_2, "email" => $CURRENTUSEREMAIL_2, "tag" => $CURRENTUSERTAG_2, "color" => $CURRENTUSERCOLOR_2),
    array("name" => $CURRENTUSERNAME_3, "initials" => $CURRENTUSERINITIALS_3, "email" => $CURRENTUSEREMAIL_3, "tag" => $CURRENTUSERTAG_3, "color" => $CURRENTUSERCOLOR_3),
  );*/

 //echo "<pre>"; print_r($_SERVER["DOCUMENT_ROOT"]); die;
 ?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8"></meta>
		<meta name="viewport" content="width=device-width, initial-scale=1.0"></meta>
		<title><?php //echo $page_title; ?></title>

		<style>
			
			@font-face {
			   font-family: CourierPrime-Regular;
			   src: url("<?php echo base_url('/assets/fonts/Courier_Prime/CourierPrime-Regular.ttf');?>");
			}

			@font-face {
			   font-family: NotoSans-Regular;
			   src: url("<?php echo base_url('/assets/fonts/Noto_Sans/NotoSans-Regular.ttf');?>");
			}

			@font-face {
			   font-family: Times-New-Roman;
			   src: url("<?php echo base_url('/assets/fonts/Times_New_Roman/Times-New-Roman.ttf');?>");
			}

			@font-face {
			   font-family: Helvetica;
			   src: url("<?php echo base_url('/assets/fonts/Helvetica_Font/Helvetica.ttf');?>");
			}

			@font-face {
			   font-family: Great-Vibes;
			   src: url("<?php echo base_url('/assets/fonts/Great_Vibes/GreatVibes-Regular.ttf');?>");
			}

			@font-face {
			   font-family: Dancing-Script;
			   src: url("<?php echo base_url('/assets/fonts/Dancing_Script/DancingScript-VariableFont_wght.ttf');?>");
			}

			@font-face {
			   font-family: Allura;
			   src: url("<?php echo base_url('/assets/fonts/Allura/Allura-Regular.ttf');?>");
			}

			@font-face {
			   font-family: Sacramento;
			   src: url("<?php echo base_url('/assets/fonts/Sacramento/Sacramento-Regular.ttf');?>");
			}
			
		</style>
		<link rel="stylesheet" type="text/css" href="<?php echo base_url("/assets/css/bootstrap.min.css");?>">
		<link rel="stylesheet" type="text/css" href="<?php echo base_url("/assets/js/jquery-ui.css");?>">
		<link rel="stylesheet" type="text/css" href="<?php echo base_url("/assets/css/style.css");?>">
		<link rel="stylesheet" href="https://maxst.icons8.com/vue-static/landings/line-awesome/line-awesome/1.3.0/css/line-awesome.min.css">
		
		<script src="<?php echo base_url("/assets/js/jquery.min.js");?>"></script>
		<script src="<?php echo base_url("/assets/js/jquery-ui.js");?>"></script>
		<script src="<?php echo base_url("/assets/js/jquery-ui.js");?>"></script>
		<script src="<?php echo base_url("/assets/js/jquery.slimscroll.js");?>"></script>
		
		<script src="<?php echo base_url("/assets/js/bootstrap.bundle.min.js");?>"></script>
		
		
		<script>
		var FCPATH = "<?php echo $_SERVER["DOCUMENT_ROOT"]; //FCPATH; ?>";
		var SITEURL = "<?php echo site_url(); ?>";
		var BASEURL = "<?php echo base_url(); ?>";
		var SERVICEURL = "<?php echo site_url(); ?>";
		var CURRENTUSERINITIALS_1 = "<?php echo $CURRENTUSERINITIALS_1; ?>";
		var CURRENTUSERNAME_1 = "<?php echo $CURRENTUSERNAME_1; ?>";
		var CURRENTUSEREMAIL_1 = "<?php echo $CURRENTUSEREMAIL_1; ?>";
		var CURRENTUSERTAG_1 = "<?php echo $CURRENTUSERTAG_1; ?>";
		var CURRENTUSERCOLOR_1 = "<?php echo $CURRENTUSERCOLOR_1; ?>";

		var SELECTEDUSERS = <?php echo json_encode($SELECTEDUSERS); ?>;
		var SEPERATOR = '#DK#';	
		</script>
		
		<script src="<?php echo base_url("/assets/customjs/config.js");?>"></script>
	</head>
	<body>
		<header>
			<!--
			<nav class="navbar navbar-expand-lg navbar-light bg-light">
				<div class="container">
					<a class="navbar-brand" href="#">Navbar</a>
					<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
					<span class="navbar-toggler-icon"></span>
					</button>
					<div class="collapse navbar-collapse" id="navbarNav">
					<ul class="navbar-nav">
						<li class="nav-item">
							<a class="nav-link active" aria-current="page" href="<?php echo base_url('/'); ?>">Home</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="<?php echo base_url('signin'); ?>">Sign In</a>
						</li>
						<li class="nav-item">
							<a class="nav-link" href="<?php echo base_url('signup'); ?>">Sign UP</a>
						</li>
					</ul>
					</div>
				</div>
			</nav>
		-->
		</header>
		<div class="main-container">