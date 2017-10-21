<?php error_reporting("E^NOTICE");
include_once 'includes/session.php';
include_once("config/accessConfig.php");
 
$intLocationID=$_SESSION["loc"];
$lh_id=$_REQUEST['lhid'];

$sql = mysql_query("select delivery , togo , open_thu from locations where  id =".$_SESSION["loc"]);
$value= mysql_fetch_array($sql);
$delivery = $value['delivery'];
$togo = $value['togo'];
 if($_SESSION['togo'] == "")
 {
 	$_SESSION['togo'] = "Yes";
 }
 if($_SESSION['delivery'] == "")
 {
 	$_SESSION['delivery'] = "Yes";
 }
 
    
$restaurantDropDown = "display: block;";
$restaurantHead = "active";
$restaurantMenu9 = "active";
function server_get($emp)
{
	$sql =mysql_query("select CONCAT(first_name,' ',last_name) as name from employees where id =".$emp);
	$value = mysql_fetch_array($sql);
	return $value['name'];
	
}

$location_id = $_SESSION['loc'];
$rd = $_SESSION["employee_id"];
$sql = "SELECT DISTINCT(payment_type) FROM location_payments WHERE location_id = '$location_id'";
$res = mysql_query($sql);
//$rows = mysql_fetch_array($res);
$sql12="select check_number,employee_id,order_status from client_orders where id=".$_REQUEST['id'];
	
	$res12 = mysql_query($sql12);
	while($val = mysql_fetch_array($res12))
	{
		$check_number = $val['check_number'];
		$emp = $val['employee_id'];
		$server = server_get($emp);
		
	}
$res_emp = mysql_fetch_array(mysql_query("select pos_update_other_server,Allow_discount,Allow_adjustment from employees where id=".$_SESSION['employee_id'].""));
$update_other_server = $res_emp['pos_update_other_server'];	
$Allow_discount = $res_emp['Allow_discount'];	
$Allow_adjustment = $res_emp['Allow_adjustment'];	
?>


<!DOCTYPE html>
<html><head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title><?php echo $_SESSION['SITE_TITLE']; ?></title>
<link rel="stylesheet" href="themes/pickadate.01.default.css">
<link rel="stylesheet" href="css/style.default.css" type="text/css" />
<link rel="stylesheet" href="css/responsive-tables.css">
<link rel="stylesheet" href="lib/themes/default.css" id="theme_base">
<link rel="stylesheet" href="lib/themes/default.date.css" id="theme_date">
<link rel="stylesheet" href="lib/themes/default.time.css" id="theme_time">
<link rel="stylesheet" href="css/restaurant.css">

<link rel="stylesheet" href="css/pos_point.css" type="text/css" />
<link rel="stylesheet" href="css/tabcontent.css"  type="text/css"/>

<script type="text/javascript">
	var location_id = "<?=$_SESSION['loc']?>";
	var created_by = "<?=$_SESSION['employee_id']?>";
	var employee_id = created_by;
	var table_id = "<?=$_GET['tableid']?>";
	var pos_update_other_server1 = "<?=$_SESSION['pos_update_other_server']; ?>";
	var global_podeoutroserver1 = true;
	if(pos_update_other_server1=='no'){
		global_podeoutroserver1 = false;
	}
</script>

<script type="text/javascript" src="js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="js/jquery-migrate-1.1.1.min.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.9.2.min.js"></script>
<script type="text/javascript" src="js/flot/jquery.flot.min.js"></script>
<script type="text/javascript" src="js/flot/jquery.flot.resize.min.js"></script>
<script type="text/javascript" src="js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="js/jquery.cookie.js"></script>
<script type="text/javascript" src="js/jquery.form.js"></script>
<script type="text/javascript" src="js/jquery.datetimepicker.js"></script>
<!--<script type="text/javascript" src="js/chosen.jquery.min.js"></script>
<script type="text/javascript" src="js/jquery.tagsinput.min.js"></script>
<script type="text/javascript" src="js/jquery.autogrow-textarea.js"></script>
<script type="text/javascript" src="js/chosen.jquery.min.js"></script>
-->
<script type="text/javascript" src="js/jquery.jgrowl.js"></script>
<script type="text/javascript" src="js/jquery.alerts.js"></script>


<script type="text/javascript" src="js/jquery-ui-1.8.16.custom.min.js"></script>
<script type="text/javascript" src="js/plugins/columnizer/jquery.columnizer.js"></script>
<script type="text/javascript" src="js/jquery-ui.min.js" ></script>

<script type="text/javascript" src="../internalaccess/url.js"></script>

<script type="text/javascript" src="<?=HTTPP;?>maps.google.com/maps/api/js?sensor=false"></script>
<script  type="text/javascript" src="js/token.js"></script>
<script src="js/order.js"></script>
<script type="text/javascript" src="js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/modernizr.min.js"></script>
<script type="text/javascript" src="js/responsive-tables.js"></script>
<script type="text/javascript" src="js/custom.js"></script>
<script src="js/dragscrollable.js"></script>
<script src="js/scrollsync.js"></script>
<script src="js/seat.js"></script>
<script src="js/client.js"></script>
<script src="js/payments.js"></script>
<script src="js/functions2.js"></script>
<!--[if lte IE 8]><script language="javascript" type="text/javascript" src="js/excanvas.min.js"></script><![endif]-->
<script type="text/javascript" src="js/elements.js"></script>
<script type="text/javascript" src="prettify/prettify.js"></script>
<script type="text/javascript" src="js/ui.spinner.min.js"></script>
<script type="text/javascript" src="js/webcam/webcam.js"></script>
<script type="text/javascript" src="mtjmsr.js"></script>
<script type="text/javascript" src="js/simpleautocomplete/js/simpleAutoComplete.js"></script>
<script type="text/javascript">
jQuery(".loading-image").show();
	setTimeout(function()
	{ 
		jQuery(".loading-image").hide();		
	},6500);
	var myVar;
	var emp_login_id = <?php echo $rd;?> ;
	var update_other_server = '<?php echo $update_other_server;?>';
	//alert(update_other_server);
	GLOBAL_url=API; 
	var $ = jQuery;
	var defsize=4;
	var pay=3;	
	var Allow_discount= '<?php echo $Allow_discount;?>';	
	var Allow_adjustment = '<?php echo $Allow_adjustment;?>';
	
	
</script>
<script type="text/javascript" src="js/validation.js"></script>

<script src="js/attendance.js"></script>

<script src="js/togles.js"></script>
<script src="js/menuandorder.js"></script> 
<script src="api/api-functions.js"></script>
<script src="lib/picker.js"></script>
<script src="lib/picker.date.js"></script>
<script src="lib/picker.time.js"></script>
<script src="lib/legacy.js"></script>
<script src="js/main_pos.js"></script>
<script src="source/pickadate.js"></script>

<script src="js/tabcontent.js" type="text/javascript"/></script>

<style>
.orngridmenu li img{
	height:111px;
	width:112px;
}
.searchbtn { width:8% !important; height:28px; padding:0px; float:left; background:url(./images/icons/search.png) center center no-repeat;  left:0;  cursor:pointer; border:none;}
.removebtm { width:5%; height:10%;  background:url(./images/searchbtn.png) center center no-repeat; position:absolute;  top:9px;  border:none; background-size:20px 20px;}
.searchboxinner { width:100% !important; height:28px; float:left; background:#fff;border-color:#ccc;border-style:solid;border-width:1px;}
.all_button{
	margin-top:5px;
	width:67px;
	padding:6px 0px;
}
.contgroup{
	border:2px solid #376193;
	margin-bottom:0.5%;
}
@media screen and (max-width: 1024px) {
	.all_button{
	margin-top:5px;
	width:55px;
	padding:10px 5px;
}
}
.clientdetail{ line-height:18px;}
.addclient-greybar{border-bottom:1px solid #DDDDDD; width:100% !important;}
.clientname { color:#0866C6; float:left; font-weight:bold;	}
.clientadr{ color:#000000; float:left;}
.clientaction{ margin-top:22px;}
#mymodal_htmladd, #mymodal_html333{ padding:0px 15px;}
.clientaction{padding-top:0% !important; float:left;}
.ui-tabs-panel{ padding:15px 15px 0;}
</style>

<!--for popo point-->

<script src="js/lightbox-form.js" type="text/javascript"></script>

<!--end pos point-->


<script>

function resizeDiv() {
			vpw = jQuery(window).width();
			vph = jQuery(window).height();
			vph = vph - 280; //340
			
			vresdiv =jQuery(window).height();
			vresdiv = vresdiv - 331; // 420
			
			jQuery(".tabbedwidget").css({"height": vph + "px"});
			jQuery("#e-7,#e-8,#e-9").css({"height": vresdiv + "px"});
			//jQuery("#general,#description").css({"overflow-y": "scroll"});
			var desheight = jQuery("#d-7").height();
				if(desheight > vresdiv){
					jQuery("#e-7").css({"overflow-y": "scroll"});
				}
				else{
					jQuery("#e-7").css({"overflow-y": "hidden"});
				}
			
			var desheight1 = jQuery("#d-8").height();
			console.log(desheight1+'==>'+vph);
				if(desheight1 > vresdiv){
					console.log('yes');
					jQuery("#e-8").css({"overflow-y": "scroll"});
				}
				else{
					jQuery("#e-8").css({"overflow-y": "hidden"});
				}
			var desheight2 = jQuery("#d-9").height();
			console.log(desheight2+'==>'+vph);
				if(desheight2 > vresdiv){
					console.log('yes');
					jQuery("#e-9").css({"overflow-y": "scroll"});
				}
				else{
					jQuery("#e-9").css({"overflow-y": "hidden"});
				}
				
				setTimeout(function(){
					resizeDiv();
				},5000);
				
			}
		
		function resizeDiv2() {
			vpw = jQuery(window).width();
			vph = jQuery(window).height();
			var btn_hei = jQuery("#buttonsmid").height();			
			vph = vph - 326; //340
			vph = eval(vph) - eval(btn_hei);
			
			vresdiv =jQuery(window).height();
			
			vresdiv = vresdiv - 390; //350			
			
			
			jQuery("#resdiv").css({"height": vph + "px"});
			jQuery(".widgetcontent").css({"height": vresdiv + "px"});
			//jQuery("#resdiv").css({"overflow-y": "scroll"});
			jQuery(".widgetcontent").css({"border": "0"});
			
			var desheight = jQuery("#pop_wrap").height();
				if(desheight > vph){
					jQuery("#resdiv").css({"overflow-y": "scroll"});
				}
				else{
					jQuery("#resdiv").css({"overflow-y": "auto"});
				}
			
		}
		window.onresize = function(event) {
			resizeDiv();
			resizeDiv2();
		}
jQuery(document).ready(function() {
			
				setTimeout(function(){
					resizeDiv();
					resizeDiv2();
				},1000);
	});

    var vtotalzao=0;
	var str_location_current_time = 0;
	var delivery="N";
	function getrestofapi(){
		var d = new Date(); 
		 
		return "&created_on=BusinessPanel&token="+generatetoken()+"&tmok="+d.getTime();
		
	}
	  
	function transformurl(url){

	  return 	url+getrestofapi();
	}
	  
	 function check_add(){
	 	jQuery("#added_item").show();
		jQuery("#order_item").hide();
	 }
	 function check_ord(){
	 	jQuery("#order_item").show();
		jQuery("#added_item").hide();
	 }
	function replace_dropdown_value (dropdown, textfield)
	{ 
		
		validaforneworder();
		//alert(textfield);
		//return false;
		//document.getElementById(textfield).value='';
		//if (document.getElementById(dropdown).value != ''){
		if(document.getElementById(dropdown).selectedIndex >= 0){
			var st = jQuery("#"+dropdown).find('option:selected').text();
			jQuery("#"+textfield).val(st);
		}
		/*if(document.getElementById(dropdown).selectedIndex >= 0){
		  document.getElementById(textfield).value=document.getElementById(dropdown).options[document.getElementById(dropdown).selectedIndex].text;
		}*/
	}
    
</script>


<script type="text/javascript">
  
  var vtotalzao=0;
  jQuery(function() { 
    jQuery( "ul.droptrue" ).sortable({
      connectWith: "ul"
    });
 
    jQuery( "ul.dropfalse" ).sortable({
      connectWith: "ul",
      dropOnEmpty: false
    });
 
    jQuery( "#sortable1, #sortable2, #sortable3" ).disableSelection();
	 
	
     jQuery("#sortable1, #sortable2, #sortable3" ).mouseover(function() {
         conrfire();
		       });
  });
  </script>
<script type="text/javascript">
	var tab_index=0;
	var isiPadnow = navigator.userAgent.match(/iPad/i) != null;
	function tabSettings(tabno)
	{
		tab_index=tabno;
	}

	var  global_p_time=timenow();
	setTimeout(function()
	{ 
		diftm(global_p_time);		
	},3000);
	var  global_p_time="";
	
	function diftm()
	{ 
			var a = global_p_time ;
		  	var re=''; 
		    if (a == ""){
		  		re='';
		    }else {
			   
				var b=timenow();
				var aa1=a.split(":");
				var aa2=b.split(":");
				var aukjix=aa2;
				aa2=aukjix[0];
				var cntss=0;
				var palaux='';
				var plreal=aa2;
				var sg=0; 


				for (var sg2=0;sg2<aa2.length;sg2++){
					if (aa2[sg2] == ' '){
						 
							palaux='';
						 
					}else{
					  palaux=palaux+aa2[sg2];
					}
				}

				plreal=palaux;

				aa2=plreal;     
				var sfjhklm = new Array();
				    sfjhklm[0]=aa2;
					sfjhklm[1]=aukjix[1];
					sfjhklm[2]=aukjix[2];
					aa2=sfjhklm;
					if (aa2[2].indexOf('pm')>0){
						aa2[0]=parseFloat(aa2[0])+12;
				        var skjm=aa2[2];
						skjm=skjm.split(' ');
						aa2[2]=skjm;
					}else if (aa2[2].indexOf('am')>0){
						var skjm=aa2[2];
						skjm=skjm.split(' ');
						aa2[2]=skjm;
					} 
					
				var d1=new Date(parseInt("2001",10),(parseInt("01",10))-1,parseInt("01",10),parseInt(aa1[0],10),parseInt(aa1[1],10),parseInt(aa1[2],10));
				var d2=new Date(parseInt("2001",10),(parseInt("01",10))-1,parseInt("01",10),parseInt(aa2[0],10),parseInt(aa2[1],10),parseInt(aa2[2],10));
				 
				  
				var con = get_time_difference(d1,d2); 
				re=con.duration;
  			} 
		    var v_start = $("#ck_starttime").html();
		    var v_eend = $("#ck_endtime").html();
		     
		    if (v_eend != ""){ 
				re = diftam(v_eend,v_start);  
		    }
  
	  	$("#ck_elapsedtime").html(re);	
			setTimeout(function(){
	  			diftm();
			},1000);
  	} 
  
  	$(window).resize(function () {
		wHeight = $(window).height();
		wwidth = $(window).width();
		bx = wHeight - 120;
			bx = 600;
		if(wHeight == 411 && wwidth == 1109)
		{		
			jQuery('.itemstblerow_col5').css('width','12%');
			jQuery('.itemstblerow_col2').css('width','65%');		
		}
		else
		{
			jQuery('.itemstblerow_col5').css('width','8%');
			jQuery('.itemstblerow_col2').css('width','69%');		
		}
	});
	
	// jQuery("#divmainwrapper").append("<div id='opaque' style='display: none;'></div>");
	// jQuery("#payment_tab").click(function () {
	
	// });
	
	jQuery("#btnClose1").click(function(){
		jQuery("#client_modal_interface").hide();
		jQuery("#opaque").hide();
	});

	jQuery("#client_name").blur(function(){
		jQuery('#searchfldaddclient').val(jQuery('#client_name').val());
		loadclient();
		jQuery('#imgClient').click();	
	});
	
	jQuery("#client_phone").blur(function(){
		jQuery('#searchfldaddclient').val(jQuery('#client_phone').val());
		loadclient();
		jQuery('#imgClient').click();
	});
	
	jQuery('#txtAddressCountry_autocomplete').typeahead({
		source: function (query, process) {
			return jQuery.ajax({
				url: 'ajax_get_country_with_id.php',
				type: 'post',
				data: { query: query,  autoCompleteClassName:'autocomplete',
				selectedClassName:'sel',
				attrCallBack:'rel',
				identifier:'estado'},
				dataType: 'json',
				success: function (result) {
					var resultList = result.map(function (item) {			
						var aItem = { id: item.id, name: item.label };
						return JSON.stringify(aItem);
					});		
					return process(resultList);
				}
			});
		},

		matcher: function (obj) {
			var item = JSON.parse(obj);
			return ~item.name.toLowerCase().indexOf(this.query.toLowerCase())
		},

		sorter: function (items) {          
		   var beginswith = [], caseSensitive = [], caseInsensitive = [], item;
			while (aItem = items.shift()) {
				var item = JSON.parse(aItem);
				if (!item.name.toLowerCase().indexOf(this.query.toLowerCase())) beginswith.push(JSON.stringify(item));
				else if (~item.name.indexOf(this.query)) caseSensitive.push(JSON.stringify(item));
				else caseInsensitive.push(JSON.stringify(item));
			}

			return beginswith.concat(caseSensitive, caseInsensitive)

		},

		highlighter: function (obj) {
			var item = JSON.parse(obj);
			var query = this.query.replace(/[\-\[\]{}()*+?.,\\\^$|#\s]/g, '\\$&')
			var locvalue=item.name.replace(new RegExp('(' + query + ')', 'ig'), function ($1, match) {
				return '<strong>' + match + '</strong>'
			})
			return locvalue;
		},

		updater: function (obj) {
			var item = JSON.parse(obj);
			var location = jQuery('#ddAddressCountry').attr('value', item.id);
			jQuery('#txtAddressCountry_autocomplete').attr('value', item.name);
			GetStateList(item.id);
			return item.name;
		}
	});

	jQuery("#slider4").slider({
		range: "min",
		value: 48,
		min: 1,
		max: 100,
		slide: function( event, ui ) {
			jQuery("#amount4").text(ui.value);
		}
	});
	
	jQuery("#amount4").text(jQuery("#slider4").slider("value"));

	var $tabs = jQuery('.tabbedwidget').tabs({
        activate: function (event, ui) {
			selected = ui.newTab.context.id;
           	tabSettings(selected);
        }
    });

	var selected = $tabs.tabs('option', 'active');
	tabSettings(selected);
	
	<?php if($_REQUEST['id'] != "" ) {?>
		jQuery("#item_open").addClass("ui-state-default ui-corner-top ui-tabs-selected ui-state-active");
	<?php } ?>
		
    jQuery('#global_tbl,#global_tbl2,#global_tbl3').dataTable({
        "sPaginationType": "full_numbers",
        "aaSorting": [[ 2, "asc" ]],
        "bJQuery": true,
        "fnDrawCallback": function(oSettings) {
            // jQuery.uniform.update();
        }
    });
		
	jQuery("#readybtnid").click(function(){ 
		jQuery("#cover,#table,#server,#server1").hide();
		jQuery(".del_info,#rt").hide();
		jQuery("#readybtnid").css("opacity",1);
		jQuery("#deliverybtnid").css("opacity",0.5);
		jQuery("#parcelbtnid").css("opacity",0.5);
		jQuery("#tblbtnid").css("opacity",0.5);
	});

	jQuery("#deliverybtnid").click(function(){ 
		jQuery("#table,#server,#server1").hide();
		jQuery("#cover,#rt,.del_info").show();
		jQuery("#deliverybtnid").css("opacity",1);
		jQuery("#tblbtnid").css("opacity",0.5);
		jQuery("#parcelbtnid").css("opacity",0.5);
		jQuery("#readybtnid").css("opacity",0.5);
	});

	jQuery("#parcelbtnid").click(function(){ 
		jQuery("#table,#server,.del_info,#server1").hide();
		jQuery("#cover,#rt").show();
		jQuery("#parcelbtnid").css("opacity",1);
		jQuery("#tblbtnid").css("opacity",0.5);
		jQuery("#deliverybtnid").css("opacity",0.5);
		jQuery("#readybtnid").css("opacity",0.5);
	});

	jQuery("#tblbtnid").click(function(){ 
		jQuery(".del_info,#rt").hide();
		jQuery("#cover,#table,#server,#server1").show();
		jQuery("#tblbtnid").css("opacity",1);
		jQuery("#parcelbtnid").css("opacity",0.5);
		jQuery("#deliverybtnid").css("opacity",0.5);
		jQuery("#readybtnid").css("opacity",0.5);	
	});
	
	jQuery("#sale_detail_btn").click(function(){ 
		jQuery("#sale_detail").show();	
	});
	
	jQuery("#divmainwrapper").append("<div id='opaque' style='display: none;'></div>");	
	jQuery('#seat_modal').css({top:'50%',left:'50%',margin:'-'+(jQuery('#seat_modal').height() / 2)+'px 0 0 -'+(jQuery('#seat_modal').width() / 2)+'px',position:'fixed'});
	jQuery('#menu_modal_1').css({top:'50%',left:'50%',margin:'-'+(jQuery('#menu_modal_1').height() / 1)+'px 0 0 -'+(jQuery('#menu_modal_1').width() / 2)+'px',position:'fixed'});
	jQuery('#menu_modal').css({top:'50%',left:'50%',margin:'-'+(jQuery('#menu_modal').height() / 2)+'px 0 0 -'+(jQuery('#menu_modal').width() / 2)+'px',position:'fixed'});
	jQuery('#fire_modal').css({top:'50%',left:'50%',margin:'-'+(jQuery('#fire_modal').height() / 2)+'px 0 0 -'+(jQuery('#fire_modal').width() / 2)+'px',position:'fixed'});
	jQuery('#box15').css({top:'50%',left:'50%',margin:'-'+(jQuery('#box15').height() / 2)+'px 0 0 -'+(jQuery('#box15').width() / 2)+'px',position:'fixed'});
	jQuery('#client_modal_interface').css({top:'50%',left:'50%',margin:'-'+(jQuery('#client_modal_interface').height() / 2)+'px 0 0 -'+(jQuery('#client_modal_interface').width() / 2)+'px',position:'fixed'});
	jQuery('#boxweight').css({top:'50%',left:'50%',margin:'-'+(jQuery('#boxweight').height() / 2)+'px 0 0 -'+(jQuery('#boxweight').width() / 2)+'px',position:'fixed'});
	jQuery('#manager_password').css({top:'50%',left:'50%',margin:'-'+(jQuery('#manager_password').height() / 2)+'px 0 0 -'+(jQuery('#manager_password').width() / 2)+'px',position:'fixed'});
	jQuery('#opendrawer').css({top:'50%',left:'50%',margin:'-'+(jQuery('#opendrawer').height() / 2)+'px 0 0 -'+(jQuery('#opendrawer').width() / 2)+'px',position:'fixed'});
	jQuery('#filter_modal').css({top:'50%',left:'50%',margin:'-'+(jQuery('#filter_modal').height() / 2)+'px 0 0 -'+(jQuery('#filter_modal').width() / 2)+'px',position:'fixed'});
	
	jQuery("#allergic_t").click(function(){ 
		jQuery("#allergic_t").css({"background-color":"#DD0000","border-color":"#DD0000"});
	});
	jQuery("#side_t").click(function(){ 
		jQuery("#side_t").css({"background-color":"#0866C6","border-color":"#0866C6"});
	});
	jQuery("#only_t").click(function(){ 
		jQuery("#only_t").css({"background-color":"#0866C6","border-color":"#0866C6"});
	});
	jQuery("#less_t").click(function(){ 
		jQuery("#less_t").css({"background-color":"#0866C6","border-color":"#0866C6"});
	});
	jQuery("#extra_t").click(function(){ 
		jQuery("#extra_t").css({"background-color":"#0866C6","border-color":"#0866C6"});
	});
	jQuery("#select_t").click(function(){ 
		jQuery("#select_t").css({"background-color":"#0866C6","border-color":"#0866C6"});
	});
	jQuery("#remove_t").click(function(){ 
		jQuery("#remove_t").css({"background-color":"#0866C6","border-color":"#0866C6"});
	});
	
	jQuery("#allergic_b").click(function(){ 
		jQuery("#allergic_b").css({"background-color":"#DD0000","border-color":"#DD0000"});
		jQuery("#side_b,#only_b,#less_b,#extra_b,#select_b,#remove_b").css({"background-color":"#777777","border-color":"#777777"});
	});
	jQuery("#side_b").click(function(){ 
		jQuery("#side_b").css({"background-color":"#0866C6","border-color":"#0866C6"});
		jQuery("#allergic_b,#only_b,#less_b,#extra_b,#select_b,#remove_b").css({"background-color":"#777777","border-color":"#777777"});
	});
	jQuery("#only_b").click(function(){ 
		jQuery("#only_b").css({"background-color":"#0866C6","border-color":"#0866C6"});
		jQuery("#side_b,#allergic_b,#less_b,#extra_b,#select_b,#remove_b").css({"background-color":"#777777","border-color":"#777777"});
	});
	jQuery("#less_b").click(function(){ 
		jQuery("#less_b").css({"background-color":"#0866C6","border-color":"#0866C6"});
		jQuery("#side_b,#only_b,#allergic_b,#extra_b,#select_b,#remove_b").css({"background-color":"#777777","border-color":"#777777"});
	});
	jQuery("#extra_b").click(function(){ 
		jQuery("#extra_b").css({"background-color":"#0866C6","border-color":"#0866C6"});
		jQuery("#side_b,#only_b,#less_b,#allergic_b,#select_b,#remove_b").css({"background-color":"#777777","border-color":"#777777"});
	});
	jQuery("#select_b").click(function(){ 
		jQuery("#select_b").css({"background-color":"#0866C6","border-color":"#0866C6"});
		jQuery("#side_b,#only_b,#less_b,#extra_b,#allergic_b,#remove_b").css({"background-color":"#777777","border-color":"#777777"});
	});
	jQuery("#remove_b").click(function(){ 
		jQuery("#remove_b").css({"background-color":"#0866C6","border-color":"#0866C6"});
		jQuery("#side_b,#only_b,#less_b,#extra_b,#select_b,#allergic_b").css({"background-color":"#777777","border-color":"#777777"});
	});	
	
	jQuery("#seat1").click(function(){ 
		jQuery("#seat_img1").css("opacity",1);
		return false;
	});
	jQuery("#seat2").click(function(){ 
		jQuery("#seat_img2").css("opacity",1);
		return false;
	});
	jQuery("#seat3").click(function(){ 
		jQuery("#seat_img3").css("opacity",1);
		return false;
	});
	jQuery("#seat4").click(function(){ 
		jQuery("#seat_img4").css("opacity",1);
		return false;
	});
	jQuery("#seat5").click(function(){ 
		jQuery("#seat_img5").css("opacity",1);
		return false;
	});
	jQuery("#seat6").click(function(){ 
		jQuery("#seat_img6").css("opacity",1);
		return false;
	});

	jQuery('#item-hide').click(function () {
		jQuery("#item_open").addClass("ui-state-default ui-corner-top");		
	});
	
	jQuery("#Cancelled").click(function(){
		jQuery('#searchfldaddclient').val('');
		jQuery('#searchfldaddclient').blur();
	});
	
	jQuery("#Search_client").click(function(){
		//loadclient();
	});
	
	jQuery(".filterbtn15").click(function(){
		jQuery("#filter_modal").modal('show');
	});
//});	

</script>
<link rel="stylesheet" href="prettify/prettify.css" type="text/css" />

</head>
<body onLoad="fixspaces();">


<input type="text" id="ccinput"  style="position:fixed;top:-80px;left:-40px"/>
<input type="hidden" id="hideCallOn" value="POS" >
<input type="hidden" id="tabl_of" name="tabl_of" value="">
<input type="hidden" id="tabl_of_status" name="tabl_of_status" value="">
<input type="hidden" id="tabl_of_account" name="tabl_of_account" value="">
<input type="hidden" id="tabl_of_total" name="tabl_of_total" value="">
<input type="hidden" id="tabl_of_covers" name="tabl_of_covers" value="">
<input type="hidden" id="access_pos_payments" name="access_pos_payments" value="<?php echo $_SESSION['access_pos_payments'] ;?>">
<input type="hidden" id="closed" name="closed" value="no">
<input type="hidden" id="sortby" name="sortby" value="ord1.order_Status">
<input type="hidden" id="order" name="order" value="desc">
<input type="hidden" id="table" name="table" value="">
<input type="hidden" id="table" name="table" value="">
<input type="hidden" id="togo" name="togo" value="yes">
<input type="hidden" id="delivery" name="delivery" value="yes">
<input type="hidden" id="phone" name="phone" value="">
<input type="hidden" id="filter" name="filter" value="">
<input type="hidden" id="client_dispatch" name="client_dispatch" value="<?php echo $_REQUEST['clientid'];?>">
<input type="hidden" id="dispatch_id" name="dispatch_id" value="<?php echo $_REQUEST['dispatch'];?>">
<input type="hidden" id="order_id" name="order_id" value="<?php echo $_REQUEST['id'];?>">
<input type="hidden" id="pos_update_other_server" value="<?=$_SESSION['pos_update_other_server'];?>" >
<div id="creditcardcontainer" style="top:-1000px; position:fixed; left:-40px;"></div>



<input type="hidden" id="access_pos_cash" name="access_pos_cash" value="<?php echo $_SESSION['access_pos_cash'] ;?>">
<section class="loading-image"  style="display:none;">
<img src="images/loader7.gif" id="loader-image">
<section id="csloadingtitle" style="width:100%;font-size:24px;position:absolute; color:#00000;top:45%;left:1%;"><center></center></section>
 </section>
<input type="hidden" id="location_id" name="location_id" value="<?=$location_id;?>"/>
<div class="mainwrapper" id="divmainwrapper">
  <?php include_once 'includes/header.php';?>
  <div class="leftpanel">
    <?php include_once 'includes/left_menu.php';?>
  </div>
  <!-- leftpanel -->
  <div class="rightpanel">
   
    <ul class="breadcrumbs">
      <li><a href="dashboard.html"><i class="iconfa-home"></i></a> <span class="separator"></span></li>
      <li>Restaurant<span class="separator"></span></li><li>Register</li>
      <li class="right"> <a href="" data-toggle="dropdown" class="dropdown-toggle"><i class="icon-tint"></i> Color Skins</a>
        <ul class="dropdown-menu pull-right skin-color">
          <li><a href="default">Default</a></li>
          <li><a href="navyblue">Navy Blue</a></li>
          <li><a href="palegreen">Pale Green</a></li>
          <li><a href="red">Red</a></li>
          <li><a href="green">Green</a></li>
          <li><a href="brown">Brown</a></li>
        </ul>
      </li>
	  <?php require_once("lang_code.php");?>
    </ul>
    <div class="pageheader">
      <div style="float:right;margin-top: 11px;">
        
        <span id="open_check"><?php if($_REQUEST['id'] != "" ) { ?>
         <button id="first_btn" onClick="openpageconfi('wrapper8');" class="btn btn-primary btn-large">Back</button><button id="second_btn" onClick="openpageconfi('wrapper8');" class="btn btn-primary btn-large" style="display:none;">Back</button>     
         <?php } else { ?>
         	<button id="first_btn" onClick="delivery='Y';openpage('wrapper8');" class="btn btn-primary btn-large">Back</button><button id="second_btn" onClick="delivery='Y';openpageconfi('wrapper8');" class="btn btn-primary btn-large" style="display:none;">Back</button>
         <?php } ?></span>
         <span id="closed_check" style="display:none;"><a href="restaurant_register.php"><button  class="btn btn-primary btn-large">Back</button></a></span>
         <a href="restaurant_register_form.php?action=add">               
 <button class="btn btn-success btn-large" >New Tab</button>
</a>
         </div>
      <div class="pageicon"><span class="iconfa-star"></span></div>
      <div class="pagetitle">
        <h5>Manage Check</h5>
        <h1><?php if($_REQUEST['id'] != "") { echo $check_number." -  ".$server;?><?php } else {?><span class="hd1_new" style="float:left; margin-top:9px;"></span>    <span class="hd2" style="float:left; width:auto;"></span><span class="hide_heading">New Check</span><?php } ?></h1>
      </div>
    </div>
    <!--pageheader-->
    <div class="maincontent">
      <div class="maincontentinner">
        <div class="row-fluid">
          <div class="span8" id="left_mailDiv">           		
                <div class="tabbedwidget tab-primary" id="main_div" > <!--height:500px;overflow:scroll;-->
                    <ul style="height:38px; float:left; width:100%;">
                        <li id="item_close" class="ui-state-default ui-corner-top ui-tabs-selected ui-state-active" onClick="resizeDiv()">
                            <a href="#e-7" id="first_tb" style=" vertical-align:top; text-align:center;" onClick="openpage('wrapper11');">
                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr><td class="tborng-lft" style="vertical-align:middle;" >Details</td></tr>
                            </table>
                            </a>                        </li>
                        <li id="item_open" style="display:none;" class="ui-state-default ui-corner-top ui-tabs-selected ui-state-active" onClick="resizeDiv()">
                            <a href="#e-7" id="item_second"  style=" vertical-align:top; text-align:center;">
                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr><td class="tborng-lft" style="vertical-align:middle;" onClick="ocxop=1;loaditja();ocxop=1;openpageconfitrue('wrapper11');ocxop=1;">Details</td></tr>
                            </table>
                            </a>    
                         </li>	
                        <li id="item-tab" onClick="resizeDiv()">
                        	 <a href="#" style=" vertical-align:top; text-align:center;" onClick="lastpagao='wrapper11';fodaoss=false;openpage('wrapper23');" id="open_order_first">
                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr><td class="tborng-cntr" style="vertical-align:middle;" >Items</td></tr>
                            </table>
                            </a>
                        </li>
                  		<li id="item-hide" style="display:none;" onClick="resizeDiv()">
                            <a href="#e-8" id="open_order" style=" vertical-align:top; text-align:center;"  onClick="openpage('wrapper23');" >
                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr><td class="tborng-cntr" style="vertical-align:middle;" >Items</td></tr>
                            </table>
                            </a> 
                        </li>
                        <li id="item-closetab" style="display:none;" onClick="resizeDiv()">
                            <a href="#" id="open_order" style=" vertical-align:top; text-align:center;"  onClick="sendpaymen=false;openpage('wrapper23');" >
                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr><td class="tborng-cntr" style="vertical-align:middle;" >Items</td></tr>
                            </table>
                            </a> 
                        </li>
                        <li id="payment_tab" onClick="resizeDiv()">
                            <a href="#e-9" id="payment_open" style="vertical-align:top; text-align:center;" onClick="vkjhjaaa=true;pay=0;updatecoversorder();openpageconfitrue('wrapper15');">
                             <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr><td class="tborng-rit"  style="vertical-align:middle;" >Payments</td></tr>
                            </table>
                            </a>
                        </li>
                        <li id="payment_tab_edit" style="display:none;" onClick="resizeDiv()">
                            <a href="#e-9" id="payment_open1" style="vertical-align:top; text-align:center;" onClick="pay=1;openpageconfitrue('wrapper15');">
                             <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                <tr><td class="tborng-rit"  style="vertical-align:middle;" >Payments</td></tr>
                            </table>
                            </a>
                        </li>                          
                    </ul>
            <div id="e-7" class="form_tab" > <!--height:auto;-->	
            <div id="d-7">
            <div align="center">
            <img onClick="showtabreal('tabletab')" title="Table" src="images/Order_Type_Table.png" width="60" id="tblbtnid" class="setopacity">
            <img onClick="showtabreal('readytab')" title="Fast" src="images/Order_Type_Fast_Posting.png" width="60" id="readybtnid" class="setopacity" >         
            <img onClick="showtabreal('deliverytab')" title="Delivery" src="images/Order_Type_Delivery.png" width="60" id="deliverybtnid" class="setopacity" >            
            <img onClick="showtabreal('parceltab')" title="ToGo"  src="images/Order_Type_To_Go.png" width="60" id="parcelbtnid" class="setopacity">
          
            </div>
            <form name="frmGeneral" id="frmGeneral" method="post">
            <input type="hidden" name="hidFlag" id="hidFlag" value="RR">
            <input type="hidden" name="employee_id" id="employee_id" value="<?php echo $_SESSION['employee_id_et'];?>">
            <table width="100%" id="dyntable_1"  width="">
                <tr >
                    <td id="covers">
                    <select name="table_" id="cvrs" class="select1 required" style="text-align:left" onChange="global_dana=true;">
                        <option value="1">1</option>
                        <option value="2">2</option>
                        <option value="3">3</option>
                        <option value="4">4</option>
                        <option value="5">5</option>
                        <option value="6">6</option>
                        <option value="7">7</option>
                        <option value="8">8</option>
                        <option value="9">9</option>
                        <option value="10">10</option>
                        <option value="11">11</option>
                        <option value="12">12</option>
                        <option value="13">13</option>
                        <option value="14">14</option>
                        <option value="15">15</option>
                        <option value="16">16</option>
                        <option value="17">17</option>
                        <option value="18">18</option>
                        <option value="19">19</option>
                        <option value="20">20</option>
                    </select>
                    </td>
                </tr> 
                <tr >
                    <td id="tblsrvr">
                       	 <input type="hidden" id="table2" value="" name="table2" style="text-align:left;direction:ltr" placeholder="Select Table"/>
                          <select name="table_" id="tbl" class="select1 required" style="text-align:left" onChange="global_dana=true;replace_dropdown_value('tbl', 'table2');ChnageHostes(this)">                    
                        </select>
                    </td>
                </tr>
                <tr >
                    <td id="tblsrvr1">                
                        <input type="hidden"  id="server" style="text-align:left;direction:ltr;width:450px" placeholder="Select Server"/>
                         <select name="table_" id="srvr" class="select1 required"  style="text-align:left" onChange="global_dana=true;replace_dropdown_value('srvr', 'server')">              
                        </select>
                    </td>
                </tr>
                
                <tr >
                    <td id="ready" style="display:none">                 
                    	<input tabindex="1" name="readytime" id="readytime" type="time" class="input-block-level" value="" placeholder="Ready Time" style="min-height:30px;text-align:left">
                    </td>
                </tr>                          	
                            	
                <tr id="clientinfo" style="display:none;">
                	<table width="100%" border="0" cellspacing="0" cellpadding="0">
                         <tr>
                            <td>
                                <select name="source_of_business" id="source_of_business" class="select1 required"  style="text-align:left"> 
                                    <option value="">Select Source Of Business</option> 
                                    <option value="External">External</option> 
                                    <option value="Fax">Fax</option>
                                    <option value="Mobile">Mobile</option>
                                    <option value="Phone">Phone</option>
                                    <option selected="selected" value="Walk In">Walk In</option>
                                    <option value="Website">Website</option>             
                        		</select>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                    <tr>
                                        <td>
                                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                                <tr>
                                                    <td height="30" valign="middle" class="tdheading">Client Information </td>
                                                    <td class="addminusarrow" align="right"><a  id="imgClient" name="imgClient" href="#"><img src="images/Add_16.png"></a></td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                <table width="100%" border="0" cellspacing="0" cellpadding="0" id="client_order_area">
                                    <tr>
                                        <td><input tabindex="1" name="txtClientName_1" id="txtClientName_1" type="text" class="input-block-level" value="" placeholder="Client1" style="min-height:30px; text-align:left;"></td>
                                        <td class="addminusarrow" style="width:6%;" align="right"><a href="#" data-id="txtClientName_1" id="btnNameLookup_1" name="btnNameLookup_1"><!--<i class="icon-chevron-right"/>--><img src="images/Edit_16.png"></a></td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr id="tab_client_name" style="display:none;">
                            <td>
                                <table width="100%" border="0" cellspacing="0" cellpadding="0" id="client_order_area">
                                    <tr>
                                        <td colspan="2"><input name="client_name" id="client_name" type="text" class="input-block-level" value="" placeholder="Client Name" style="min-height:30px; text-align:left;"></td>
                                    </tr>
                                </table>
                            </td>
                        </tr>
                        <tr id="tab_client_phone" style="display:none;">
                            <td>
                                <table width="100%" border="0" cellspacing="0" cellpadding="0" id="client_order_area">
                                    <tr>
                                        <td colspan="2"><input  name="client_phone" id="client_phone" type="text" class="input-block-level" value="" placeholder="Client Phone" style="min-height:30px; text-align:left;"></td>                                    </tr>
                                </table>
                            </td>
                        </tr>
                    </table>
                </tr>
                           	
                             <span id="deliveryinfo" style="display:none">
                              <tr class="" id="del_info" >
                              <td>
                              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                   <tr>
                                       <td>
                                       <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                           <tr>
                                              <td height="30" valign="middle" class="tdheading">Delivery Information</td>
                                           </tr>
                                       </table>
                                       </td>
                                </tr>
                               </table>
                               </td>
                             </tr>
                              <tr class="del_info" >
                                <td><table width="100%" border="0" cellspacing="0" cellpadding="0" id="spMoreClient">
                                    <tr>
                                    <td><input tabindex="1" name="phone" id="clientphone" onBlur="validaforneworder()" onKeyPress="validaforneworder()" style="text-align:left" type="text" class="txtinput1" value="" placeholder="Phone *"></td>
                                     <td class="addminusarrow" align="right">&nbsp;</td>
                                    </tr>
                                    </table>
                                </td>
                              </tr>
                               <tr class="del_info" >
                                <td><table width="100%" border="0" cellspacing="0" cellpadding="0" id="adrsa" style="display:none;" >
                                    <tr>
                                    <td><input type="hidden" class="hidden_textfield2" id="clientaddress1_cnt2" placeholder="" onFocus="document.getElementById('delivery_state2').focus();" onBlur="validaforneworder()" onKeyPress="validaforneworder()" />
                                          <select  id="clientaddress1_cnt" class="select1 required" style="text-align:left" onChange="selad(this.value);replace_dropdown_value('clientaddress1_cnt', 'clientaddress1_cnt2')">
                                          </select>
                                    </td>
                                     <td class="addminusarrow" align="right">&nbsp;</td>
                                    </tr>
                                    </table>
                                 </td>
                              </tr>
                               <tr class="del_info"  >
                                <td><table width="100%" border="0" cellspacing="0" cellpadding="0" id="addresssingle">
                                    <tr>
                                    <td><input tabindex="1" name="add1" id="clientaddress1" type="text" class="txtinput1" style="text-align:left" onBlur="validaforneworder();checkend();" onKeyPress="validaforneworder();" value="" placeholder="Address 1*"></td>
                                     <td class="addminusarrow" align="right">&nbsp;</td>
                                    </tr>
                                    </table>
                                 </td>
                              </tr>
                               <tr class="del_info" >
                                <td><table width="100%" border="0" cellspacing="0" cellpadding="0" id="spMoreClient">
                                    <tr>
                                    <td><input tabindex="1" name="add2" id="clientaddress2" onBlur="validaforneworder();checkend();" style="text-align:left" onKeyPress="validaforneworder()" type="text" class="txtinput1" value="" placeholder="Address 2"></td>
                                     <td class="addminusarrow" align="right">&nbsp;</td>
                                    </tr>
                                    </table>
                                 </td>
                              </tr>
                               <tr class="del_info" >
                                <td><table width="100%" border="0" cellspacing="0" cellpadding="0" id="spMoreClient">
                                    <tr>
                                    <td><input tabindex="1" name="city" id="clientcity" onBlur="validaforneworder();checkend();" style="text-align:left" onKeyPress="validaforneworder()" type="text" class="txtinput1" value="" placeholder="City*"></td>
                                     <td class="addminusarrow" align="right">&nbsp;</td>
                                    </tr>
                                    </table>
                                 </td>
                              </tr>
                              <tr class="del_info" >
                                <td><table width="100%" border="0" cellspacing="0" cellpadding="0" id="spMoreClient">
                                    <tr>
                                    <td><select name="delivery_state2" id="delivery_state2" class="select1 required" style="text-align:left">
                                             <option value="">State*</option>
                                             <option value="">2</option>
                                         </select>
                                     </td>
                                    </tr>
                                    </table>
                                </td>
                              </tr>
                              <tr class="del_info" >
                                <td><table width="100%" border="0" cellspacing="0" cellpadding="0" id="spMoreClient">
                                    <tr>
                                    <td><input tabindex="1" name="deliveryzipcode" id="deliveryzipcode" type="text" style="text-align:left" onBlur="validaforneworder();checkend();" onKeyPress="validaforneworder()"  class="txtinput1" value="" placeholder="Zip Code*"></td>
                                     <td class="addminusarrow" align="right">&nbsp;</td>
                                    </tr>
                                    </table>
                                </td>
                              </tr>
                              </span>
                             
                             <span id="checkdetails" style="display:none">                          
                                      <tr>
                              <td>
                              <span id="delcom" style="display:none">
                              <table width="100%" border="0" cellspacing="0" cellpadding="0" class="" >
                                   <tr>
                                       <td>
                                       <table width="100%" border="0" cellspacing="0" cellpadding="1">
                                           <tr>
                                              <td>Distance From Location</td>
                                              <td align="right" style="color:#000000;font-weight:bold;" id="ck_distance"></td>
                                           </tr>
                                       </table>
                                       </td>
                                </tr>
                               </table>
                               <table width="100%" border="0" cellspacing="0" cellpadding="0" class="">
                                   <tr>
                                       <td>
                                       <table width="100%" border="0" cellspacing="0" cellpadding="1">
                                           <tr>
                                              <td>Estimated Travel</td>
                                              <td align="right" style="color:#000000;font-weight:bold;" id="ck_estimated"></td>
                                           </tr>
                                       </table>
                                       </td>
                                </tr>
                               </table>
                               </span>
                              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                   <tr>
                                       <td>
                                       <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                           <tr>
                                              <td height="30" valign="middle" class="tdheading">Check Details </td>
                                           </tr>
                                       </table>
                                       </td>
                                </tr>
                               </table>
                               </td>
                             </tr>
                              <tr>
                              <td>
                              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                   <tr>
                                       <td>
                                       <table width="100%" border="0" cellspacing="0" cellpadding="1">
                                           <tr>
                                              <td>Check Start Time</td>
                                              <td align="right" style="color:#000000;font-weight:bold;" id="ck_starttime">15:00</td>
                                           </tr>
                                       </table>
                                       </td>
                                </tr>
                               </table>
                               </td>
                             </tr>
                              <tr>
                              <td>
                              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                   <tr>
                                       <td>
                                       <table width="100%" border="0" cellspacing="0" cellpadding="1">
                                           <tr>
                                              <td>Check End Time</td>
                                              <td align="right" style="color:#000000;font-weight:bold;" id="ck_endtime"></td>
                                           </tr>
                                       </table>
                                       </td>
                                </tr>
                               </table>
                               </td>
                             </tr>
                             <tr>
                              <td>
                              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                   <tr>
                                       <td>
                                       <table width="100%" border="0" cellspacing="0" cellpadding="1">
                                           <tr>
                                              <td>Check Elapsed Time</td>
                                              <td align="right" style="color:#000000;font-weight:bold;" ><span id="ck_elapsedtime">-12:29</span> </td>
                                           </tr>
                                       </table>
                                       </td>
                                </tr>
                               </table>
                               </td>
                             </tr>
                             <tr>
                              <td>
                              <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                   <tr>
                                       <td>
                                       <table width="100%" border="0" cellspacing="0" cellpadding="1">
                                           <tr>
                                              <td>Order Status</td>
                                              <td align="right" style="color:#000000;font-weight:bold; text-transform:capitalize;" id="ck_statusss"></td>
                                           </tr>
                                       </table>
                                       </td>
                                </tr>
                               </table>
                               </td>
                             </tr>
                             <tr>
                                <td><table width="100%" border="0" cellspacing="0" cellpadding="0" id="spMoreClient">
                                    <tr>
                                    <td><select name="hostess_status" id="hostess_status" class="select1" style="text-align:left">
                                             <option value="">Hostess Status</option>
                                             <option value="Dirty">Dirty</option>
                                             <option value="Ready">Ready</option>
                                             <option value="New">New</option>
                                             <option value="Menus">Menus</option>
                                             <option value="Ordered">Ordered</option>
                                             <option value="Appetizer">Appetizer</option>
                                             <option value="Entree">Entree</option>
                                             <option value="Dessert">Dessert</option>
                                             <option value="Check">Check</option>
                                             <option value="Receipt">Receipt</option>
                                             <option value="Finished">Finished</option>
                                             <option value="Taxi">Transportation - Taxi</option>
                                             <option value="Valet">Transportation - Valet</option>                                             
                                         </select>
                                        
                                         
                                     </td>
                                    </tr>
                                    </table>
                                </td>
                              </tr>
                             </span>
                    </table>
                </form>
              </div>
          </div> <!--end e-7-->
            <div id="e-8"> <!--height:auto;-->
            	<div id="d-8">
                <div id="pop_wrap" class="popup">
                    <div style="padding: 10px 0px;clear:both;">
                        <table width="100%" cellspacing="0" cellpadding="0" border="0">
                            <tr><td style="width:90%;">
                               	<label id="searchfldscnd_placeholder" style="display:none">Search For Items</label> <input type="text" style="background: url('images/icons/search.png') no-repeat scroll 98% 7px #FFFFFF;font-size: 12px;padding:4px;margin-right:5px;margin-bottom: 0;" placeholder="Search For Items" value="" name="keyword" id="searchfldscnd" onKeyUp="searchit();"></td>
                            	<td style="text-align:left; width:8%;">
                                <a  class="filterbtn15"  href="#" style="margin-left:15px;">
                                <img src="images/filter_icon.png" style="vertical-align:middle;">
                                </a>
                             	</td>
                          </tr>
                        </table>
                    </div>
                </div>
            <div id="content_prods" class="tabsorange-content">
                
            </div>
            </div> 
            </div>
            <!--end e-8-->
            <div id="e-9">
            	<div id="d-9">                  
            	 <div style="width:100%; float:left;"	>
                        <input type="hidden"  id="methodofpayment-input" placeholder="cash" class="input-block-level">
                        <select id="methodofpayment" name="methodofpayment" onChange="payment(this.value);" class="select1">                            
                      </select>
                        
                        <input id="vlpayment_code" type="hidden" class="hidden_textfield2 input-block-level"  style="direction:ltr !important;text-align:right" placeholder=""/>  
                        <div id="paymenttype" style="width:100%; float:left;">
                        <select name="pymntyp" id="pymntyp" class="select1" title="Type" onChange="paymentcode(this.value)">                            
                        </select>
                        </div>
                    </div>
                    
                    <div id="receivable" style="display:none; width:100%; float:left;">
                        	<table width="100%"	 border="0" cellpadding="0" cellspacing="0">											
                                <tr>
                                    <td>
                                    <table width="100%" border="0">
                                            <tr>
                                                <td width="95%">
                                                    <input name="txtcompany" id="txtcompany"  type="text" class="input-block-level" value="" placeholder="Name" title="Name" style="text-align:right; min-height:30px;">
                                                    <input type="hidden" id="company_id" value="">
                                                </td>
                                                <td class="addminusarrow" width="5%;"><img name="btnSearchAffiliate" onClick="getCompanydetail();"  style="cursor:pointer; padding-left:5px;" src="images/searchiocn.png"></td>
                                            </tr>
                                        </table>													
                                    </td>
                                </tr> 
                                <tr>
                                    <td>
                                        Address <span style="float:right" id="company_addresses"></span>													
                                    </td>                                                
                                </tr>
                                <tr id="address2" style="display:none;">
                                    <td>
                                        Address2 <span style="float:right" id="company_addresses2"></span>													
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        City, State, Zip <span style="float:right" id="company_city"></span>													
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Phone <span style="float:right" id="company_phone"></span>													
                                    </td>
                                </tr>                                                                        
                            </table>
                        </div>
                    
                    <div id="btnauthsale" style="display:none; width:100%; float:left; margin:15px 0px;">
                    	<center>
                        <button class="btn btn-primary btn-large auth" id="abc-autho" style=" padding:5px 30px;border:0;" onClick="payment2a('autho')">Authorize</button>
                        <button class="btn btn-primary btn-large" style="width:120px;padding:5px 30px;border:0;" id="abc-salefn" onClick="payment2a('salefn')">Sale</button>
                        </center>
                    </div>
                    
                    <div id="btnauthsaleht" style="display:none; width:95%;float:left; margin:15px 0px;">
                    	<center>
                        <button class="btn btn-primary btn-large auth" id="abc-autho-ht" style=" padding:5px 30px;border:0;" onClick="payment('autho-ht')">Authorize</button>
                        <button class="btn btn-primary btn-large" id="abc-salefn-ht" style="width:120px;padding:5px 30px;border:0;"  onClick="payment('salefn-ht')">Sale</button>
                        </center>
                    </div>
                    
                    <div id="conthotelauth" class="lftform" style="border-bottom:1px solid #969696; height:100px;"> </div>
                    
                    
                    <div id="visaamxbtns" style="display:none; width:100%; float:left; margin-bottom:10px;">
                        <div id="content_autho" class="lftform" style="border-bottom:1px solid #969696; height:auto;">
                            <div class="grybtn_lrg" onClick="payment('visafn')">
                                <div class="lfttbtnicon">Visa - 2318</div>
                                <div class="cntrtbtnicon"></div>
                                <div class="rittbtnicon">80.00</div>
                            </div>
                            <div class="slverbtn_lrg" onClick="payment('amxfn')">
                                <div class="lfttbtnicon" style="color:#676767;">AMEX - 18237</div>
                                <div class="cntrtbtnicon"></div>
                                <div class="rittbtnicon" style="color:#000;">95.00</div>
                            </div>
                        </div>
                    </div>
                    
                    <div id="txtsmall" style="display:none;width:100%; float:left;">  
                          <div id="fku" class="lftform" style=" width:97.4%;margin-left:2%;margin-right:3%;position:relative;left:-8px">
                            <div class="clientname-form" style="height:40px; width:100%; float:left;">
                              <div class="clientemail_lft2 form-text-lft" style=" width:50%;float:left;" > Payment Type </div>
                              <div id="cctxt_payment_code" style="width:30%;float:right; text-align:right;" class="clientemail_rit-ful form-text-rit"> Visa </div>
                            </div>
                            <div class="clientname-form" style="height:40px; width:100%; float:left;">
                              <div class="clientemail_lft2 form-text-lft" style="width:auto; float:left;"> CC Number </div>
                              <div id="cctxt_cc_number" style="line-height:25px;float:right; text-align:right;" class="clientemail_rit-ful form-text-rit" > XXXX-XXXX-XXXX-1234 </div>
                            </div>
                            <div style="width:40%; float:left;">
                              <div class="clientname-form" style="height:40px;">
                                <div class="clientemail_lft2 form-text-lft" style="width:49%">
                                	<span>Exp. Month</span>
                                    <span id="cctxt_cc_exp_month" style="color:black; font-size:14px; text-align:left; padding-left:10px; font-weight:bold; line-height:32px;"></span>
                                </div>
                              </div>
                            </div>
                            <div style="width:40%; float:left;">
                              <div class="clientname-form" style="height:40px;">
                                <div class="clientemail_lft2 form-text-lft" style="direction:rtl">
                                	<span>Year</span>
                                    <span id="cctxt_cc_exp_year" style="color:black; font-size:14px; text-align:left; padding-left:10px; font-weight:bold; line-height:32px;"></span>
                                </div>
                              </div>
                            </div>
                            <div class="clientname-form" style="height:40px;float:left;width:40%">
                              <div class="clientemail_lft2 form-text-lft"  style="float:left;width:100px;">
                              		<span>CVV</span>
                                    <span id="cctxt_cc_ccv" style="color:black; font-size:14px; text-align:left; padding-left:10px; font-weight:bold; line-height:32px;"></span>
                              </div>
                            </div>
                            <div class="clientname-form" style="height:40px;width:40%;float:left">
                              <div class="clientemail_lft2 form-text-lft"  style="direction:ltr;text-align:left;width:auto">
                               		<span>Authorization</span>
                                    <span id="cctxt_cc_authorization" style="color:black; font-size:14px; text-align:left; padding-left:10px; font-weight:bold; line-height:32px;"></span>
                             </div>
                            </div>
                          </div>
                        </div>
                                
                    <div id="fku2">
                        <div id="ccnum" style="display:none; width:100%; float:left;">
                            <section id="maskcard" style="position:relative;background-color:white;top:25px;font-size:15px;float:right;right:38px">xxxx-xxxx-xxxx-</section>
                            <input type="text" name="first_name_cc" id="first_name_cc" class="input-block-level" placeholder="First Name" value="" style="width:49%;min-height:30px; text-align:right"><input type="text" name="last_name_cc" id="last_name_cc" class="input-block-level" value="" style="width:49%;min-height:30px; text-align:right; margin-left:2%;" placeholder="Last Name">
                            <input tabindex="1" name="pay_cc_number" id="pay_cc_number" type="text" class="input-block-level" value="" placeholder="CC Number" style="min-height:30px; text-align:right;" onBlur="checkcctypebynumber();">
                             
                            <input   id="pay_cc_name" class="txtfld_clientname form-text-rit" name="" type="hidden" placeholder="" />
                            <input   id="swiped" class="txtfld_clientname form-text-rit" name="" type="hidden" placeholder="" />
                                    <input type="hidden" id="payid" name="payid" value =''/>         
                                <input type="hidden" name="monthselected" id="monthselected" onFocus="$('#expmnth').click();" >
                            <select name="expmnth" id="expmnth" class="input-block-level" title="Exp. Month" style=" width:49%; float:left;min-height:30px;" onFocus="$('#expmnth').click();" onChange="$('#monthselected').val(this.value)">
                                <option value="">10</option>
                            </select>
                                <input style="width:30%" tabindex="99" type="hidden" class="hidden_textfield2" placeholder="" id="yearselected" onFocus="$('#year').click();" readonly/>
                            <select name="year" id="year" class="input-block-level" title="Year" style="width:49%; min-height:30px; float:right;" onChange="$('#yearselected').val(this.value)">
                                <option value="">2015</option>
                            </select>
                             <input tabindex="1" maxlength="4" name="cc_ccv" id="cc_ccv" type="text" class="input-block-level" value="" placeholder="CVV" onKeyUp="forcanumero(this.id)" style="min-height:30px; text-align:right;" onFocus="checkcvvsize();">
                        </div>
                        
                        <div class="testwidth2" style="height:auto;">
                            <div id="containerseats" style="width:100%;height:auto;margin-bottom:10px;"> </div>
                        </div>
                        
                        <div id="amountdue" style="display:block; width:100%; float:left;">
                        <table width="100%" border="0" cellpadding="0" cellspacing="0">
                            <tr><td align="center" style="text-align:left;"> Amount Due</td>
                            <td style="text-align:right;" id="vlamountdue"> $ 0.00 </td></tr>
                        </table>
                        </div>
                        
                        <div id="gratuitya" style="display:block; width:100%; float:left;">
                            <input id="gratuity2a" class="input-block-level" name="" type="text" value="" onKeyUp="calcpercad2(this.value);calculatepay()" placeholder="Percentage Of Gratuity" style="min-height:30px; text-align:right;" onBlur="calctotcar()"/>
                        </div>
                        
                        <div id="gratuity" style="display:block; width:100%; float:left;">
                            <input name="gratuity2" id="gratuity2" type="text" class="input-block-level" onKeyUp="limpareceived();" onBlur="calctotcar()" onFocus="this.select();" value="Gratuity" placeholder="Gratuity" style=" min-height:30px;text-align:right;">
                        </div>
                        
                        <div id="htt_amount" style="display:block; width:100%; float:left;">
                            <input id="ht_amount" class="input-block-level" name="" type="text" value=""  placeholder="Amount"  style="min-height:30px; text-align:right;"  />
                        </div>
                        
                        <div id="htt_client_listsc" class="txtfld_div_clintname_dropd" style="display:block; width:100%; float:left;display:none">
                              <input type="text" class="hidden_textfield2" placeholder="" id="htt_client_list-input" value=""/>
                              <select style="font-weight:bold; position:relative; opacity:0;" name="htt_client_list" id="htt_client_list" class="dropdown_clientname_newstyle" onChange="fillroomst(this.value);">         
                              </select> 
                        </div>
                        
                        <div id="htt_client_room" style="display:block; width:100%; float:left;">
                            <input id="ht_client_room" class="input-block-level" name="" type="text"  placeholder="Client Room"  style="min-height:30px; text-align:right;" onKeyUp="if (event.keyCode == 8){devapaht=true}" onBlur="if(this.value != ''){tpkh=1;showmessageroom=true;findroom()}else{clearho()}" value="" /> 
                        </div>
                        
                        <div id="htt_client_name" style="display:block; width:100%; float:left;">
                            <input id="ht_client_name" class="input-block-level" name="" type="text"  placeholder="Client Name" value=""  style="min-height:30px;text-align:right;" onKeyUp="if (event.keyCode != 8){tpkh=0;findrooml()}else{devapaht=true}"   onBlur="if(this.value != ''){tpkh=2;showmessageroom=true;findroom()}else{clearho()}"/>
                        </div>
                        
                        <div id="htt_client_account" style="display:block; width:100%; float:left;">
                            <input id="ht_client_account" class="input-block-level" name="" type="text"  placeholder="Client Account"  style="min-height:30px;text-align:right;" value=""  onKeyUp="if (event.keyCode == 8){devapaht=true}" onBlur="if(this.value != ''){tpkh=3;showmessageroom=true;findroom()}else{clearho()}" />  
                        </div>
                        
                        <div id="gift" class="clientname-form" style="width:100%; float:left;">
                            <input name="giftnumber" id="giftnumber" type="text" class="input-block-level" onBlur="getamountgift();" onClick="this.select();" value="" placeholder="Gift Certificate Number" style="min-height:30px;text-align:right;">
                            <table width="100%" border="0" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td align="center" style="text-align:left;"> Balance</td>
                                    <td style="text-align:right;" id="blbalance"> $ 0.00 </td>
                                </tr>
                            </table>
                        </div>
                        
                        
                        
                        <div id="payment" class="clientname-form" style="width:100%; float:left;">
                            <input tabindex="1" name="vlpayment" id="vlpayment" type="text" class="input-block-level" value="" placeholder="Payment" style="min-height:30px; text-align:right;" onKeyup="calccashtps()" onClick="this.select();" onBlur="ccck(this.id);">
                        </div>
                        
                        <div id="btnsrechng" style="display:block; width:100%; float:left;">
                        	<center>
                                <div id="sawtle" class="buttonsmid" style="margin:10px;">
                                 <table width="100%" border="0" cellpadding="0" cellspacing="0" style="margin-bottom:10px;">
                                    <tr><td align="center"> 
                                        <button id="female" style="width:100px;padding:7px 8px;" class="btn btn-primary btn-large" onClick="dvlor(this.id);">175.00</button>
                                        <button  id="male" style="width:100px;padding:7px 8px;" class="btn btn-primary btn-large" onClick="dvlor(this.id);" >180.00</button>
                                        <button id="child" style="width:100px;padding:7px 8px;" class="btn btn-primary btn-large" onClick="dvlor(this.id);">200.00</button>
                                        <button id="child2" style="width:100px;padding:7px 8px;" class="btn btn-primary btn-large" onClick="dvlor(this.id);">250.00</button>
                                    </td></tr>
                                </table>
                                </div>
                            </center>
                            <input id="received" class="input-block-level" name="" type="text" placeholder="0.00"  style="min-height:30px; text-align:right;text-align:right;direction:ltr" onKeyUp="calccash()" onFocus="this.select()" onBlur="calccashupdchange()"/>
                            <table width="100%" border="0" cellpadding="0" cellspacing="0" style="margin-bottom:10px;">
                                <tr>
                                    <td align="center" style="text-align:left;">Change</td>
                                    <td style="text-align:right;" id="vlchange"> 5.00 </td>
                                </tr>
                            </table>
                        </div>
                        
                            
                            
                        <script>
                            var devapaht=false;
                            function clearho(){
                                if (devapaht)
                                { 
                                    $("#ht_client_room").val("");
                                    $("#ht_client_name").val("");
                                    $("#ht_client_account").val("");
                                }
                                devapaht=false;
                            } 
                        
                            function limpareceived()
                            {
                                $("#received").val("");
                                    setTimeout(function(){
                                        calculatepay();
                                },500);      	
                            }
                            
                            function calccashtps()
                            {
                                if (($("#received").val() == '')||($("#received").val() < $("#vlpayment").val()))
                                {
                                    $("#received").val($("#vlpayment").val());
                                    if ($("#received").val() > 0)
                                    {
                                        $("#totalk").val( ckvst($("#vlpayment").val()));
                                        var receive = $("#received").val();
                                        var payment = $("#vlpayment").val();
                                        var to=parseFloat(receive)-parseFloat(payment);
                                        if (to > 0)
                                        {
                                            $("#vlchange").html(ckvst(to));	
                                        }
                                    }
                                    else
                                    {
                                        $("#totalk").val( "0.00");
                                        $("#vlpayment").val("0.00");
                                    }
                                }
                                else
                                {
                                    calccash();	
                                }
                            }
                        
                            function calccashupdchange()
                            {
                                setTimeout(function()
                                {
                                    if ($("#received").val() > 0)
                                    {
                                        $("#totalk").val( ckvst($("#vlpayment").val()));
                                        var receive = $("#received").val();
                                        var payment = $("#vlpayment").val();
                                        var to=parseFloat(receive)-parseFloat(payment);
                                        if (to > 0)
                                        {
                                            $("#vlchange").html(ckvst(to)); 
                                        }
                                        else
                                        { 
                                            $("#vlchange").html(ckvst(0)); 
                                        }
                                    }
                                    else
                                    {
                                    }
                                },500);
                            }  
                        
                            function calccashupd()
                            {
                                setTimeout(function()
                                {
                                    if ($("#received").val() > 0)
                                    {
                                        $("#totalk").val( ckvst($("#vlpayment").val()));
                                        var receive = $("#received").val();
                                        var payment = $("#vlpayment").val();
                                        var to=parseFloat(receive)-parseFloat(payment);
                                        if (to > 0)
                                        {
                                            $("#vlchange").html(ckvst(to)); 
                                        }
                                        else
                                        { 
                                            $("#vlchange").html(ckvst(0)); 
                                        }	
                                    }
                                    else
                                    {
                                    }
                                },500);
                            }
                        
                            function calccash()
                            {
                                if ($("#received").val() > 0)
                                {
                                    $("#totalk").val( ckvst($("#vlpayment").val()));
                                    var receive = $("#received").val();
                                    var payment = $("#vlpayment").val();
                                    var to=parseFloat(receive)-parseFloat(payment);
                                    if (to > 0)
                                    {
                                        $("#vlchange").html(ckvst(to)); 
                                    }
                                    else
                                    { 
                                        $("#vlchange").html(ckvst(0));
                                    }
                                }
                                else
                                {
									$("#received").val('0.00');
                                }
                            }
                        </script>
                        
                        
                        
                        <div id="mannualautho" style="display:none; width:100%; float:left;">
                            <input tabindex="1" name="received2" id="received2" type="text" class="input-block-level" value="" placeholder="Manual Authorization" style="min-height:30px; text-align:right;">
                        </div>
                        
                        <div id="totalkjhg" style="display:none; width:100%; float:left;">
                            <input id="totalk" class="input-block-level" readonly name="" type="text" placeholder="0.00" value="0.00" />
                        </div>
                        
                        <div id="prcntadjsmntamnt" style="display:none; width:100%; float:left;">
                             <input  name="poaa" id="poaa" type="text" onKeyUp="if(this.value > 100){this.value=''}calcpercad(this.value)" class="input-block-level" value="" placeholder="Percentage of Adjustments Amount" style="min-height:30px;text-align:right;">
                        </div>
                        
                        <div id="amount1" style="display:none; width:100%; float:left;">
                        	<input id="receivedam" class="input-block-level txtfld_clientname form-text-rit" name="" placeholder="Amount"  type="text" value="" onBlur="ccck(this.id);" onFocus="this.select();" onClick="this.select();"  style="min-height:30px; text-align:right;" onKeyUp="calcamountxbx(this);calcpereverse(this.value);"/>
                        </div>
                        
                        <div id="totalamount" style="display:none; width:100%; float:left;">
                            <input  name="vtotalamount" id="amt" type="text" class="input-block-level" value="" placeholder="Total Amount"  style="min-height:30px; text-align:right;">
                        </div>
                        
                        <div id="clintexpntabid" style="display:none; width:100%; float:left;">
                            <input name="clientemail" id="clientemail" type="text" class="input-block-level" value="" placeholder="Client" onBlur="checkemailet(this.value,true);" style="min-height:30px; text-align:right;">
                            <input name="clientetac" id="clientetac" type="text" class="input-block-level" value="" placeholder="ExpenseTAB Client ID" style="min-height:30px; text-align:right;">
                        </div>
                               
                        <div id="descriptiontxtarea" style="display:none; width:100%; float:left;">
                             <textarea name="desc" id="desc" class="input-block-level" rows="5" style="min-height:30px; text-align:right;" placeholder="Description"></textarea>
                        </div>
                        
                        
                        
                        <button id="scanepntabgreenbtn" class="btn btn-primary btn-large" style="width:100%; float:left;text-align:center;background-color:#009900;border-color:#090;margin-bottom:5px;padding: 2px;">
                            <table width="100%" border="0" cellpadding="0" cellspacing="0">
                                <tr>
                                    <td style="text-align:right; vertical-align:middle;width:40%;">
                                        <img src="images/scan.png" width="25" style="margin:0; padding:0;" >
                                    </td>
                                    <td style="text-align:left; vertical-align:middle;width:60%;">&nbsp;Scan ExpenseTAB QR Code For Easy Entry </td>
                                 </tr>
                            </table>
                        </button>
                    </div>
                    
                    <div id="kjhkjhdaadsda" style="display:none;position:relative;width:100%;height:1px;min-width:100%"><br></div>
                
                    <button id="sbmtpaymntbluebtn" style="width:100%;text-align:center;margin-top:10px;padding:2px;" class="btn btn-primary btn-large" onClick="submitpayments();">
                        <table width="100%" cellspacing="0" cellpadding="0" border="0">
                            <tbody>
                                <tr>
                                    <td style="text-align:right; vertical-align:middle;width:41%;"><img width="25" style="margin:0 7px 0 0; padding:0;" src="images/card2.png"></td>
                                    <td style="text-align:left; vertical-align:middle;width:60%;">&nbsp;Submit Payment</td></tr>
                            </tbody>
                         </table>
                    </button>
                    
                    
            </div>
            </div>
       </div>
           <!--</div> end messagepanel-->
          </div>
          <div class="span4" style="float:right;" id="Right_main">
           
                <div style="padding-left:0;width:100%;" class="span4">            
                    <h4 id="right-panel-oi" class="widgettitle headingbg" style="margin-bottom:0px;">
                        <div class="hd1"></div>
                        <div class="hd2"></div>
                        <div class="hd4"></div>
                        <div class="hd3"></div>
                    </h4>
                    <div id="resdiv" style="border:2px solid #0866C6; background-color:#FFFFFF;">
                    <table width="50%" cellspacing="0" cellpadding="0" id="spOrder" border="0" style="margin:5px auto; height:37px; float:left; margin-left:15px;" class="widgettitle">
                        <tbody>
                            <tr>
                                <td  onclick="fc_show_ordered();" class="tborng-cntr spb" style="text-align:center;font-size:15px;vertical-align:middle;width: 33%; border-right:1px solid #333333">
                                    <span class="ord_c1">Ordered Items</span>
                                </td>
                                <td style="text-align:center;font-size:15px;vertical-align:middle;width: 33%;" onClick="fc_show_added();" class="tborng-lft spb ord_c2">
                                    <div class="ord_c2">Added Items</div>
                                </td>
                            </tr>                        
                        </tbody>
                    </table>
                    
                    
                    <div  class="right-panel-wrap3" style="display:none;width:100%; float:left;">
                    	<div id="order_item" class="itemstablerow">
                        	<div class="itemstblerow_col1" style="width:3%;">&nbsp;</div>
                            <div class="itemstblerow_col21" style="width:81%; float:left;">Added Items</div>
                            <div class="itemstblerow_col5"></div>
                             <div class="itemstblerow_col31"  style="width:7%; float:left;"></div>
                              <div class="itemstblerow_col4">
                              <input  id="abc" name="abc" type="checkbox" style="vertical-align:top;" class="plcsw" src="images/radio_check.png" onClick="clickallitems();"></div>
                            
                        </div>                        
                        <div style="width:95%;border-bottom:2px solid #777777; float:left; margin-left:12px;padding:0;"></div>
                        <div id="tableofitems1"></div>
                        <table width="95%" cellspacing="0" cellpadding="0" border="0" style="margin:0px 12px 5px 10px; float:left; text-align:center;">
                            <tr>
                                <td colspan="5">
                                <div style="width:95%;height:20px;border-bottom:2px solid #777777;padding:0;margin:0 auto;"></div>
                                </td>
                            </tr>
                            <tbody>
                                <tr>
                                	<td style="text-align:center;" class="subtotal">Subtotal <br /> 0.00</td>
                                    <td style="text-align:center;" class="service">Service <br /> 0.00</td>
                                    <td style="text-align:center;" class="tax">Tax <br /> 0.00</td>
                                    <td style="text-align:center;" class="payments">Payments <br /> 0.00</td>
                                    <td style="text-align:center;" class="totals">Totals <br /> 0.00</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                
                    <div  class="right-panel-wrap5" style="width:98%; float:left;">
                        <div id="order_item" class="itemstablerow">
                        	<div class="itemstblerow_col1" style="width:3%;">&nbsp;</div>
                            <div class="itemstblerow_col21" style="width:64%; float:left;">Ordered Items</div>
                            <div class="itemstblerow_col51"><div id="view-buttons2"></div></div>
                             <div class="itemstblerow_col31">
                             	<img title="Percentage" width="25" height="25" style="vertical-align:bottom;float:right" id="s_perc" class="setopacity perc" onClick="selectview('Equally');" src="images/Split_Percentage.png" />
                                <img title="Seat" width="25" height="25" style="vertical-align:bottom;float:right" id="s_seat" class="setopacity chr" onClick="selectview('Chair');" src="images/Split_Seat.png" />
                                <img title="Table" width="25" height="25" style="vertical-align:bottom; float:right" id="s_table" class="setopacity tbl" onClick="selectview('Table');" src="images/Split_Table.png" />                                
                             </div>
                              <div class="itemstblerow_col4"><input type="checkbox" name="check_box1" class="plcsw" onClick="clickallitemsord();"></div>
                            
                        </div>
                       
                        <div style="width:95%;border-bottom:2px solid #777777; float:left; margin-left:12px;padding:0;"></div>
                    
                        <div id="ordereditems2" style="float:left;width:100%;"></div>		
                        <table width="95%" cellspacing="0" cellpadding="0" border="0" style="margin:0px 12px 5px 12px; float:left; text-align:center;">
                            <tr>
                                <td colspan="5">
                                    <div style="width:100%;height:10px;border-bottom:2px solid #777777;padding:0;margin:0 auto;"></div>
                                </td>
                                </tr>
                                <tr class="tabletotals" >
                                <td style="text-align:center;" class="subtotal">Subtotal <br /> 0.00</td>
                                <td style="text-align:center;" class="service">Service <br /> 0.00</td>
                                <td style="text-align:center;" class="tax">Tax <br /> 0.00</td>
                                <td style="text-align:center;" class="payments">Payments <br /> 0.00</td>
                                <td style="text-align:center;" class="totals">Totals <br /> 0.00</td>
                            </tr>
                        </table>
                    </div>
                    <table width="100%"  class="table table-bordered responsive" border="0" cellpadding="0" cellspacing="0" id="compnay_detail" style="display:none;background-color:#FFFFFF; margin:2% 0% 2% 2%; width:95%;"></table>
                    </div>
                     </div>       
         	
 		<div class="span4 buttonsmid" id="buttonsmid" style="width:100%; text-align:center; padding:0px;">
        <center>
        <table style="margin-top:1%;">
        	<tr>
            	<td rowspan="2"><button onClick="printcheck()" class="btn btn-primary btnbtm-bluess all_button" style="height:75px; margin-right:5px;">Print</button></td>
                <td><button id="btadd_modify2" onClick="mod=2;openmodifiers();" class="btn btn-primary all_button" >Modify</button>
			<button id="btadd_discount2" onClick="opendiscount();"  class="btn btn-primary all_button" >Discount</button>         
			<button id="btadd_delete2" onClick="opendelete();" class="btnbtm-red btn btn-primary all_button" style="background-color:#DD0000;border-color:#DD0000; ">Delete</button>
			<button id="btadd_fire2" onClick="openfire();" class="btn btn-primary all_button"  >Fire</button>
            
				<button class="btn btn-primary all_button"  onClick="opensplit();" >Split</button>
				<button id="btadd_repeat2" onClick="openrepeat();" class="btn btn-primary all_button"  >Repeat</button></td>
                <td rowspan="2"><button onClick="submititens()" class="btn btn-primary btnbtm-green all_button" style="height:75px; margin-left:5px; ">Send</button>
            <button onClick="submititens(true);" class="btn btn-primary btnbtm-green all_button" style="height:75px; ">Send & Close</button>
            </td>
            </tr>
            <tr>
            	<td><button class="btn btn-primary all_button"  id="btadd_togo2"  onClick="addSpecial('TOGO');event.stopPropagation();">Togo</button>
            <button id="btadd_dnt2"  onClick="addSpecial('DONT MAKE');event.stopPropagation();" class="btn btn-primary all_button"  >Dont Make</button>	
            <button class="btn btn-primary all_button"  onClick="openseat();">Seat</button>
            <button id="btadd_hold2" onClick="openhold();" class="btn btn-primary all_button"  >Hold</button>	
            <button onClick="loaddetailsordercomp()" class="btn btn-primary all_button" >Details</button>			
			<button onClick="opqpayment()" class="btn btn-primary btnbtm-blue12 all_button" >Payment</button>
            </td>
            </tr>
        </table>
        </center>
		
        </div>
        	
         </div>
          <!--footer-->
        </div>
                 
         <a id="btn_split"  data-toggle="modal" data-target="#seat_modal" href="" style="display:none"></a>
          <a id="btn_seat" data-toggle="modal" data-target="#seat_modal" href="#"></a>
        <!--row-fluid-->
        <?php include_once 'includes/footer.php';?>
        <!--footer-->
      </div>
      <!--maincontentinner-->
    </div>
    <!--maincontent-->
  </div>
  <!--rightpanel-->
</div>
<!--mainwrapper-->

<div id="box15" class="modal hide fade" style="width:490px;"> 
	<div class="modal-header" >
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h3>Discount Options</h3>
	</div>
    <div class="modal-body" style="padding:0px 15px; max-height:468px; overflow-y:auto; overflow-x:hidden;">
    <span id="boxtitle15" ></span>
    
      <div id="discon"></div> 
      <div class="dailpadinner" style="font-size:14px; padding-left:30%; width:100%; float:left;">
      	<table>
        	<tr><td>Discount</td><td>&nbsp;&nbsp;&nbsp;</td><td><input class="dailpadtextfld qwerty" style="font-size:14px;width:100px;margin:0px 0px 7px 0px;" value=""  id="vl_discount" type="text" onKeyUp="calculatediscount();" />  
        &nbsp;%</td></tr>
            <tr><td>Amount</td><td></td>&nbsp;&nbsp;&nbsp;<td><input class="dailpadtextfld qwerty" style="font-size:14px !important; width:100px; margin:0px 0px 7px 0px;" onBlur="calculatediscount();"  onKeyUp="calculatediscountinverse();"  id="vl_discount_amount" value="" type="text" />
        &nbsp;</td></tr>
        </table>     	
     </div>
        <div id="content" class="content" style="z-index:0">        
          <div id="dailpad" class="dailpad" style="position:relative;margin-top:0px;padding-top:0px">
            <div class="dailpadinner" style="width:60%; float:left; padding-left:26%;">
              <div class="dailskeysright"><img src="images/dail-1.png" onClick="selectnumber3('1');" /></div>
              <div class="dailskeysleft2"><img src="images/dail-2.png" onClick="selectnumber3('2');" /></div>
              <div class="dailskeysleft2"><img src="images/dail-3.png" onClick="selectnumber3('3');" /></div>
              <div class="dailskeysright"><img src="images/dail-4.png" onClick="selectnumber3('4');" /></div>
              <div class="dailskeysleft2"><img src="images/dail-5.png" onClick="selectnumber3('5');" /></div>
              <div class="dailskeysleft2"><img src="images/dail-6.png" onClick="selectnumber3('6');" /></div>
              <div class="dailskeysright"><img src="images/dail-7.png" onClick="selectnumber3('7');" /></div>
              <div class="dailskeysleft2"><img src="images/dail-8.png" onClick="selectnumber3('8');" /></div>
              <div class="dailskeysleft2"><img src="images/dail-9.png" onClick="selectnumber3('9');" /></div>
              <div class="dailskeysright"><img src="images/dail-backspace.png" onClick="deletenumber3();" /></div>
              <div class="dailskeysleft2"><img src="images/dail-0.png" onClick="selectnumber3('0');" /></div>
              <div class="dailskeysleft2"><img src="images/dot.png" onClick="selectnumber3('15');" /></div>
            </div>
          </div>
        
      </div>
      </div>
		<div class="modal-footer" style="text-align: center; padding:8px 12px 8px;">
        <button data-dismiss="modal" id="close_modal" class="btn">Cancel</button>
        <button type="button" id="btn_discount_password" onClick="confirmdiscount();" name="btnSearch" class="btn btn-primary" >Submit</button>        
      </div>
    
</div>
<a href="#" id="btn_manager_pass" data-toggle="modal" data-target="#manager_password"  style="display:none;" ></a>
<div id="manager_password" class="modal hide fade" style="top:10%;width:450px;margin-left:-170px;"> 
	<div class="modal-header">
		<button data-dismiss="modal" onClick="cancelmddp('manager_password');" type="button" class="close close_btn" aria-hidden="true">×</button>
		<h4>Manager Password</h4>
	</div>
    <div class="dailpadinner modal-body" style="padding:0px 15px; ">
    	<center><div style="width:60%; padding-top:15px;margin-left:10%;" class="dailpadinner">
        		<input type="password" name="emplo_password" id="emplo_password" value="" onKeyPress="return handleEnter(event);"    style="width:200px; float:left;">
                <div class="dailskeysright"><img onClick="selectnumber2('1');" src="images/dail-1.png"></div>
                <div class="dailskeysleft2"><img onClick="selectnumber2('2');" src="images/dail-2.png"></div>
                <div class="dailskeysleft2"><img onClick="selectnumber2('3');" src="images/dail-3.png"></div>
                <div class="dailskeysright"><img onClick="selectnumber2('4');" src="images/dail-4.png"></div>
                <div class="dailskeysleft2"><img onClick="selectnumber2('5');" src="images/dail-5.png"></div>
                <div class="dailskeysleft2"><img onClick="selectnumber2('6');" src="images/dail-6.png"></div>
                <div class="dailskeysright"><img onClick="selectnumber2('7');" src="images/dail-7.png"></div>
                <div class="dailskeysleft2"><img onClick="selectnumber2('8');" src="images/dail-8.png"></div>
                <div class="dailskeysleft2"><img onClick="selectnumber2('9');" src="images/dail-9.png"></div>
                <div class="dailskeysright"><img onClick="deletenumber2();" src="images/dail-backspace.png"></div>
                <div class="dailskeysleft2"><img onClick="selectnumber2('0');" src="images/dail-0.png"></div>
                <div class="dailskeysleft2"><img onClick="document.getElementById('vl_discount').focus()" src="images/dail-keyboard.png" id="keyboard"></div>
        </div>   </center>
    </div>
    <div class="modal-footer" style="text-align: center;">
        <button data-dismiss="modal" id="close_modal_1" class="btn">Cancel</button>
        <button type="button" id="btnSearch" name="btnSearch" class="btn btn-primary"  onClick="dialpad2();">Submit</button>        
    </div>
</div>

<div id="menu_modal"  class="modal hide fade" style="width:554px; height:600px;">
    <div id="box8" > 
        <div class="modal-header" id="box8_header">                    
			<span id="BXTEMP"><h4 id="boxtitle8"></h4><br></span>
            <section id="box8_product_name" style="position:relative;margin-left:16px;width:auto; float:left;">
                <span id="box8-product-name" style="font-size: 18px;">Product name</span>
            </section>	  
            <div id="modfi-div" style="height:50px;width:auto; padding:0px;">
            	<center><label id="searchfldmodfi_placeholder" for="searchfldmodfi_placeholder" style="display:none;">Search for Ingredients, Sides, etc.</label>
                    <input style="height: 20px;width: 200px;margin-bottom:0px;" id="searchfldmodfi" name="" type="text" placeholder="Search for Ingredients, Sides, etc." onKeyUp="buscarmdf(this.value);"/>			
                    <button class="btn btn-primary" onClick="buscarmdfletter(0)" style="margin-right:2px;">A-I</button>
                    <button class="btn btn-primary" onClick="buscarmdfletter(1)" style="margin-right:2px;">J-R</button>
                    <button class="btn btn-primary" onClick="buscarmdfletter(2)" style="margin-right:2px;">S-Z</button>
                    <button class="btn btn-primary" id="buscarmdfletter3" onClick="buscarmdfletter(3)" style="margin-right:2px;">Global</button>
                    <section id="box8_page_number" style="width:10%;float:right;">
                        <span id="box8-page-number" >Page 1 of 1</span>
                    </section>    
                </center>
            </div>
		</div>
        <div id="box8x" style="position:relative; height:480px;overflow:auto; margin-left:2%;">
        	<div id="altotmod" class="modfi-scroll" style="position:relative;left:0px;"></div>
            <section id="altotmod-content" class="modfi-scroll" style="position:relative;left:-5px;width:99%;"> </section>
                <div id="modifier-template" style="display:none;">
                    <div class='modifier-content'></div>
                </div>
            </section>
        </div>           
        <div class="modal-footer" style="text-align:center;">
            <section id="box8_btn_left" style="width:28%;position:relative; height:auto;display:inline-block;float:left;">
            	&nbsp;
           		<button class="btn btn-primary" id="modifier-nav-previous" style="float:left;margin-left:30px;">Previous</button>				
            </section>
            <section id="box8_btn" style="width:42%;position:relative;height:auto; display:inline-block;float:left;">
                <button data-dismiss="modal" id="close_btn_menu" class="btn  close_btn" onClick="cancelmddp('menu_modal');">Cancel</button>
                <button class="btn btn-primary" id="save_imgdigital" onClick="confirmmdf()">Submit</button>
            </section>
            <section id="box8_btn_right" style="width:28%;position:relative; height:auto; display:inline-block;float:left;">
            	&nbsp;
                <button class="btn btn-primary" id="modifier-nav-next" style="float:right;">Next</button>	            
            </section>			
			<span  id="modifier-nav" style="display:none;">Pagination Page</span>
       </div>
    </div>
</div>

<div id="seat_modal" class="modal hide fade" style="width:380px;"> 
	<form name="frmSales" id="frmSales" method="post">
	<input type="hidden" id="hidIsSearch" name="hidIsSearch" value="">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h3 id="boxtitle5"></h3>
		</div>
        <center>
		<div class="seatatble"  id="seatcontent">
         	<img src="images/table1.png">         
		</div>
        </center>
		<div class="modal-footer" style="text-align: center; ">
			<p>
				<button data-dismiss="modal" id="btn_seat_close" class="btn">Cancel</button>
				<button type="button" id="btnSearch" name="btnSearch" class="btn btn-primary" onClick="close_modal_btn=true;confirmseat()">Apply</button>				
			</p>
		</div>
	</form>
</div>
<div id="menu_modal_1" class="modal hide fade" style="width:630px;">
	 <div class="modal-header">   
     	 <button data-dismiss="modal"  type="button" id="menu_modal_hide" class="close close_btn" aria-hidden="true" style="display:none">×</button>
     	<table width="100%">
        	<tr>
            	<td align="left"><div id="btsignup2jh" class="btn btn-primary bt_esqsd" onClick="backpizza();">Back  </div></td>
                <td align="center"><h3 id="boxtitle11" style="font-size:20px;">Select Size </h3></td>
                <td align="right"><div   class="btn btn-primary bt_esqsd" onClick="fodemod=true;openmdfdefault();">Modify  </div></td>
            </tr>
        </table>     	
     </div>     
         <div id="box11"  class="modal-body" style="padding:15px 15px">
            <center>
            <div id="ctn12" style="height:auto;width:100%;"></div>
            </center>
            <div id="ctn11" style="width:100%; float:left; margin-top:30px;"></div>
         </div>     
     <div class="modal-footer" style="text-align:center;">
        <button data-dismiss="modal" id="close_btn_menu" class="btn  close_btn" onClick="cancelmddp('menu_modal_1');">Cancel</button>
        <button class="btn btn-primary" id="btnpizzasub" onClick="confirmmdf()">Submit</button>
     </div>
</div>

<div id="boxweight" class="modal hide fade" style="width:400px;" >
    <div class="modal-header">
	    <button data-dismiss="modal"  type="button" id="menu_modal_hide" class="close close_btn" aria-hidden="true" >×</button>
    	<center><h3 id="boxtitleweight"></h3></center>
    </div>
    <div class="modal-body" style="padding:0px 15px;">
        <center>
        	<br>
            <div class="dailpadinner" style="font-size:25px;">
                <center>
                    <table style="width:50%;font-size:25px">
                    	<tr>
                        	<td id="kiloprice"> </td><td id="kilotipo" ></td>
                        </tr>
                    </table>
                    <br>
                    <table style="width:50%;font-size:25px">
                    	<tr>
                        	<td style="margin-right:2px;"> Weight &nbsp;</td>
                            <td>
                            	<table>
                                	<tr>
                                    	<td><input class="dailpadtextfld" style="font-size:22px;width:100px; margin-left:10px;"  id="vl_weight" type="number" onClick="calculateweight();" onBlur="calculateweight();" onKeyUp="calculateweight();" onMouseUp="calculateweight();"/> </td>										<td id="weight_ounce" ><input class="dailpadtextfld qwerty" style="font-size:22px;width:100px;margin-left:5px;"  id="vl_weight_ounce" type="number" onClick="calculateweight();" onBlur="calculateweight();" onKeyUp="calculateweight();"/>  </td>
                                    </tr>
                               	</table>
                    		&nbsp;</td>
                       </tr>
                 	</table> 
                </center>
            </div><br>
            <div id="totalweight" class="dailpadinner" style="position:relative;top:-23px;font-size:24px;text-align:center">&nbsp;</div>
        </center>
    </div>
    <div class="modal-footer" style="text-align: center;">
        <button data-dismiss="modal" id="close_btn_weight" class="btn  close_btn" onClick="cancelmddp();">Cancel</button>
        <button class="btn btn-primary" id="save_imgdigital" onClick="confirmweight()">Submit</button>
    </div>     
</div>

<div id="opendrawer" class="modal hide fade" style="width:400px;" >
    <div class="modal-body" style="padding:0px 15px;">
        <div class="nosale" style="height:217px; text-align:center; padding-top:20%;"></div>
    </div>
    <button data-dismiss="modal"  class="btn  close_btn" onClick="closedrawer();" style="margin-bottom:10%; margin-left:43%;">Close</button>      
</div>

<a id="btn_fire"  data-toggle="modal" data-target="#fire_modal" href="" style="display:none"></a>
<div id="fire_modal" class="modal hide fade" style="width:550px;height:500px;">
    <div class="modal-header">
        <button data-dismiss="modal" onClick="cancelmddp();" type="button" class="close close_btn" aria-hidden="true">×</button>
        <h3>Fire Order</h3>
    </div>
    <div class="modal-body" style="padding:0px 15px; height:390px;">
        <section id="box10" >
            <center>
            	<section id="fireordercont" style="margin-bottom:20px; float:left;width:100%;">
        <div class="fullsi">FIRST</div>
       
        <section class="bx12">
          <ul id="sortable1" class="droptrue"  >
           
          </ul>
        </section>
        <section class="bx12"><br>
          <br>
          <div class="fullsi" >SECOND</div>
          
          <ul id="sortable2" class="droptrue" >
          
          </ul>
        </section>
        <section class="bx12"><br>
          
          <div class="fullsi">LAST</div>
         
          <ul id="sortable3" class="droptrue"  >
            
          </ul>
        </section>
      </section>
            </center> 
        </section>  
    </div>
    <div class="modal-footer" style="text-align: center;">
    	<button data-dismiss="modal" id="close_btn_menu_fire_new" class="btn  close_btn">Cancel</button>
        <button class="btn btn-primary" id="save_imgdigital" onClick="confirmfire()">Apply</button>
   </div>    
</div>

<div id="filter_modal" class="modal hide fade" style="top:30%;width:360px;margin-left:-170px;"> <!--style="width:30%;margin-left:-195px;"-->
	<form name="frmSales" id="frmSales" method="post">
	<input type="hidden" id="hidIsSearch" name="hidIsSearch" value="" >
		<div class="modal-header" >
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h3>Menu Options</h3>
		</div>
		<div class="modal-body" style="min-height: 120px;">
         <table width="100%">
                <tr>
                    <td valign="top">&nbsp;</td>
                </tr>
                <tr>
                    <td valign="top" align="center">
                        <select name="menu" id="menu">
                            <option value="Menu">Menu</option>
                            <option value="Dinner">Dinner</option>
                        </select>                    </td>
                </tr>
                <tr>
               		<td valign="top" align="center">
                    	<select name="menuview" id="menuview">
                            <option value="List">List</option>
                            <option value="Gallery">Gallery</option>                            
                        </select>                    </td>
            	</tr>
    	</table>
		</div>
		<div class="modal-footer" style="text-align: center;">
			<p>
				<button data-dismiss="modal" id="btn_cancel_menu" class="btn">Cancel</button>
				<button type="button" id="btnSearch" name="btnSearch" class="btn btn-primary"  onClick="selmenu();">Submit</button>
				
			</p>
		</div>
	</form>
</div>



<div id="client_modal" class="modal hide fade">
		<div class="modal-header" >
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h3>Add a Client</h3>
		</div>
		<div class="modal-body" style="max-height:500px !important;" > <!--style="height:auto !important; overflow:hidden;"-->
			
            <table width="100%" border="0" cellpadding="0" cellspacing="0">
              <tr>
              	<td valign="top" style="width:100%;" class="searchboxinner">
                <input type="button" class="searchbtn" value="" style="cursor:pointer;" id="Search_client" name="btnsearchclint">
                <label id="searchfldaddclient_placeholder" style="display:none;">Search for Client</label><input type="text" style="font-size: 12px; height:20px;margin-bottom:0;width:84%; border:none; box-shadow:none;" placeholder="Search for Client" value="" name="searchfldaddclient" id="searchfldaddclient" onKeyUp="loadclient();">
                <button id="Cancelled" class="removebtm" type="button"></button>
                </td>                
                <td align="right" style="vertical-align:middle; width: 6%;">
                  <img src="images/Add_16.png" id="client_add" style="cursor:pointer; margin-top:2px;"></td>
              </tr>
            </table>
            <ul style="height: 350px;" class="msglist" id="v_listclient">
            </ul>
		</div>
		<div class="modal-footer" style="text-align: center;">
			<p>
				<button id="btnClose" data-dismiss="modal" name="btnClose" class="btn">Close</button>
				<!--<button type="button" id="btnSearchClient" name="btnSearchClient" class="btn btn-primary">Submit</button>-->
			</p>
		</div>
<link rel="stylesheet" type="text/css" href="js/simpleautocomplete/css/simpleAutoComplete.css"/>
<script type="text/javascript" language="javascript">
/*jQuery('#colorbox_add').click(function() {	
    jQuery('#colorbox_add').colorbox({width:"500px",height:"auto",href:"edit_client.php"});
}); */

jQuery("#imgClient").click(function(){
	jQuery("#searchfldaddclient").val("");
	jQuery("#v_listclient").html("");
	jQuery("#client_modal").modal('show');
	//jQuery("#client_modal").show();
	//jQuery("#opaque").show();
	jQuery("#txtClientName").focus();
});

jQuery("#btnClose").click(function(){
		jQuery("#client_modal").hide();
		jQuery("#opaque").hide();
	});
/*jQuery("#btnSearchClient").click(function(){
		jQuery("#client_modal").hide();
		jQuery("#opaque").hide();
	});*/

jQuery('#txtClientName').typeahead({
	
	minLength: 3,			
    source: function (query, process) {
		return jQuery.ajax({
            url: 'ajax-clientserch.php',
            type: 'post',
            data: { query: query,  autoCompleteClassName:'autocomplete',
            selectedClassName:'sel',
            attrCallBack:'rel',
            identifier:'estado'},
            dataType: 'json',
            success: function (result) { 
				 jQuery("#spClientList").html('');
					var resultList = result.map(function (item) {
					if(item.image!="" && item.image != null ){
						var Item_ImageURL=API + "images/" + item.image;
					}else{						
						var Item_ImageURL="images/avatar.png";		
					}
					
					strFullName="";
					if(item.name_first!="" && item.name_first!=null){
						strFullName = item.name_last + ", " + item.name_first;
					}else{
						strFullName = item.name;
					}
					
					strPhone="";
					if(item.phone!="" && item.phone!=null){
						strPhone = item.phone;
					}
					
					strCity = "";
					if(item.city!="" && item.city!=null){
						strCity = item.city;
					}
					
					strState = "";
					if(item.statename!="" && item.statename!=null){
						strState = item.statename;
					}
					
					
					jQuery("#spClientList").append('<li onClick=\"addclientinfo(\'' + item.id + '\', \'' + item.email  + '\' ,\'' + strFullName  + '\', \'' + item.state  + '\', \'' + strPhone  + '\',true);\" class="getmessage" style="padding: 10px 10px 4px 10px;"><div class="thumb" style="width:50px;height:50px;"> <img alt="" style="width:50px;" src="'+ Item_ImageURL +'"> </div><div class="summary"><div style="width:100%; float:left; "><div style="width:50%; float:left; margin-left: 20px;"><div style="float:left;width:100%; "><h4>'+ strFullName +'</h4></div><div style="float:left;width:100%; ">'+ strCity + ', ' + strState +'</div><div style="float:left;width:100%; ">' + strPhone + '</div></div><div style="width:45%; float:left;margin-top: 20px; "><div style="float:right;width:100%; text-align:right "> <a name="btnCLookup_1"  onClick=\"addclientinfo(\'' + item.id + '\', \'' + item.email  + '\' ,\'' + strFullName  + '\', \'' + item.state  + '\', \'' + strPhone  + '\',true);\" id="btnCLookup_1" href="#"> <img src="images/Edit_16.png"> </a> </div></div></div></div></li>');
				});			
				
				if (result.length == 0) {
                    jQuery("#spClientList").append('<li>No Results Found.</li>');
                }
				
				return process(resultList);
			}		
      });
    },
});
</script>	
</div>
<div id="client_modal_interface" class="modal" style="display:none;">
		<div class="modal-header" >
			<h3>Search a Client</h3>
		</div>
		<div class="modal-body">
			<input id="searchfldaddclientht" name="" type="text" placeholder="Search for Client" onKeyUp="findroom();"/>
            <section class="crossbtnaddclient" onClick="$('#searchfldaddclient').val('');"></section>
            <div id="v_listclienthotel" style="height:260px; overflow-y:auto"></div>
		</div>
		<div class="modal-footer" style="text-align: center;">
			<p>
				<button data-dismiss="modal" id="btnClose1" name="btnClose1" class="btn">Close</button>
				<button type="button" id="btn" name="btn" class="btn btn-primary">Submit</button>
			</p>
		</div>	
</div>

<div id="client_add_modal" class="modal hide fade" > 
	<form name="frmSales" id="frmSales" method="post">
	<input type="hidden" id="hidIsSearch" name="hidIsSearch" value="">
		<div class="modal-header" >
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			<h3>Client Information: First Name + Last Name</h3>
		</div>
		<div class="modal-body" style="min-height:610px;">
         	<div class="tabbedwidget tab-primary"> 
                            <ul>
								<li>
                                <a href="#e-10" style=" vertical-align:top; text-align:center;">
								Information                                </a></li>	
								<li>
								<a href="#e-11" style=" vertical-align:top; text-align:center;">
                               	Email								</a></li>
								<li>
								<a href="#e-12" style=" vertical-align:top; text-align:center;">
                                Address								</a></li>
                                <li>
								<a href="#e-13" style=" vertical-align:top; text-align:center;">
                                Phone Number								</a></li>
                            </ul>
                            <div id="e-10" class="form_tab">
                            	<table width="100%" border="0" cellpadding="0" cellspacing="0">
                                    <tr>
                                        <td valign="top" style="width:40%;vertical-align: top;">
                                         <select name="c_title" id="c_title" style="width:98%;">
                                         	<option value="">Title</option>
                                            <option value="Mr.">Mr.</option>
                                            <option value="Mrs.">Mrs.</option>
                                            <option value="Miss">Miss</option>
                                         </select>
                                         <input name="f_name" id="f_name" type="text" class="txtinput1" value="First Name*" placeholder="" style="width:94%;">
                                         <input name="l_name" id="l_name" type="text" class="txtinput1" value="Last Name*" placeholder="" style="width:94%;">
                                         <select name="c_suffix" id="c_suffix" style="width:98%;">
                                            <option value="">Suffix</option>
                                            <option value="">abc</option>
                                         </select>
                                         <select name="c_gender" id="c_gender" style="width:98%;">
                                         	<option value="">Gender</option>
                                            <option value="Male">Male</option>
                                            <option value="Female">Female</option>
                                         </select>
                                         <input type="text" id="c_dob" name="c_dob" style="font-size: 13px;width:94%;" value="Date of Birth" />
                                          <select name="c_smoker" id="c_smoker" style="width:98%;">
                                          	<option value="">Smoker</option>
                                            <option value="No">No</option>
                                            <option value="Yes">Yes</option>
                                         </select>
                                         <select name="c_handicap" id="c_handicap" style="width:98%;">
                                         	<option value="">Handicap</option>
                                            <option value="Yes">Yes</option>
                                            <option value="No">No</option>
                                         </select>                                        </td>
                                        <td align="right" style="width:30%"> 
                                     	  <img src="images/default-user.png" style="width:150px;" alt="">
                                          <a class="btn btn-primary imageLink" style="padding:3px;margin-right:25px;" data-toggle="modal" data-imgloc="image" role="button" href="upload_client_image.php" data-target="#imageModal">Upload Images</a>                                        </td>
                                    </tr>
          						</table>
                            </div>
                             <div id="e-11">
                             	<table width="100%" border="0" cellpadding="0" cellspacing="0" style="margin-bottom:10px;">
                                    <tr>
                                        <td valign="top" style=" width:100%;vertical-align: top;text-align:right;">
                                        	<a data-toggle="modal" data-target="#c_email_modal" href="#">
                                            <button class="btn btn-success btn-large addcode">Add</button>
                                            </a>                                        </td>
                                    </tr>
                                 </table>
                                 <table class="table table-bordered table-infinite" id="global_tbl" >
                                    <colgroup>
                                        <col class="con0" style="width:5%;"/>
                                        <col class="con1" style="width:20%;"/>
                                        <col class="con0" style="width:60%;"/>
                                        <col class="con1" style="width:4%;"/>
                                    </colgroup>
                                    <thead>
                                        <tr>
                                        <th class="head0">#</th>
                                        <th class="head1">Type</th>
                                        <th class="head0">Email</th>
                                        <th class="head1">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                        <td class="center">1</td>
                                        <td>test</td>
                                        <td>test@testing.com</td>
                                        <td class="center">
                                        	<a data-toggle="modal" data-target="#c_email_modal" href="#">
												<img src="images/Edit_16.png" style=" vertical-align: middle;">											</a>
                                            &nbsp;
                                            <a href="#">
												<img style="height:14px; vertical-align: middle;" src="images/minus.png">											</a>                                        </td>
                                        </tr>
                                        <tr>
                                        <td class="center">2</td>
                                        <td>test</td>
                                        <td>test@testing.com 2</td>
                                        <td class="center">
                                        	<a data-toggle="modal" data-target="#c_email_modal" href="#">
												<img src="images/Edit_16.png" style=" vertical-align: middle;">											</a>
                                            &nbsp;
                                            <a href="#">
												<img style="height:14px; vertical-align: middle;" src="images/minus.png">											</a>                                        </td>
                                        </tr>
								</tbody>
							</table>
                            </div>
                             <div id="e-12" >
                             	<table width="100%" border="0" cellpadding="0" cellspacing="0" style="margin-bottom:10px;">
                                    <tr>
                                        <td valign="top" style=" width:100%;vertical-align: top;text-align:right;">
                                        	<a data-toggle="modal" data-target="#c_address_modal" href="#">
                                            <button class="btn btn-success btn-large addcode">Add</button>
                                            </a>                                        </td>
                                    </tr>
                                 </table>
                                 <table class="table table-bordered table-infinite" id="global_tbl2" >
                                    <colgroup>
                                        <col class="con0" style="width:5%;"/>
                                        <col class="con1" style="width:20%;"/>
                                        <col class="con0" style="width:60%;"/>
                                        <col class="con1" style="width:4%;"/>
                                    </colgroup>
                                    <thead>
                                        <tr>
                                        <th class="head0">#</th>
                                        <th class="head1">Type</th>
                                        <th class="head0">Address</th>
                                        <th class="head1">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                        <td class="center">1</td>
                                        <td>test</td>
                                        <td>address abc test</td>
                                        <td class="center">
                                        	<a data-toggle="modal" data-target="#c_address_modal" href="#">
												<img src="images/Edit_16.png" style=" vertical-align: middle;">											</a>
                                            &nbsp;
                                            <a href="#">
												<img style="height:14px; vertical-align: middle;" src="images/minus.png">											</a>                                        </td>
                                        </tr>
                                        <tr>
                                        <td class="center">2</td>
                                        <td>test</td>
                                        <td>address 2 abc test</td>
                                        <td class="center">
                                        	<a data-toggle="modal" data-target="#c_address_modal" href="#">
												<img src="images/Edit_16.png" style=" vertical-align: middle;">											</a>
                                            &nbsp;
                                            <a href="#">
												<img style="height:14px; vertical-align: middle;" src="images/minus.png">											</a>                                        </td>
                                        </tr>
								</tbody>
							</table>
                            </div>
                            <div id="e-13" >
                            	<table width="100%" border="0" cellpadding="0" cellspacing="0" style="margin-bottom:10px;">
                                    <tr>
                                        <td valign="top" style=" width:100%;vertical-align: top;text-align:right;">
                                        	<a data-toggle="modal" data-target="#c_ph_modal" href="#">
                                            <button class="btn btn-success btn-large addcode">Add</button>
                                            </a>                                        </td>
                                    </tr>
                                 </table>
                                 <table class="table table-bordered table-infinite" id="global_tbl3" >
                                    <colgroup>
                                        <col class="con0" style="width:5%;"/>
                                        <col class="con1" style="width:20%;"/>
                                        <col class="con0" style="width:60%;"/>
                                        <col class="con1" style="width:4%;"/>
                                    </colgroup>
                                    <thead>
                                        <tr>
                                        <th class="head0">#</th>
                                        <th class="head1">Type</th>
                                        <th class="head0">Phone Number</th>
                                        <th class="head1">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                        <td class="center">1</td>
                                        <td>test</td>
                                        <td>576687999000</td>
                                        <td class="center">
                                        	<a data-toggle="modal" data-target="#c_ph_modal" href="#">
												<img src="images/Edit_16.png" style=" vertical-align: middle;">											</a>
                                            &nbsp;
                                            <a href="#">
												<img style="height:14px; vertical-align: middle;" src="images/minus.png">											</a>                                        </td>
                                        </tr>
                                        <tr>
                                        <td class="center">2</td>
                                        <td>test</td>
                                        <td>66878798989</td>
                                        <td class="center">
                                        	<a data-toggle="modal" data-target="#c_ph_modal" href="#">
												<img src="images/Edit_16.png" style=" vertical-align: middle;">											</a>
                                            &nbsp;
                                            <a href="#">
												<img style="height:14px; vertical-align: middle;" src="images/minus.png">											</a>                                        </td>
                                        </tr>
								</tbody>
							</table>
                            </div>
                   </div> <!--end tabbedwidget-->
                <script>
</script>
		</div>
		<div class="modal-footer" style="text-align: center;">
			<p>
				<button data-dismiss="modal" class="btn">Cancel</button>
				<button type="button" id="btnSearch" name="btnSearch" class="btn btn-primary">Submit</button>
				
			</p>
		</div>
	</form>
</div>

<div id="mymodal333" class="modal hide fade">
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
<h3>Edit Client</h3>
</div>
<form class="stdform" name="empform" id="empform" method="post" ENCTYPE="multipart/form-data">
<div class="modal-body" style="height:430px !important;" id="mymodal_html333"></div>
<div class="modal-footer" style="text-align: center;">
			
				<button data-dismiss="modal" id="cancel" class="btn">Cancel</button>
                <button type="submit" class="btn btn-primary" id="btnSubmit" name="btnSubmit">Submit</button>
				
			
	</div>
  </form>

</div>

<div id="mymodaladd" class="modal hide fade">
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
<h3>Add Client</h3>
</div>
<form class="stdform" name="empform" id="empform" method="post" ENCTYPE="multipart/form-data">
<div class="modal-body" style="height:auto !important; overflow:hidden;" id="mymodal_htmladd"></div>
<div class="modal-footer" style="text-align: center;">
    <button data-dismiss="modal" id="cancel_add" class="btn">Cancel</button>
    <button type="submit" class="btn btn-primary" id="btnSubmit" name="btnSubmit">Submit</button>
</div>
  </form>

</div>


<div id="imgTakephoto" class="modal hide fade" >
	  <div class="modal-header">
		<button aria-hidden="true" data-dismiss="modal" class="close" type="button">&times;</button>
		<h3 id="imgModalLabel1">Take Photo</h3>
	  </div>
	  <div class="modal-body">
		<div class="par" style="text-align: center;">
		  <div id="webcam">
			<script>
															jQuery("#webcam").html(webcam.get_html(320, 240));
														   // document.write(webcam.get_html(320, 240));
														</script>
		  </div>
		  <input type=button value="Configure..." class="btn" onClick="webcam.configure();">
		  &nbsp;&nbsp;
		  <input type=button value="Take Snapshot" class="btn" onClick="webcam.snap();">
		  &nbsp;&nbsp;
		  <input type="hidden" name="tookimage" id="tookimage">
		</div>
	  </div>
      <div class="modal-footer" style="text-align: center;">
      	<p class="stdformbutton">
		  <button data-dismiss="modal" id="webcam_cancel" class="btn">Cancel</button>
		  <button data-dismiss="modal" class="btn btn-primary">Save Changes</button>
		</p>
      </div>
</div>
	
</body>
</html>



<script>
function printDiv(divName) { 
	jQuery("#"+divName).printElement({
		 overrideElementCSS:[
			'css/styles_inner.css',
		{ href:'css/print.css',media:'print'}]
	 });
}

jQuery('#client_add').click(function(){
	jQuery("#client_modal").modal('hide');
	//btnClose
	jQuery('.loading-image').show();
	var hid= jQuery('#hidSalesID').val();
	jQuery.ajax({
				type: "POST",
				url: "edit_client.php",
				
				data : { "client_id" :'', "lhid": hid },
				
			}).done(function(msg){				
				jQuery("#mymodal_htmladd").html(msg);
				jQuery("#mymodaladd").modal('show');
			});
				jQuery('.loading-image').hide();
	});

function slideupDown(cls){
		
	if ( jQuery( "."+cls ).is( ":hidden" ) ) {
		
		jQuery( ".mybar" ).slideUp( "slow" );
		jQuery( "."+cls ).slideDown( "slow" );
	}else
		jQuery( "."+cls ).slideUp( "slow" );
	
}
function slideupDown_inner(cls){
		
	if ( jQuery( "."+cls ).is( ":hidden" ) ) {
		
		//jQuery( ".mybar" ).slideUp( "slow" );
		jQuery( "."+cls ).slideDown( "slow" );
	}else
		jQuery( "."+cls ).slideUp( "slow" );
	
}
function slideupDown_Popup(cls){
		
	if ( jQuery( "."+cls ).is( ":hidden" ) ) {
		
		jQuery( ".mybar_p" ).slideUp( "slow" );
		jQuery( "."+cls ).slideDown( "slow" );
	}else
		jQuery( "."+cls ).slideUp( "slow" );
	
}
var params;
    var allowed = true;//set to true upon user selecting allow on camera dialog
    webcam.set_api_url('webcam_upload_client.php');
    webcam.set_quality(90); // JPEG quality (1 - 100)
    webcam.set_shutter_sound(false); // play shutter click sound
   // webcam.set_hook( 'onAllowed', 'on_allowed' );
    webcam.set_hook('onComplete', 'my_callback_function');

    function on_allowed(){
        allowed = true;//user clicked allow
    }
			
	function my_callback_function(response) {
		
		jQuery("#digital_image_name").val(response);
		//jQuery('#tempImage').val();
		//jQuery("#mainimage").attr("src",'data:image/jpg;base64,'+response);
		jQuery('#imagebox').html('<img src="data:image/jpg;base64,'+response+'" width="150px;" height="150px;">');
	}
                

function msg()
{
    alert('ERROR - Camera not detected');
}
</script>
<!--order menu group orange ends-->
<!--check folder Check pages ends-->
<script type="text/javascript">
setTimeout(function(){
    var now= new Date() 
    ampm= 'am'
    h= now.getHours()
	
    m= now.getMinutes()+30;
	if (m >= 60){
	   h++;	
	}
    
  },500);
  
	
	var fodaoss= false;
	var iiddglobal=0;
	var ocxop=0;
	var cepao=0;
	var sentitemsnow=false;
	var pgho='';
  var lastpagao='';
  var global_totkkj=0;
  var changed_order=false;
  var jclkbt=false;
 
var default_paymentselected='Cash';
var defpayidds=""; 
function checkauthonow()
{ 
	if (lastpagao != 'wrapper15')
	{
		var achouitp=false;
	  	if (jclkbt==false)
		{
	  		setTimeout(function()
			{   
		 		if (authovl.length >=1 )
				{
					for (var xgh=0;xgh<authovl.length;xgh++)
					{
						if (achouitp == false)
						{
				 			var vx=authovl[xgh];  
				       		if (vx.autho_AMOUNT > 0)
							{  
				      			achouitp=true;
					  			payment('Credit Card');
					  			payment('salefn');							  
							 /* if (authovl.length ==1 ){
								   
							  setTimeout(function(){
								openauthodetails(0,'asw'+vx.id);
							  },2000);
							  }*/
						  }//if (vx.autho_AMOUNT > 0){  
				   		}//if (achouitp == false){
					}//for (var xgh=0;xgh<authovl.length;xgh++){
				}//if (authovl.length >=1 ){
				jclkbt=false;
	  		},1000);
		}			
	}
	else
	{  
  		jclkbt=false;
  	}
}
function checkauthonowopp(){
		  
		 if (jclkbt == false){
	  var achouitp=false;
	  setTimeout(function(){   
		 	if (authovl.length >=1 ){
				for (var xgh=0;xgh<authovl.length;xgh++){
					if (achouitp == false){
				
				 var vx=authovl[xgh];  
				   
				  if (vx.autho_AMOUNT > 0){  
				   openpage('wrapper15');
				      achouitp=true;
					  payment('Credit Card');
					  payment('salefn');
					
				  }
				   }
				}
			}
				
	  },1000);
					}
  }

function openpageconfitrue(idp){
	//jQuery("#item-tab").removeClass("ui-state-active");
		//jQuery("#payment_tab").addClass("ui-state-active");
		//jQuery("#e-8").addClass("ui-tabs-panel ui-widget-content ui-corner-bottom ui-tabs-hide");
		/*if(pay == 0)
		{
			jQuery("#payment_tab").click();			
		}else if(pay == 1){
			jQuery("#payment_tab_edit").click();			
		}else{
			jQuery("#payment_tab").addClass("ui-state-default ui-corner-top ui-state-disabled");
			jQuery("#payment_tab_edit").addClass("ui-state-default ui-corner-top ui-state-disabled");			
		}*/
		
		//defaultarraypayments = new Array();
		//loadpayment_types();
		if(idp == 'wrapper15' && global_podeoutroserver == false){
			jQuery.alerts.okButton = 'Ok';
			jAlert('You cannot apply Payments to this check.','Alert Dialog');
			//return false;
			////stoppreload();
			setTimeout(function(){
				jQuery("#item_open").click();
				jQuery("#item_second").click();
			},200);
			return false;
			}
		
		if(global_podeoutroserver == false){
			jQuery.alerts.okButton = 'Ok';
			jAlert('You can not create a Check for another Server.','Alert Dialog');			
			return false;
			jQuery("#item_second").click();
		}
		
		fcn();
	var k = 0;
		globa_array_items_selected_added = new Array();
	    $("#tableofitems1 input:checkbox[name=checkboxitemsadded]").each(function(){
	      k++;
		});  
    if (k >= 1){
		
		 
	// submititens();
	       /*   global_order_id=0;
			  
	          global_order_idss=0;
			  checklist_home_left();*/
		      openpage(idp); 
			  
			  
		 
	}else{
		 
	         /* global_order_id=0;
			  
	          global_order_idss=0;
			  checklist_home_left();*/ 
		openpage(idp);
	}
}
function openpageconfi(idp)
{
	var k = 0;
	globa_array_items_selected_added = new Array();
	$("#tableofitems1 input:checkbox[name=checkboxitemsadded]").each(function(){
		k++;
	});  
	
	if (k >= 1)
	{
		////stoppreload();
		jQuery.alerts.okButton = 'Send';
		jQuery.alerts.cancelButton = 'Discard';  
		jConfirm('Would you like to send or discard your changes before exting this page?', 'Confirm', function(r)
		{
			if(r){ 
				submititens(1);
			}
			else
			{
				global_order_id=0;
				global_order_idss=0;
				checklist_home_left();
				openpage(idp); 
			}
		});
	}else{   
		if (defarrayzaoprodord.length == 0 && $(".hd1").html() != '' )
		{  
			////stoppreload();
			jQuery.alerts.okButton = 'Yes';
			jQuery.alerts.cancelButton = 'No';  
			jConfirm("Would you like to cancel this check?","Confirm Dialog",function(r)
			{
				if(r)
				{ 
					var urll=transformurl(GLOBAL_url+"api2/pos/cancelorder.php?order_id="+global_order_id+"&emp_id="+geralzaoid);
					//startpreloader();
					$.getJSON(urll, { format: "json" },
					function(data)
					{
						////stoppreload(); 
						global_order_id=0;
						global_order_idss=0;
						//checklist_home_left();
						//openpage(idp); 
						window.location.href = 'restaurant_register.php'; 
					});
					
				}else{
					window.location.href = 'restaurant_register.php'; 
					global_order_idss=0;
					global_order_id=0;
					checklist_home_left();
					openpage(idp); 
				}
			});
		}else{
			window.location.href = 'restaurant_register.php'; 
			global_order_idss=0;
			global_order_id=0;
			//checklist_home_left();
			openpage(idp);
		}
	}
}

var global_payments_access = jQuery("#access_pos_payments").val();;
function foropenpg(idp)
{ 
	if (vision == 'Bar')
	{
		if (idp == 'wrapper8')
		{
			idp='wrapper26';
		}
	}	
	dopa=false;
	sendpaymen=false;   
	lastpagao=idp;
   	iiddglobal=idp;	
}


 var noneedopenpg = false; 
 var vision ; 
 var checkDetailLoaded = false;
function openpage(idp)
{	
	var goahead = true;
	
	if ((idp == 'wrapper23')&&(lastpagao=='wrapper11')&&($("#tbl").val()=='')&&(default_tab == 'tabletab'))
	{
		jAlert('Please select a table','Alert Dialog'); 
		jQuery('#Right_main').hide();
		goahead = false;
		return false;
	}else if((idp == 'wrapper23' || idp == 'wrapper15') && global_podeoutroserver1 == false && emp_login_id!=$('#srvr').val() && $('#srvr').val()!='' && $('#srvr').val()!=null){
		jQuery.alerts.okButton = 'Ok';
		jAlert('You can not create a Check for another Server.','Alert Dialog');
		//return false;
		//stoppreload();
		goahead =false;
		setTimeout(function(){
			jQuery("#item_open").click();
			jQuery("#item_second").click();
		},200);
		return false;
	}else
	{	
		if(goahead && idp!='wrapper11' && jQuery('#order_id').val()!=''){
			
			jQuery('#Right_main').show();
		}
		//alert(global_podeoutroserver);
		//alert(idp);
		if(idp == 'wrapper15' && global_podeoutroserver == false){
			jQuery.alerts.okButton = 'Ok';
			jAlert('You cannot apply Payments to this check.','Alert Dialog');
			goahead = false;
			//return false;
			//stoppreload();
			setTimeout(function(){
				jQuery("#item_open").click();
				jQuery("#item_second").click();
			},200);
			return false;
		}else if(idp == 'wrapper23' && global_podeoutroserver == false){
			jQuery.alerts.okButton = 'Ok';
			jAlert('You cannot add items to this check.','Alert Dialog');
			//return false;
			//stoppreload();
			setTimeout(function(){
				jQuery("#item_open").click();
				jQuery("#item_second").click();
			},200);
			return false;
		}
		if (vision == 'Bar')
		{
			if (idp == 'wrapper8')
			{
				idp='wrapper26';
			}
		}
		if (idp == 'wrapper2')
		{
			jQuery("#emp_password").val("");	
			//$("#searchfld").val("");	
			searchit(); 
		}
		//console.log('changed_order:'+changed_order);
		if (changed_order)
		{
		  changetypeoforder();
		  changed_order=false;	
		}
		else
		{
			if (($("#menuview").val()  != "List") && (lastgroupaag != ''))
			{
			  showgroups();
			  lastgroupaag='';
			}
			//console.log('noneedopenpg:'+noneedopenpg);
			 if (noneedopenpg == false)
			 {
			 	var postgg=false;			
				if (idp == "wrapper23")
				{
					
					if ((va_global_order == 'closed')||(va_global_order == 'cancelled'))
					{					
						if (sendpaymen)
						{
							va_global_order='';					
							postgg=true;					
							//console.log('clearing Global_ID          00000015');
							global_order_id=0;
							global_order_idss=0;
							foropenpg('wrapper8');   
						}
						else
						{
							postgg=true;
							jAlert("This order is closed. You cannot add items!",'Alert Dialog');
							return false;
						}
						////stoppreload(); 
					}
					else
					{
						postgg == false;  
					}				
				}
				//console.log('postgg:'+postgg);
				//console.log('dopa:'+dopa);
				if (postgg == false)
				{	
								if (idp == "wrapper15")
								{
								jQuery("#ccinput").focus();	
									if (isiPadnow == false)
									{	
										jQuery("#ccinput").val("");
										
									}
								}
								if (idp == "wrapper2")
								{
									//console.log('clearing Global_ID          00000016');
									global_order_id=0;
									global_order_idss=0;		
								}		
								if (idp == 'wrapper8')
								{
									$("#methodofpayment").html(defaultpmstring);
									 
								}
								pgho=lastpagao;
								
								if (idp == 'wrapper3')
								{ 
									var msg1=""; 
									if (pgho == 'wrapper8')
									{
										msg1="The homepage allows you to view active tabs in real time. It also provides easy access to view active receipts and any pending payments.";
									}else if (pgho == 'wrapper4')
									{
										msg1="The Employee page allows your employees to Clock In and Out with ease. As well as see their monthly schedule and send or receive messages.";
									}else if (pgho == 'wrapper5')
									{
										msg1="The Schedule page will display your monthly schedule and the first week of the incoming month if already assigned.";
									}else if (pgho == 'wrapper11')
									{
										
										msg1="The New Tab page allows your employees to enter in everything needed to start and close out a tab including choosing clients, ordering items, and completing the payment process.";
									}else
									{							
									}
									msg1+="\nIf you have any questions or issues please contact SoftPoint Support at +1 (800) 915-4012"
						
									jAlert(msg1,'Alert Dialog');
								}
								else
								{			
								if (idp == 'wrapper333')
								{
									idp='wrapper3';
								}
			
								if (dopa){
									idp = 'wrapper15'; 
								}
								
								if (idp == 'wrapper8')
								{
									paytotal=0;	
								}
								pagina_agora = idp;
								var goto=true;   
								var mss="";		
								var s2=vtotalzao;		 
								s2=ckv(s2);  
								
								if (idp == 'wrapper15')
								{ 
									/*if ((global_payments_access != 'yes'))
									{
										goto=false;						
										jAlert('A payment is not authorized at this time.','Alert Dialog');
										return false;
									}*/
									
									if (global_view != 'Table')
									{
										jQuery("#containerseats").show();
									}
									else
									{
										jQuery("#containerseats").hide();	
									}
					
								}  
					
								if (((idp == 'wrapper15') && (s2 == 0))&&(libgeralpay==false))
								{ 
									if (arraydeitemstosend.length > 0 )
									{ 		 
										goto=false; 
										//stoppreload();
										jQuery.alerts.okButton = 'Send';
										jQuery.alerts.cancelButton = 'Discard';  
										/*for add check*/
										jConfirm("Do you want to send the added item(s)?","Confirm",function(r)
										{
											if (r)
											{  
												sentitemsnow=true;
												submititens();
								
											}
											else
											{
												arraydeitemstosend_mdf = new Array();
                                                arraydeitemstosend = new Array();
                                                def_m_o_d = new Array();                                                
                                                loaditemsadded();
                                                sentitemsnow = false;
											}
										}); 
					 
									}
									else
									{
										if (sentitemsnow)
										{
											setTimeout(function(){
												
												openpage('wrapper15');
											},3000);
										}
										else
										{
											goto=false;
											//openpage('wrapper23');
											//stoppreload();
											if ((sendpaymen)&&(va_global_order == 'closed'))
											{			 
												foropenpg('wrapper8');	
											}
											else
											{ 
											if ((lastpagao != 'wrapper15' )&&(lastpagao != 'wrapper8' )&&(global_order_id != ''))
											{												
												mss="Please Add Items before add Payments!";
												setTimeout(function(){
													// foropenpg('wrapper23');
													returnseatsbyorder();	
												},2000);
												
											}
											else
											{
												goto=true; 
											}
										}
									}
								}
							}
							else if (((idp == 'wrapper15') )&&(libgeralpay==false))
							{ 
								if (arraydeitemstosend.length > 0 )
								{ 	 
									goto=false;  
									//stoppreload();
									jQuery.alerts.okButton = 'Send';
									jQuery.alerts.cancelButton = 'Discard';  
									
									/*for edit check*/
									jConfirm("Do you want to send the added item(s)?","Confirm",function(r)
									{
										if (r)
										{  
											  //goto=true;
											//jQuery("#payment_open1").attr("href","#e-9");																				
											//jQuery("#payment_open1").click();	
											 sentitemsnow=true;
											 submititens();
										}
										else
										{
											arraydeitemstosend_mdf = new Array();
											arraydeitemstosend = new Array();
											def_m_o_d = new Array();											
											loaditemsadded();
											sentitemsnow = false;
										}
									}); 		 
								} 
							}
							else
							{
							}	
							
							if (((idp == 'wrapper11') ))
							{ 
								checkDetailLoaded = false;
								if (arraydeitemstosend.length > 0 )
								{
									
									goto=false;  
									//stoppreload();
									jQuery.alerts.okButton = 'Send';
									jQuery.alerts.cancelButton = 'Discard';  
									jConfirm("Do you want to send the added item(s)?","Confirm",function(r)
									{
										if (r)
										{  
											nopens=false;
											//goto=true; 
											sentitemsnow=true;
											submititens();
											
										}
										else
										{
											arraydeitemstosend_mdf = new Array();
											arraydeitemstosend = new Array();
											def_m_o_d = new Array();											
											loaditemsadded();
											sentitemsnow = true;
											openpage('wrapper11');
											arraydeitemstosend = new Array();											
											$("#item_open").click();
											// openpage('wrapper11');
										}
									}); 		 
								}				
							}
				   
							if (idp == "wrapper23")
							{   	
								if (fodaoss)
								{
									loadtb();
								}
								fodaoss=true;   
								jQuery("#searchfldscnd").val("");
								if (global_order_id > 0)
								{ 
									setTimeout(function(){		 			
										updatecoversorder();
									},100)
								}
								else
								{
									goto=false;
									//console.log('n order 002');
									insertneworder();  
									mss="";	
								}		 
								global_order_idss = 0;								
								if($('#order_id').val()>0){
									if(!checkDetailLoaded){
										updatecheckDetails();
									}
								}
								  
							}
							if (goto == false)
							{
								if (mss == "")
								{
								}
								else
								{
									
									jAlert(mss,'Alert Dialog');
								
								}
							}
							else
							{ 
								if (idp == 'wrapper8')
								{
									//global_order_id='';
									
								}
								iiddglobal=idp;
								
								if (idp == 'wrapper11')
								{
									//var ga='';
									//var p = $("#tblkkji");
									//var offset = p.offset(); 
									//var gs1=offset.top;
									
									//var p2 = $("#empee8");
									//var offset2 = p2.offset(); 
									////console.log(offset2); 
									
									//var gs2=offset2.top;
									//var ga = gs2-gs1;  			
									
									//jQuery("#tblkkji").css({"height":ga,"max-height":ga,"min-height":ga});
								}
								if (idp == 'wrapper8')
								{
									if (global_forcerleft)
									{
									   checklist_home_left();	
									} 
								}
												
								if (idp == "wrapper9")
								{ 
									np();
									loadtablesshow();
									//startpreload();
								}	
				
								if (idp == "wrapper2"){ 
									jQuery("#emp_password").focus();
								}
							
								if (idp == "wrapper15")
								{
									checkauthonow(); 
									libpay=false;
									jQuery("#containerseats").html("");
									returnseatsbyorder();
								}
				
								if (idp == "wrapper11")
								{ 
									if (cepao != 1)
									{
										mostrabts();
									}
									if (cepao != 1)
									{
										if (ocxop == 0)
										{
											setTimeout(function()
											{
												np();
											},1000);
										}
										else
										{
											// if ((va_global_order == 'closed')||(va_global_order == 'cancelled')){
											if ((va_global_order == 'closed')||(va_global_order == 'cancelled'))
											{
												$("#aaw2ps").hide(); 
												$(".buttonsmid").hide(); 
											}
											else
											{
												$("#aaw2ps").show(); 
												$(".buttonsmid").show(); 
												
											}
											$("#aaw2ps2").show();
											//$(".btnbtm-bluess").show();
											if(true)
											{
												$("#right-panel-oi").show();
												setTimeout(function()
												{
													$("#right-panel-oi").show();
													if ((va_global_order == 'closed')||(va_global_order == 'cancelled'))
													{
														$("#aaw2ps").hide(); 
														$(".buttonsmid").hide(); 
													}
													else
													{
														$("#aaw2ps").show(); 
														$(".buttonsmid").show();
														
													} 
													$("#aaw2ps2").show();
													//$(".btnbtm-bluess").show();
											  },1200);
											}
										}
									}
									ocxop=0; 
									jQuery("#").hide();
									cepao=0;
								}		  
								
								if (idp == "wrapper23")
								{
									mostrabts();
									if (global_order_id > 0)
									{			  
									}
									else
									{
										 //console.log('n order 001');
										insertneworder();  
									}		 
									if (global_menu_id == "")
									{
										return_listmenu();  
									}
									else
									{
										//loadmenu(global_menu_id);	
									}										   
					  
									jQuery('.content_int').show();
								}
								else if (idp == "wrapper4")
								{
									jQuery('.content_int').show();
									//sessionCheck();
									
					   
								}
								else if (idp == "wrapper5")
								{
									//showSchedule();	
								}
								else if (idp == "wrapper8")
								{ 
									if (global_menu_id == "")
									{
										return_listmenu();  
									}
									//chkmsg();
									var id_of_check = '<?php echo $_REQUEST['id'];?>';
									if(delivery == 'Y'){
										if(jQuery("#dispatch_id").val() != '' && jQuery("#dispatch_id").val() != null)
										{
											window.location.href = 'delivery_dispatch.php?id='+jQuery("#order_id").val()+"&timereq="+'<?php echo $_GET["timereq"];?>'+"&d_status="+'<?php echo $_GET['d_status']; ?>'; 
										}else{
											window.location.href = 'restaurant_register.php'; 
										}
									}else{
										window.location.href = 'restaurant_register.php'; 
									}
								}
								else if (idp == "wrapper2")
								{
									jQuery(".nomess").hide(); 
									jQuery("#nameemp").html("");
									jQuery("#timetable_info").html("");
									jQuery("#caixamess").html(""); 	
								}					
							}
						}
					}
				}				
				
				if (idp == "wrapper11" && goahead){ 
									
					if(jQuery('#order_id').val()!=''){
						jQuery('#Right_main').show();
						
					}else{
						jQuery('#Right_main').hide();
						
						
					}
				}
				lastpagao=idp; 
				
				
				
				
				if (idp == 'wrapper4')
				{
					//var p = $("#containerbtm");
					//var offset = p.offset(); 
					////console.log(offset); 
					//var gs1=offset.top;
				
					//var p2 = $("#timetable_info");
					//var offset2 = p2.offset(); 
					////console.log(offset2); 
					//var gs2=offset2.top;
					//var g = gs1-gs2; 
					//$("#timetable_info").css({"height":g,"max-height":g});
					//g=g+23;
					//$("#caixamess").css({"height":g,"max-height":g});
				}
				else if (idp == 'wrapper11')
				{
					$("#tblkkji").scrollTop(0);
				}
			}
		}	
} 
function payment2a(abc){
	var ppc1=$("#pymntyp").val();
	var ppc2=$("#vlpayment_code").val();
	var sp = $("#abc-autho").attr('class'); 
	if ((sp == 'auth-in') && (abc  == 'salefn')){  
	
	}else{
	  if (((sp == 'auth-in') && (abc  == 'autho'))||((sp = 'auth') && (abc  == 'salefn'))){
		   
		payment(abc); 
		
			$("#pymntyp").val(ppc1);
			$("#vlpayment_code").val(ppc2);
		setTimeout(function(){
			$("#pymntyp").val(ppc1);
			$("#vlpayment_code").val(ppc2);
			var abcd=$("#vlpayment_code").val();
	        var sname=abcd[1]; 
	        defpayidds=abcd[0];
			
        	sname=sname.toLowerCase();
	        if ((sname.indexOf('amex')>=0)||(sname.indexOf('american')>=0)||(sname.indexOf('dinner')>=0)){
		       defsize=4;
	        }else{
	           defsize=3;	
	        }
	       // document.getElementById('cc_ccv').maxLength = defsize; 
	
		},1000);
	  }
	}
	
 
}

function payment(abc)
{
	
	$("#poaa").val("");
	$("#receivedam").val("");
	$("#gift").hide();
	ggg_authorization = 0;
	defsize=4;
	setTimeout(function(){ 
		defsize=4;
 	},2000);	
	 
	defaultselectedautho_amount=0;
	$("#conthotelauth").hide();
	$("#btnauthsaleht").hide();
	//alert(abc);
    $("#kjhkjh").hide();
    if ((abc == 'Interface')||(abc == 'Room Charge'))
	{
		$("#vlpayment_code").val('');
		$("#ht_amount").val($("#vlamountdue").html()); 
		if (global_cliida > 0 )
		{
			findroom();
		} 
	}
               
	$('#htt_client_room,  #htt_client_name, #htt_client_account,#htt_amount').css("display", "none");
	//$("#fku").show();
	$("#fku2").show();
	$('#btnauthsale, #paymenttype, #gratuity,#ccnum, #mannualautho, #payment, #scancardgreenbtn, #sbmtpaymntbluebtn').css("display", "block");
	$('#amountdue,  #btnsrechng, #visaamxbtns,#gratuitya, #txtsmall, #total, #amount1, #totalamount, #clintexpntabid, #prcntadjsmntamnt, #descriptiontxtarea, #scanepntabgreenbtn').css("display", "none");
		    
	def_vl_p=abc;
	//$("#vlpayment").val(0);
	$("#sbmbt").html("Submit Payment");
	//$("#methodofpayment").val(abc);
	default_paymentselected=abc;
	
	if (abc == 'salefn'){
		default_paymentselected="Credit Card";
	}
	if (abc == "Gratuity")
	{
		$('#paymenttype, #amountdue, #gratuity, #gratuitya,  #sbmtpaymntbluebtn').css("display", "block");
		$('#btnauthsale, #ccnum, #mannualautho,#payment, #scancardgreenbtn,#btnsrechng, #visaamxbtns, #txtsmall, #total, #amount1, #totalamount, #clintexpntabid, #prcntadjsmntamnt, #descriptiontxtarea, #scanepntabgreenbtn,#receivable').css("display", "none");
		$('#methodofpayment').val(abc);
		//document.getElementById('methodofpayment-input').value = abc;
	}else if (abc == "Cash")
	{
		$('#paymenttype, #amountdue, #gratuity, #payment, #btnsrechng, #sbmtpaymntbluebtn').css("display", "block");
		$('#btnauthsale, #ccnum, #mannualautho, #scancardgreenbtn, #visaamxbtns, #txtsmall, #total, #amount1, #totalamount, #clintexpntabid, #prcntadjsmntamnt, #descriptiontxtarea, #scanepntabgreenbtn,#receivable').css("display", "none");
		$('#methodofpayment').val(abc);
		$('#gratuity2').val('');
		//document.getElementById('methodofpayment-input').value = abc;
	}
	if (abc == "Gift Certificate")
	{
		$('#paymenttype, #amountdue, #gratuity, #payment,#gift,  #sbmtpaymntbluebtn').css("display", "block");
		$('#btnauthsale, #ccnum, #mannualautho, #scancardgreenbtn,#btnsrechng, #visaamxbtns, #txtsmall, #total, #amount1, #totalamount, #clintexpntabid, #prcntadjsmntamnt, #descriptiontxtarea, #scanepntabgreenbtn,#receivable').css("display", "none");
		$('#methodofpayment').val(abc);
		//document.getElementById('methodofpayment-input').value = abc;
	}else if (abc == "Credit Card")
	{		
		ggg_authorization = 1;
		$("#fku").show();
		$("#fku2").show();
		var sda=parseFloat(parseFloat(deftotal)+(parseFloat(deftotal) * 0.15)).toFixed(2);
		var grt=parseFloat((parseFloat(deftotal) * 0.15)).toFixed(2); 
		$("#gratuity2").val(grt);
		$("#receivedam").val(sda);
		$("#vlpayment").val(sda);
		$('#amountdue,#btnauthsale, #paymenttype, #gratuity,#ccnum, #mannualautho, #payment, #scancardgreenbtn, #sbmtpaymntbluebtn').css("display", "block");
		$('  #btnsrechng, #visaamxbtns, #txtsmall, #total, #amount1, #totalamount, #clintexpntabid, #prcntadjsmntamnt, #descriptiontxtarea, #scanepntabgreenbtn,#receivable').css("display", "none");
		$('#methodofpayment').val(abc);
		if (arrayofclientsorder.length > 0)
		{
			var name = arrayofclientsorder[0].cli_name.split(" ");
			jQuery("#first_name_cc").val(name[0]);
			jQuery("#last_name_cc").val(name[1]);
		}
		
		//document.getElementById('methodofpayment-input').value = abc;
	}else if (abc == "Debit Card")
	{
		var sda=parseFloat(vtotalgr).toFixed(2);
  		$("#vlpayment").val(sda);
		$("#receivedam").val(sda);
		$('#paymenttype, #ccnum, #amount1, #totalamount, #scancardgreenbtn, #sbmtpaymntbluebtn, #txtlrgcc, #gratuity').css("display", "block");
		$('#amountdue, #payment, #btnsrechng, #btnauthsale, #mannualautho, #visaamxbtns, #txtsmall, #total, #clintexpntabid, #prcntadjsmntamnt, #descriptiontxtarea, #scanepntabgreenbtn, #txtlrgamx,#receivable').css("display", "none");
		$('#methodofpayment').val(abc);
		//document.getElementById('methodofpayment-input').value = abc;
		if (arrayofclientsorder.length > 0)
		{
			var name = arrayofclientsorder[0].cli_name.split(" ");
			jQuery("#first_name_cc").val(name[0]);
			jQuery("#last_name_cc").val(name[1]);
		}
	}else if (abc == "ExpenseTAB")
	{
		jQuery("#clientetac").val("");
		jQuery("#clientemail").val("");
		if (arrayofclientsorder.length > 0)
		{ 
		 	jQuery("#clientemail").val(arrayofclientsorder[0].cli_email);
		    checkemailet(arrayofclientsorder[0].cli_email,false);	
		}
		
		$('#clintexpntabid, #scanepntabgreenbtn, #sbmtpaymntbluebtn,  #amountdue').css("display", "block");
		$('#paymenttype, #ccnum, #amount1, #totalamount, #scancardgreenbtn, #gratuity, #payment, #btnsrechng, #btnauthsale, #mannualautho, #visaamxbtns, #txtsmall, #total, #prcntadjsmntamnt, #descriptiontxtarea,#receivable').css("display", "none");
		$('#methodofpayment').val(abc);
		//document.getElementById('methodofpayment-input').value = abc;
	}else if (abc == "Adjustments")
	{
		//$("#sbmbt").html("Submit Adjustment");
		jQuery('#clintexpntabid, #scanepntabgreenbtn,  #ccnum, #totalamount, #scancardgreenbtn, #gratuity, #amountdue, #payment, #btnsrechng, #btnauthsale, #mannualautho, #visaamxbtns, #txtsmall, #total,#receivable').css("display", "none");
		jQuery('#paymenttype, #prcntadjsmntamnt, #amount1, #descriptiontxtarea, #sbmtpaymntbluebtn').css("display", "block");
		
		jQuery('#methodofpayment').val(abc);
		//document.getElementById('methodofpayment-input').value = abc;
	}else if (abc == "Surcharge")
	{ 
		$('#paymenttype, #amount1, #descriptiontxtarea, #sbmtpaymntbluebtn').css("display", "block");
		$('#prcntadjsmntamnt, #clintexpntabid, #scanepntabgreenbtn, #ccnum, #totalamount, #scancardgreenbtn, #gratuity, #amountdue, #payment, #btnsrechng, #btnauthsale, #mannualautho, #visaamxbtns, #txtsmall, #total,#receivable').css("display", "none");
		$('#methodofpayment').val(abc);
		//document.getElementById('methodofpayment-input').value = abc;
	}else if (abc == "autho")
	{ 
		ggg_authorization = 1;
		$("#fku").show();
		$("#fku2").show();
	    cleanpayment();
	    payment('Credit Card'); 
		$("#abc-autho").attr('class', 'auth');
		$("#abc-salefn").attr('class', 'sale-in');
		$('#btnauthsale, #gratuity,#paymenttype, #ccnum,#amountdue, #mannualautho, #payment, #scancardgreenbtn, #sbmtpaymntbluebtn').css("display", "block");
		$('#btnsrechng, #visaamxbtns, #txtsmall, #total, #amount1, #totalamount, #clintexpntabid, #prcntadjsmntamnt, #descriptiontxtarea, #scanepntabgreenbtn,#receivable').css("display", "none");
	}else if (abc == "salefn") 
	{
		ggg_authorization = 0;
		var pgs=deftotal; 
	    cleanpayment();
		deftotal=pgs;  
		payment('Credit Card');  
	/*	$("#content_autho").html(srglobb);
		$("#fku").hide();
		//$("#fku2").hide();
		var sda=parseFloat(deftotal).toFixed(2);
  		$("#vlpayment").val(sda);
		$("#abc-autho").attr('class', 'auth-in');
		$("#abc-salefn").attr('class', 'sale');
		$("#gratuity2").val("");
		$('#amountdue,#btnauthsale,#visaamxbtns, #txtsmall, #payment, #gratuity, #sbmtpaymntbluebtn,#kjhkjh').css("display", "block");
		$(' #paymenttype, #ccnum, #mannualautho, #scancardgreenbtn,  #btnsrechng, #scanepntabgreenbtn,#total, #amount1, #totalamount, #clintexpntabid, #prcntadjsmntamnt, #descriptiontxtarea,#receivable').css("display", "none");
		$('#btnauthsale, #paymenttype, #ccnum,#amountdue, #mannualautho, #payment, #gratuity,#scancardgreenbtn, #sbmtpaymntbluebtn').css("display", "block");
		//$("#fku").show(); */
		setTimeout(function() {
			$("#content_autho").html(srglobb);
			setTimeout(function() {
				$("#content_autho").html(srglobb);
			}, 1000);
			$("#fku").hide();
			$("#fku2").hide();
			var sda=parseFloat(deftotal).toFixed(2);
			$("#vlpayment").val(sda);
			$("#abc-autho").attr('class', 'auth-in');
			$("#abc-salefn").attr('class', 'sale');
			$("#gratuity2").val("");
			$('#amountdue,#btnauthsale,#visaamxbtns, #txtsmall, #payment, #gratuity, #sbmtpaymntbluebtn,#kjhkjh').css("display", "block");
			$(' #paymenttype, #ccnum, #mannualautho, #scancardgreenbtn,  #btnsrechng, #scanepntabgreenbtn,#total, #amount1, #totalamount, #clintexpntabid, #prcntadjsmntamnt, #descriptiontxtarea,#receivable').css("display", "none");
			$('#btnauthsale, #paymenttype, #ccnum,#amountdue, #mannualautho, #payment, #gratuity,#scancardgreenbtn, #sbmtpaymntbluebtn').css("display", "block");
			//$("#fku").show(); 
			if (srglobb!='') { // juni [req REQ_021] - 2014-09-29 -  POS - Sep 27 - CJ06.jpg -> do not display cc info if i already have made a payment using cc
				//if (isDebugFine()) //console.log('index - payment() - hide cc');
				$('#ccnum').css('display','none');
			}			
			$("#fku2").show();

		}, 1000);
		
	}else if (abc == "visafn")
	{
		$('#visaamxbtns, #txtsmall, #payment, #gratuity,  #sbmtpaymntbluebtn').css("display", "block");
		$('#btnauthsale, #paymenttype, #ccnum, #mannualautho,#total, #scancardgreenbtn,  #amountdue, #btnsrechng, #scanepntabgreenbtn, #amount1, #totalamount, #clintexpntabid, #prcntadjsmntamnt, #descriptiontxtarea,#receivable').css("display", "none");
	}else if (abc == "amxfn")
	{
		$('#visaamxbtns, #txtlrgamx').css("display", "block");
		$('#txtsmall, #payment, #gratuity,  #sbmtpaymntbluebtn, #btnauthsale, #paymenttype, #ccnum, #mannualautho, #scancardgreenbtn, #txtlrgcc, #amountdue, #btnsrechng, #total,#total, #amount1, #totalamount, #clintexpntabid, #prcntadjsmntamnt, #descriptiontxtarea,#receivable').css("display", "none");
	}else if ((abc == "Room Charge")||(abc == "Interface")||(abc == "salefn-ht")||(abc == "autho-ht"))
	{ 
	 	//$("#btnauthsaleht").show();
		$('#amountdue,#btnauthsale,#visaamxbtns,   #payment, #gratuity, #total,#visaamxbtns, #txtsmall, #payment, #gratuity, #total, #sbmtpaymntbluebtn,#btnauthsale, #paymenttype, #ccnum, #mannualautho, #scancardgreenbtn,  #amountdue, #btnsrechng, #scanepntabgreenbtn, #amount1, #totalamount, #clintexpntabid, #prcntadjsmntamnt, #descriptiontxtarea,#receivable').css("display", "none");  
		$('#paymenttype, #sbmtpaymntbluebtn,#htt_client_room,#containerseats,  #htt_client_name, #htt_client_account,#htt_amount, #gratuity').css("display", "block"); 
			if (abc == 'salefn-ht'){
				global_authorizehotel=0;  
		        payment('Interface');
				abc='Interface';
			}else if (abc == 'autho-ht'){
				global_authorizehotel=1;
		         payment('Interface'); 
				abc='Interface';
			}
			$('#methodofpayment').val("Interface");
			//document.getElementById('methodofpayment-input').value = 'Interface';
			if (global_authorizehotel == 0){
				$("#abc-autho-ht").attr('class', 'auth-in');
		        $("#abc-salefn-ht").attr('class', 'sale');
				$("#conthotelauth").show();			   
			}else{
				 $("#abc-autho-ht").attr('class', 'auth');
		         $("#abc-salefn-ht").attr('class', 'sale-in'); 
			     $("#conthotelauth").hide();
				 setTimeout(function(){
				  	document.getElementById('ht_client_name').disabled=false;
					document.getElementById('ht_client_account').disabled=false;
					document.getElementById('ht_client_room').disabled=false; 
				 },1500); 
				 
			}			
	}else if(abc == "Receivables")	{
		$('#receivable,#paymenttype, #amountdue, #gratuity, #payment,  #sbmtpaymntbluebtn').css("display", "block");
		$('#btnauthsale, #ccnum, #mannualautho, #scancardgreenbtn,#btnsrechng, #visaamxbtns, #txtsmall, #total, #amount1, #totalamount, #clintexpntabid, #prcntadjsmntamnt, #descriptiontxtarea, #scanepntabgreenbtn,#gratuitya').css("display", "none");
		$('#methodofpayment').val(abc);
	}
	//loadpayment_types();	
	if(loadpayment_types())
	{
		jQuery('#methodofpayment').val(abc);	
	}
	
	setTimeout(function () {	
	if(abc == "salefn" || abc == "autho")
	{
		jQuery('#methodofpayment').val('Credit Card');
	}
	else
	{
		jQuery('#methodofpayment').val(abc);
	}
	},1000);
	
	
	
}
function paymentcode(abc)
{ 
if (abc == ''){
	
	$("#vlpayment_code").val('');
}else{
   if ((abc != null)&&(abc != '')){
	   if ($("#methodofpayment").val() == 'Interface'){
		 $("#ht_client_name").val("");   
		 $("#ht_client_account").val("");
		 $("#ht_client_room").val("");
		 findroom(); 
	   }
	abc=abc.split(",");  
	var sst = abc[1];
	if ((abc[2] != null) && (abc[2] != '')){
		sst = abc[2];
	}
	
	$("#vlpayment_code").val(sst);
	var sname=abc[1]; 
	defpayidds=abc[0];
	sname=sname.toLowerCase();
	 
	if ((sname.indexOf('amex')>=0)||(sname.indexOf('american')>=0)||(sname.indexOf('dinner')>=0)){
		defsize=4;
	}else{
	    defsize=3;	
	}
	//var defsize = $('#cc_cvv').attr('maxlength');
	//alert(defsize);
	//document.getElementById('cc_ccv').maxLength = defsize;    
   }else{
	  // showmessage($("#vlpayment_code").val());
   }
}
}
function checkcvvsize(){
	 
	var abc = $("#pymntyp").val();
	if (abc == ''){
		defsize=4;
	}else{
	abc=abc.split(",");  
	var sst = abc[1];
	if ((abc[2] != null) && (abc[2] != '')){
		sst = abc[2];
	}
	
	 
	var sname=abc[1]; 
	defpayidds=abc[0];
	sname=sname.toLowerCase();
	 
	if ((sname.indexOf('amex')>=0)||(sname.indexOf('american')>=0)||(sname.indexOf('dinner')>=0)){
		
		defsize=4;
	}else{
		
	    defsize=3;	
	}
	}
	//document.getElementById('cc_ccv').maxLength = defsize;   
	  
}
var global_authorizehotel=1;
//check pages starts
var vtabao='';
var vtabant='';
var global_dana=false;
var default_tab="";
function showtabreal(tab)
{ 
	jQuery('#tab_client_name').hide();
	jQuery('#tab_client_phone').hide();
	jQuery('#client_name').val('');
	jQuery('#client_phone').val('');
	jQuery('#source_of_business').val('');
	default_tab=tab;
	if(tab == 'readytab'){
		jQuery('#tab_client_name').show();
		jQuery('#source_of_business').val('Walk In');
	}else if(tab == 'parceltab'){
		jQuery('#tab_client_name').show();
		jQuery('#tab_client_phone').show();
		jQuery('#source_of_business').val('Phone');
	}else if(tab == 'tabletab'){
		jQuery('#source_of_business').val('');
	}else if(tab == 'deliverytab'){
		jQuery('#source_of_business').val('Phone');
	}
	
	if (global_order_id > 0)
	{ 
		if(va_global_order == 'closed')
		{
			jAlert('This order is closed','Alert Dialog');
		}else{
			jQuery.alerts.okButton = 'Yes';
			jQuery.alerts.cancelButton = 'Cancel';  
	      jConfirm("Are you sure you change the Type of order?","Confirm Dialog",function(r){
          	if (r){ 
	   			changed_order=true;
	   			showtab(tab);
		  	}
		  });
		}
    }else{
		//jQuery("#tbl").val(""); 
		replace_dropdown_value('cvrs', 'no_of_covers');
		replace_dropdown_value('tbl', 'table2');    
		showtab(tab);
	}
}
//var vtabant='';
function changetypeoforder(){

		
		  var tab=default_tab;
		  	  criouordem=true;
		     var type_of_order = "";
			 var location_id = $("#location_id").val();
		     var url = transformurl(GLOBAL_url+'api2/pos/changeordertype.php?order_id='+global_order_id+'&location_id='+location_id+'&table='+$("#tbl").val()+'&typeorder='+default_tab);	 	
			 //startpreload();
             $.ajax ({type:'GET', url:url, cache:false, dataType:'json', success:function (data) {
				 	//console.log('chk left 005');
		 			checklist_home_left();
					if(global_order_id > 0){
					loadorderdetails(global_order_id);
					}
					setTimeout(function(){
						searchit(); 
				 	},2000); 
			 }});
			  
}
var global_togo="YES";
var global_delivery = "YES";
function showtab(tab)
{
	//alert(tab);
	jQuery("#delcom").hide();
	if (tab != "tabletab")
	{ 
	   jQuery("#table2").val("");		    
	   replace_dropdown_value('tbl', 'table2');	    
	}
	
	if (vtabant != tab){
		global_dana=true;
	}else{
	    global_dana=false;	
	}
	vtabant=vtabao;
	var podeseg=true;
	
	  if ( tab == "deliverytab"){ 
	  
	  	  globtp='Delivery';
		  jQuery("#delcom").show();
		    if (global_delivery.toLowerCase() != "yes"){
			    podeseg=false;
				tab="tabletab";
				vtabao="tabletab";
				jAlert('This type of order is not avaiable for this location at this time','Alert Dialog');	
			}
	  }else if ( tab == "parceltab"){ 
	  		
		    if (global_togo.toLowerCase() != "yes"){
			    podeseg=false;
				tab="tabletab";
				vtabao="tabletab"; 
				jAlert('This type of order is not avaiable for this location at this time','Alert Dialog');	
			}
	  } 
	vtabao=tab;	
	
	if (podeseg){
	if (tab == "tabletab")
	{		
		globtp='Table';		
		$('#covers, #tblsrvr,#tblsrvr1, #clientinfo, #checkdetails').css("display", "block");
		$('#ready, #deliveryinfo').css("display", "none");
		$("#tblbtnid").removeClass("setopacity");
		
	} 	
	else if (tab == "readytab")
	{	
		globtp='Togo';	
		$('#readybtnid').removeClass('setopacity');
		$('#item-tab').hide();
		$('#item_close').hide();
		$('#item_open').show();
		
		$('#clientinfo, #checkdetails').css("display", "block");
		$('#covers,#tblsrvr1, #tblsrvr, #ready, #deliveryinfo').css("display", "none");
		if(global_order_id == "")
		{
			$('#item-hide').show();
			$('#item_open').addClass("ui-state-default ui-corner-top ui-tabs-selected ui-state-active");
			
		}
		
	}	
	else if (tab == "deliverytab")
	{	
		globtp='Delivery';		
		$('#covers, #ready, #clientinfo, #deliveryinfo, #checkdetails').css("display", "block");
		$('#tblsrvr,#tblsrvr1').css("display", "none");
		$('#deliverybtnid').removeClass('setopacity');
		//jQuery("#srvr").val(geralzaoid); 
	}
	
	else if (tab == "parceltab")
	{
		globtp='Fast';
		$('#covers, #ready, #clientinfo, #checkdetails').css("display", "block");
		$('#tblsrvr,#tblsrvr1, #deliveryinfo').css("display", "none");
		if (global_order_id > 0){
			
		}else{
		  $("#cvrs").val('2');
		  replace_dropdown_value('cvrs', 'no_of_covers');
		}
		$('#parcelbtnid').removeClass('setopacity');
	}
	validaforneworder();
	}
	jQuery('#readytime').removeAttr("readonly");
}
var globaltime=0;
	function checktime(){
		if (keeploading==true){
		  globaltime=0;	
		}
		globaltime++;
		 
		if (globaltime > 20){
		  //stoppreload();
		  globaltime=0;
		}
		setTimeout(function(){
			checktime();
		},1000);
	}
	checktime();
	function ccck(sd){ 
	  document.getElementById(sd).value = ckvst(document.getElementById(sd).value);	
	}
	var devapaht=false;
	function clearho(){
		if (devapaht){ 
		$("#ht_client_room").val("");
		$("#ht_client_name").val("");
		$("#ht_client_account").val("");
		}
		devapaht=false;
	} 

function upditt(){
	  //console.log('ok 1 a');
 
}
 $( ".txtfld_div_clientname label" ).click(function(event) {
	 $("#"+event.control.id+" input").focus();
  
});

$( ".txtfld_div_clientname" ).click(function(event) {
	 for (var ksg=0;ksg<event.target.childNodes.length;ksg++){ 
		if ((event.target.childNodes[ksg].localName =='input')||(event.target.childNodes[ksg].localName =='select')){
		  $("#"+event.target.childNodes[ksg].id).focus(); 
		}
	 }
  //$("#"+event.target.id+" input").focus();
  
});
function escape_string(str)
{ 
	var f =str.replace(/\"/g, "");
	var s=f.replace(/[\"]/g, ""); 
	 s=s.replace(/[\']/g, ""); 
	
	return s;
}
function startpreloader(){ 
	
	jQuery(".loading-image").show();
}
function startpreload()
{
	startpreloader();
}
//function stoppreloader()
{
	//stoppreload();
}
function stoppreload(){
	if ((naoaceitaload == false) && (forcepree == false)) {
        jQuery("#csloadingtitle").html("<center></center>");
        //	showmessage('stp');
        keeploading = false;
        jQuery(".loading-image").hide();
        globaltime = 0;
    } else {
        globaltime = 0;
    }
}
function selmenu(){
  global_menu_id=document.getElementById('menu').value; 
  //jQuery("#filter_modal").hide();
  loadmenu(global_menu_id);
  jQuery("#btn_cancel_menu").click();
}
</script>
<script type="text/javascript">
	$( '[type=date], .datepicker' ).pickadate({  format: 'mm/dd/yyyy',
    formatSubmit: 'mm/dd/yyyy'});
	$('#filter_d').pickadate({   format: 'mm/dd/yyyy',  formatSubmit: 'mm/dd/yyyy',min: -1 , max: true });
 
	   
	   
	
	function chch(vl){ 
	var now= new Date();
	   var mm= now.getMonth()+1;
	   var dd= now.getDate();
	   var yy= now.getFullYear();
	   var d1a=now.getDate()-1;
	   if (mm < 9) mm='0'+mm;
	   if (dd < 9) dd='0'+dd;
	   if (d1a < 9) d1a='0'+d1a;
	   var dt1_defs=mm+'/'+dd+'/'+yy;
	   var dt2_defs=mm+'/'+d1a+'/'+yy; 
	  if ((vl != dt1_defs)&&(vl != dt2_defs)){
		   
		  $("#filter_d").val(dt1_defs);
		  jAlert('Please select a valid date','Alert Dialog');
		}
	}
	
       $( '[type=time], #readytime' ).pickatime();
var global_array_states="";
function validatecccc(gss){
	
$("#monthselected").val(""); 
$("#yearselected").val(""); 
  payment('Credit Card');
  setTimeout(function(){
  $("#pay_cc_number").val("");
  $("#year").val("");
  $("#expmnth").val("");
  $("#pay_cc_name").val(""); 
  var gst= gss;	//$("#ccinput").val(); 
  var ccholder = gst.split("^");
  ccholder = ccholder[1]; 
  $("#pay_cc_name").val(ccholder);
   jQuery("#first_name_cc").val(ccholder.split(" ")[0]);
   jQuery("#last_name_cc").val(ccholder.split(" ")[1]); 
  var ccnumber = gst.split(";");
  ccnumber = ccnumber[1];
  ccnumber = ccnumber.split("=");
  var ccyear=ccnumber[1];
  var ccmonth=ccnumber[1];
  ccnumber = ccnumber[0]; 
  $("#pay_cc_number").val(ccnumber);
  
  ccyear='20'+ccyear[0]+''+ccyear[1];
  ccmonth=ccmonth[2]+''+ccmonth[3];
  ccyear=parseInt(ccyear);
  $("#year").val(ccyear);   
  $("#yearselected").val(ccyear);
  $("#monthselected").val(ccmonth);
  $("#expmnth").val(ccmonth);
  
  var ret = retorna_tipoccpornumero(ccnumber);
  $("#swiped").val("1");
  $("#maskcard").show();
  $("#vlpayment_code").val(ret);
  $("#ccinput").val("");
  $("#cc_ccv").focus();
  },1000);
}

var deidd="";
var contdeidd=0;

function fcn(){

	 activeObj = document.activeElement;
  var inFocus = false;
   
  if (activeObj.tagName == "INPUT" || activeObj.tagName == "TEXTAREA" )
  {
     inFocus = true;
  }else{
	  
	  var is_firefox = navigator.userAgent.toLowerCase().indexOf('firefox') > -1;
	  is_firefox=true;
	  if ((is_firefox) && (activeObj.tagName == "SELECT")){
		  if (deidd == activeObj.id){
		     contdeidd=contdeidd+1;
		  }else{
			contdeidd=0;
			deidd= activeObj.id; 
		  }
		   
		  if (contdeidd > 2){
			  $("#ccinput").val("");
	          $("#ccinput").focus();
			  contdeidd=0;
			  deidd="";  
		  }
	  }else{
	    $("#ccinput").val("");
	    $("#ccinput").focus();  
	  }
  }
  setTimeout(function(){
	 fcn(); 
  },1500);
}

$( "input" ).keypress(function() {   
   
 var s=this.value;  
  if (s[31] != null){
    //console.log(s+'  -  '+s.length+'  -   '+s[31]);
  }
  if ((s.length == 32)&&((s[31]=='c')||(s[31]=='C'))){
	  var fol='';
	  /*
	  for (var j=0; j <32;j++){
		  fol=fol+s[j];  
	  }*/
	  fol=s;
	 // alert(fol);
	  global_rfid=fol;
	  //dialpad();
	  //closebox(); 
	  
  }else if (s.length > 77){
    validatecccc(this.value);
  }
}); 

if(jQuery('#payment_tab_edit').attr('class') == 'ui-state-default ui-corner-top ui-tabs-selected ui-state-active' || jQuery('#payment_tab').attr('class') == 'ui-state-default ui-corner-top ui-tabs-selected ui-state-active')
{
	//fcn();
}
 var global_tablesel=1;
var global_coverssel=1;
var keeploading="";
function fixspaces(){   
 
 	
	mountdate();
	 var url = transformurl(GLOBAL_url+'api2/return_states.php?country=3');
  $.ajax ({type:'GET', url:url, cache:false, dataType:'json', success:function (data) {
	  
	  var states;
	  global_array_states=data;
	  $.each(data, function (key, value) {
		  var code = value['id'];
		  var name = value['name'];
		  
		  states += '<option value="'+code+'">'+name+'</option>';
	  });
	  $("#delivery_state2").html(states);
	  
  }
  })
	//checklist_home_left();
  
}
function getCompanydetail()
{	
	if(jQuery("#txtcompany").val().length>=3){
		var url = transformurl(GLOBAL_url+"api2/pos/getcompany.php?val=1");
		data={"token":apiToken,"txtcompany":jQuery("#txtcompany").val()};
		//alert(crossDomainUrl + "?intRandNum="+intRandNum+"&txtSearchCompany="+$("#txtSearchCompany").val());
		 jQuery.ajax({	
				url: url,
				data:data,
				dataType: 'jsonp'
		 });
	}else{
		jAlert("Please enter at least 3 characters to search.");
	}
}
function returnCompanyDetail(strList)
{
	var strDetails='';
	jQuery('#compnay_detail').html('');
	
	if(strList!=null)
	{
		
		for(i=0;i<strList.length;i++){				
				var name="";
				var businesstype="";
				var company_name="";
				if(strList[i].name != null){
					name = strList[i].name;
				}
				if(strList[i].affi_type != null){
					businesstype=strList[i].affi_type;
				}	
				if(strList[i].company_name != null){
					company_name = strList[i].company_name;
				}
				strDetails = strDetails + "<tr class='gry' id='traffi_"+strList[i].company_id+"' style='line-height:45px;cursor:pointer;' onClick=\"SelectCompany('"+strList[i].company_id+"','"+strList[i].name+"','"+strList[i].addresses+"','"+strList[i].addresses2+"','"+strList[i].city+"','"+strList[i].statename+"','"+strList[i].zip+"','"+strList[i].phone+"');\"><td style='padding:0px 8px;'>";
				strDetails = strDetails + "<table width='100%' border='0' cellspacing='1' cellpadding='1'>";
				strDetails = strDetails + "<tr><td colspan='2' style='font-size:14px;border:none;padding:4px;'>"+ name +"</td></tr>";				
				strDetails = strDetails + "<tr><td style='font-size:14px;border:none;padding:4px;'>"+ strList[i].city + ", " + strList[i].statename +", "+company_name+"</td><td style='text-align:right;font-size:14px;border:none;padding:4px;'>"+ strList[i].phone +"</td></tr>";
				strDetails = strDetails + "<tr><td style='font-size:14px;border:none;padding:4px;'>"+ businesstype +"</td><td style='text-align:right;font-size:14px;border:none;padding:4px;'>"+ strList[i].representative  +"</td></tr>";
				strDetails = strDetails + "</table></td><tr>";	
		}
		
		jQuery("#compnay_detail").html(strDetails);
		jQuery("#spOrder").hide();
		jQuery(".right-panel-wrap5").css("display", "none");
		jQuery("#compnay_detail").show();
	}
}
function SelectCompany(id, name,addresses,addresses2,city,state,zip,phone)
{
	
		jQuery("#txtcompany").val(name);		
		jQuery('#company_id').val(id);
		jQuery("#company_addresses").html(addresses);
		jQuery("#address2").hide();
		if(addresses2 != "")
		{
			jQuery("#address2").show();
			jQuery("#company_addresses2").html(addresses2);
		}
		jQuery("#company_city").html(city+", "+state+", "+zip);		
		jQuery("#company_phone").html(phone);
		jQuery("#compnay_detail").html('');
		jQuery("#spOrder").show();
		jQuery(".right-panel-wrap5").css("display", "block");		
		jQuery("#compnay_detail").hide();
		
}
function handleEnter(e){
	if (e.keyCode == 13 || e.which == 13){
		dialpad2();
	}	
}
</script>

<div id="sjuips"></div>
<?php
if($_REQUEST['id'] != "" )
{
	$sql="select * from client_orders where id=".$_REQUEST['id'];
	
	$res = mysql_query($sql);
	while($val = mysql_fetch_array($res))
	{
		$check_number = $val['check_number'];
		$emp = $val['employee_id'];
		$server = server_get($emp);
		$status = $val['order_status'];
		if($status == "closed") { ?>
        <script type="text/javascript">
			jQuery("#item-closetab").show();
			jQuery("#item-tab").hide();
			jQuery("#item-hide").hide();
			jQuery("#item_close").hide();
			jQuery("#item_open").show();
			jQuery("#payment_tab").hide();
			jQuery("#payment_tab_edit").show();
			jQuery("#closed").val('');
			jQuery("#closed").val('yes');	
			jQuery("#closed_check").show();
			jQuery("#open_check").hide();
			setTimeout(function(){
				jQuery("#payment_open1").click();					
			},500);
		</script>
        <?php } else { ?>
        	<script type="text/javascript">
			
			jQuery("#item-tab").hide();
			jQuery("#item-hide").show();
			jQuery("#item_close").hide();
			jQuery("#item_open").show();
			</script>
            <?php if($_REQUEST['pay'] != ''){ ?>
            
              <script type="text/javascript">

			  	setTimeout(function(){
							  jQuery('#payment_open').click();
					loadpayment_types();				
				},2000);
			  </script>
            <?php }else{?>
            <script type="text/javascript">
			setTimeout(function(){
			jQuery("#item_open").removeClass("ui-tabs-selected ui-state-active");	
			jQuery("#item_open").addClass('ui-state-default ui-corner-top');
			jQuery("#e-7").addClass('ui-tabs-hide');
			
			jQuery("#item-hide").addClass('ui-state-default ui-corner-top ui-tabs-selected ui-state-active');
			jQuery("#e-8").removeClass('ui-tabs-hide');
			jQuery("#e-8").addClass('ui-tabs-panel ui-widget-content ui-corner-bottom');		
			//jQuery("#e-8").show();
			openpage('wrapper23');
			},100);			
			setTimeout(function(){
				loadpayment_types();				
			},2000);
			jQuery("#item-closetab").hide();
			jQuery("#payment_tab").hide();
			jQuery("#payment_tab_edit").show();
            </script>
            <?php } ?>
		var edit_id = true;
		</script>
        <?php }
		?>
        <script type="text/javascript">
		//jQuery("#item_open").addClass("ui-state-default ui-corner-top ui-tabs-selected ui-state-active");
		
		loadtb();	
		jclkbt=false;
		global_show_det=false;
		var place_an_order = 2;
		loadorderdetailsg('<? echo $val['id'];?>','Table','Table','<? echo $val['order_status'];?>',<? echo $val['check_number'];?>,<? echo $val['order_total'];?>,<? echo $val['covers'];?>);
		</script>
<?php } } 
if($_REQUEST['action'] != "" && $_REQUEST['action'] == "add")
	{ ?>
    <script type="text/javascript">
		var place_an_order = 0;
		napal();
		loadtb();
		jQuery('#payment_open').attr('href','#');
		//loadpayment_types();
		//clearview();
		</script>
        <?php if($_REQUEST['clientid'] != ''){?>
        <script type="text/javascript">
		var client_id_new = '<?php echo $_REQUEST['clientid'];?>';
		setTimeout(function(){
			
			select_client(client_id_new);
			jQuery('#clientphone').val('<?php echo $_COOKIE['SESS_BP_phone'];?>');			
			jQuery('#clientaddress1').val('<?php echo $_COOKIE['SESS_BP_address'];?>');
			jQuery('#clientaddress2').val('<?php echo $_COOKIE['SESS_BP_address2'];?>');
			jQuery('#clientcity').val('<?php echo $_COOKIE['SESS_BP_city'];?>');
			jQuery('#deliveryzipcode').val('<?php echo $_COOKIE['SESS_BP_zip'];?>');
		},700);
		setTimeout(function(){
		showtabreal('deliverytab');		
		jQuery('#deliverybtnid').click();
		},1000);
		function select_client(id){	
	var urll=transformurl(GLOBAL_url+"api2/find_clients.php?search=''&a=1&inactive=1&client_id="+id+"");
	jQuery.getJSON(urll,
	{	
		format: "json"
	},
	function(data) {  
		data=data.cli;  
		jQuery.each(data, function(key, val) { 
			arraydefclissg=data; 
			//addclientinfo(val.id,val.email,val.name,val.state,val.phone,true,val.city);	
		});
	});		
}
		</script>
        <?php } 
		}
?>
<script type="text/javascript">
var j191;
jQuery(document).ready(function(){
startpreloader();	
	wwidth = jQuery(window).width();
	wHeight = jQuery(window).height();	
	box8Width = jQuery(window).width();
	box8HmodifierBoxWidth = jQuery(window).width();//get defaults
	box8LeftMargin = 1;
	
	jQuery(document).bind('htmlchanged', function (e, data) {
		var sr = document.getElementById('buscarmdfletter3').src;//
		if (jQuery("#buscarmdfletter3").attr('class') == "btn btn-primary"){
			global_btnsmodnow=false;   	
			globalm = 1;
		}else{
			global_btnsmodnow=true; 
			globalm = 0;
		}
		if(globalm==1)
		{
		   jQuery("#apply_modifiers").css({"opacity": "0.5"});
		}else{
		   jQuery("#apply_modifiers").css({"opacity": "1"});			
		}		
		
		var typeId = "";
		var typeName = "";
		var typeVisible = 0;
		
		jQuery('.modif-type-'+globalm).each(function(){
			typeId = jQuery(this).attr('id');
			if (!jIsEmpty(typeId)) {
				typeName = typeId.replace('modif-type-'+globalm+'-','');				
				if (!jIsEmpty(typeName)) {
					if (typeName!='TEMPERATURE') {
						typeVisible = jQuery('.modif-elem-'+globalm+'-'+typeName+":visible").length;
						if (typeVisible==0 && globalm ==1) {
							jQuery('#'+typeId).hide();
						}
					}else{
						if (global_require_temperature==false){
							jQuery('#'+typeId).hide();
						}
					}
				}
			}
		});
		
		var noOfColumns = 0;
		var visibleModifiers = jQuery('#altotmod').children('.modfi-greybar:visible').length;
		if (visibleModifiers > 0 && ((visibleModifiers < 3 && global_require_temperature==true )||visibleModifiers < 5)){
			noOfColumns = 1;
			box8LeftMargin = 35;
		}else if(((visibleModifiers > 3 && global_require_temperature==true ) || visibleModifiers > 5 )&& visibleModifiers < 10){
			noOfColumns = 2;
			box8LeftMargin = 20;
		}else if (visibleModifiers >= 10) {
			noOfColumns = 3;		
			box8LeftMargin = 1;			
		}
		
		modifierBoxWidth = 559;
		if (modifierBoxWidth*3+10 > wwidth){
			modifierBoxWidth = (wwidth/3-10).toFixed(0);
		}
		
		var modifBoxHeight = 450;
		jQuery('.column > *').unwrap();

		if(jQuery('#altotmod').contents().length > 0){
			jQuery("#altotmod-content").html('');
		}

		var content_height = 430;
		var currrentPage = 1; 
		var lastModifierType = '';
		var firstElOnPage = '';
		function paginateModifiers(){
			if(jQuery('#altotmod').contents().length > 0){			
				$page = $("#modifier-template").clone(true).addClass("page").css("display", "block");
				$("#altotmod-content").append($page);
				jQuery('#altotmod').columnize({
					columns: noOfColumns,
					target: ".page:last .modifier-content",
					buildOnce: true,
					overflow: {
						height: content_height,
						id: "#altotmod",
						doneFunc: function(){
							paginateModifiers();
						}
					}
				});				
			}
			
			var $pagination = $("#modifier-nav-next");
			var total = $('.page').length;
			var current = $pagination.data("Current") ? $pagination.data("Current") : 1;		
			jQuery('.page').hide();
			jQuery('#altotmod').hide();
			jQuery('.page:first').show();
			jQuery("#box8-page-number").text("Page " + (current) + " of " + total + ""); 
			if (total<2){
				jQuery(".modifier-nav").hide();
			}else{
				jQuery(".modifier-nav").show();
			}
			
			if(current==1){
				jQuery("#modifier-nav-previous").hide();
			}else{
				jQuery("#modifier-nav-previous").show();				
			}
		}
	
		if (global_btnsmodnow==true && noOfColumns > 1){
			box8Width =  modifierBoxWidth*noOfColumns+"px";
			box8HmodifierBoxWidth = modifierBoxWidth*noOfColumns+30+ "px " + bx + "px";
			jQuery("#menu_modal").css({
				"width": box8Width,
				"background-size": box8HmodifierBoxWidth,
				"left":"20%",
				"right":box8LeftMargin+"%"//to center align
			});		
			jQuery('#boxtitle8').css('left','30%');
			jQuery('#cancelModifier').css('margin-left','30%');
			jQuery("#box8_container").css('margin-top','-30px');
			//jQuery("#box8_header").css({'margin-left':'22px','background':'url("images/bghed_addclient_colorbox.png") repeat-x scroll left top rgba(0, 0, 0, 0)','width':'97.5%'});
			//
			jQuery('#box8_btn').css({'float':'left'});
			jQuery("#altotmod-content").show();
			jQuery("#box8-product-name").html(jQuery('#boxtitle8').html());
			jQuery("#BXTEMP").hide();
			jQuery("#boxtitle8").hide();
			jQuery("#box8_product_name").show();
			jQuery("#box8_page_number").show();
			jQuery("#box8_btn_left").show();
			jQuery("#box8_btn_right").show();
			setTimeout(paginateModifiers());	
				var currrentPage = 1;
				setTimeout(function(){
					jQuery(".page").each(function(){
						lastModifierType = '';
						$page = jQuery(this);
						$page.attr('id', 'modifier-page-'+currrentPage);
						if (currrentPage > 1 && !isNaN(currrentPage)) {							
							var modifierTypes = jQuery('#modifier-page-'+(currrentPage-1)+' .modfi-bluebar').each(function(){ //if i have multiple pages , some won't be visible
								lastModifierType = jQuery(this);
							});
							if (!jIsEmpty(lastModifierType)) {
								firstElOnPage = jQuery('#modifier-page-'+(currrentPage)+' .first section:visible');
								if (lastModifierType.attr('id')!=firstElOnPage.attr('id')) {
									$page.children().children('.first').prepend(lastModifierType.clone(true).append(' (Continued)'));
								}
							}
						}
						currrentPage++;		
					});				
				},500);		
			
			jQuery("#box8x").css({"width":"100%","margin-left":"2%;"});	
		} else {
			
			box8Width =  "554px";
			box8HmodifierBoxWidth = "559px " + bx + "px";
			box8LeftMargin = 35;
			jQuery("#menu_modal").css({//width:554px;background-size:559px 620px;height:600px
				"background-size": box8HmodifierBoxWidth,
				"width":box8Width,
				"height":"600px",
				"left":"50%",
				"right":box8LeftMargin+"%"//to center align				
			});
			jQuery('#boxtitle8').css('left','20%');
			jQuery('#cancelModifier').css('margin-left','0');
			jQuery("#box8_container").css('margin-top','0');
			//jQuery("#box8_header").css({'margin-left':'0','background':'transparent','width':'96%'});
			//
			jQuery('#altotmod').show();
			jQuery('#box8_btn').css({'float':'none'});
			jQuery("#altotmod-content").hide();
			jQuery("#BXTEMP").show();
			jQuery("#boxtitle8").show();

			jQuery("#box8-product-name").html('');
			jQuery("#box8_product_name").hide();
			jQuery("#box8_page_number").hide();
			jQuery("#box8_btn_left").hide();
			jQuery("#box8_btn_right").hide();
			jQuery("#box8x").css({"height":"480px","width":"98%","margin":"1% 2% 0% 2%"});	
		}
	});			

	jQuery(window).resize(function(){
		if(jQuery('#altotmod-content').css("display") == 'none'){
		}else{
		jQuery("#menu_modal").css({//
				"width":box8Width,
				"background-size": box8HmodifierBoxWidth,
				"left":"20%"
		});
		}
	});

	jQuery("#modifier-nav-next").click(function (e) {//todo: proper paging
		e.preventDefault();
		var jQuerythis = jQuery(this);
		var jQuerypagination = jQuery("#modifier-nav");
		var jQuerythepage = jQuery(".page");
		var total = jQuery('.page').length;
		var current = jQuerypagination.data("Current") ? jQuerypagination.data("Current") : 0;
		if (jQuerythis.index() == 0) { 
			current = ((current + 1) == total ? 0 : (current + 1));//always forward
		}else { 
			current = ((current + 1) == total ? 0 : (current + 1));
		}
		
		jQuerypagination.data("Current", current);
		jQuerythepage.css("display", "none").eq(current).css("display", "").fadeIn(500);
		jQuery("#box8-page-number").text("Page " + (current+1) + " of " + total + "");
		
		if (current+1==total){
			jQuery("#modifier-nav-next").hide();
		}else{
			jQuery("#modifier-nav-next").show();	
		}
		if(current==0){
			jQuery("#modifier-nav-previous").hide();
		}else{
			jQuery("#modifier-nav-previous").show();
		}
	});

	jQuery("#modifier-nav-previous").click(function (e) {
		e.preventDefault();
		var jQuerythis = jQuery(this);
		var jQuerypagination = jQuery("#modifier-nav");
		var jQuerythepage = jQuery(".page");	
		var total = jQuery('.page').length;	   
		var current = jQuerypagination.data("Current") ? jQuerypagination.data("Current") : 0;	
		if (jQuerythis.index() == 0) { 
			current = ((current - 1) < 0 ? (total - 1) : (current - 1));
		} else { 
			current = ((current - 1) < 0 ? (total - 1) : (current - 1));//alwaya backward
		}
		
		jQuerypagination.data("Current", current);
		jQuerythepage.css("display", "none").eq(current).css("display", "").fadeIn(500);
		jQuery("#box8-page-number").text("Page " + (current+1) + " of " + total + "");
		
		if (current+1==total){
			jQuery("#modifier-nav-next").hide();
		}else{
			jQuery("#modifier-nav-next").show();	
		}
		if(current==0){
			jQuery("#modifier-nav-previous").hide();
		}else{
			jQuery("#modifier-nav-previous").show();
		}
	});

	jQuery( "#c_dob" ).datepicker({
		changeMonth: true,
		dateFormat:"yy-mm-dd",
		changeYear: true,
	});
         
	<?php if (isset($_GET["clientid"]) && $_GET["clientid"]!="" && $_GET["clientid"]!=0){
			$sql = "SELECT *, COALESCE(name, CONCAT(name_first,' ',name_last)) as clientname FROM clients WHERE id=".  mysql_real_escape_string($_GET["clientid"]);
			$result = mysql_query($sql);
				if (mysql_num_rows($result)>0){
					$row = mysql_fetch_assoc($result);
	?>
			addclientinfo('<?php echo $row["id"];?>','<?php echo $row["email"];?>','<?php echo $row["clientname"];?>','<?php echo $row["state"];?>','<?php echo $row["phone"];?>',true,'<?php echo $row["city"];?>',false);
			<?php }
		}?>

	jQuery('html,body').animate({ scrollTop: 0 });

	bx = wHeight - 120;
	bx = 600;	
	
	
	setTimeout(function(){
			var a = jQuery('#searchfldaddclient_placeholder').html().replace(/<[^>]+>[^<]*<[^>]+>|<[^\/]+\/>/ig, "");	
			jQuery('#searchfldaddclient').attr('placeholder',a);
			var b = jQuery('#searchfldmodfi_placeholder').html().replace(/<[^>]+>[^<]*<[^>]+>|<[^\/]+\/>/ig, "");	
			jQuery('#searchfldmodfi').attr('placeholder',b);
			var c = jQuery('#searchfldscnd_placeholder').html().replace(/<[^>]+>[^<]*<[^>]+>|<[^\/]+\/>/ig, "");	
			jQuery('#searchfldscnd').attr('placeholder',c);
			
	},5000);
	
	$('#form_new').show();
	$('#form').hide();
	
	jQuery('#cancel_add').click(function(){
		jQuery("#open_order_first").attr('href','#e-8');
	});
	jQuery('#btnClose').click(function(){
		jQuery("#open_order_first").attr('href','#e-8');
	});
	
	jQuery("#btn_seat_close").click(function(){
		setorder='no';
	});

	wHeight = $(window).height();
	wwidth = $(window).width();
	if(wHeight == 411 && wwidth == 1109){
		setTimeout(function(){
		jQuery('.itemstblerow_col5').css('width','12%');
		jQuery('.itemstblerow_col2').css('width','65%');
		},2500);
	}
	
	var mousewheelevt = (/Firefox/i.test(navigator.userAgent)) ? "DOMMouseScroll" : "mousewheel" //FF doesn't recognize mousewheel as of FF3.x
	$('#vl_weight').bind(mousewheelevt, function(e){
	    var evt = window.event || e //equalize event object     
	    evt = evt.originalEvent ? evt.originalEvent : evt; //convert to originalEvent if possible               
	    var delta = evt.detail ? evt.detail*(-40) : evt.wheelDelta //check for detail first, because it is used by Opera and FF

	    if(delta > 0) {
		calculateweight(1,'');
	        //scroll up
	    }
	    else{
		calculateweight(-1,'');
	        //scroll down
	    }   
	});

	$('#vl_weight_ounce').bind(mousewheelevt, function(e){

	    var evt = window.event || e //equalize event object     
	    evt = evt.originalEvent ? evt.originalEvent : evt; //convert to originalEvent if possible               
	    var delta = evt.detail ? evt.detail*(-40) : evt.wheelDelta //check for detail first, because it is used by Opera and FF

	    if(delta > 0) {
		calculateweight('',1);
	        //scroll up
	    }
	    else{
		calculateweight('',-1);
	        //scroll down
	    }   
	});
	
	var access_pos_business_panel = '<?php echo $_SESSION['access_pos_cash'];?>';
	getinfolocation();
	jQuery.fn.removeDuplicates = function() {
  		var original = [];
  
	  	this.each(function() {
		    var el = this, $el, isDuplicate;
		    
		    jQuery.each(original, function() {
		    	$el = jQuery(el);
		    	
		    	// check whichever properties 
		    	// you believe determine whether 
		    	// it's a duplicate or not
		    	if (el.tagName === this.tagName && 
		    	    el.className === this.className && 
		    	    el.id === this.id && 
		    	    el.value === this.value && 
		    	    el.href === this.href && 
		    	    $el.html() === jQuery(this).html()) {
		    	  isDuplicate = true;
		    	  $el.remove();
		    	}
		    });
		    
		    if (!isDuplicate) {
		    	original.push(el);
		    }
		});
	};

	stoppreload();
});

function ChnageHostes(ths){
	jQuery('#hostess_status').val(jQuery('#tbl option:selected').attr('rel'));	
}
</script>