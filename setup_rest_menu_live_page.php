<?php
header('Content-Type: text/html; charset=utf-8');
include_once 'includes/session.php';
include_once("config/accessConfig.php");
$setupHead      = "active";
$setupDropDown1 = "display: block;";
$setupDropDown  = "display: block;";
$setupResMenu14  = "active";
$setupMenu3     = "active";


if(isset($_POST) && $_POST['action']=='delete' && $_REQUEST['menugrp_id']>0){
	$del = "DELETE FROM location_menu_group WHERE id = '".$_REQUEST['menugrp_id']."'";
	$res = mysql_query($del);
	if($res){
		echo 'yes';
	}
	exit();
}

$menu=$_REQUEST['menu'];
$menu_id=$_REQUEST['menu_id'];

$sql = "SELECT id, menu, image, description, TIME_FORMAT( starttime,'%k:%i') starttime , TIME_FORMAT( endtime,'%k:%i') endtime FROM location_menus where location_ID = " . $_SESSION['loc'] . " AND (`type` is null OR `type` = 'POS' ) ORDER BY menu ASC";
$query = mysql_query($sql) or die(mysql_error());

if(mysql_num_rows($query)!=0 && $menu=='')
{
	$rowmenu=mysql_fetch_assoc($query);
	$menu=$rowmenu['id'];
}
$querysel = "SELECT * FROM location_menu_group WHERE location_id=" . $_SESSION['loc'] . " ORDER BY menu_group ASC";
$resultgroup = mysql_query($querysel);



$sqlpayroll = "SELECT currency_symbol FROM locations WHERE id=".$_SESSION["loc"];
$resultpayroll = mysql_query($sqlpayroll);
$rowperiod = mysql_fetch_assoc($resultpayroll);

$currency = $rowperiod["currency_symbol"];
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>SoftPoint | BusinessPanel</title>
<link rel="stylesheet" href="css/style.default.css" type="text/css" />
<link rel="stylesheet" href="css/responsive-tables.css">
<style>
.line3 { background:#808080 !important; color:#FFFFFF !important ;}
.progress { position:relative; width:100%; border: 1px solid #ddd; padding: 1px; border-radius: 3px;  display:none; margin-top:10px; }
.bar { background-color: #B4F5B4; width:0%; height:20px; border-radius: 3px; }
.percent { position:absolute; display:inline-block; top:3px; left:48%; }
#dyntable2_filter input
{
    width: 70px
}
.paginate_button, .dataTables_paginate .next, .dataTables_paginate .last {
padding: 5px 8px !important;
}
.dataTables_paginate .first, .dataTables_paginate .previous, .dataTables_paginate .paginate_active, .dataTables_paginate .paginate_button, .dataTables_paginate .next, .dataTables_paginate .last{
 padding: 5px 8px !important;
}
.modal-footer {
    -webkit-border-radius: 0 !important;
    -moz-border-radius: 0 !important;
     border-radius: 0 !important;

}
</style>
<script type="text/javascript" src="js/jquery-1.9.1.min.js"></script>
<script type="text/javascript" src="js/jquery-migrate-1.1.1.min.js"></script>
<script type="text/javascript" src="js/jquery-ui-1.9.2.min.js"></script>
<script type="text/javascript" src="prettify/prettify.js"></script>
<script type="text/javascript" src="js/bootstrap.min.js"></script>
<script type="text/javascript" src="js/jquery.uniform.min.js"></script>
<script type="text/javascript" src="js/jquery.dataTables.min.js"></script>
<script type="text/javascript" src="js/jquery.jgrowl.js"></script>
<script type="text/javascript" src="js/jquery.alerts.js"></script>
<script type="text/javascript" src="js/jquery.cookie.js"></script>
<script type="text/javascript" src="js/modernizr.min.js"></script>
<script type="text/javascript" src="js/responsive-tables.js"></script>
<script type="text/javascript" src="js/custom.js"></script>
<script type="text/javascript" src="js/datetime-picker.min.js"></script>
<script type="text/javascript" src="js/elements.js"></script>

<script type="text/javascript" src="js/tablednd.js"></script>
<script type="text/javascript" src="js/jquery.form.js"></script>

<style>
    .s_panel{
        border-bottom: 1px solid lightgray;
    }
</style>
<script type="text/javascript">
    function clearForm(){
		jQuery('#ResetButton').show();
        jQuery("#menuform")[0].reset();
		clearField();
		jQuery('.line3').removeClass('line3');
    }



	function SubmitItemBFLeaving(returnUrl){
		jQuery("#hidItemIds").val(jQuery("#hidItemIds").val().slice(0,-2));
		jQuery.ajax({
				type: "POST",
				url: "ajax_add_menu_article_drop.php",
				data: {itemid:0, menugroupid: jQuery("#hidMenuGroupID").val(),menu:jQuery("#hidMenu").val(),itemIds:jQuery("#hidItemIds").val()},
				success: function (data) {

						jQuery("#btn_submit").addClass("btn-active");
						jAlert('Items are added to menu group successfully!', 'Alert', function (r){
							if(r){
								window.location.href = returnUrl;
							}
						});
					}

			   });
	}

    jQuery(document).ready(function(){

        jQuery(".menuitems").on("click",function(){
            jQuery(".line3").removeClass("line3");
            jQuery(this).addClass("line3");
        })

        <?php if ($_GET["insert"]=="yes"){?>
                jAlert("Item was updated successfully!");
        <?php }


		?>


		jQuery("#btn_submit").click(function(){

			if(jQuery(this).hasClass("btn-active")){
			}else{
				jQuery("#hidItemIds").val(jQuery("#hidItemIds").val().slice(0,-2));
				jQuery.alerts.okButton = 'OK';
				jQuery.alerts.cancelButton = 'Cancel';
				jConfirm("Would you like to apply the changes now?", "Confirm Dialog", function(r){
					if(r){
						if(jQuery("#hidItemIds").val()==''){
							jQuery("#btn_submit").addClass("btn-active");
							jAlert('Items are added to menu group successfully!', 'Alert', function () {
								window.location.href = "setup_rest_menu_live_page.php?menu="+ jQuery("#hidMenu").val()+"&menu_id="+jQuery("#hidMenuGroupID").val();
							});
							return false;
						}
						jQuery.ajax({
							type: "POST",
							url: "ajax_add_menu_article_drop.php",
							//data: {itemid:ui.helper.data("id"), menugroupid: menugroupid,menu:menu},
							//data: {itemid: , itemIds:itemid, menugroupid: menugroupid,menu:menu},

							data: {itemid:0, menugroupid: jQuery("#hidMenuGroupID").val(),menu:jQuery("#hidMenu").val(),itemIds:jQuery("#hidItemIds").val()},
							success: function (data) {

								if (data==1){
									jQuery("#btn_submit").addClass("btn-active");
									jAlert('Items are added to menu group successfully!', 'Alert', function () {
										window.location.href = "setup_rest_menu_live_page.php?menu="+ jQuery("#hidMenu").val()+"&menu_id="+jQuery("#hidMenuGroupID").val();
									});
								}else if (data==0){
									jAlert('Item already exists in selected group!', 'Alert', function () {

									});
								}
							}

						   }); // end jQuery.ajax
					}else{
						return false;
					}
				});


				}
		});

        jQuery(".droppable").droppable({
                over: function(event, ui) {
                    jQuery(this).css("background","gray");
                    jQuery(this).find(".accordion-toggle").trigger("click");
                 },

				drop: function (event, ui) {


                   var $this = jQuery(this);
                   var menugroupid = $this.data("id");
                   var menu = $this.data("menu");

				   var itemIds = "";
				   jQuery(".menuitems_checkBox").each(function(){
				   		if(this.checked){
							if(itemIds.toString().indexOf(jQuery(this).attr('rel'))==-1){
								itemIds = itemIds + jQuery(this).attr('rel')+',';
							}
						}
				   });
				   if(itemIds!=''){
				   		if(itemIds.toString().indexOf(ui.helper.data("id"))==-1){
				   			itemIds = itemIds + ui.helper.data("id");
						}else{
							itemIds = itemIds.slice(0,-1);
						}
				   }


				   console.log("Menu Group: " + menugroupid);
				   console.log("Menu: " + menu);
				   console.log("Item Ids: " + itemIds);


				   /*var itemid = [];
				   var menugroupid = [];
				   var menu = [];
					*/
					/*
					var itemid = '';
				   //var menugroupid = '';
				   //var menu = '';
				   jQuery.each(jQuery('.menuitems_checkBox'), function(i, v){
				   		if(jQuery(v).attr('checked')){
							itemid += jQuery(v).attr('rel') +',';
							//menugroupid += jQuery(v).data('id') +',';
							//menu += jQuery(v).data('menu') +',';
						}
				   });

				   if(itemid == ''){
				   		itemid = ui.helper.data("id");
						console.log('itemid ui::' +itemid);
				   }

				   console.log('itemid:' + itemid);
				   console.log('menugroupid:' + menugroupid);
				   */
				   jConfirm("Are you sure you want to add these items to the menu group?","Confirm Dialog",function(r){
                   if (ui.helper.hasClass("menuitem")){

				   		var t = jQuery('#menu_table_'+menugroupid).DataTable();
						var strPrio = jQuery("#priority_"+menu).val();

						if(itemIds!=''){
							var itemIdss  = itemIds.split(',');
							jQuery.each(itemIdss,function(index,value){
								if(value!=ui.helper.data("id")){
									var obj = jQuery("#dyntable2 #"+value+" .menuitem");
									console.log(obj);
									var editbuttons = '<a data-toggle="modal" data-target="#edit_item_modal" href="ajax-edit-menu-item.php?itemid='+obj.data("id")+'&menu='+ menu +'&group='+ menugroupid +'" style="background:none;!important;  line-height: 10px !important;margin-top: 5px !important;    padding: 1px !important; "><span class="a"><img src="images/edit.png"></span></a>';
									editbuttons = editbuttons + '&nbsp;<span class="deletemenu1" data-menugroupid="'+menugroupid+'" data-id="'+ obj.data("id") +'" style="cursor:pointer!important;"><img src="images/Delete.png"></span>';

									t.row.add( [
										''+ strPrio +'',
										''+ obj.data("itemname") + '',
										''+ obj.data("plu") + '',
										''+ obj.data("drink") + '',
										''+ obj.data("fire") + '',
										''+ obj.data("taxable") + '',
										''+ obj.data("printer") + '',
										''+ obj.data("price") + 'test1',
										'' + editbuttons + ''
									] ).draw( false );
									strPrio++;
									jQuery("#dyntable2 #"+value+" .menuitems_checkBox").trigger('click');
									jQuery("#hidItemIds").val(obj.data("id")+'|'+ menugroupid + "," + jQuery("#hidItemIds").val());
								}
							});
						}


						var editbuttons = '<a data-toggle="modal" data-target="#edit_item_modal" href="ajax-edit-menu-item.php?itemid='+ui.helper.data("id")+'&menu='+ menu +'&group='+ menugroupid +'" style="background:none;!important;  line-height: 10px !important;margin-top: 5px !important;    padding: 1px !important; "><span class="a"><img src="images/edit.png"></span></a>';
                            editbuttons = editbuttons + '&nbsp;<span class="deletemenu1" data-menugroupid="'+menugroupid+'" data-id="'+ ui.helper.data("id") +'" style="cursor:pointer!important;"><img src="images/Delete.png"></span>';

						t.row.add( [
							''+ strPrio +'',
							''+ ui.helper.data("itemname") + '',
							''+ ui.helper.data("plu") + '',
							''+ ui.helper.data("drink") + '',
							''+ ui.helper.data("fire") + '',
							''+ ui.helper.data("taxable") + '',
							''+ ui.helper.data("printer") + '',
							''+ ui.helper.data("price") + '',
							'' + editbuttons + ''
						] ).draw( false );
						jQuery("#menu_table_"+menugroupid+" tr").each(function(){
							jQuery(this).find('td:nth-child(8)').css('text-align','right');
							//jQuery(this + ' td:nth-child(2)').css('color','red');
						});
						strPrio++;

				   		jQuery("#hidItemIds").val(ui.helper.data("id")+'|'+ menugroupid + "," + jQuery("#hidItemIds").val());
						jQuery("#hidMenuGroupID").val(menugroupid);
						jQuery("#hidMenu").val(menu);
						jQuery("#btn_submit").removeClass("btn-active");
						jQuery("#btn_submit").addClass("btn-success");
						jQuery("#priority_"+menu).val(eval(jQuery("#priority_"+menu).val())+1);
						 jQuery("#dyntable2 #"+ui.helper.data("id")+" .menuitems_checkBox").trigger('click');
						 console.log("Item ID:" + ui.helper.data("id"));

						  console.log("hidItemIds ID:" + jQuery("#hidItemIds").val().slice(0,-2));
						  jQuery("#priority_"+menu).val(strPrio);
				   	jQuery(".draggable").draggable({
                            tolerance: 'fit',
                            helper: "clone"
                        });
				   t.destroy();
				   }
				});



                }


            }



        );
            jQuery(".draggable").draggable({
                tolerance: 'fit',
                helper: "clone"
            }

        );
        // dynamic table
        jQuery('#dyntable').dataTable({
            "sPaginationType": "full_numbers",
         //   "aaSortingFixed": [[0,'asc']],
            "fnDrawCallback": function(oSettings) {
                jQuery.uniform.update();
            }
        });
        jQuery('#dyntable2').dataTable({
            "sPaginationType": "full_numbers",
            "info":     false,
            "bPaginate": false,
            "sScrollY": "500px",
            "bFilter": false, "bInfo": false,
         //   "aaSortingFixed": [[0,'asc']],
            "fnDrawCallback": function(oSettings) {
                jQuery.uniform.update();
            }
        });
        jQuery("#dyntable2_length").css("display","none");
    jQuery("#dyntable2_filter input").css("width","110px");
    jQuery("#dyntable2_filter input").css("margin-bottom","5px");

          jQuery('#ccstarttime').timepicker();
        jQuery('#ccendtime').timepicker();

	jQuery("#menuselect" ).change(function() {
		window.location="<?=$_SERVER['PHP_SELF']?>?menu="+jQuery(this).val();
	});




	 jQuery('#edit_modifier_modal').on('hidden', function(e){
			jQuery(this).removeData('modal');
			jQuery('.modal-body', this).empty();
		});

         jQuery('#edit_item_modal').on('hidden', function(e){
			jQuery(this).removeData('modal');
			jQuery('.modal-body', this).empty();
		});


    });


function validacMenu() {

        if (document.forms.menuform.item_group.value == "") {
         	jAlert('Please Select a Menu Group!', 'Alert Dialog', function(){
            });

        } else if (document.forms.menuform.ccitem.value == "") {
           jAlert('please insert Menu Item!', 'Alert Dialog', function(){
            });
        } else if (document.forms.menuform.item_priority.value == "") {
            jAlert('please insert Item Priority!', 'Alert Dialog', function(){
            });
			jQuery('#item_priority').removeAttr("disabled");
        }else if (document.forms.menuform.ccplu.value == "") {
           jAlert('This item requires a PLU!', 'Alert Dialog', function(){
            });
			jQuery('#ccplu').removeAttr("disabled");
        } else if (document.forms.menuform.ccpriority.value == "") {
          jAlert('Please insert Article Priority!', 'Alert Dialog', function(){
            });
			jQuery('#ccpriority').removeAttr("disabled");
        } else if (document.forms.menuform.ccprice.value == "") {
          	jAlert('Please insert Article Price!', 'Alert Dialog', function(){
            });
			jQuery('#ccprice').removeAttr("disabled");
        } else if (document.forms.menuform.cctaxable.value == "") {
           jAlert('Please select Taxable!', 'Alert Dialog', function(){
            });
			jQuery('#cctaxable').removeAttr("disabled");
        } /*else if (document.forms.menuform.ccmax_quantity.value == "") {
           jAlert('Please insert Maximum Quantity!', 'Alert Dialog', function(){
            });
			jQuery('#ccmax_quantity').removeAttr("disabled");
        } else if (document.forms.menuform.cctogo.value == "") {
           jAlert('Please select Togo!', 'Alert Dialog', function(){
            });
			jQuery('#cctogo').removeAttr("disabled");
        } else if (document.forms.menuform.ccdelivery.value == "") {
          	jAlert('Please select Delivery!', 'Alert Dialog', function(){
            });
			jQuery('#ccdelivery').removeAttr("disabled");
        } else if (document.forms.menuform.ccrequire_temperature.value == "") {
           jAlert('Please select Require Temperature!', 'Alert Dialog', function(){
            });
			jQuery('#ccrequire_temperature').removeAttr("disabled");
        } else if (document.forms.menuform.ccdrink.value == "") {
           jAlert('Please select Drink!', 'Alert Dialog', function(){
            });
			jQuery('#ccdrink').removeAttr("disabled");
        } else if (document.forms.menuform.ccglass.value == "") {
          jAlert('Please select Glass!', 'Alert Dialog', function(){
            });
			jQuery('#ccglass').removeAttr("disabled");
		} else if (document.forms.menuform.ccglass.value == "yes" && document.forms.menuform.ccglass_price.value == " ") {
          jAlert('Please insert Glass Price!', 'Alert Dialog', function(){
            });
			jQuery('#ccglass_price').removeAttr("disabled");
		} else if (document.forms.menuform.ccglass.value == "yes" && document.forms.menuform.ccglass_price2.value == " ") {
          jAlert('Please insert 2nd Glass Price!', 'Alert Dialog', function(){
            });
			jQuery('#ccglass_price2').removeAttr("disabled");
        } else if (document.forms.menuform.ccdivide.value == "") {
           jAlert('Please select Divide!', 'Alert Dialog', function(){
            });
			jQuery('#ccdivide').removeAttr("disabled");
        } else if (document.forms.menuform.ccmax_divide.value == "" && document.forms.menuform.ccdivide.value == "yes") {
            jAlert('Please insert Max Divide!', 'Alert Dialog', function(){
            });
			jQuery('#ccmax_divide').removeAttr("disabled");
        } else if (document.forms.menuform.ccfire_order.value == "") {
           jAlert('Please select Fire Order!', 'Alert Dialog', function(){
            });
			jQuery('#ccfire_order').removeAttr("disabled");
        } else if (document.forms.menuform.ccsides.value == "") {
           jAlert('Please select Sides!', 'Alert Dialog', function(){
            });
			jQuery('#ccsides').removeAttr("disabled");
        }  else if (document.forms.menuform.ccrefills.value == "") {
            jAlert('Please select Refills!', 'Alert Dialog', function(){
            });
			jQuery('#ccrefills').removeAttr("disabled");
        } */
        else if(jQuery("#promotion_type").val()=="Fixed Amount" && jQuery("#promotion_amount").val()=="" && jQuery("#promotion").val()=="Yes"){
            jAlert('Please enter promotion amount!', 'Alert Dialog', function(){
            });
        } else if(jQuery("#promotion_type").val()=="Percentage" && jQuery("#promotion_percentage").val()=="" && jQuery("#promotion").val()=="Yes"){
            jAlert('Please enter promotion percentage!', 'Alert Dialog', function(){
            });
        } else if(jQuery("#promotion_type").val()=="Percentage" && jQuery("#percentage_round").val()=="" && jQuery("#promotion").val()=="Yes"){
            jAlert('Please enter promotion percentage round!', 'Alert Dialog', function(){
            });
        } else if(jQuery("#promotion_type").val()=="Percentage" && jQuery("#percentage_round_to").val()=="" && jQuery("#promotion").val()=="Yes"){
            jAlert('Please enter promotion percentage round to!', 'Alert Dialog', function(){
            });
        } else if(jQuery("#promotion").val()=="Yes" && jQuery("#promotion_req_qty").val()==''){
            //jAlert('Promotion Required Quantity has to be greater than zero!', 'Alert Dialog', function(){
			jAlert('Please enter promotion required quantity!', 'Alert Dialog', function(){
            });
		} else if(jQuery("#promotion").val()=="Yes" && jQuery("#promotion_req_qty").val()==0){
            jAlert('Promotion Required Quantity has to be greater than zero!', 'Alert Dialog', function(){
            });
        }else if(jQuery("#promotion").val()=="Yes" && jQuery("#promotion_continued").val()==""){
            jAlert('Please select promotion continued!', 'Alert Dialog', function(){
            });
        }else if(jQuery("#promotion_continued").val()=="Yes" && (jQuery("#promotion_dow").val()=="" || jQuery("#promotion_dow").val()==null)){
            jAlert('Please select promotion days of week!', 'Alert Dialog', function(){
            });
        } else {

            jQuery('Input,select,Textarea').removeAttr("disabled");

			jQuery.ajax({
				url:'insertmenuarticle_rest.php',
				type:'POST',
				data:jQuery("#menuform").serialize(),
				success:function(data){
					var menu_id = jQuery("#menuform #menu_id").val();
					jQuery("#delete_items_"+menu_id).html(data);
					jQuery("#menuform")[0].reset();
					jQuery("#edit_item_modal").modal("hide");
					jAlert("Item Updated Successfully!","Alert Dialog");
					return false;

				}
			});

			//return true;
         }
        return false;
    }

    function SearchItems(){
        jQuery.ajax({
                    type: "POST",
                    url: "ajax-menu-article-search.php",
                    data: { menu:'<?php echo $menu;?>', articletype: jQuery("#articletype").val(), desc:jQuery("#search_n").val() },
                    async: false,
                    success: function(data){

                        jQuery("#dyntable2").html(data);
                        jQuery(".draggable").draggable({
                            tolerance: 'fit',
                            helper: "clone"
                        });
                    }
             })
    }

	/*window.onhashchange = function() {
		jAlert("Back Button Pressed");
		return false;
	}*/
	jQuery('.leftmenu a').live('click',function(e){
		var ths = jQuery(this);
		var href = jQuery(ths).attr('href');
		if(href!='' && href!='javascript:void(0)' && href!='#'){
			if(jQuery("#btn_submit").hasClass("btn-active")){

			}else{
				e.preventDefault();
				jQuery.alerts.okButton = 'Yes';
				jQuery.alerts.cancelButton = 'No';
				jConfirm("Would you like to apply the changes before leaving the page?","Confirm Dialog",function(r){
					if(r){
						var href = jQuery(ths).attr('href');
						SubmitItemBFLeaving(href);
					}else{
						jQuery("#btn_submit").addClass("btn-active")
						var href = jQuery(ths).attr('href');
						window.location.href = href;
					}
				});
			}
		}
	});
	jQuery('.header a').live('click',function(e){
		var ths = jQuery(this);
		var href = jQuery(ths).attr('href');
		if(href!='' && href!='javascript:void(0)' && href!='#'){
			if(jQuery("#btn_submit").hasClass("btn-active")){

			}else{
				e.preventDefault();
				jQuery.alerts.okButton = 'Yes';
				jQuery.alerts.cancelButton = 'No';
				jConfirm("Would you like to apply the changes before leaving the page?","Confirm Dialog",function(r){
					if(r){
						var href = jQuery(ths).attr('href');
						SubmitItemBFLeaving(href);
					}else{
						jQuery("#btn_submit").addClass("btn-active")
						var href = jQuery(ths).attr('href');
						window.location.href = href;
					}
				});
			}
		}
	});

	/*jQuery(window).bind("beforeunload",function(event) {
		if(jQuery("#btn_submit").hasClass("btn-active")){

		}else{
			return "You have some unsaved changes";
		}
	});*/
</script>



</head>

<body >

<div class="mainwrapper">

    <div class="header">
        <?php include_once 'includes/header.php';?>
    </div>

    <div class="leftpanel">

        <div class="leftmenu">
            <?php include_once 'includes/left_menu.php';?>
        </div><!--leftmenu-->

    </div><!-- leftpanel -->

    <div class="rightpanel">

        <ul class="breadcrumbs">

            <li><a href="messages.php"><i class="iconfa-home"></i></a> <span class="separator"></span></li>
            <li>Setup <span class="separator"></span> Restaurant <span class="separator"></span></li>
										<li><!--<a href="menu_live_page.php">-->"Menu Live"</li>

            <li class="right">
                <a href="" data-toggle="dropdown" class="dropdown-toggle"><i class="icon-tint"></i> Color Skins</a>
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
		<!--
                        <div style="float:right;margin-top: 11px;">
<a href="javascript:clearForm();" role="button"  ><button id="add" class="btn btn-success btn-large">Add</button></a>
</div>-->
			 <div style="float:right;margin-top: 10px;">
					<button id="btn_submit" class="btn btn-active" style="height: 42px;">Submit</button>
                    <input type="hidden" id="hidItemIds" name="hidItemIds" value="0">
                    <input type="hidden" id="hidMenu" name="hidMenu" value="0">
                    <input type="hidden" id="hidMenuGroupID" name="hidMenuGroupID" value="0">
			<select name="menuselect" style="width:180px; margin: 0px 0px 0px 5px; height: 42px;" id="menuselect">
			<?
if(mysql_num_rows($query)!=0)
{
	mysql_data_seek($query,0);
	while ($row_allworker = mysql_fetch_array($query))
	{
				?>
				  <option value="<?=$row_allworker['id']?>" <?  if($menu==$row_allworker['id']){?> selected <? }?> ><? echo $row_allworker['menu'];?></option>
				  <?
	}
}
			  ?>
	</select>
            </div>

            <div class="pageicon"><span class="iconfa-cog"></span></div>
            <div class="pagetitle">
                <h5>Add and Edit Menu Live</h5>
                <h1>Menu Live</h1>
            </div>
        </div><!--pageheader-->

        <div class="maincontent">
            <div class="maincontentinner">
							<div class="row-fluid">
								<div class="span8" id="left_mailDiv">
								                <div class="tabbedwidget tab-primary ui-tabs ui-widget ui-widget-content ui-corner-all" id="main_div" style="height: 744px;"> <!--height:500px;overflow:scroll;-->
								                    <ul style="height:38px; float:left; width:100%;" class="ui-tabs-nav ui-helper-reset ui-helper-clearfix ui-widget-header ui-corner-all">


								                        <li id="item_open" style="" class="ui-state-default ui-corner-top" onclick="resizeDiv()">

																				</li>

								                        <li id="item-tab" onclick="resizeDiv()" class="ui-state-default ui-corner-top ui-state-disabled" style="display: none;">
								                        	 <a href="#e-8" style=" vertical-align:top; text-align:center;" onclick="lastpagao='wrapper11';fodaoss=false;openpage('wrapper23');" id="open_order_first" class="">
								                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
								                                <tbody><tr><td class="tborng-cntr" style="vertical-align:middle;">Items</td></tr>
								                            </tbody></table>
								                            </a>
								                        </li>
<!-- 								                  		<li id="item-hide" style="" onclick="resizeDiv()" class="ui-state-default ui-corner-top ui-tabs-selected ui-state-active">
								                            <a href="#e-8" id="open_order" style=" vertical-align:top; text-align:center;" onclick="openpage('wrapper23');">
								                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
								                                <tbody><tr><td class="tborng-cntr" style="vertical-align:middle;">Items</td></tr>
								                            </tbody></table>
								                            </a>
								                        </li>
								                        <li id="item-closetab" style="display:none;" onclick="resizeDiv()" class="ui-state-default ui-corner-top ui-state-disabled">
								                            <a href="#" id="open_order" style=" vertical-align:top; text-align:center;" onclick="sendpaymen=false;openpage('wrapper23');">
								                            <table width="100%" border="0" cellspacing="0" cellpadding="0">
								                                <tbody><tr><td class="tborng-cntr" style="vertical-align:middle;">Items</td></tr>
								                            </tbody></table>
								                            </a>
								                        </li> -->
								                    </ul>

								            <div id="e-8" class="ui-tabs-panel ui-widget-content ui-corner-bottom" style="height: 693px; overflow-y: hidden;"> <!--height:auto;-->
								                <div id="pop_wrap" class="popup loading">
								                    <div style="padding: 10px 0px;clear:both;">
								                        <table width="100%" cellspacing="0" cellpadding="0" border="0">
								                            <tbody>
																							<tr>
																								<td style="width:95%;">
								                               	<label id="searchfldscnd_placeholder" style="display:none">Search For Menu Items</label>
																								 <input type="text" style="background: url('images/icons/search.png') no-repeat scroll 98% 7px #FFFFFF;font-size: 12px;padding:2px;margin-right:5px;margin-bottom: 0;" placeholder="Search For Items" value="" name="keyword" id="searchfldscnd" onkeyup="searchit();">
																							 	</td>
								                            		<td style="text-align:left; width:8%;">
								                                	<a class="filterbtn15" href="#" style="margin-left:15px;">
								                                		<img src="images/filter_icon.png" style="vertical-align:middle;">
								                                	</a>
								                             		</td>
								                          		</tr>
								                        		</tbody>
																					</table>
								                    	</div>
								                	</div>

														<div class="container fluid">
														  <div class="row align-items-start">
														    <div class="col">
														      <input class="btn btn-primary btn-large active" aria-pressed="false" role="button"  data-toggle="buttons" type="submit" value="Submit">
														    </div>
														    </div>

														    </div>



														<!-- <section id="gui-1179" class="widgettitle_lgtblue" onclick="opengroup('1179')">
															<section id="bluebartext" class="bluebartext" style="height:35px;min-height:35px;max-height:35px;overflow:hidden;">
																<section class="bluebartextcell" style="padding-top:7px;height:35px;min-height:35px;max-height:35px;overflow:hidden;text-align:center;">Salade</section>
																</section>
															</section>
 -->
															<!-- <div id="adg-1179" class="contgroup" style="display:none;border:"><section class="greybardiv" style="marin-bottom:1%;overflow:hidden"><section class="greybar-tab" style="height:35px;display:table"><section class="ibtndiv" onclick="infoitem('13495'); "><img style="vertical-align:middle" src="images/ibtn-slver.png"></section><section class="dishnamew" style=" width:75%;display:table-cell;height:100%;vertical-align:middle;min-width:100%;padding-left:30px;text-align:left" id="valit-13495" onclick="additem('1179','no','99','1','0','340','13495','59.00' ,'Nicoise','0','First','no','no','0','no','no','No','','no','0','1','Food','','','0.00');event.stopPropagation();">Nicoise</section><section class="dishprice222" style="display:table-cell;height:100%;vertical-align:middle;min-width:94px;padding-right:20px;text-align:right" onclick="additem('1179','no','99','1','0','340','13495','59.00' ,'Nicoise','0','First','no','no','0','no','no','No','','no','0','1','Food','','','0.00');event.stopPropagation();">Dh 589.00</section><section id="mbtndiv" class="mbtndiv" style="display:table-cell;height:100%;vertical-align:middle;width:40px;min-width:40px;"><section style=""></section> </section></section><section id="iddet-13495" class="greybardiv-extnd" style="display:none"><section class="greybar-tab-extnd"><section class="dishimg" style="margin-top:5px;margin-left:5px"><img style="float:left; height:88px" src="http://www.softpointdev.com/images/location_menu_item/44c38a7ef8e2406a023e224e5add557f-.jpg"></section><section class="dishdesc" style="padding-left:115px;"><section class="dishtitle">PLU#502</section><section class="dishingr"></section></section></section> </section><section class="greybardiv" style="marin-bottom:1%;overflow:hidden"><section class="greybar-tab" style="height:35px;display:table"><section class="ibtndiv" onclick="infoitem('13496'); "><img style="vertical-align:middle" src="images/ibtn-slver.png"></section><section class="dishnamew" style=" width:75%;display:table-cell;height:100%;vertical-align:middle;min-width:100%;padding-left:30px;text-align:left" id="valit-13496" onclick="additem('1179','no','99','1','0','340','13496','55.00' ,'Cesar','0','First','no','no','0','no','no','No','','no','0','1','Food','','','0.00');event.stopPropagation();">Cesar</section><section class="dishprice222" style="display:table-cell;height:100%;vertical-align:middle;min-width:94px;padding-right:20px;text-align:right" onclick="additem('1179','no','99','1','0','340','13496','55.00' ,'Cesar','0','First','no','no','0','no','no','No','','no','0','1','Food','','','0.00');event.stopPropagation();">Dh 55.00</section><section id="mbtndiv" class="mbtndiv" style="display:table-cell;height:100%;vertical-align:middle;width:40px;min-width:40px;"><section style=""></section> </section></section><section id="iddet-13496" class="greybardiv-extnd" style="display:none"><section class="greybar-tab-extnd"><section class="dishimg" style="margin-top:5px;margin-left:5px"><img style="float:left; height:88px" src="http://www.softpointdev.com/images/location_menu_item/9ce991602a3afb623453601e5c8526f7-.jpg"></section><section class="dishdesc" style="padding-left:115px;"><section class="dishtitle">PLU#503</section><section class="dishingr"></section></section></section> </section><section class="greybardiv" style="marin-bottom:1%;overflow:hidden"><section class="greybar-tab" style="height:35px;display:table"><section class="ibtndiv" onclick="infoitem('13497'); "><img style="vertical-align:middle" src="images/ibtn-slver.png"></section><section class="dishnamew" style=" width:75%;display:table-cell;height:100%;vertical-align:middle;min-width:100%;padding-left:30px;text-align:left" id="valit-13497" onclick="additem('1179','no','99','1','0','340','13497','50.00' ,'Fatouche','0','First','no','no','0','no','no','No','','no','0','1','Food','','','0.00');event.stopPropagation();">Fatouche</section><section class="dishprice222" style="display:table-cell;height:100%;vertical-align:middle;min-width:94px;padding-right:20px;text-align:right" onclick="additem('1179','no','99','1','0','340','13497','50.00' ,'Fatouche','0','First','no','no','0','no','no','No','','no','0','1','Food','','','0.00');event.stopPropagation();">Dh 50.00</section><section id="mbtndiv" class="mbtndiv" style="display:table-cell;height:100%;vertical-align:middle;width:40px;min-width:40px;"><section style=""></section> </section></section><section id="iddet-13497" class="greybardiv-extnd" style="display:none"><section class="greybar-tab-extnd"><section class="dishimg" style="margin-top:5px;margin-left:5px"><img style="float:left; height:88px" src="http://www.softpointdev.com/images/location_menu_item/5bf400d550c75f145857d38c4ad77be5-.png"></section><section class="dishdesc" style="padding-left:115px;"><section class="dishtitle">PLU#504</section><section class="dishingr"></section></section></section> </section><section class="greybardiv" style="marin-bottom:1%;overflow:hidden"><section class="greybar-tab" style="height:35px;display:table"><section class="ibtndiv" onclick="infoitem('13498'); "><img style="vertical-align:middle" src="images/ibtn-slver.png"></section><section class="dishnamew" style=" width:75%;display:table-cell;height:100%;vertical-align:middle;min-width:100%;padding-left:30px;text-align:left" id="valit-13498" onclick="additem('1179','no','99','1','0','340','13498','60.00' ,'Houmousse','0','First','no','no','0','no','no','No','','no','0','1','Food','','','0.00');event.stopPropagation();">Houmousse</section><section class="dishprice222" style="display:table-cell;height:100%;vertical-align:middle;min-width:94px;padding-right:20px;text-align:right" onclick="additem('1179','no','99','1','0','340','13498','60.00' ,'Houmousse','0','First','no','no','0','no','no','No','','no','0','1','Food','','','0.00');event.stopPropagation();">Dh 60.00</section><section id="mbtndiv" class="mbtndiv" style="display:table-cell;height:100%;vertical-align:middle;width:40px;min-width:40px;"><section style=""></section> </section></section><section id="iddet-13498" class="greybardiv-extnd" style="display:none"><section class="greybar-tab-extnd"><section class="dishimg" style="margin-top:5px;margin-left:5px"><img style="float:left; height:88px" src="http://www.softpointdev.com/images/location_menu_item/c2ff73b4eabd66ab869f3fa8f3c109ac-.png"></section><section class="dishdesc" style="padding-left:115px;"><section class="dishtitle">PLU#505</section><section class="dishingr"></section></section></section> </section><section class="greybardiv" style="marin-bottom:1%;overflow:hidden"><section class="greybar-tab" style="height:35px;display:table"><section class="ibtndiv" onclick="infoitem('13499'); "><img style="vertical-align:middle" src="images/ibtn-slver.png"></section><section class="dishnamew" style=" width:75%;display:table-cell;height:100%;vertical-align:middle;min-width:100%;padding-left:30px;text-align:left" id="valit-13499" onclick="additem('1179','no','99','1','0','340','13499','60.00' ,'Salade Marocain','0','First','no','no','0','no','no','No','','no','0','1','Food','','','0.00');event.stopPropagation();">Salade Marocain</section><section class="dishprice222" style="display:table-cell;height:100%;vertical-align:middle;min-width:94px;padding-right:20px;text-align:right" onclick="additem('1179','no','99','1','0','340','13499','60.00' ,'Salade Marocain','0','First','no','no','0','no','no','No','','no','0','1','Food','','','0.00');event.stopPropagation();">Dh 60.00</section><section id="mbtndiv" class="mbtndiv" style="display:table-cell;height:100%;vertical-align:middle;width:40px;min-width:40px;"><section style=""></section> </section></section><section id="iddet-13499" class="greybardiv-extnd" style="display:none"><section class="greybar-tab-extnd"><section class="dishimg" style="margin-top:5px;margin-left:5px"><img style="float:left; height:88px" src="http://www.softpointdev.com/images/location_menu_item/5adb6dc4610c07f54a142a997c5bbfd6-.png"></section><section class="dishdesc" style="padding-left:115px;"><section class="dishtitle">PLU#506</section><section class="dishingr"></section></section></section> </section><section class="greybardiv" style="marin-bottom:1%;overflow:hidden"><section class="greybar-tab" style="height:35px;display:table"><section class="ibtndiv" onclick="infoitem('13500'); "><img style="vertical-align:middle" src="images/ibtn-slver.png"></section><section class="dishnamew" style=" width:75%;display:table-cell;height:100%;vertical-align:middle;min-width:100%;padding-left:30px;text-align:left" id="valit-13500" onclick="additem('1179','no','99','1','0','340','13500','70.00' ,'Moutabal','0','First','no','no','0','no','no','No','','no','0','1','Food','','','0.00');event.stopPropagation();">Moutabal</section><section class="dishprice222" style="display:table-cell;height:100%;vertical-align:middle;min-width:94px;padding-right:20px;text-align:right" onclick="additem('1179','no','99','1','0','340','13500','70.00' ,'Moutabal','0','First','no','no','0','no','no','No','','no','0','1','Food','','','0.00');event.stopPropagation();">Dh 70.00</section><section id="mbtndiv" class="mbtndiv" style="display:table-cell;height:100%;vertical-align:middle;width:40px;min-width:40px;"><section style=""></section> </section></section><section id="iddet-13500" class="greybardiv-extnd" style="display:none"><section class="greybar-tab-extnd"><section class="dishimg" style="margin-top:5px;margin-left:5px"><img style="float:left; height:88px" src="http://www.softpointdev.com/images/location_menu_item/f7348199c2a328f041a918a50f1dca2c-.jpg"></section><section class="dishdesc" style="padding-left:115px;"><section class="dishtitle">PLU#507</section><section class="dishingr"></section></section></section> </section><section class="greybardiv" style="marin-bottom:1%;overflow:hidden"><section class="greybar-tab" style="height:35px;display:table"><section class="ibtndiv" onclick="infoitem('13501'); "><img style="vertical-align:middle" src="images/ibtn-slver.png"></section><section class="dishnamew" style=" width:75%;display:table-cell;height:100%;vertical-align:middle;min-width:100%;padding-left:30px;text-align:left" id="valit-13501" onclick="additem('1179','no','99','1','0','340','13501','45.00' ,'Salad Rose','0','First','no','no','0','no','no','No','','no','0','1','Food','','','0.00');event.stopPropagation();">Salad Rose</section><section class="dishprice222" style="display:table-cell;height:100%;vertical-align:middle;min-width:94px;padding-right:20px;text-align:right" onclick="additem('1179','no','99','1','0','340','13501','45.00' ,'Salad Rose','0','First','no','no','0','no','no','No','','no','0','1','Food','','','0.00');event.stopPropagation();">Dh 45.00</section><section id="mbtndiv" class="mbtndiv" style="display:table-cell;height:100%;vertical-align:middle;width:40px;min-width:40px;"><section style=""></section> </section></section><section id="iddet-13501" class="greybardiv-extnd" style="display:none"><section class="greybar-tab-extnd"><section class="dishimg" style="margin-top:5px;margin-left:5px"><img style="float:left; height:88px" src="http://www.softpointdev.com/images/location_menu_item/45333c539793f384811f3c3d0ec28e18-.png"></section><section class="dishdesc" style="padding-left:115px;"><section class="dishtitle">PLU#508</section><section class="dishingr"></section></section></section> </section></section></section></section></section></section></section></section></div><section id="gui-1180" class="widgettitle_lgtblue" onclick="opengroup('1180')"><section id="bluebartext" class="bluebartext" style="height:35px;min-height:35px;max-height:35px;overflow:hidden;"><section class="bluebartextcell" style="padding-top:7px;height:35px;min-height:35px;max-height:35px;overflow:hidden;text-align:center;">Sindwitch</section></section></section><div id="adg-1180" class="contgroup" style="display:none;border:"><section class="greybardiv" style="marin-bottom:1%;overflow:hidden"><section class="greybar-tab" style="height:35px;display:table"><section class="ibtndiv" onclick="infoitem('7617'); "><img style="vertical-align:middle" src="images/ibtn-slver.png"></section><section class="dishnamew" style=" width:75%;display:table-cell;height:100%;vertical-align:middle;min-width:100%;padding-left:30px;text-align:left" id="valit-7617" onclick="additem('1180','no','99','1','0','340','7617','69.00' ,'Panini Poulet','0','Second','no','no','0','no','yes','No','','no','0','1','Food','','','0.00');event.stopPropagation();">Panini Poulet</section><section class="dishprice222" style="display:table-cell;height:100%;vertical-align:middle;min-width:94px;padding-right:20px;text-align:right" onclick="additem('1180','no','99','1','0','340','7617','69.00' ,'Panini Poulet','0','Second','no','no','0','no','yes','No','','no','0','1','Food','','','0.00');event.stopPropagation();">Dh 69.00</section><section id="mbtndiv" class="mbtndiv" style="display:table-cell;height:100%;vertical-align:middle;width:40px;min-width:40px;"><section style=""></section> </section></section><section id="iddet-7617" class="greybardiv-extnd" style="display:none"><section class="greybar-tab-extnd"><section class="dishimg" style="margin-top:5px;margin-left:5px"><img style="float:left; height:88px" src="http://www.softpointdev.com/images/location_menu_item/cc46cba5da67a991e34e1ade124f1f00-.jpg"></section><section class="dishdesc" style="padding-left:115px;"><section class="dishtitle">PLU#124</section><section class="dishingr"></section></section></section> </section><section class="greybardiv" style="marin-bottom:1%;overflow:hidden"><section class="greybar-tab" style="height:35px;display:table"><section class="ibtndiv" onclick="infoitem('7615'); "><img style="vertical-align:middle" src="images/ibtn-slver.png"></section><section class="dishnamew" style=" width:75%;display:table-cell;height:100%;vertical-align:middle;min-width:100%;padding-left:30px;text-align:left" id="valit-7615" onclick="additem('1180','no','99','1','0','340','7615','69.00' ,'Panini Viande Hachée ','0','Second','no','no','0','no','yes','No','','no','0','1','Food','','','0.00');event.stopPropagation();">Panini Viande Hachée </section><section class="dishprice222" style="display:table-cell;height:100%;vertical-align:middle;min-width:94px;padding-right:20px;text-align:right" onclick="additem('1180','no','99','1','0','340','7615','69.00' ,'Panini Viande Hachée ','0','Second','no','no','0','no','yes','No','','no','0','1','Food','','','0.00');event.stopPropagation();">Dh 69.00</section><section id="mbtndiv" class="mbtndiv" style="display:table-cell;height:100%;vertical-align:middle;width:40px;min-width:40px;"><section style=""></section> </section></section><section id="iddet-7615" class="greybardiv-extnd" style="display:none"><section class="greybar-tab-extnd"><section class="dishimg" style="margin-top:5px;margin-left:5px"><img style="float:left; height:88px" src="http://www.softpointdev.com/images/location_menu_item/1bf4a52c7071e756dae859de04337ab3-.jpg"></section><section class="dishdesc" style="padding-left:115px;"><section class="dishtitle">PLU#122</section><section class="dishingr"></section></section></section> </section><section class="greybardiv" style="marin-bottom:1%;overflow:hidden"><section class="greybar-tab" style="height:35px;display:table"><section class="ibtndiv" onclick="infoitem('7616'); "><img style="vertical-align:middle" src="images/ibtn-slver.png"></section><section class="dishnamew" style=" width:75%;display:table-cell;height:100%;vertical-align:middle;min-width:100%;padding-left:30px;text-align:left" id="valit-7616" onclick="additem('1180','no','99','1','0','340','7616','69.00' ,'Panini Thon','0','Second','no','no','0','no','yes','No','','no','0','1','Food','','','0.00');event.stopPropagation();">Panini Thon</section><section class="dishprice222" style="display:table-cell;height:100%;vertical-align:middle;min-width:94px;padding-right:20px;text-align:right" onclick="additem('1180','no','99','1','0','340','7616','69.00' ,'Panini Thon','0','Second','no','no','0','no','yes','No','','no','0','1','Food','','','0.00');event.stopPropagation();">Dh 69.00</section><section id="mbtndiv" class="mbtndiv" style="display:table-cell;height:100%;vertical-align:middle;width:40px;min-width:40px;"><section style=""></section> </section></section><section id="iddet-7616" class="greybardiv-extnd" style="display:none"><section class="greybar-tab-extnd"><section class="dishimg" style="margin-top:5px;margin-left:5px"><img style="float:left; height:88px" src="http://www.softpointdev.com/images/location_menu_item/fd438115fb04bc5ed7e37fbead851e8a-.jpg"></section><section class="dishdesc" style="padding-left:115px;"><section class="dishtitle">PLU#123</section><section class="dishingr"></section></section></section> </section><section class="greybardiv" style="marin-bottom:1%;overflow:hidden"><section class="greybar-tab" style="height:35px;display:table"><section class="ibtndiv" onclick="infoitem('13502'); "><img style="vertical-align:middle" src="images/ibtn-slver.png"></section><section class="dishnamew" style=" width:75%;display:table-cell;height:100%;vertical-align:middle;min-width:100%;padding-left:30px;text-align:left" id="valit-13502" onclick="additem('1180','no','99','1','0','340','13502','79.00' ,'Sindwitch Mixte','0','First','no','no','0','no','no','No','','no','0','1','Food','','','0.00');event.stopPropagation();">Sindwitch Mixte</section><section class="dishprice222" style="display:table-cell;height:100%;vertical-align:middle;min-width:94px;padding-right:20px;text-align:right" onclick="additem('1180','no','99','1','0','340','13502','79.00' ,'Sindwitch Mixte','0','First','no','no','0','no','no','No','','no','0','1','Food','','','0.00');event.stopPropagation();">Dh 79.00</section><section id="mbtndiv" class="mbtndiv" style="display:table-cell;height:100%;vertical-align:middle;width:40px;min-width:40px;"><section style=""></section> </section></section><section id="iddet-13502" class="greybardiv-extnd" style="display:none"><section class="greybar-tab-extnd"><section class="dishimg" style="margin-top:5px;margin-left:5px"><img style="float:left; height:88px" src="images/defimgpro.png"></section><section class="dishdesc" style="padding-left:115px;"><section class="dishtitle">PLU#509</section><section class="dishingr"></section></section></section> </section><section class="greybardiv" style="marin-bottom:1%;overflow:hidden"><section class="greybar-tab" style="height:35px;display:table"><section class="ibtndiv" onclick="infoitem('13503'); "><img style="vertical-align:middle" src="images/ibtn-slver.png"></section><section class="dishnamew" style=" width:75%;display:table-cell;height:100%;vertical-align:middle;min-width:100%;padding-left:30px;text-align:left" id="valit-13503" onclick="additem('1180','no','99','1','0','340','13503','79.00' ,'Sindwich Marrakchi','0','First','no','no','0','no','no','No','','no','0','1','Food','','','0.00');event.stopPropagation();">Sindwich Marrakchi</section><section class="dishprice222" style="display:table-cell;height:100%;vertical-align:middle;min-width:94px;padding-right:20px;text-align:right" onclick="additem('1180','no','99','1','0','340','13503','79.00' ,'Sindwich Marrakchi','0','First','no','no','0','no','no','No','','no','0','1','Food','','','0.00');event.stopPropagation();">Dh 79.00</section><section id="mbtndiv" class="mbtndiv" style="display:table-cell;height:100%;vertical-align:middle;width:40px;min-width:40px;"><section style=""></section> </section></section><section id="iddet-13503" class="greybardiv-extnd" style="display:none"><section class="greybar-tab-extnd"><section class="dishimg" style="margin-top:5px;margin-left:5px"><img style="float:left; height:88px" src="http://www.softpointdev.com/images/location_menu_item/5ceb0468e803fa8825f054f9f9e57201-.png"></section><section class="dishdesc" style="padding-left:115px;"><section class="dishtitle">PLU#510</section><section class="dishingr"></section></section></section> </section><section class="greybardiv" style="marin-bottom:1%;overflow:hidden"><section class="greybar-tab" style="height:35px;display:table"><section class="ibtndiv" onclick="infoitem('13504'); "><img style="vertical-align:middle" src="images/ibtn-slver.png"></section><section class="dishnamew" style=" width:75%;display:table-cell;height:100%;vertical-align:middle;min-width:100%;padding-left:30px;text-align:left" id="valit-13504" onclick="additem('1180','no','99','1','0','340','13504','79.00' ,'Humburger','0','First','no','no','0','no','no','No','','no','0','1','Food','','','0.00');event.stopPropagation();">Humburger</section><section class="dishprice222" style="display:table-cell;height:100%;vertical-align:middle;min-width:94px;padding-right:20px;text-align:right" onclick="additem('1180','no','99','1','0','340','13504','79.00' ,'Humburger','0','First','no','no','0','no','no','No','','no','0','1','Food','','','0.00');event.stopPropagation();">Dh 79.00</section><section id="mbtndiv" class="mbtndiv" style="display:table-cell;height:100%;vertical-align:middle;width:40px;min-width:40px;"><section style=""></section> </section></section><section id="iddet-13504" class="greybardiv-extnd" style="display:none"><section class="greybar-tab-extnd"><section class="dishimg" style="margin-top:5px;margin-left:5px"><img style="float:left; height:88px" src="http://www.softpointdev.com/images/location_menu_item/0d6cc13eec05c866815c6419f856d7ba-.jpg"></section><section class="dishdesc" style="padding-left:115px;"><section class="dishtitle">PLU#511</section><section class="dishingr"></section></section></section> </section><section class="greybardiv" style="marin-bottom:1%;overflow:hidden"><section class="greybar-tab" style="height:35px;display:table"><section class="ibtndiv" onclick="infoitem('13505'); "><img style="vertical-align:middle" src="images/ibtn-slver.png"></section><section class="dishnamew" style=" width:75%;display:table-cell;height:100%;vertical-align:middle;min-width:100%;padding-left:30px;text-align:left" id="valit-13505" onclick="additem('1180','no','99','1','0','340','13505','79.00' ,'Checken Bureger','0','First','no','no','0','no','no','No','','no','0','1','Food','','','0.00');event.stopPropagation();">Checken Bureger</section><section class="dishprice222" style="display:table-cell;height:100%;vertical-align:middle;min-width:94px;padding-right:20px;text-align:right" onclick="additem('1180','no','99','1','0','340','13505','79.00' ,'Checken Bureger','0','First','no','no','0','no','no','No','','no','0','1','Food','','','0.00');event.stopPropagation();">Dh 79.00</section><section id="mbtndiv" class="mbtndiv" style="display:table-cell;height:100%;vertical-align:middle;width:40px;min-width:40px;"><section style=""></section> </section></section><section id="iddet-13505" class="greybardiv-extnd" style="display:none"><section class="greybar-tab-extnd"><section class="dishimg" style="margin-top:5px;margin-left:5px"><img style="float:left; height:88px" src="http://www.softpointdev.com/images/location_menu_item/d1574e1ec3c60e77dc1420f7e60e615b-.png"></section><section class="dishdesc" style="padding-left:115px;"><section class="dishtitle">PLU#512</section><section class="dishingr"></section></section></section> </section><section class="greybardiv" style="marin-bottom:1%;overflow:hidden"><section class="greybar-tab" style="height:35px;display:table"><section class="ibtndiv" onclick="infoitem('13506'); "><img style="vertical-align:middle" src="images/ibtn-slver.png"></section><section class="dishnamew" style=" width:75%;display:table-cell;height:100%;vertical-align:middle;min-width:100%;padding-left:30px;text-align:left" id="valit-13506" onclick="additem('1180','no','99','1','0','340','13506','79.00' ,'Club Sindwich Poulet','0','First','no','no','0','no','no','No','','no','0','1','Food','','','0.00');event.stopPropagation();">Club Sindwich Poulet</section><section class="dishprice222" style="display:table-cell;height:100%;vertical-align:middle;min-width:94px;padding-right:20px;text-align:right" onclick="additem('1180','no','99','1','0','340','13506','79.00' ,'Club Sindwich Poulet','0','First','no','no','0','no','no','No','','no','0','1','Food','','','0.00');event.stopPropagation();">Dh 79.00</section><section id="mbtndiv" class="mbtndiv" style="display:table-cell;height:100%;vertical-align:middle;width:40px;min-width:40px;"><section style=""></section> </section></section><section id="iddet-13506" class="greybardiv-extnd" style="display:none"><section class="greybar-tab-extnd"><section class="dishimg" style="margin-top:5px;margin-left:5px"><img style="float:left; height:88px" src="http://www.softpointdev.com/images/location_menu_item/d50ba596206bd42f88dd73456bee7b32-.png"></section><section class="dishdesc" style="padding-left:115px;"><section class="dishtitle">PLU#513</section><section class="dishingr"></section></section></section> </section><section class="greybardiv" style="marin-bottom:1%;overflow:hidden"><section class="greybar-tab" style="height:35px;display:table"><section class="ibtndiv" onclick="infoitem('13507'); "><img style="vertical-align:middle" src="images/ibtn-slver.png"></section><section class="dishnamew" style=" width:75%;display:table-cell;height:100%;vertical-align:middle;min-width:100%;padding-left:30px;text-align:left" id="valit-13507" onclick="additem('1180','no','99','1','0','340','13507','79.00' ,'Club Sindwich Thon','0','First','no','no','0','no','no','No','','no','0','1','Food','','','0.00');event.stopPropagation();">Club Sindwich Thon</section><section class="dishprice222" style="display:table-cell;height:100%;vertical-align:middle;min-width:94px;padding-right:20px;text-align:right" onclick="additem('1180','no','99','1','0','340','13507','79.00' ,'Club Sindwich Thon','0','First','no','no','0','no','no','No','','no','0','1','Food','','','0.00');event.stopPropagation();">Dh 79.00</section><section id="mbtndiv" class="mbtndiv" style="display:table-cell;height:100%;vertical-align:middle;width:40px;min-width:40px;"><section style=""></section> </section></section><section id="iddet-13507" class="greybardiv-extnd" style="display:none"><section class="greybar-tab-extnd"><section class="dishimg" style="margin-top:5px;margin-left:5px"><img style="float:left; height:88px" src="http://www.softpointdev.com/images/location_menu_item/62059da8f8ff7ec3ce265ec62f013d69-.jpg"></section><section class="dishdesc" style="padding-left:115px;"><section class="dishtitle">PLU#514</section><section class="dishingr"></section></section></section> </section></section></section></section></section></section></section></section></section></section></div><section id="gui-1181" class="widgettitle_lgtblue" onclick="opengroup('1181')"><section id="bluebartext" class="bluebartext" style="height:35px;min-height:35px;max-height:35px;overflow:hidden;"><section class="bluebartextcell" style="padding-top:7px;height:35px;min-height:35px;max-height:35px;overflow:hidden;text-align:center;">Les Plats</section></section></section><div id="adg-1181" class="contgroup" style="display:none;border:"><section class="greybardiv" style="marin-bottom:1%;overflow:hidden"><section class="greybar-tab" style="height:35px;display:table"><section class="ibtndiv" onclick="infoitem('13508'); "><img style="vertical-align:middle" src="images/ibtn-slver.png"></section><section class="dishnamew" style=" width:75%;display:table-cell;height:100%;vertical-align:middle;min-width:100%;padding-left:30px;text-align:left" id="valit-13508" onclick="additem('1181','no','99','1','0','340','13508','99.00' ,'Kabssa','0','First','no','no','0','no','no','No','','no','0','1','Food','','','0.00');event.stopPropagation();">Kabssa</section><section class="dishprice222" style="display:table-cell;height:100%;vertical-align:middle;min-width:94px;padding-right:20px;text-align:right" onclick="additem('1181','no','99','1','0','340','13508','99.00' ,'Kabssa','0','First','no','no','0','no','no','No','','no','0','1','Food','','','0.00');event.stopPropagation();">Dh 99.00</section><section id="mbtndiv" class="mbtndiv" style="display:table-cell;height:100%;vertical-align:middle;width:40px;min-width:40px;"><section style=""></section> </section></section><section id="iddet-13508" class="greybardiv-extnd" style="display:none"><section class="greybar-tab-extnd"><section class="dishimg" style="margin-top:5px;margin-left:5px"><img style="float:left; height:88px" src="http://www.softpointdev.com/images/location_menu_item/c4d35d10682ca6e99fbd6bfa19aaf610-.jpg"></section><section class="dishdesc" style="padding-left:115px;"><section class="dishtitle">PLU#515</section><section class="dishingr"></section></section></section> </section><section class="greybardiv" style="marin-bottom:1%;overflow:hidden"><section class="greybar-tab" style="height:35px;display:table"><section class="ibtndiv" onclick="infoitem('13509'); "><img style="vertical-align:middle" src="images/ibtn-slver.png"></section><section class="dishnamew" style=" width:75%;display:table-cell;height:100%;vertical-align:middle;min-width:100%;padding-left:30px;text-align:left" id="valit-13509" onclick="additem('1181','no','99','1','0','340','13509','99.00' ,'Mandi','0','First','no','no','0','no','no','No','','no','0','1','Food','','','0.00');event.stopPropagation();">Mandi</section><section class="dishprice222" style="display:table-cell;height:100%;vertical-align:middle;min-width:94px;padding-right:20px;text-align:right" onclick="additem('1181','no','99','1','0','340','13509','99.00' ,'Mandi','0','First','no','no','0','no','no','No','','no','0','1','Food','','','0.00');event.stopPropagation();">Dh 99.00</section><section id="mbtndiv" class="mbtndiv" style="display:table-cell;height:100%;vertical-align:middle;width:40px;min-width:40px;"><section style=""></section> </section></section><section id="iddet-13509" class="greybardiv-extnd" style="display:none"><section class="greybar-tab-extnd"><section class="dishimg" style="margin-top:5px;margin-left:5px"><img style="float:left; height:88px" src="http://www.softpointdev.com/images/location_menu_item/816ce900603bd977c1a06d01ee84e7c6-.jpg"></section><section class="dishdesc" style="padding-left:115px;"><section class="dishtitle">PLU#516</section><section class="dishingr"></section></section></section> </section><section class="greybardiv" style="marin-bottom:1%;overflow:hidden"><section class="greybar-tab" style="height:35px;display:table"><section class="ibtndiv" onclick="infoitem('13510'); "><img style="vertical-align:middle" src="images/ibtn-slver.png"></section><section class="dishnamew" style=" width:75%;display:table-cell;height:100%;vertical-align:middle;min-width:100%;padding-left:30px;text-align:left" id="valit-13510" onclick="additem('1181','no','99','1','0','340','13510','89.00' ,'Tajine Poulet','0','First','no','no','0','no','no','No','','no','0','1','Food','','','0.00');event.stopPropagation();">Tajine Poulet</section><section class="dishprice222" style="display:table-cell;height:100%;vertical-align:middle;min-width:94px;padding-right:20px;text-align:right" onclick="additem('1181','no','99','1','0','340','13510','89.00' ,'Tajine Poulet','0','First','no','no','0','no','no','No','','no','0','1','Food','','','0.00');event.stopPropagation();">Dh 89.00</section><section id="mbtndiv" class="mbtndiv" style="display:table-cell;height:100%;vertical-align:middle;width:40px;min-width:40px;"><section style=""></section> </section></section><section id="iddet-13510" class="greybardiv-extnd" style="display:none"><section class="greybar-tab-extnd"><section class="dishimg" style="margin-top:5px;margin-left:5px"><img style="float:left; height:88px" src="http://www.softpointdev.com/images/location_menu_item/e60f3dfb5a4f05050b8dc05d527605ed-.png"></section><section class="dishdesc" style="padding-left:115px;"><section class="dishtitle">PLU#517</section><section class="dishingr"></section></section></section> </section><section class="greybardiv" style="marin-bottom:1%;overflow:hidden"><section class="greybar-tab" style="height:35px;display:table"><section class="ibtndiv" onclick="infoitem('13511'); "><img style="vertical-align:middle" src="images/ibtn-slver.png"></section><section class="dishnamew" style=" width:75%;display:table-cell;height:100%;vertical-align:middle;min-width:100%;padding-left:30px;text-align:left" id="valit-13511" onclick="additem('1181','no','99','1','0','340','13511','89.00' ,'Tajine Viande','0','First','no','no','0','no','no','No','','no','0','1','Food','','','0.00');event.stopPropagation();">Tajine Viande</section><section class="dishprice222" style="display:table-cell;height:100%;vertical-align:middle;min-width:94px;padding-right:20px;text-align:right" onclick="additem('1181','no','99','1','0','340','13511','89.00' ,'Tajine Viande','0','First','no','no','0','no','no','No','','no','0','1','Food','','','0.00');event.stopPropagation();">Dh 89.00</section><section id="mbtndiv" class="mbtndiv" style="display:table-cell;height:100%;vertical-align:middle;width:40px;min-width:40px;"><section style=""></section> </section></section><section id="iddet-13511" class="greybardiv-extnd" style="display:none"><section class="greybar-tab-extnd"><section class="dishimg" style="margin-top:5px;margin-left:5px"><img style="float:left; height:88px" src="http://www.softpointdev.com/images/location_menu_item/07f52c7d3dd05de309e726ce4bca0162-.png"></section><section class="dishdesc" style="padding-left:115px;"><section class="dishtitle">PLU#518</section><section class="dishingr"></section></section></section> </section><section class="greybardiv" style="marin-bottom:1%;overflow:hidden"><section class="greybar-tab" style="height:35px;display:table"><section class="ibtndiv" onclick="infoitem('13512'); "><img style="vertical-align:middle" src="images/ibtn-slver.png"></section><section class="dishnamew" style=" width:75%;display:table-cell;height:100%;vertical-align:middle;min-width:100%;padding-left:30px;text-align:left" id="valit-13512" onclick="additem('1181','no','99','1','0','340','13512','79.00' ,'Emaince De Poulet','0','First','no','no','0','no','no','No','','no','0','1','Food','','','0.00');event.stopPropagation();">Emaince De Poulet</section><section class="dishprice222" style="display:table-cell;height:100%;vertical-align:middle;min-width:94px;padding-right:20px;text-align:right" onclick="additem('1181','no','99','1','0','340','13512','79.00' ,'Emaince De Poulet','0','First','no','no','0','no','no','No','','no','0','1','Food','','','0.00');event.stopPropagation();">Dh 79.00</section><section id="mbtndiv" class="mbtndiv" style="display:table-cell;height:100%;vertical-align:middle;width:40px;min-width:40px;"><section style=""></section> </section></section><section id="iddet-13512" class="greybardiv-extnd" style="display:none"><section class="greybar-tab-extnd"><section class="dishimg" style="margin-top:5px;margin-left:5px"><img style="float:left; height:88px" src="http://www.softpointdev.com/images/location_menu_item/39b6fb04f98d24cc46f60f06762089fe-.png"></section><section class="dishdesc" style="padding-left:115px;"><section class="dishtitle">PLU#519</section><section class="dishingr"></section></section></section> </section><section class="greybardiv" style="marin-bottom:1%;overflow:hidden"><section class="greybar-tab" style="height:35px;display:table"><section class="ibtndiv" onclick="infoitem('13513'); "><img style="vertical-align:middle" src="images/ibtn-slver.png"></section><section class="dishnamew" style=" width:75%;display:table-cell;height:100%;vertical-align:middle;min-width:100%;padding-left:30px;text-align:left" id="valit-13513" onclick="additem('1181','no','99','1','0','340','13513','69.00' ,'Escalop De Dind','0','First','no','no','0','no','no','No','','no','0','1','Food','','','0.00');event.stopPropagation();">Escalop De Dind</section><section class="dishprice222" style="display:table-cell;height:100%;vertical-align:middle;min-width:94px;padding-right:20px;text-align:right" onclick="additem('1181','no','99','1','0','340','13513','69.00' ,'Escalop De Dind','0','First','no','no','0','no','no','No','','no','0','1','Food','','','0.00');event.stopPropagation();">Dh 69.00</section><section id="mbtndiv" class="mbtndiv" style="display:table-cell;height:100%;vertical-align:middle;width:40px;min-width:40px;"><section style=""></section> </section></section><section id="iddet-13513" class="greybardiv-extnd" style="display:none"><section class="greybar-tab-extnd"><section class="dishimg" style="margin-top:5px;margin-left:5px"><img style="float:left; height:88px" src="images/defimgpro.png"></section><section class="dishdesc" style="padding-left:115px;"><section class="dishtitle">PLU#520</section><section class="dishingr"></section></section></section> </section><section class="greybardiv" style="marin-bottom:1%;overflow:hidden"><section class="greybar-tab" style="height:35px;display:table"><section class="ibtndiv" onclick="infoitem('13514'); "><img style="vertical-align:middle" src="images/ibtn-slver.png"></section><section class="dishnamew" style=" width:75%;display:table-cell;height:100%;vertical-align:middle;min-width:100%;padding-left:30px;text-align:left" id="valit-13514" onclick="additem('1181','no','99','1','0','340','13514','59.00' ,'Steak A Cheval','0','First','no','no','0','no','no','No','','no','0','1','Food','','','0.00');event.stopPropagation();">Steak A Cheval</section><section class="dishprice222" style="display:table-cell;height:100%;vertical-align:middle;min-width:94px;padding-right:20px;text-align:right" onclick="additem('1181','no','99','1','0','340','13514','59.00' ,'Steak A Cheval','0','First','no','no','0','no','no','No','','no','0','1','Food','','','0.00');event.stopPropagation();">Dh 59.00</section><section id="mbtndiv" class="mbtndiv" style="display:table-cell;height:100%;vertical-align:middle;width:40px;min-width:40px;"><section style=""></section> </section></section><section id="iddet-13514" class="greybardiv-extnd" style="display:none"><section class="greybar-tab-extnd"><section class="dishimg" style="margin-top:5px;margin-left:5px"><img style="float:left; height:88px" src="http://www.softpointdev.com/images/location_menu_item/bb8bcde1b9b66a27d15444821996ea47-.png"></section><section class="dishdesc" style="padding-left:115px;"><section class="dishtitle">PLU#521</section><section class="dishingr"></section></section></section> </section><section class="greybardiv" style="marin-bottom:1%;overflow:hidden"><section class="greybar-tab" style="height:35px;display:table"><section class="ibtndiv" onclick="infoitem('13515'); "><img style="vertical-align:middle" src="images/ibtn-slver.png"></section><section class="dishnamew" style=" width:75%;display:table-cell;height:100%;vertical-align:middle;min-width:100%;padding-left:30px;text-align:left" id="valit-13515" onclick="additem('1181','no','99','1','0','340','13515','99.00' ,'Coute D Andeaux','0','First','no','no','0','no','no','No','','no','0','1','Food','','','0.00');event.stopPropagation();">Coute D Andeaux</section><section class="dishprice222" style="display:table-cell;height:100%;vertical-align:middle;min-width:94px;padding-right:20px;text-align:right" onclick="additem('1181','no','99','1','0','340','13515','99.00' ,'Coute D Andeaux','0','First','no','no','0','no','no','No','','no','0','1','Food','','','0.00');event.stopPropagation();">Dh 99.00</section><section id="mbtndiv" class="mbtndiv" style="display:table-cell;height:100%;vertical-align:middle;width:40px;min-width:40px;"><section style=""></section> </section></section><section id="iddet-13515" class="greybardiv-extnd" style="display:none"><section class="greybar-tab-extnd"><section class="dishimg" style="margin-top:5px;margin-left:5px"><img style="float:left; height:88px" src="images/defimgpro.png"></section><section class="dishdesc" style="padding-left:115px;"><section class="dishtitle">PLU#522</section><section class="dishingr"></section></section></section> </section></section></section></section></section></section></section></section></section></div><section id="gui-1053" class="widgettitle_lgtblue" onclick="opengroup('1053')"><section id="bluebartext" class="bluebartext" style="height:35px;min-height:35px;max-height:35px;overflow:hidden;"><section class="bluebartextcell" style="padding-top:7px;height:35px;min-height:35px;max-height:35px;overflow:hidden;text-align:center;">Alcool</section></section></section><div id="adg-1053" class="contgroup" style="display:none;border:"><section class="greybardiv" style="marin-bottom:1%;overflow:hidden"><section class="greybar-tab" style="height:35px;display:table"><section class="ibtndiv" onclick="infoitem('8107'); "><img style="vertical-align:middle" src="images/ibtn-slver.png"></section><section class="dishnamew" style=" width:75%;display:table-cell;height:100%;vertical-align:middle;min-width:100%;padding-left:30px;text-align:left" id="valit-8107" onclick="additem('1053','no','99','1','0','340','8107','35.00' ,'Aperitifs','0','First','no','no','0','no','no','No','','no','0','1','Bar','','','0.00');event.stopPropagation();">Aperitifs</section><section class="dishprice222" style="display:table-cell;height:100%;vertical-align:middle;min-width:94px;padding-right:20px;text-align:right" onclick="additem('1053','no','99','1','0','340','8107','35.00' ,'Aperitifs','0','First','no','no','0','no','no','No','','no','0','1','Bar','','','0.00');event.stopPropagation();">Dh 35.00</section><section id="mbtndiv" class="mbtndiv" style="display:table-cell;height:100%;vertical-align:middle;width:40px;min-width:40px;"><section style=""></section> </section></section><section id="iddet-8107" class="greybardiv-extnd" style="display:none"><section class="greybar-tab-extnd"><section class="dishimg" style="margin-top:5px;margin-left:5px"><img style="float:left; height:88px" src="http://www.softpointdev.com/images/location_menu_item/099ef43a0f058767e354658f2cc3c016-.jpg"></section><section class="dishdesc" style="padding-left:115px;"><section class="dishtitle">PLU#240</section><section class="dishingr"></section></section></section> </section><section class="greybardiv" style="marin-bottom:1%;overflow:hidden"><section class="greybar-tab" style="height:35px;display:table"><section class="ibtndiv" onclick="infoitem('8111'); "><img style="vertical-align:middle" src="images/ibtn-slver.png"></section><section class="dishnamew" style=" width:75%;display:table-cell;height:100%;vertical-align:middle;min-width:100%;padding-left:30px;text-align:left" id="valit-8111" onclick="additem('1053','no','99','1','0','340','8111','40.00' ,'Vodka','0','First','','','0','no','no','No','','no','0','1','Bar','','','0.00');event.stopPropagation();">Vodka</section><section class="dishprice222" style="display:table-cell;height:100%;vertical-align:middle;min-width:94px;padding-right:20px;text-align:right" onclick="additem('1053','no','99','1','0','340','8111','40.00' ,'Vodka','0','First','','','0','no','no','No','','no','0','1','Bar','','','0.00');event.stopPropagation();">Dh 40.00</section><section id="mbtndiv" class="mbtndiv" style="display:table-cell;height:100%;vertical-align:middle;width:40px;min-width:40px;"><section style=""></section> </section></section><section id="iddet-8111" class="greybardiv-extnd" style="display:none"><section class="greybar-tab-extnd"><section class="dishimg" style="margin-top:5px;margin-left:5px"><img style="float:left; height:88px" src="http://www.softpointdev.com/images/location_menu_item/d8fbb35b8c3b5e2d1742f2dff790676b-.jpg"></section><section class="dishdesc" style="padding-left:115px;"><section class="dishtitle">PLU#244</section><section class="dishingr"></section></section></section> </section><section class="greybardiv" style="marin-bottom:1%;overflow:hidden"><section class="greybar-tab" style="height:35px;display:table"><section class="ibtndiv" onclick="infoitem('8113'); "><img style="vertical-align:middle" src="images/ibtn-slver.png"></section><section class="dishnamew" style=" width:75%;display:table-cell;height:100%;vertical-align:middle;min-width:100%;padding-left:30px;text-align:left" id="valit-8113" onclick="additem('1053','no','99','1','0','340','8113','40.00' ,'Whiskies classiques','0','First','','','0','no','no','No','','no','0','1','Bar','','','0.00');event.stopPropagation();">Whiskies classiques</section><section class="dishprice222" style="display:table-cell;height:100%;vertical-align:middle;min-width:94px;padding-right:20px;text-align:right" onclick="additem('1053','no','99','1','0','340','8113','40.00' ,'Whiskies classiques','0','First','','','0','no','no','No','','no','0','1','Bar','','','0.00');event.stopPropagation();">Dh 40.00</section><section id="mbtndiv" class="mbtndiv" style="display:table-cell;height:100%;vertical-align:middle;width:40px;min-width:40px;"><section style=""></section> </section></section><section id="iddet-8113" class="greybardiv-extnd" style="display:none"><section class="greybar-tab-extnd"><section class="dishimg" style="margin-top:5px;margin-left:5px"><img style="float:left; height:88px" src="http://www.softpointdev.com/images/location_menu_item/473a83e79097c1de2098b801f4e552fd-.jpg"></section><section class="dishdesc" style="padding-left:115px;"><section class="dishtitle">PLU#245</section><section class="dishingr"></section></section></section> </section><section class="greybardiv" style="marin-bottom:1%;overflow:hidden"><section class="greybar-tab" style="height:35px;display:table"><section class="ibtndiv" onclick="infoitem('8114'); "><img style="vertical-align:middle" src="images/ibtn-slver.png"></section><section class="dishnamew" style=" width:75%;display:table-cell;height:100%;vertical-align:middle;min-width:100%;padding-left:30px;text-align:left" id="valit-8114" onclick="additem('1053','no','99','1','0','340','8114','60.00' ,'Whiskies luxe','0','First','','','0','no','no','No','','no','0','1','Bar','','','0.00');event.stopPropagation();">Whiskies luxe</section><section class="dishprice222" style="display:table-cell;height:100%;vertical-align:middle;min-width:94px;padding-right:20px;text-align:right" onclick="additem('1053','no','99','1','0','340','8114','60.00' ,'Whiskies luxe','0','First','','','0','no','no','No','','no','0','1','Bar','','','0.00');event.stopPropagation();">Dh 60.00</section><section id="mbtndiv" class="mbtndiv" style="display:table-cell;height:100%;vertical-align:middle;width:40px;min-width:40px;"><section style=""></section> </section></section><section id="iddet-8114" class="greybardiv-extnd" style="display:none"><section class="greybar-tab-extnd"><section class="dishimg" style="margin-top:5px;margin-left:5px"><img style="float:left; height:88px" src="http://www.softpointdev.com/images/location_menu_item/7088cf3e3e97e3f6a47087f91f608d5b-.jpg"></section><section class="dishdesc" style="padding-left:115px;"><section class="dishtitle">PLU#246</section><section class="dishingr"></section></section></section> </section><section class="greybardiv" style="marin-bottom:1%;overflow:hidden"><section class="greybar-tab" style="height:35px;display:table"><section class="ibtndiv" onclick="infoitem('8108'); "><img style="vertical-align:middle" src="images/ibtn-slver.png"></section><section class="dishnamew" style=" width:75%;display:table-cell;height:100%;vertical-align:middle;min-width:100%;padding-left:30px;text-align:left" id="valit-8108" onclick="additem('1053','no','99','1','0','340','8108','50.00' ,'Digestifs','0','First','no','no','0','no','no','No','','no','0','1','Bar','','','0.00');event.stopPropagation();">Digestifs</section><section class="dishprice222" style="display:table-cell;height:100%;vertical-align:middle;min-width:94px;padding-right:20px;text-align:right" onclick="additem('1053','no','99','1','0','340','8108','50.00' ,'Digestifs','0','First','no','no','0','no','no','No','','no','0','1','Bar','','','0.00');event.stopPropagation();">Dh 50.00</section><section id="mbtndiv" class="mbtndiv" style="display:table-cell;height:100%;vertical-align:middle;width:40px;min-width:40px;"><section style=""></section> </section></section><section id="iddet-8108" class="greybardiv-extnd" style="display:none"><section class="greybar-tab-extnd"><section class="dishimg" style="margin-top:5px;margin-left:5px"><img style="float:left; height:88px" src="http://www.softpointdev.com/images/location_menu_item/9129d06c66a8e931efa475e6563c12f6-.jpg"></section><section class="dishdesc" style="padding-left:115px;"><section class="dishtitle">PLU#241</section><section class="dishingr"></section></section></section> </section></section></section></section></section></section></div><section id="gui-1054" class="widgettitle_lgtblue" onclick="opengroup('1054')"><section id="bluebartext" class="bluebartext" style="height:35px;min-height:35px;max-height:35px;overflow:hidden;"><section class="bluebartextcell" style="padding-top:7px;height:35px;min-height:35px;max-height:35px;overflow:hidden;text-align:center;">Vins Rouge</section></section></section><div id="adg-1054" class="contgroup" style="display:none;border:"><section class="greybardiv" style="marin-bottom:1%;overflow:hidden"><section class="greybar-tab" style="height:35px;display:table"><section class="ibtndiv" onclick="infoitem('8116'); "><img style="vertical-align:middle" src="images/ibtn-slver.png"></section><section class="dishnamew" style=" width:75%;display:table-cell;height:100%;vertical-align:middle;min-width:100%;padding-left:30px;text-align:left" id="valit-8116" onclick="additem('1054','no','99','1','0','340','8116','300.00' ,'Cabernet Presédent 3/4','0','First','','','0','no','no','No','','no','0','1','Bar','','','0.00');event.stopPropagation();">Cabernet Presédent 3/4</section><section class="dishprice222" style="display:table-cell;height:100%;vertical-align:middle;min-width:94px;padding-right:20px;text-align:right" onclick="additem('1054','no','99','1','0','340','8116','300.00' ,'Cabernet Presédent 3/4','0','First','','','0','no','no','No','','no','0','1','Bar','','','0.00');event.stopPropagation();">Dh 300.00</section><section id="mbtndiv" class="mbtndiv" style="display:table-cell;height:100%;vertical-align:middle;width:40px;min-width:40px;"><section style=""></section> </section></section><section id="iddet-8116" class="greybardiv-extnd" style="display:none"><section class="greybar-tab-extnd"><section class="dishimg" style="margin-top:5px;margin-left:5px"><img style="float:left; height:88px" src="http://www.softpointdev.com/images/location_menu_item/27c7c89f2c5bdc4ccb533a4de4d9b751-.jpg"></section><section class="dishdesc" style="padding-left:115px;"><section class="dishtitle">PLU#248</section><section class="dishingr">Vins Rouge</section></section></section> </section><section class="greybardiv" style="marin-bottom:1%;overflow:hidden"><section class="greybar-tab" style="height:35px;display:table"><section class="ibtndiv" onclick="infoitem('8117'); "><img style="vertical-align:middle" src="images/ibtn-slver.png"></section><section class="dishnamew" style=" width:75%;display:table-cell;height:100%;vertical-align:middle;min-width:100%;padding-left:30px;text-align:left" id="valit-8117" onclick="additem('1054','no','99','1','0','340','8117','60.00' ,'Cabernet Presédent 1/2','0','First','','','0','no','no','No','','no','0','1','Bar','','','0.00');event.stopPropagation();">Cabernet Presédent 1/2</section><section class="dishprice222" style="display:table-cell;height:100%;vertical-align:middle;min-width:94px;padding-right:20px;text-align:right" onclick="additem('1054','no','99','1','0','340','8117','60.00' ,'Cabernet Presédent 1/2','0','First','','','0','no','no','No','','no','0','1','Bar','','','0.00');event.stopPropagation();">Dh 60.00</section><section id="mbtndiv" class="mbtndiv" style="display:table-cell;height:100%;vertical-align:middle;width:40px;min-width:40px;"><section style=""></section> </section></section><section id="iddet-8117" class="greybardiv-extnd" style="display:none"><section class="greybar-tab-extnd"><section class="dishimg" style="margin-top:5px;margin-left:5px"><img style="float:left; height:88px" src="http://www.softpointdev.com/images/location_menu_item/4156f500a8056c3b6ad980c45f11ccd5-.jpg"></section><section class="dishdesc" style="padding-left:115px;"><section class="dishtitle">PLU#249</section><section class="dishingr">Vins Rouge</section></section></section> </section><section class="greybardiv" style="marin-bottom:1%;overflow:hidden"><section class="greybar-tab" style="height:35px;display:table"><section class="ibtndiv" onclick="infoitem('8118'); "><img style="vertical-align:middle" src="images/ibtn-slver.png"></section><section class="dishnamew" style=" width:75%;display:table-cell;height:100%;vertical-align:middle;min-width:100%;padding-left:30px;text-align:left" id="valit-8118" onclick="additem('1054','no','99','1','0','340','8118','250.00' ,'Ksar 3/4','0','First','','','0','no','no','No','','no','0','1','Bar','','','0.00');event.stopPropagation();">Ksar 3/4</section><section class="dishprice222" style="display:table-cell;height:100%;vertical-align:middle;min-width:94px;padding-right:20px;text-align:right" onclick="additem('1054','no','99','1','0','340','8118','250.00' ,'Ksar 3/4','0','First','','','0','no','no','No','','no','0','1','Bar','','','0.00');event.stopPropagation();">Dh 250.00</section><section id="mbtndiv" class="mbtndiv" style="display:table-cell;height:100%;vertical-align:middle;width:40px;min-width:40px;"><section style=""></section> </section></section><section id="iddet-8118" class="greybardiv-extnd" style="display:none"><section class="greybar-tab-extnd"><section class="dishimg" style="margin-top:5px;margin-left:5px"><img style="float:left; height:88px" src="http://www.softpointdev.com/images/location_menu_item/c2bead669c4250ecf6cbbdf9e0baf23e-.jpg"></section><section class="dishdesc" style="padding-left:115px;"><section class="dishtitle">PLU#250</section><section class="dishingr"></section></section></section> </section><section class="greybardiv" style="marin-bottom:1%;overflow:hidden"><section class="greybar-tab" style="height:35px;display:table"><section class="ibtndiv" onclick="infoitem('8119'); "><img style="vertical-align:middle" src="images/ibtn-slver.png"></section><section class="dishnamew" style=" width:75%;display:table-cell;height:100%;vertical-align:middle;min-width:100%;padding-left:30px;text-align:left" id="valit-8119" onclick="additem('1054','no','99','1','0','340','8119','60.00' ,'Ksar 1/2','0','First','','','0','no','no','No','','no','0','1','Bar','','','0.00');event.stopPropagation();">Ksar 1/2</section><section class="dishprice222" style="display:table-cell;height:100%;vertical-align:middle;min-width:94px;padding-right:20px;text-align:right" onclick="additem('1054','no','99','1','0','340','8119','60.00' ,'Ksar 1/2','0','First','','','0','no','no','No','','no','0','1','Bar','','','0.00');event.stopPropagation();">Dh 60.00</section><section id="mbtndiv" class="mbtndiv" style="display:table-cell;height:100%;vertical-align:middle;width:40px;min-width:40px;"><section style=""></section> </section></section><section id="iddet-8119" class="greybardiv-extnd" style="display:none"><section class="greybar-tab-extnd"><section class="dishimg" style="margin-top:5px;margin-left:5px"><img style="float:left; height:88px" src="http://www.softpointdev.com/images/location_menu_item/ebfaccccdb6574aa2512492f38b685e0-.jpg"></section><section class="dishdesc" style="padding-left:115px;"><section class="dishtitle">PLU#251</section><section class="dishingr"></section></section></section> </section><section class="greybardiv" style="marin-bottom:1%;overflow:hidden"><section class="greybar-tab" style="height:35px;display:table"><section class="ibtndiv" onclick="infoitem('8120'); "><img style="vertical-align:middle" src="images/ibtn-slver.png"></section><section class="dishnamew" style=" width:75%;display:table-cell;height:100%;vertical-align:middle;min-width:100%;padding-left:30px;text-align:left" id="valit-8120" onclick="additem('1054','no','99','1','0','340','8120','250.00' ,'Cabernet médaillon 3/4','0','First','','','0','no','no','No','','no','0','1','Bar','','','0.00');event.stopPropagation();">Cabernet médaillon 3/4</section><section class="dishprice222" style="display:table-cell;height:100%;vertical-align:middle;min-width:94px;padding-right:20px;text-align:right" onclick="additem('1054','no','99','1','0','340','8120','250.00' ,'Cabernet médaillon 3/4','0','First','','','0','no','no','No','','no','0','1','Bar','','','0.00');event.stopPropagation();">Dh 250.00</section><section id="mbtndiv" class="mbtndiv" style="display:table-cell;height:100%;vertical-align:middle;width:40px;min-width:40px;"><section style=""></section> </section></section><section id="iddet-8120" class="greybardiv-extnd" style="display:none"><section class="greybar-tab-extnd"><section class="dishimg" style="margin-top:5px;margin-left:5px"><img style="float:left; height:88px" src="http://www.softpointdev.com/images/location_menu_item/97e5e3766be0abdedb5939cad1c8db62-.jpg"></section><section class="dishdesc" style="padding-left:115px;"><section class="dishtitle">PLU#252</section><section class="dishingr"></section></section></section> </section><section class="greybardiv" style="marin-bottom:1%;overflow:hidden"><section class="greybar-tab" style="height:35px;display:table"><section class="ibtndiv" onclick="infoitem('8121'); "><img style="vertical-align:middle" src="images/ibtn-slver.png"></section><section class="dishnamew" style=" width:75%;display:table-cell;height:100%;vertical-align:middle;min-width:100%;padding-left:30px;text-align:left" id="valit-8121" onclick="additem('1054','no','99','1','0','340','8121','60.00' ,'Cabernet médaillon 1/2','0','First','','','0','no','no','No','','no','0','1','Bar','','','0.00');event.stopPropagation();">Cabernet médaillon 1/2</section><section class="dishprice222" style="display:table-cell;height:100%;vertical-align:middle;min-width:94px;padding-right:20px;text-align:right" onclick="additem('1054','no','99','1','0','340','8121','60.00' ,'Cabernet médaillon 1/2','0','First','','','0','no','no','No','','no','0','1','Bar','','','0.00');event.stopPropagation();">Dh 60.00</section><section id="mbtndiv" class="mbtndiv" style="display:table-cell;height:100%;vertical-align:middle;width:40px;min-width:40px;"><section style=""></section> </section></section><section id="iddet-8121" class="greybardiv-extnd" style="display:none"><section class="greybar-tab-extnd"><section class="dishimg" style="margin-top:5px;margin-left:5px"><img style="float:left; height:88px" src="http://www.softpointdev.com/images/location_menu_item/855b57107b984a87fded4b328f9b41fd-.jpg"></section><section class="dishdesc" style="padding-left:115px;"><section class="dishtitle">PLU#253</section><section class="dishingr"></section></section></section> </section><section class="greybardiv" style="marin-bottom:1%;overflow:hidden"><section class="greybar-tab" style="height:35px;display:table"><section class="ibtndiv" onclick="infoitem('8122'); "><img style="vertical-align:middle" src="images/ibtn-slver.png"></section><section class="dishnamew" style=" width:75%;display:table-cell;height:100%;vertical-align:middle;min-width:100%;padding-left:30px;text-align:left" id="valit-8122" onclick="additem('1054','no','99','1','0','340','8122','250.00' ,'Beauvallon 3/4','0','First','','','0','no','no','No','','no','0','1','Bar','','','0.00');event.stopPropagation();">Beauvallon 3/4</section><section class="dishprice222" style="display:table-cell;height:100%;vertical-align:middle;min-width:94px;padding-right:20px;text-align:right" onclick="additem('1054','no','99','1','0','340','8122','250.00' ,'Beauvallon 3/4','0','First','','','0','no','no','No','','no','0','1','Bar','','','0.00');event.stopPropagation();">Dh 250.00</section><section id="mbtndiv" class="mbtndiv" style="display:table-cell;height:100%;vertical-align:middle;width:40px;min-width:40px;"><section style=""></section> </section></section><section id="iddet-8122" class="greybardiv-extnd" style="display:none"><section class="greybar-tab-extnd"><section class="dishimg" style="margin-top:5px;margin-left:5px"><img style="float:left; height:88px" src="http://www.softpointdev.com/images/location_menu_item/7696ac766312b2e13f1e2ef6061ab517-.jpg"></section><section class="dishdesc" style="padding-left:115px;"><section class="dishtitle">PLU#254</section><section class="dishingr"></section></section></section> </section><section class="greybardiv" style="marin-bottom:1%;overflow:hidden"><section class="greybar-tab" style="height:35px;display:table"><section class="ibtndiv" onclick="infoitem('8123'); "><img style="vertical-align:middle" src="images/ibtn-slver.png"></section><section class="dishnamew" style=" width:75%;display:table-cell;height:100%;vertical-align:middle;min-width:100%;padding-left:30px;text-align:left" id="valit-8123" onclick="additem('1054','no','99','1','0','340','8123','60.00' ,'Beauvallon 1/2','0','First','','','0','no','no','No','','no','0','1','Bar','','','0.00');event.stopPropagation();">Beauvallon 1/2</section><section class="dishprice222" style="display:table-cell;height:100%;vertical-align:middle;min-width:94px;padding-right:20px;text-align:right" onclick="additem('1054','no','99','1','0','340','8123','60.00' ,'Beauvallon 1/2','0','First','','','0','no','no','No','','no','0','1','Bar','','','0.00');event.stopPropagation();">Dh 60.00</section><section id="mbtndiv" class="mbtndiv" style="display:table-cell;height:100%;vertical-align:middle;width:40px;min-width:40px;"><section style=""></section> </section></section><section id="iddet-8123" class="greybardiv-extnd" style="display:none"><section class="greybar-tab-extnd"><section class="dishimg" style="margin-top:5px;margin-left:5px"><img style="float:left; height:88px" src="http://www.softpointdev.com/images/location_menu_item/6f5ba94aa1a01739a699f796de17645d-.jpg"></section><section class="dishdesc" style="padding-left:115px;"><section class="dishtitle">PLU#255</section><section class="dishingr"></section></section></section> </section><section class="greybardiv" style="marin-bottom:1%;overflow:hidden"><section class="greybar-tab" style="height:35px;display:table"><section class="ibtndiv" onclick="infoitem('8124'); "><img style="vertical-align:middle" src="images/ibtn-slver.png"></section><section class="dishnamew" style=" width:75%;display:table-cell;height:100%;vertical-align:middle;min-width:100%;padding-left:30px;text-align:left" id="valit-8124" onclick="additem('1054','no','99','1','0','340','8124','200.00' ,'Vin italien 3/4','0','First','','','0','no','no','No','','no','0','1','Bar','','','0.00');event.stopPropagation();">Vin italien 3/4</section><section class="dishprice222" style="display:table-cell;height:100%;vertical-align:middle;min-width:94px;padding-right:20px;text-align:right" onclick="additem('1054','no','99','1','0','340','8124','200.00' ,'Vin italien 3/4','0','First','','','0','no','no','No','','no','0','1','Bar','','','0.00');event.stopPropagation();">Dh 200.00</section><section id="mbtndiv" class="mbtndiv" style="display:table-cell;height:100%;vertical-align:middle;width:40px;min-width:40px;"><section style=""></section> </section></section><section id="iddet-8124" class="greybardiv-extnd" style="display:none"><section class="greybar-tab-extnd"><section class="dishimg" style="margin-top:5px;margin-left:5px"><img style="float:left; height:88px" src="http://www.softpointdev.com/images/location_menu_item/b06bfa1d1c63ecf500a92bfb2296c7ef-.jpg"></section><section class="dishdesc" style="padding-left:115px;"><section class="dishtitle">PLU#256</section><section class="dishingr"></section></section></section> </section><section class="greybardiv" style="marin-bottom:1%;overflow:hidden"><section class="greybar-tab" style="height:35px;display:table"><section class="ibtndiv" onclick="infoitem('8125'); "><img style="vertical-align:middle" src="images/ibtn-slver.png"></section><section class="dishnamew" style=" width:75%;display:table-cell;height:100%;vertical-align:middle;min-width:100%;padding-left:30px;text-align:left" id="valit-8125" onclick="additem('1054','no','99','1','0','340','8125','60.00' ,'Vin italien 1/2','0','First','','','0','no','no','No','','no','0','1','Bar','','','0.00');event.stopPropagation();">Vin italien 1/2</section><section class="dishprice222" style="display:table-cell;height:100%;vertical-align:middle;min-width:94px;padding-right:20px;text-align:right" onclick="additem('1054','no','99','1','0','340','8125','60.00' ,'Vin italien 1/2','0','First','','','0','no','no','No','','no','0','1','Bar','','','0.00');event.stopPropagation();">Dh 60.00</section><section id="mbtndiv" class="mbtndiv" style="display:table-cell;height:100%;vertical-align:middle;width:40px;min-width:40px;"><section style=""></section> </section></section><section id="iddet-8125" class="greybardiv-extnd" style="display:none"><section class="greybar-tab-extnd"><section class="dishimg" style="margin-top:5px;margin-left:5px"><img style="float:left; height:88px" src="http://www.softpointdev.com/images/location_menu_item/8786f781bae1057c2c2d496eb99e5139-.jpg"></section><section class="dishdesc" style="padding-left:115px;"><section class="dishtitle">PLU#257</section><section class="dishingr"></section></section></section> </section></section></section></section></section></section></section></section></section></section></section></div><section id="gui-1055" class="widgettitle_lgtblue" onclick="opengroup('1055')"><section id="bluebartext" class="bluebartext" style="height:35px;min-height:35px;max-height:35px;overflow:hidden;"><section class="bluebartextcell" style="padding-top:7px;height:35px;min-height:35px;max-height:35px;overflow:hidden;text-align:center;">Vins Rosés</section></section></section><div id="adg-1055" class="contgroup" style="display:none;border:"><section class="greybardiv" style="marin-bottom:1%;overflow:hidden"><section class="greybar-tab" style="height:35px;display:table"><section class="ibtndiv" onclick="infoitem('8126'); "><img style="vertical-align:middle" src="images/ibtn-slver.png"></section><section class="dishnamew" style=" width:75%;display:table-cell;height:100%;vertical-align:middle;min-width:100%;padding-left:30px;text-align:left" id="valit-8126" onclick="additem('1055','no','99','1','0','340','8126','300.00' ,'Cabernet Président 3/4','0','First','','','0','no','no','No','','no','0','1','Bar','','','0.00');event.stopPropagation();">Cabernet Président 3/4</section><section class="dishprice222" style="display:table-cell;height:100%;vertical-align:middle;min-width:94px;padding-right:20px;text-align:right" onclick="additem('1055','no','99','1','0','340','8126','300.00' ,'Cabernet Président 3/4','0','First','','','0','no','no','No','','no','0','1','Bar','','','0.00');event.stopPropagation();">Dh 300.00</section><section id="mbtndiv" class="mbtndiv" style="display:table-cell;height:100%;vertical-align:middle;width:40px;min-width:40px;"><section style=""></section> </section></section><section id="iddet-8126" class="greybardiv-extnd" style="display:none"><section class="greybar-tab-extnd"><section class="dishimg" style="margin-top:5px;margin-left:5px"><img style="float:left; height:88px" src="http://www.softpointdev.com/images/location_menu_item/c6a4771b6d38a7d773a2662b5f7d9ca9-.jpg"></section><section class="dishdesc" style="padding-left:115px;"><section class="dishtitle">PLU#258</section><section class="dishingr">Vins Rosés</section></section></section> </section><section class="greybardiv" style="marin-bottom:1%;overflow:hidden"><section class="greybar-tab" style="height:35px;display:table"><section class="ibtndiv" onclick="infoitem('8127'); "><img style="vertical-align:middle" src="images/ibtn-slver.png"></section><section class="dishnamew" style=" width:75%;display:table-cell;height:100%;vertical-align:middle;min-width:100%;padding-left:30px;text-align:left" id="valit-8127" onclick="additem('1055','no','99','1','0','340','8127','60.00' ,'Cabernet Président 1/2','0','First','','','0','no','no','No','','no','0','1','Bar','','','0.00');event.stopPropagation();">Cabernet Président 1/2</section><section class="dishprice222" style="display:table-cell;height:100%;vertical-align:middle;min-width:94px;padding-right:20px;text-align:right" onclick="additem('1055','no','99','1','0','340','8127','60.00' ,'Cabernet Président 1/2','0','First','','','0','no','no','No','','no','0','1','Bar','','','0.00');event.stopPropagation();">Dh 60.00</section><section id="mbtndiv" class="mbtndiv" style="display:table-cell;height:100%;vertical-align:middle;width:40px;min-width:40px;"><section style=""></section> </section></section><section id="iddet-8127" class="greybardiv-extnd" style="display:none"><section class="greybar-tab-extnd"><section class="dishimg" style="margin-top:5px;margin-left:5px"><img style="float:left; height:88px" src="http://www.softpointdev.com/images/location_menu_item/da0191f77c7d7de960269a760048c290-.jpg"></section><section class="dishdesc" style="padding-left:115px;"><section class="dishtitle">PLU#259</section><section class="dishingr">Vins Rosés</section></section></section> </section><section class="greybardiv" style="marin-bottom:1%;overflow:hidden"><section class="greybar-tab" style="height:35px;display:table"><section class="ibtndiv" onclick="infoitem('8130'); "><img style="vertical-align:middle" src="images/ibtn-slver.png"></section><section class="dishnamew" style=" width:75%;display:table-cell;height:100%;vertical-align:middle;min-width:100%;padding-left:30px;text-align:left" id="valit-8130" onclick="additem('1055','no','99','1','0','340','8130','100.00' ,'Guerrouane 3/4','0','First','','','0','no','no','No','','no','0','1','Bar','','','0.00');event.stopPropagation();">Guerrouane 3/4</section><section class="dishprice222" style="display:table-cell;height:100%;vertical-align:middle;min-width:94px;padding-right:20px;text-align:right" onclick="additem('1055','no','99','1','0','340','8130','100.00' ,'Guerrouane 3/4','0','First','','','0','no','no','No','','no','0','1','Bar','','','0.00');event.stopPropagation();">Dh 100.00</section><section id="mbtndiv" class="mbtndiv" style="display:table-cell;height:100%;vertical-align:middle;width:40px;min-width:40px;"><section style=""></section> </section></section><section id="iddet-8130" class="greybardiv-extnd" style="display:none"><section class="greybar-tab-extnd"><section class="dishimg" style="margin-top:5px;margin-left:5px"><img style="float:left; height:88px" src="http://www.softpointdev.com/images/location_menu_item/12b115541a0505a83ccd4cb0066d512a-.jpg"></section><section class="dishdesc" style="padding-left:115px;"><section class="dishtitle">PLU#262</section><section class="dishingr"></section></section></section> </section><section class="greybardiv" style="marin-bottom:1%;overflow:hidden"><section class="greybar-tab" style="height:35px;display:table"><section class="ibtndiv" onclick="infoitem('8131'); "><img style="vertical-align:middle" src="images/ibtn-slver.png"></section><section class="dishnamew" style=" width:75%;display:table-cell;height:100%;vertical-align:middle;min-width:100%;padding-left:30px;text-align:left" id="valit-8131" onclick="additem('1055','no','99','1','0','340','8131','60.00' ,'Guerrouane 1/2','0','First','','','0','no','no','No','','no','0','1','Bar','','','0.00');event.stopPropagation();">Guerrouane 1/2</section><section class="dishprice222" style="display:table-cell;height:100%;vertical-align:middle;min-width:94px;padding-right:20px;text-align:right" onclick="additem('1055','no','99','1','0','340','8131','60.00' ,'Guerrouane 1/2','0','First','','','0','no','no','No','','no','0','1','Bar','','','0.00');event.stopPropagation();">Dh 60.00</section><section id="mbtndiv" class="mbtndiv" style="display:table-cell;height:100%;vertical-align:middle;width:40px;min-width:40px;"><section style=""></section> </section></section><section id="iddet-8131" class="greybardiv-extnd" style="display:none"><section class="greybar-tab-extnd"><section class="dishimg" style="margin-top:5px;margin-left:5px"><img style="float:left; height:88px" src="http://www.softpointdev.com/images/location_menu_item/9378c3a864298bb2295ae0823dce5782-.jpg"></section><section class="dishdesc" style="padding-left:115px;"><section class="dishtitle">PLU#263</section><section class="dishingr"></section></section></section> </section><section class="greybardiv" style="marin-bottom:1%;overflow:hidden"><section class="greybar-tab" style="height:35px;display:table"><section class="ibtndiv" onclick="infoitem('8132'); "><img style="vertical-align:middle" src="images/ibtn-slver.png"></section><section class="dishnamew" style=" width:75%;display:table-cell;height:100%;vertical-align:middle;min-width:100%;padding-left:30px;text-align:left" id="valit-8132" onclick="additem('1055','no','99','1','0','340','8132','100.00' ,'Gris de Guerrouane 3/4','0','First','','','0','no','no','No','','no','0','1','Bar','','','0.00');event.stopPropagation();">Gris de Guerrouane 3/4</section><section class="dishprice222" style="display:table-cell;height:100%;vertical-align:middle;min-width:94px;padding-right:20px;text-align:right" onclick="additem('1055','no','99','1','0','340','8132','100.00' ,'Gris de Guerrouane 3/4','0','First','','','0','no','no','No','','no','0','1','Bar','','','0.00');event.stopPropagation();">Dh 100.00</section><section id="mbtndiv" class="mbtndiv" style="display:table-cell;height:100%;vertical-align:middle;width:40px;min-width:40px;"><section style=""></section> </section></section><section id="iddet-8132" class="greybardiv-extnd" style="display:none"><section class="greybar-tab-extnd"><section class="dishimg" style="margin-top:5px;margin-left:5px"><img style="float:left; height:88px" src="http://www.softpointdev.com/images/location_menu_item/ad1fdb42f3a3b9d2e94cb8be9d5fcc7c-.jpg"></section><section class="dishdesc" style="padding-left:115px;"><section class="dishtitle">PLU#264</section><section class="dishingr"></section></section></section> </section><section class="greybardiv" style="marin-bottom:1%;overflow:hidden"><section class="greybar-tab" style="height:35px;display:table"><section class="ibtndiv" onclick="infoitem('8133'); "><img style="vertical-align:middle" src="images/ibtn-slver.png"></section><section class="dishnamew" style=" width:75%;display:table-cell;height:100%;vertical-align:middle;min-width:100%;padding-left:30px;text-align:left" id="valit-8133" onclick="additem('1055','no','99','1','0','340','8133','60.00' ,'Gris de Guerrouane 1/2','0','First','','','0','no','no','No','','no','0','1','Bar','','','0.00');event.stopPropagation();">Gris de Guerrouane 1/2</section><section class="dishprice222" style="display:table-cell;height:100%;vertical-align:middle;min-width:94px;padding-right:20px;text-align:right" onclick="additem('1055','no','99','1','0','340','8133','60.00' ,'Gris de Guerrouane 1/2','0','First','','','0','no','no','No','','no','0','1','Bar','','','0.00');event.stopPropagation();">Dh 60.00</section><section id="mbtndiv" class="mbtndiv" style="display:table-cell;height:100%;vertical-align:middle;width:40px;min-width:40px;"><section style=""></section> </section></section><section id="iddet-8133" class="greybardiv-extnd" style="display:none"><section class="greybar-tab-extnd"><section class="dishimg" style="margin-top:5px;margin-left:5px"><img style="float:left; height:88px" src="http://www.softpointdev.com/images/location_menu_item/ee658fab4665f61d7716ae64838f1e3b-.jpg"></section><section class="dishdesc" style="padding-left:115px;"><section class="dishtitle">PLU#265</section><section class="dishingr"></section></section></section> </section></section></section></section></section></section></section></div><section id="gui-1056" class="widgettitle_lgtblue" onclick="opengroup('1056')"><section id="bluebartext" class="bluebartext" style="height:35px;min-height:35px;max-height:35px;overflow:hidden;"><section class="bluebartextcell" style="padding-top:7px;height:35px;min-height:35px;max-height:35px;overflow:hidden;text-align:center;">Vins Blancs</section></section></section><div id="adg-1056" class="contgroup" style="display:none;border:"><section class="greybardiv" style="marin-bottom:1%;overflow:hidden"><section class="greybar-tab" style="height:35px;display:table"><section class="ibtndiv" onclick="infoitem('8134'); "><img style="vertical-align:middle" src="images/ibtn-slver.png"></section><section class="dishnamew" style=" width:75%;display:table-cell;height:100%;vertical-align:middle;min-width:100%;padding-left:30px;text-align:left" id="valit-8134" onclick="additem('1056','no','99','1','0','340','8134','100.00' ,'Sémillant 3/4','0','First','','','0','no','no','No','','no','0','1','Bar','','','0.00');event.stopPropagation();">Sémillant 3/4</section><section class="dishprice222" style="display:table-cell;height:100%;vertical-align:middle;min-width:94px;padding-right:20px;text-align:right" onclick="additem('1056','no','99','1','0','340','8134','100.00' ,'Sémillant 3/4','0','First','','','0','no','no','No','','no','0','1','Bar','','','0.00');event.stopPropagation();">Dh 100.00</section><section id="mbtndiv" class="mbtndiv" style="display:table-cell;height:100%;vertical-align:middle;width:40px;min-width:40px;"><section style=""></section> </section></section><section id="iddet-8134" class="greybardiv-extnd" style="display:none"><section class="greybar-tab-extnd"><section class="dishimg" style="margin-top:5px;margin-left:5px"><img style="float:left; height:88px" src="http://www.softpointdev.com/images/location_menu_item/ea93322f5466ef617f8c859037cd434c-.jpg"></section><section class="dishdesc" style="padding-left:115px;"><section class="dishtitle">PLU#266</section><section class="dishingr"></section></section></section> </section><section class="greybardiv" style="marin-bottom:1%;overflow:hidden"><section class="greybar-tab" style="height:35px;display:table"><section class="ibtndiv" onclick="infoitem('8135'); "><img style="vertical-align:middle" src="images/ibtn-slver.png"></section><section class="dishnamew" style=" width:75%;display:table-cell;height:100%;vertical-align:middle;min-width:100%;padding-left:30px;text-align:left" id="valit-8135" onclick="additem('1056','no','99','1','0','340','8135','60.00' ,'Sémillant 1/2','0','First','','','0','no','no','No','','no','0','1','Bar','','','0.00');event.stopPropagation();">Sémillant 1/2</section><section class="dishprice222" style="display:table-cell;height:100%;vertical-align:middle;min-width:94px;padding-right:20px;text-align:right" onclick="additem('1056','no','99','1','0','340','8135','60.00' ,'Sémillant 1/2','0','First','','','0','no','no','No','','no','0','1','Bar','','','0.00');event.stopPropagation();">Dh 60.00</section><section id="mbtndiv" class="mbtndiv" style="display:table-cell;height:100%;vertical-align:middle;width:40px;min-width:40px;"><section style=""></section> </section></section><section id="iddet-8135" class="greybardiv-extnd" style="display:none"><section class="greybar-tab-extnd"><section class="dishimg" style="margin-top:5px;margin-left:5px"><img style="float:left; height:88px" src="http://www.softpointdev.com/images/location_menu_item/3ffe9942174b1d12c2d269f693c31ab6-.jpg"></section><section class="dishdesc" style="padding-left:115px;"><section class="dishtitle">PLU#267</section><section class="dishingr"></section></section></section> </section><section class="greybardiv" style="marin-bottom:1%;overflow:hidden"><section class="greybar-tab" style="height:35px;display:table"><section class="ibtndiv" onclick="infoitem('8136'); "><img style="vertical-align:middle" src="images/ibtn-slver.png"></section><section class="dishnamew" style=" width:75%;display:table-cell;height:100%;vertical-align:middle;min-width:100%;padding-left:30px;text-align:left" id="valit-8136" onclick="additem('1056','no','99','1','0','340','8136','100.00' ,'Spéciale coquillage 3/4','0','First','','','0','no','no','No','','no','0','1','Bar','','','0.00');event.stopPropagation();">Spéciale coquillage 3/4</section><section class="dishprice222" style="display:table-cell;height:100%;vertical-align:middle;min-width:94px;padding-right:20px;text-align:right" onclick="additem('1056','no','99','1','0','340','8136','100.00' ,'Spéciale coquillage 3/4','0','First','','','0','no','no','No','','no','0','1','Bar','','','0.00');event.stopPropagation();">Dh 100.00</section><section id="mbtndiv" class="mbtndiv" style="display:table-cell;height:100%;vertical-align:middle;width:40px;min-width:40px;"><section style=""></section> </section></section><section id="iddet-8136" class="greybardiv-extnd" style="display:none"><section class="greybar-tab-extnd"><section class="dishimg" style="margin-top:5px;margin-left:5px"><img style="float:left; height:88px" src="http://www.softpointdev.com/images/location_menu_item/cd3b076b26ef3e6cc1e6f1ed2bd1ac4d-.jpg"></section><section class="dishdesc" style="padding-left:115px;"><section class="dishtitle">PLU#268</section><section class="dishingr"></section></section></section> </section><section class="greybardiv" style="marin-bottom:1%;overflow:hidden"><section class="greybar-tab" style="height:35px;display:table"><section class="ibtndiv" onclick="infoitem('8137'); "><img style="vertical-align:middle" src="images/ibtn-slver.png"></section><section class="dishnamew" style=" width:75%;display:table-cell;height:100%;vertical-align:middle;min-width:100%;padding-left:30px;text-align:left" id="valit-8137" onclick="additem('1056','no','99','1','0','340','8137','60.00' ,'Spéciale coquillage','0','First','','','0','no','no','No','','no','0','1','Bar','','','0.00');event.stopPropagation();">Spéciale coquillage</section><section class="dishprice222" style="display:table-cell;height:100%;vertical-align:middle;min-width:94px;padding-right:20px;text-align:right" onclick="additem('1056','no','99','1','0','340','8137','60.00' ,'Spéciale coquillage','0','First','','','0','no','no','No','','no','0','1','Bar','','','0.00');event.stopPropagation();">Dh 60.00</section><section id="mbtndiv" class="mbtndiv" style="display:table-cell;height:100%;vertical-align:middle;width:40px;min-width:40px;"><section style=""></section> </section></section><section id="iddet-8137" class="greybardiv-extnd" style="display:none"><section class="greybar-tab-extnd"><section class="dishimg" style="margin-top:5px;margin-left:5px"><img style="float:left; height:88px" src="http://www.softpointdev.com/images/location_menu_item/e4fb714e069e9c73b711ad679fe727c2-.jpg"></section><section class="dishdesc" style="padding-left:115px;"><section class="dishtitle">PLU#269</section><section class="dishingr"></section></section></section> </section></section></section></section></section></div><section id="gui-1058" class="widgettitle_lgtblue" onclick="opengroup('1058')"><section id="bluebartext" class="bluebartext" style="height:35px;min-height:35px;max-height:35px;overflow:hidden;"><section class="bluebartextcell" style="padding-top:7px;height:35px;min-height:35px;max-height:35px;overflow:hidden;text-align:center;">Eau minérales et sodas</section></section></section><div id="adg-1058" class="contgroup" style="display:none;border:"><section class="greybardiv" style="marin-bottom:1%;overflow:hidden"><section class="greybar-tab" style="height:35px;display:table"><section class="ibtndiv" onclick="infoitem('7630'); "><img style="vertical-align:middle" src="images/ibtn-slver.png"></section><section class="dishnamew" style=" width:75%;display:table-cell;height:100%;vertical-align:middle;min-width:100%;padding-left:30px;text-align:left" id="valit-7630" onclick="additem('1058','no','99','1','0','340','7630','25.00' ,'Sidi Ali  1.5L','0','First','no','no','0','no','no','No','','no','0','1','Bar','','','0.00');event.stopPropagation();">Sidi Ali  1.5L</section><section class="dishprice222" style="display:table-cell;height:100%;vertical-align:middle;min-width:94px;padding-right:20px;text-align:right" onclick="additem('1058','no','99','1','0','340','7630','25.00' ,'Sidi Ali  1.5L','0','First','no','no','0','no','no','No','','no','0','1','Bar','','','0.00');event.stopPropagation();">Dh 25.00</section><section id="mbtndiv" class="mbtndiv" style="display:table-cell;height:100%;vertical-align:middle;width:40px;min-width:40px;"><section style=""></section> </section></section><section id="iddet-7630" class="greybardiv-extnd" style="display:none"><section class="greybar-tab-extnd"><section class="dishimg" style="margin-top:5px;margin-left:5px"><img style="float:left; height:88px" src="http://www.softpointdev.com/images/location_menu_item/9c4dc20b83fec4663d019f39adf51e92-.jpg"></section><section class="dishdesc" style="padding-left:115px;"><section class="dishtitle">PLU#137</section><section class="dishingr"></section></section></section> </section><section class="greybardiv" style="marin-bottom:1%;overflow:hidden"><section class="greybar-tab" style="height:35px;display:table"><section class="ibtndiv" onclick="infoitem('7629'); "><img style="vertical-align:middle" src="images/ibtn-slver.png"></section><section class="dishnamew" style=" width:75%;display:table-cell;height:100%;vertical-align:middle;min-width:100%;padding-left:30px;text-align:left" id="valit-7629" onclick="additem('1058','no','99','1','0','340','7629','20.00' ,'Sidi Ali 1/2L','0','First','no','no','0','no','no','No','','no','0','1','Bar','','','0.00');event.stopPropagation();">Sidi Ali 1/2L</section><section class="dishprice222" style="display:table-cell;height:100%;vertical-align:middle;min-width:94px;padding-right:20px;text-align:right" onclick="additem('1058','no','99','1','0','340','7629','20.00' ,'Sidi Ali 1/2L','0','First','no','no','0','no','no','No','','no','0','1','Bar','','','0.00');event.stopPropagation();">Dh 20.00</section><section id="mbtndiv" class="mbtndiv" style="display:table-cell;height:100%;vertical-align:middle;width:40px;min-width:40px;"><section style=""></section> </section></section><section id="iddet-7629" class="greybardiv-extnd" style="display:none"><section class="greybar-tab-extnd"><section class="dishimg" style="margin-top:5px;margin-left:5px"><img style="float:left; height:88px" src="http://www.softpointdev.com/images/location_menu_item/033bf6aba635c4e5610bee6d6633ff70-.jpg"></section><section class="dishdesc" style="padding-left:115px;"><section class="dishtitle">PLU#136</section><section class="dishingr"></section></section></section> </section><section class="greybardiv" style="marin-bottom:1%;overflow:hidden"><section class="greybar-tab" style="height:35px;display:table"><section class="ibtndiv" onclick="infoitem('8105'); "><img style="vertical-align:middle" src="images/ibtn-slver.png"></section><section class="dishnamew" style=" width:75%;display:table-cell;height:100%;vertical-align:middle;min-width:100%;padding-left:30px;text-align:left" id="valit-8105" onclick="additem('1058','no','99','1','0','340','8105','15.00' ,'Sidi Harazem 1.5 l','0','First','no','no','0','no','no','No','','no','0','1','Bar','','','0.00');event.stopPropagation();">Sidi Harazem 1.5 l</section><section class="dishprice222" style="display:table-cell;height:100%;vertical-align:middle;min-width:94px;padding-right:20px;text-align:right" onclick="additem('1058','no','99','1','0','340','8105','15.00' ,'Sidi Harazem 1.5 l','0','First','no','no','0','no','no','No','','no','0','1','Bar','','','0.00');event.stopPropagation();">Dh 25.00</section><section id="mbtndiv" class="mbtndiv" style="display:table-cell;height:100%;vertical-align:middle;width:40px;min-width:40px;"><section style=""></section> </section></section><section id="iddet-8105" class="greybardiv-extnd" style="display:none"><section class="greybar-tab-extnd"><section class="dishimg" style="margin-top:5px;margin-left:5px"><img style="float:left; height:88px" src="http://www.softpointdev.com/images/location_menu_item/643ee9ab34b3fd140b5f083a42105ce5-.jpg"></section><section class="dishdesc" style="padding-left:115px;"><section class="dishtitle">PLU#238</section><section class="dishingr"></section></section></section> </section><section class="greybardiv" style="marin-bottom:1%;overflow:hidden"><section class="greybar-tab" style="height:35px;display:table"><section class="ibtndiv" onclick="infoitem('8104'); "><img style="vertical-align:middle" src="images/ibtn-slver.png"></section><section class="dishnamew" style=" width:75%;display:table-cell;height:100%;vertical-align:middle;min-width:100%;padding-left:30px;text-align:left" id="valit-8104" onclick="additem('1058','no','99','1','0','340','8104','10.00' ,'Sidi Harazem 1/2','0','First','no','no','0','no','no','No','','no','0','1','Bar','','','0.00');event.stopPropagation();">Sidi Harazem 1/2</section><section class="dishprice222" style="display:table-cell;height:100%;vertical-align:middle;min-width:94px;padding-right:20px;text-align:right" onclick="additem('1058','no','99','1','0','340','8104','10.00' ,'Sidi Harazem 1/2','0','First','no','no','0','no','no','No','','no','0','1','Bar','','','0.00');event.stopPropagation();">Dh 20.00</section><section id="mbtndiv" class="mbtndiv" style="display:table-cell;height:100%;vertical-align:middle;width:40px;min-width:40px;"><section style=""></section> </section></section><section id="iddet-8104" class="greybardiv-extnd" style="display:none"><section class="greybar-tab-extnd"><section class="dishimg" style="margin-top:5px;margin-left:5px"><img style="float:left; height:88px" src="http://www.softpointdev.com/images/location_menu_item/56d6088e256c682b641e48bfd7dc3ec8-.jpg"></section><section class="dishdesc" style="padding-left:115px;"><section class="dishtitle">PLU#237</section><section class="dishingr"></section></section></section> </section><section class="greybardiv" style="marin-bottom:1%;overflow:hidden"><section class="greybar-tab" style="height:35px;display:table"><section class="ibtndiv" onclick="infoitem('7632'); "><img style="vertical-align:middle" src="images/ibtn-slver.png"></section><section class="dishnamew" style=" width:75%;display:table-cell;height:100%;vertical-align:middle;min-width:100%;padding-left:30px;text-align:left" id="valit-7632" onclick="additem('1058','no','99','1','0','340','7632','25.00' ,'Oulmes 1.5L','0','First','no','no','0','no','no','No','','no','0','1','Bar','','','0.00');event.stopPropagation();">Oulmes 1.5L</section><section class="dishprice222" style="display:table-cell;height:100%;vertical-align:middle;min-width:94px;padding-right:20px;text-align:right" onclick="additem('1058','no','99','1','0','340','7632','25.00' ,'Oulmes 1.5L','0','First','no','no','0','no','no','No','','no','0','1','Bar','','','0.00');event.stopPropagation();">Dh 25.00</section><section id="mbtndiv" class="mbtndiv" style="display:table-cell;height:100%;vertical-align:middle;width:40px;min-width:40px;"><section style=""></section> </section></section><section id="iddet-7632" class="greybardiv-extnd" style="display:none"><section class="greybar-tab-extnd"><section class="dishimg" style="margin-top:5px;margin-left:5px"><img style="float:left; height:88px" src="http://www.softpointdev.com/images/location_menu_item/7ad068df0e9c7adbe89c3a3334d22b29-.jpg"></section><section class="dishdesc" style="padding-left:115px;"><section class="dishtitle">PLU#139</section><section class="dishingr"></section></section></section> </section><section class="greybardiv" style="marin-bottom:1%;overflow:hidden"><section class="greybar-tab" style="height:35px;display:table"><section class="ibtndiv" onclick="infoitem('7631'); "><img style="vertical-align:middle" src="images/ibtn-slver.png"></section><section class="dishnamew" style=" width:75%;display:table-cell;height:100%;vertical-align:middle;min-width:100%;padding-left:30px;text-align:left" id="valit-7631" onclick="additem('1058','no','99','1','0','340','7631','20.00' ,'Oulmes 1/2L','0','First','no','no','0','no','no','No','','no','0','1','Bar','','','0.00');event.stopPropagation();">Oulmes 1/2L</section><section class="dishprice222" style="display:table-cell;height:100%;vertical-align:middle;min-width:94px;padding-right:20px;text-align:right" onclick="additem('1058','no','99','1','0','340','7631','20.00' ,'Oulmes 1/2L','0','First','no','no','0','no','no','No','','no','0','1','Bar','','','0.00');event.stopPropagation();">Dh 20.00</section><section id="mbtndiv" class="mbtndiv" style="display:table-cell;height:100%;vertical-align:middle;width:40px;min-width:40px;"><section style=""></section> </section></section><section id="iddet-7631" class="greybardiv-extnd" style="display:none"><section class="greybar-tab-extnd"><section class="dishimg" style="margin-top:5px;margin-left:5px"><img style="float:left; height:88px" src="http://www.softpointdev.com/images/location_menu_item/aa17641496247ff2aacec2dc18784a02-.jpg"></section><section class="dishdesc" style="padding-left:115px;"><section class="dishtitle">PLU#138</section><section class="dishingr"></section></section></section> </section><section class="greybardiv" style="marin-bottom:1%;overflow:hidden"><section class="greybar-tab" style="height:35px;display:table"><section class="ibtndiv" onclick="infoitem('7633'); "><img style="vertical-align:middle" src="images/ibtn-slver.png"></section><section class="dishnamew" style=" width:75%;display:table-cell;height:100%;vertical-align:middle;min-width:100%;padding-left:30px;text-align:left" id="valit-7633" onclick="additem('1058','no','99','1','0','340','7633','30.00' ,'Coca Cola','0','First','no','no','0','no','no','No','','no','0','1','Bar','','','0.00');event.stopPropagation();">Coca Cola</section><section class="dishprice222" style="display:table-cell;height:100%;vertical-align:middle;min-width:94px;padding-right:20px;text-align:right" onclick="additem('1058','no','99','1','0','340','7633','30.00' ,'Coca Cola','0','First','no','no','0','no','no','No','','no','0','1','Bar','','','0.00');event.stopPropagation();">Dh 10.00</section><section id="mbtndiv" class="mbtndiv" style="display:table-cell;height:100%;vertical-align:middle;width:40px;min-width:40px;"><section style=""></section> </section></section><section id="iddet-7633" class="greybardiv-extnd" style="display:none"><section class="greybar-tab-extnd"><section class="dishimg" style="margin-top:5px;margin-left:5px"><img style="float:left; height:88px" src="http://www.softpointdev.com/images/location_menu_item/094f8c06ed072f587f6e4691b425f977-.png"></section><section class="dishdesc" style="padding-left:115px;"><section class="dishtitle">PLU#140</section><section class="dishingr"></section></section></section> </section><section class="greybardiv" style="marin-bottom:1%;overflow:hidden"><section class="greybar-tab" style="height:35px;display:table"><section class="ibtndiv" onclick="infoitem('7634'); "><img style="vertical-align:middle" src="images/ibtn-slver.png"></section><section class="dishnamew" style=" width:75%;display:table-cell;height:100%;vertical-align:middle;min-width:100%;padding-left:30px;text-align:left" id="valit-7634" onclick="additem('1058','no','99','1','0','340','7634','30.00' ,'Fanta','0','First','no','no','0','no','no','No','','no','0','1','Bar','','','0.00');event.stopPropagation();">Fanta</section><section class="dishprice222" style="display:table-cell;height:100%;vertical-align:middle;min-width:94px;padding-right:20px;text-align:right" onclick="additem('1058','no','99','1','0','340','7634','30.00' ,'Fanta','0','First','no','no','0','no','no','No','','no','0','1','Bar','','','0.00');event.stopPropagation();">Dh 30.00</section><section id="mbtndiv" class="mbtndiv" style="display:table-cell;height:100%;vertical-align:middle;width:40px;min-width:40px;"><section style=""></section> </section></section><section id="iddet-7634" class="greybardiv-extnd" style="display:none"><section class="greybar-tab-extnd"><section class="dishimg" style="margin-top:5px;margin-left:5px"><img style="float:left; height:88px" src="http://www.softpointdev.com/images/location_menu_item/7b5db6022f89f0e6c69664d7a13e6fe4-.png"></section><section class="dishdesc" style="padding-left:115px;"><section class="dishtitle">PLU#141</section><section class="dishingr"></section></section></section> </section><section class="greybardiv" style="marin-bottom:1%;overflow:hidden"><section class="greybar-tab" style="height:35px;display:table"><section class="ibtndiv" onclick="infoitem('7635'); "><img style="vertical-align:middle" src="images/ibtn-slver.png"></section><section class="dishnamew" style=" width:75%;display:table-cell;height:100%;vertical-align:middle;min-width:100%;padding-left:30px;text-align:left" id="valit-7635" onclick="additem('1058','no','99','1','0','340','7635','10.00' ,'Sprite','0','First','no','no','0','no','no','No','','no','0','1','Bar','','','0.00');event.stopPropagation();">Sprite</section><section class="dishprice222" style="display:table-cell;height:100%;vertical-align:middle;min-width:94px;padding-right:20px;text-align:right" onclick="additem('1058','no','99','1','0','340','7635','10.00' ,'Sprite','0','First','no','no','0','no','no','No','','no','0','1','Bar','','','0.00');event.stopPropagation();">Dh 30.00</section><section id="mbtndiv" class="mbtndiv" style="display:table-cell;height:100%;vertical-align:middle;width:40px;min-width:40px;"><section style=""></section> </section></section><section id="iddet-7635" class="greybardiv-extnd" style="display:none"><section class="greybar-tab-extnd"><section class="dishimg" style="margin-top:5px;margin-left:5px"><img style="float:left; height:88px" src="http://www.softpointdev.com/images/location_menu_item/c3dcaaece2a8b0536344a24a277b7e81-.png"></section><section class="dishdesc" style="padding-left:115px;"><section class="dishtitle">PLU#142</section><section class="dishingr"></section></section></section> </section><section class="greybardiv" style="marin-bottom:1%;overflow:hidden"><section class="greybar-tab" style="height:35px;display:table"><section class="ibtndiv" onclick="infoitem('7641'); "><img style="vertical-align:middle" src="images/ibtn-slver.png"></section><section class="dishnamew" style=" width:75%;display:table-cell;height:100%;vertical-align:middle;min-width:100%;padding-left:30px;text-align:left" id="valit-7641" onclick="additem('1058','no','99','1','0','340','7641','10.00' ,'Shweppes Tonic','0','First','no','no','0','no','no','No','','no','0','1','Bar','','','0.00');event.stopPropagation();">Shweppes Tonic</section><section class="dishprice222" style="display:table-cell;height:100%;vertical-align:middle;min-width:94px;padding-right:20px;text-align:right" onclick="additem('1058','no','99','1','0','340','7641','10.00' ,'Shweppes Tonic','0','First','no','no','0','no','no','No','','no','0','1','Bar','','','0.00');event.stopPropagation();">Dh 30.00</section><section id="mbtndiv" class="mbtndiv" style="display:table-cell;height:100%;vertical-align:middle;width:40px;min-width:40px;"><section style=""></section> </section></section><section id="iddet-7641" class="greybardiv-extnd" style="display:none"><section class="greybar-tab-extnd"><section class="dishimg" style="margin-top:5px;margin-left:5px"><img style="float:left; height:88px" src="images/defimgpro.png"></section><section class="dishdesc" style="padding-left:115px;"><section class="dishtitle">PLU#148</section><section class="dishingr"></section></section></section> </section><section class="greybardiv" style="marin-bottom:1%;overflow:hidden"><section class="greybar-tab" style="height:35px;display:table"><section class="ibtndiv" onclick="infoitem('7639'); "><img style="vertical-align:middle" src="images/ibtn-slver.png"></section><section class="dishnamew" style=" width:75%;display:table-cell;height:100%;vertical-align:middle;min-width:100%;padding-left:30px;text-align:left" id="valit-7639" onclick="additem('1058','no','99','1','0','340','7639','15.00' ,'Coca Light ','0','First','no','no','0','no','no','No','','no','0','1','Bar','','','0.00');event.stopPropagation();">Coca Light </section><section class="dishprice222" style="display:table-cell;height:100%;vertical-align:middle;min-width:94px;padding-right:20px;text-align:right" onclick="additem('1058','no','99','1','0','340','7639','15.00' ,'Coca Light ','0','First','no','no','0','no','no','No','','no','0','1','Bar','','','0.00');event.stopPropagation();">Dh 30.00</section><section id="mbtndiv" class="mbtndiv" style="display:table-cell;height:100%;vertical-align:middle;width:40px;min-width:40px;"><section style=""></section> </section></section><section id="iddet-7639" class="greybardiv-extnd" style="display:none"><section class="greybar-tab-extnd"><section class="dishimg" style="margin-top:5px;margin-left:5px"><img style="float:left; height:88px" src="http://www.softpointdev.com/images/location_menu_item/cb2e2d2e9ae9efa7d0d42eabe3edad84-.jpg"></section><section class="dishdesc" style="padding-left:115px;"><section class="dishtitle">PLU#146</section><section class="dishingr"></section></section></section> </section><section class="greybardiv" style="marin-bottom:1%;overflow:hidden"><section class="greybar-tab" style="height:35px;display:table"><section class="ibtndiv" onclick="infoitem('7637'); "><img style="vertical-align:middle" src="images/ibtn-slver.png"></section><section class="dishnamew" style=" width:75%;display:table-cell;height:100%;vertical-align:middle;min-width:100%;padding-left:30px;text-align:left" id="valit-7637" onclick="additem('1058','no','99','1','0','340','7637','35.00' ,'Redbull','0','First','no','no','0','no','no','No','','no','0','1','Bar','','','0.00');event.stopPropagation();">Redbull</section><section class="dishprice222" style="display:table-cell;height:100%;vertical-align:middle;min-width:94px;padding-right:20px;text-align:right" onclick="additem('1058','no','99','1','0','340','7637','35.00' ,'Redbull','0','First','no','no','0','no','no','No','','no','0','1','Bar','','','0.00');event.stopPropagation();">Dh 50.00</section><section id="mbtndiv" class="mbtndiv" style="display:table-cell;height:100%;vertical-align:middle;width:40px;min-width:40px;"><section style=""></section> </section></section><section id="iddet-7637" class="greybardiv-extnd" style="display:none"><section class="greybar-tab-extnd"><section class="dishimg" style="margin-top:5px;margin-left:5px"><img style="float:left; height:88px" src="http://www.softpointdev.com/images/location_menu_item/a9e5047d22cab5b4a0722665cfca8e8a-.jpg"></section><section class="dishdesc" style="padding-left:115px;"><section class="dishtitle">PLU#144</section><section class="dishingr"></section></section></section> </section><section class="greybardiv" style="marin-bottom:1%;overflow:hidden"><section class="greybar-tab" style="height:35px;display:table"><section class="ibtndiv" onclick="infoitem('8103'); "><img style="vertical-align:middle" src="images/ibtn-slver.png"></section><section class="dishnamew" style=" width:75%;display:table-cell;height:100%;vertical-align:middle;min-width:100%;padding-left:30px;text-align:left" id="valit-8103" onclick="additem('1058','no','99','1','0','340','8103','15.00' ,'Jus dorange frais','0','Last','no','no','0','no','no','No','','no','0','1','Bar','','','0.00');event.stopPropagation();">Jus d'orange frais</section><section class="dishprice222" style="display:table-cell;height:100%;vertical-align:middle;min-width:94px;padding-right:20px;text-align:right" onclick="additem('1058','no','99','1','0','340','8103','15.00' ,'Jus dorange frais','0','Last','no','no','0','no','no','No','','no','0','1','Bar','','','0.00');event.stopPropagation();">Dh 30.00</section><section id="mbtndiv" class="mbtndiv" style="display:table-cell;height:100%;vertical-align:middle;width:40px;min-width:40px;"><section style=""></section> </section></section><section id="iddet-8103" class="greybardiv-extnd" style="display:none"><section class="greybar-tab-extnd"><section class="dishimg" style="margin-top:5px;margin-left:5px"><img style="float:left; height:88px" src="http://www.softpointdev.com/images/location_menu_item/7d67dde9daf3e07a80e1611e15463a51-.jpg"></section><section class="dishdesc" style="padding-left:115px;"><section class="dishtitle">PLU#236</section><section class="dishingr"></section></section></section> </section><section class="greybardiv" style="marin-bottom:1%;overflow:hidden"><section class="greybar-tab" style="height:35px;display:table"><section class="ibtndiv" onclick="infoitem('7636'); "><img style="vertical-align:middle" src="images/ibtn-slver.png"></section><section class="dishnamew" style=" width:75%;display:table-cell;height:100%;vertical-align:middle;min-width:100%;padding-left:30px;text-align:left" id="valit-7636" onclick="additem('1058','no','99','1','0','340','7636','30.00' ,'Pepsi','0','First','no','no','0','no','no','No','','no','0','1','Bar','','','0.00');event.stopPropagation();">Pepsi</section><section class="dishprice222" style="display:table-cell;height:100%;vertical-align:middle;min-width:94px;padding-right:20px;text-align:right" onclick="additem('1058','no','99','1','0','340','7636','30.00' ,'Pepsi','0','First','no','no','0','no','no','No','','no','0','1','Bar','','','0.00');event.stopPropagation();">Dh 30.00</section><section id="mbtndiv" class="mbtndiv" style="display:table-cell;height:100%;vertical-align:middle;width:40px;min-width:40px;"><section style=""></section> </section></section><section id="iddet-7636" class="greybardiv-extnd" style="display:none"><section class="greybar-tab-extnd"><section class="dishimg" style="margin-top:5px;margin-left:5px"><img style="float:left; height:88px" src="http://www.softpointdev.com/images/location_menu_item/c002ff0380bdcf872b91dc8f1515a0e4-.png"></section><section class="dishdesc" style="padding-left:115px;"><section class="dishtitle">PLU#143</section><section class="dishingr"></section></section></section> </section><section class="greybardiv" style="marin-bottom:1%;overflow:hidden"><section class="greybar-tab" style="height:35px;display:table"><section class="ibtndiv" onclick="infoitem('8315'); "><img style="vertical-align:middle" src="images/ibtn-slver.png"></section><section class="dishnamew" style=" width:75%;display:table-cell;height:100%;vertical-align:middle;min-width:100%;padding-left:30px;text-align:left" id="valit-8315" onclick="additem('1058','no','99','1','0','340','8315','60.00' ,'Schweppes Tonic Litre','0','First','','','0','no','no','No','','no','0','1','Bar','','','0.00');event.stopPropagation();">Schweppes Tonic Litre</section><section class="dishprice222" style="display:table-cell;height:100%;vertical-align:middle;min-width:94px;padding-right:20px;text-align:right" onclick="additem('1058','no','99','1','0','340','8315','60.00' ,'Schweppes Tonic Litre','0','First','','','0','no','no','No','','no','0','1','Bar','','','0.00');event.stopPropagation();">Dh 60.00</section><section id="mbtndiv" class="mbtndiv" style="display:table-cell;height:100%;vertical-align:middle;width:40px;min-width:40px;"><section style=""></section> </section></section><section id="iddet-8315" class="greybardiv-extnd" style="display:none"><section class="greybar-tab-extnd"><section class="dishimg" style="margin-top:5px;margin-left:5px"><img style="float:left; height:88px" src="images/defimgpro.png"></section><section class="dishdesc" style="padding-left:115px;"><section class="dishtitle">PLU#277</section><section class="dishingr"></section></section></section> </section><section class="greybardiv" style="marin-bottom:1%;overflow:hidden"><section class="greybar-tab" style="height:35px;display:table"><section class="ibtndiv" onclick="infoitem('8316'); "><img style="vertical-align:middle" src="images/ibtn-slver.png"></section><section class="dishnamew" style=" width:75%;display:table-cell;height:100%;vertical-align:middle;min-width:100%;padding-left:30px;text-align:left" id="valit-8316" onclick="additem('1058','no','99','1','0','340','8316','30.00' ,'Hawai','0','First','','','0','no','no','No','','no','0','1','Bar','','','0.00');event.stopPropagation();">Hawai</section><section class="dishprice222" style="display:table-cell;height:100%;vertical-align:middle;min-width:94px;padding-right:20px;text-align:right" onclick="additem('1058','no','99','1','0','340','8316','30.00' ,'Hawai','0','First','','','0','no','no','No','','no','0','1','Bar','','','0.00');event.stopPropagation();">Dh 30.00</section><section id="mbtndiv" class="mbtndiv" style="display:table-cell;height:100%;vertical-align:middle;width:40px;min-width:40px;"><section style=""></section> </section></section><section id="iddet-8316" class="greybardiv-extnd" style="display:none"><section class="greybar-tab-extnd"><section class="dishimg" style="margin-top:5px;margin-left:5px"><img style="float:left; height:88px" src="http://www.softpointdev.com/images/location_menu_item/db453cbe263fd246f0a0748413932e3f-.png"></section><section class="dishdesc" style="padding-left:115px;"><section class="dishtitle">PLU#278</section><section class="dishingr"></section></section></section> </section><section class="greybardiv" style="marin-bottom:1%;overflow:hidden"><section class="greybar-tab" style="height:35px;display:table"><section class="ibtndiv" onclick="infoitem('8317'); "><img style="vertical-align:middle" src="images/ibtn-slver.png"></section><section class="dishnamew" style=" width:75%;display:table-cell;height:100%;vertical-align:middle;min-width:100%;padding-left:30px;text-align:left" id="valit-8317" onclick="additem('1058','no','99','1','0','340','8317','30.00' ,'Schweppes','0','First','','','0','no','no','No','','no','0','1','Bar','','','0.00');event.stopPropagation();">Schweppes</section><section class="dishprice222" style="display:table-cell;height:100%;vertical-align:middle;min-width:94px;padding-right:20px;text-align:right" onclick="additem('1058','no','99','1','0','340','8317','30.00' ,'Schweppes','0','First','','','0','no','no','No','','no','0','1','Bar','','','0.00');event.stopPropagation();">Dh 30.00</section><section id="mbtndiv" class="mbtndiv" style="display:table-cell;height:100%;vertical-align:middle;width:40px;min-width:40px;"><section style=""></section> </section></section><section id="iddet-8317" class="greybardiv-extnd" style="display:none"><section class="greybar-tab-extnd"><section class="dishimg" style="margin-top:5px;margin-left:5px"><img style="float:left; height:88px" src="http://www.softpointdev.com/images/location_menu_item/7e71d0b6bd319ad440f8beac895a0cfe-.png"></section><section class="dishdesc" style="padding-left:115px;"><section class="dishtitle">PLU#279</section><section class="dishingr"></section></section></section> </section><section class="greybardiv" style="marin-bottom:1%;overflow:hidden"><section class="greybar-tab" style="height:35px;display:table"><section class="ibtndiv" onclick="infoitem('8319'); "><img style="vertical-align:middle" src="images/ibtn-slver.png"></section><section class="dishnamew" style=" width:75%;display:table-cell;height:100%;vertical-align:middle;min-width:100%;padding-left:30px;text-align:left" id="valit-8319" onclick="additem('1058','no','99','1','0','340','8319','60.00' ,'Coca Litre','0','First','','','0','no','no','No','','no','0','1','Bar','','','0.00');event.stopPropagation();">Coca Litre</section><section class="dishprice222" style="display:table-cell;height:100%;vertical-align:middle;min-width:94px;padding-right:20px;text-align:right" onclick="additem('1058','no','99','1','0','340','8319','60.00' ,'Coca Litre','0','First','','','0','no','no','No','','no','0','1','Bar','','','0.00');event.stopPropagation();">Dh 60.00</section><section id="mbtndiv" class="mbtndiv" style="display:table-cell;height:100%;vertical-align:middle;width:40px;min-width:40px;"><section style=""></section> </section></section><section id="iddet-8319" class="greybardiv-extnd" style="display:none"><section class="greybar-tab-extnd"><section class="dishimg" style="margin-top:5px;margin-left:5px"><img style="float:left; height:88px" src="http://www.softpointdev.com/images/location_menu_item/ba715144f577d6de17b110ece948b71f-.png"></section><section class="dishdesc" style="padding-left:115px;"><section class="dishtitle">PLU#281</section><section class="dishingr"></section></section></section> </section><section class="greybardiv" style="marin-bottom:1%;overflow:hidden"><section class="greybar-tab" style="height:35px;display:table"><section class="ibtndiv" onclick="infoitem('8320'); "><img style="vertical-align:middle" src="images/ibtn-slver.png"></section><section class="dishnamew" style=" width:75%;display:table-cell;height:100%;vertical-align:middle;min-width:100%;padding-left:30px;text-align:left" id="valit-8320" onclick="additem('1058','no','99','1','0','340','8320','60.00' ,'Sprite Litre','0','First','','','0','no','no','No','','no','0','1','Bar','','','0.00');event.stopPropagation();">Sprite Litre</section><section class="dishprice222" style="display:table-cell;height:100%;vertical-align:middle;min-width:94px;padding-right:20px;text-align:right" onclick="additem('1058','no','99','1','0','340','8320','60.00' ,'Sprite Litre','0','First','','','0','no','no','No','','no','0','1','Bar','','','0.00');event.stopPropagation();">Dh 60.00</section><section id="mbtndiv" class="mbtndiv" style="display:table-cell;height:100%;vertical-align:middle;width:40px;min-width:40px;"><section style=""></section> </section></section><section id="iddet-8320" class="greybardiv-extnd" style="display:none"><section class="greybar-tab-extnd"><section class="dishimg" style="margin-top:5px;margin-left:5px"><img style="float:left; height:88px" src="http://www.softpointdev.com/images/location_menu_item/fc623a0f5f141115b46ba2b8882f92eb-.png"></section><section class="dishdesc" style="padding-left:115px;"><section class="dishtitle">PLU#282</section><section class="dishingr"></section></section></section> </section><section class="greybardiv" style="marin-bottom:1%;overflow:hidden"><section class="greybar-tab" style="height:35px;display:table"><section class="ibtndiv" onclick="infoitem('8321'); "><img style="vertical-align:middle" src="images/ibtn-slver.png"></section><section class="dishnamew" style=" width:75%;display:table-cell;height:100%;vertical-align:middle;min-width:100%;padding-left:30px;text-align:left" id="valit-8321" onclick="additem('1058','no','99','1','0','340','8321','60.00' ,'Schweppes Litre','0','First','','','0','no','no','No','','no','0','1','Bar','','','0.00');event.stopPropagation();">Schweppes Litre</section><section class="dishprice222" style="display:table-cell;height:100%;vertical-align:middle;min-width:94px;padding-right:20px;text-align:right" onclick="additem('1058','no','99','1','0','340','8321','60.00' ,'Schweppes Litre','0','First','','','0','no','no','No','','no','0','1','Bar','','','0.00');event.stopPropagation();">Dh 60.00</section><section id="mbtndiv" class="mbtndiv" style="display:table-cell;height:100%;vertical-align:middle;width:40px;min-width:40px;"><section style=""></section> </section></section><section id="iddet-8321" class="greybardiv-extnd" style="display:none"><section class="greybar-tab-extnd"><section class="dishimg" style="margin-top:5px;margin-left:5px"><img style="float:left; height:88px" src="http://www.softpointdev.com/images/location_menu_item/06713fc44ca9a4b9983c6b1e4c587a87-.png"></section><section class="dishdesc" style="padding-left:115px;"><section class="dishtitle">PLU#283</section><section class="dishingr"></section></section></section> </section></section></section></section></section></section></section></section></section></section></section></section></section></section></section></section></section></section></section></section></section></div><section id="gui-1059" class="widgettitle_lgtblue" onclick="opengroup('1059')"><section id="bluebartext" class="bluebartext" style="height:35px;min-height:35px;max-height:35px;overflow:hidden;"><section class="bluebartextcell" style="padding-top:7px;height:35px;min-height:35px;max-height:35px;overflow:hidden;text-align:center;">Boissons Chaudes</section></section></section><div id="adg-1059" class="contgroup" style="display:none;border:"><section class="greybardiv" style="marin-bottom:1%;overflow:hidden"><section class="greybar-tab" style="height:35px;display:table"><section class="ibtndiv" onclick="infoitem('8101'); "><img style="vertical-align:middle" src="images/ibtn-slver.png"></section><section class="dishnamew" style=" width:75%;display:table-cell;height:100%;vertical-align:middle;min-width:100%;padding-left:30px;text-align:left" id="valit-8101" onclick="additem('1059','no','99','1','0','340','8101','30.00' ,'Cafe Latte','0','Last','no','no','0','no','no','No','','no','0','1','Bar','','','0.00');event.stopPropagation();">Cafe Latte</section><section class="dishprice222" style="display:table-cell;height:100%;vertical-align:middle;min-width:94px;padding-right:20px;text-align:right" onclick="additem('1059','no','99','1','0','340','8101','30.00' ,'Cafe Latte','0','Last','no','no','0','no','no','No','','no','0','1','Bar','','','0.00');event.stopPropagation();">Dh 30.00</section><section id="mbtndiv" class="mbtndiv" style="display:table-cell;height:100%;vertical-align:middle;width:40px;min-width:40px;"><section style=""></section> </section></section><section id="iddet-8101" class="greybardiv-extnd" style="display:none"><section class="greybar-tab-extnd"><section class="dishimg" style="margin-top:5px;margin-left:5px"><img style="float:left; height:88px" src="http://www.softpointdev.com/images/location_menu_item/71eca8f8ceaa13f6ca7026321f1de449-.jpg"></section><section class="dishdesc" style="padding-left:115px;"><section class="dishtitle">PLU#234</section><section class="dishingr"></section></section></section> </section><section class="greybardiv" style="marin-bottom:1%;overflow:hidden"><section class="greybar-tab" style="height:35px;display:table"><section class="ibtndiv" onclick="infoitem('8138'); "><img style="vertical-align:middle" src="images/ibtn-slver.png"></section><section class="dishnamew" style=" width:75%;display:table-cell;height:100%;vertical-align:middle;min-width:100%;padding-left:30px;text-align:left" id="valit-8138" onclick="additem('1059','no','99','1','0','340','8138','10.00' ,'The','0','Last','no','no','0','no','no','No','','no','0','1','Bar','','','0.00');event.stopPropagation();">The</section><section class="dishprice222" style="display:table-cell;height:100%;vertical-align:middle;min-width:94px;padding-right:20px;text-align:right" onclick="additem('1059','no','99','1','0','340','8138','10.00' ,'The','0','Last','no','no','0','no','no','No','','no','0','1','Bar','','','0.00');event.stopPropagation();">Dh 30.00</section><section id="mbtndiv" class="mbtndiv" style="display:table-cell;height:100%;vertical-align:middle;width:40px;min-width:40px;"><section style=""></section> </section></section><section id="iddet-8138" class="greybardiv-extnd" style="display:none"><section class="greybar-tab-extnd"><section class="dishimg" style="margin-top:5px;margin-left:5px"><img style="float:left; height:88px" src="http://www.softpointdev.com/images/location_menu_item/b106e7a3a757dd27f602cc6294f7f1d0-.jpg"></section><section class="dishdesc" style="padding-left:115px;"><section class="dishtitle">PLU#270</section><section class="dishingr"></section></section></section> </section><section class="greybardiv" style="marin-bottom:1%;overflow:hidden"><section class="greybar-tab" style="height:35px;display:table"><section class="ibtndiv" onclick="infoitem('8102'); "><img style="vertical-align:middle" src="images/ibtn-slver.png"></section><section class="dishnamew" style=" width:75%;display:table-cell;height:100%;vertical-align:middle;min-width:100%;padding-left:30px;text-align:left" id="valit-8102" onclick="additem('1059','no','99','1','0','340','8102','10.00' ,'Infusions','0','Last','no','no','0','no','no','No','','no','0','1','Bar','','','0.00');event.stopPropagation();">Infusions</section><section class="dishprice222" style="display:table-cell;height:100%;vertical-align:middle;min-width:94px;padding-right:20px;text-align:right" onclick="additem('1059','no','99','1','0','340','8102','10.00' ,'Infusions','0','Last','no','no','0','no','no','No','','no','0','1','Bar','','','0.00');event.stopPropagation();">Dh 30.00</section><section id="mbtndiv" class="mbtndiv" style="display:table-cell;height:100%;vertical-align:middle;width:40px;min-width:40px;"><section style=""></section> </section></section><section id="iddet-8102" class="greybardiv-extnd" style="display:none"><section class="greybar-tab-extnd"><section class="dishimg" style="margin-top:5px;margin-left:5px"><img style="float:left; height:88px" src="http://www.softpointdev.com/images/location_menu_item/546e887b1ce4d379ab74bbdc4c1705d6-.jpg"></section><section class="dishdesc" style="padding-left:115px;"><section class="dishtitle">PLU#235</section><section class="dishingr"></section></section></section> </section><section class="greybardiv" style="marin-bottom:1%;overflow:hidden"><section class="greybar-tab" style="height:35px;display:table"><section class="ibtndiv" onclick="infoitem('8322'); "><img style="vertical-align:middle" src="images/ibtn-slver.png"></section><section class="dishnamew" style=" width:75%;display:table-cell;height:100%;vertical-align:middle;min-width:100%;padding-left:30px;text-align:left" id="valit-8322" onclick="additem('1059','no','99','1','0','340','8322','30.00' ,'Caffe Créme','0','First','','','0','no','no','No','','no','0','1','Bar','','','0.00');event.stopPropagation();">Caffe Créme</section><section class="dishprice222" style="display:table-cell;height:100%;vertical-align:middle;min-width:94px;padding-right:20px;text-align:right" onclick="additem('1059','no','99','1','0','340','8322','30.00' ,'Caffe Créme','0','First','','','0','no','no','No','','no','0','1','Bar','','','0.00');event.stopPropagation();">Dh 30.00</section><section id="mbtndiv" class="mbtndiv" style="display:table-cell;height:100%;vertical-align:middle;width:40px;min-width:40px;"><section style=""></section> </section></section><section id="iddet-8322" class="greybardiv-extnd" style="display:none"><section class="greybar-tab-extnd"><section class="dishimg" style="margin-top:5px;margin-left:5px"><img style="float:left; height:88px" src="http://www.softpointdev.com/images/location_menu_item/a7cda5f18f280353c3fecfca410de349-.png"></section><section class="dishdesc" style="padding-left:115px;"><section class="dishtitle">PLU#284</section><section class="dishingr"></section></section></section> </section><section class="greybardiv" style="marin-bottom:1%;overflow:hidden"><section class="greybar-tab" style="height:35px;display:table"><section class="ibtndiv" onclick="infoitem('8323'); "><img style="vertical-align:middle" src="images/ibtn-slver.png"></section><section class="dishnamew" style=" width:75%;display:table-cell;height:100%;vertical-align:middle;min-width:100%;padding-left:30px;text-align:left" id="valit-8323" onclick="additem('1059','no','99','1','0','340','8323','30.00' ,'Caffé Noire','0','First','','','0','no','no','No','','no','0','1','Bar','','','0.00');event.stopPropagation();">Caffé Noire</section><section class="dishprice222" style="display:table-cell;height:100%;vertical-align:middle;min-width:94px;padding-right:20px;text-align:right" onclick="additem('1059','no','99','1','0','340','8323','30.00' ,'Caffé Noire','0','First','','','0','no','no','No','','no','0','1','Bar','','','0.00');event.stopPropagation();">Dh 30.00</section><section id="mbtndiv" class="mbtndiv" style="display:table-cell;height:100%;vertical-align:middle;width:40px;min-width:40px;"><section style=""></section> </section></section><section id="iddet-8323" class="greybardiv-extnd" style="display:none"><section class="greybar-tab-extnd"><section class="dishimg" style="margin-top:5px;margin-left:5px"><img style="float:left; height:88px" src="http://www.softpointdev.com/images/location_menu_item/cf33a0f36c5c84f028b13cb0d235f332-.png"></section><section class="dishdesc" style="padding-left:115px;"><section class="dishtitle">PLU#285</section><section class="dishingr"></section></section></section> </section><section class="greybardiv" style="marin-bottom:1%;overflow:hidden"><section class="greybar-tab" style="height:35px;display:table"><section class="ibtndiv" onclick="infoitem('8324'); "><img style="vertical-align:middle" src="images/ibtn-slver.png"></section><section class="dishnamew" style=" width:75%;display:table-cell;height:100%;vertical-align:middle;min-width:100%;padding-left:30px;text-align:left" id="valit-8324" onclick="additem('1059','no','99','1','0','340','8324','30.00' ,'Caffé Espresso','0','First','','','0','no','no','No','','no','0','1','Bar','','','0.00');event.stopPropagation();">Caffé Espresso</section><section class="dishprice222" style="display:table-cell;height:100%;vertical-align:middle;min-width:94px;padding-right:20px;text-align:right" onclick="additem('1059','no','99','1','0','340','8324','30.00' ,'Caffé Espresso','0','First','','','0','no','no','No','','no','0','1','Bar','','','0.00');event.stopPropagation();">Dh 30.00</section><section id="mbtndiv" class="mbtndiv" style="display:table-cell;height:100%;vertical-align:middle;width:40px;min-width:40px;"><section style=""></section> </section></section><section id="iddet-8324" class="greybardiv-extnd" style="display:none"><section class="greybar-tab-extnd"><section class="dishimg" style="margin-top:5px;margin-left:5px"><img style="float:left; height:88px" src="http://www.softpointdev.com/images/location_menu_item/9b815eb6167b0a6151e9514ac9e0238d-.png"></section><section class="dishdesc" style="padding-left:115px;"><section class="dishtitle">PLU#286</section><section class="dishingr"></section></section></section> </section><section class="greybardiv" style="marin-bottom:1%;overflow:hidden"><section class="greybar-tab" style="height:35px;display:table"><section class="ibtndiv" onclick="infoitem('8325'); "><img style="vertical-align:middle" src="images/ibtn-slver.png"></section><section class="dishnamew" style=" width:75%;display:table-cell;height:100%;vertical-align:middle;min-width:100%;padding-left:30px;text-align:left" id="valit-8325" onclick="additem('1059','no','99','1','0','340','8325','35.00' ,'Cappuchino','0','Second','','','0','no','no','No','','no','0','1','Bar','','','0.00');event.stopPropagation();">Cappuchino</section><section class="dishprice222" style="display:table-cell;height:100%;vertical-align:middle;min-width:94px;padding-right:20px;text-align:right" onclick="additem('1059','no','99','1','0','340','8325','35.00' ,'Cappuchino','0','Second','','','0','no','no','No','','no','0','1','Bar','','','0.00');event.stopPropagation();">Dh 35.00</section><section id="mbtndiv" class="mbtndiv" style="display:table-cell;height:100%;vertical-align:middle;width:40px;min-width:40px;"><section style=""></section> </section></section><section id="iddet-8325" class="greybardiv-extnd" style="display:none"><section class="greybar-tab-extnd"><section class="dishimg" style="margin-top:5px;margin-left:5px"><img style="float:left; height:88px" src="http://www.softpointdev.com/images/location_menu_item/34df09303086dac428896a7adf1be139-.png"></section><section class="dishdesc" style="padding-left:115px;"><section class="dishtitle">PLU#287</section><section class="dishingr"></section></section></section> </section><section class="greybardiv" style="marin-bottom:1%;overflow:hidden"><section class="greybar-tab" style="height:35px;display:table"><section class="ibtndiv" onclick="infoitem('8326'); "><img style="vertical-align:middle" src="images/ibtn-slver.png"></section><section class="dishnamew" style=" width:75%;display:table-cell;height:100%;vertical-align:middle;min-width:100%;padding-left:30px;text-align:left" id="valit-8326" onclick="additem('1059','no','99','1','0','340','8326','10.00' ,'Verre Lait','0','First','','','0','no','no','No','','no','0','1','Bar','','','0.00');event.stopPropagation();">Verre Lait</section><section class="dishprice222" style="display:table-cell;height:100%;vertical-align:middle;min-width:94px;padding-right:20px;text-align:right" onclick="additem('1059','no','99','1','0','340','8326','10.00' ,'Verre Lait','0','First','','','0','no','no','No','','no','0','1','Bar','','','0.00');event.stopPropagation();">Dh 10.00</section><section id="mbtndiv" class="mbtndiv" style="display:table-cell;height:100%;vertical-align:middle;width:40px;min-width:40px;"><section style=""></section> </section></section><section id="iddet-8326" class="greybardiv-extnd" style="display:none"><section class="greybar-tab-extnd"><section class="dishimg" style="margin-top:5px;margin-left:5px"><img style="float:left; height:88px" src="images/defimgpro.png"></section><section class="dishdesc" style="padding-left:115px;"><section class="dishtitle">PLU#288</section><section class="dishingr"></section></section></section> </section><section class="greybardiv" style="marin-bottom:1%;overflow:hidden"><section class="greybar-tab" style="height:35px;display:table"><section class="ibtndiv" onclick="infoitem('8327'); "><img style="vertical-align:middle" src="images/ibtn-slver.png"></section><section class="dishnamew" style=" width:75%;display:table-cell;height:100%;vertical-align:middle;min-width:100%;padding-left:30px;text-align:left" id="valit-8327" onclick="additem('1059','no','99','1','0','340','8327','30.00' ,'Verre Vien','0','First','','','0','no','no','No','','no','0','1','Bar','','','0.00');event.stopPropagation();">Verre Vien</section><section class="dishprice222" style="display:table-cell;height:100%;vertical-align:middle;min-width:94px;padding-right:20px;text-align:right" onclick="additem('1059','no','99','1','0','340','8327','30.00' ,'Verre Vien','0','First','','','0','no','no','No','','no','0','1','Bar','','','0.00');event.stopPropagation();">Dh 30.00</section><section id="mbtndiv" class="mbtndiv" style="display:table-cell;height:100%;vertical-align:middle;width:40px;min-width:40px;"><section style=""></section> </section></section><section id="iddet-8327" class="greybardiv-extnd" style="display:none"><section class="greybar-tab-extnd"><section class="dishimg" style="margin-top:5px;margin-left:5px"><img style="float:left; height:88px" src="images/defimgpro.png"></section><section class="dishdesc" style="padding-left:115px;"><section class="dishtitle">PLU#289</section><section class="dishingr"></section></section></section> </section><section class="greybardiv" style="marin-bottom:1%;overflow:hidden"><section class="greybar-tab" style="height:35px;display:table"><section class="ibtndiv" onclick="infoitem('8328'); "><img style="vertical-align:middle" src="images/ibtn-slver.png"></section><section class="dishnamew" style=" width:75%;display:table-cell;height:100%;vertical-align:middle;min-width:100%;padding-left:30px;text-align:left" id="valit-8328" onclick="additem('1059','no','99','1','0','340','8328','30.00' ,'Thé Noire','0','First','','','0','no','no','No','','no','0','1','Bar','','','0.00');event.stopPropagation();">Thé Noire</section><section class="dishprice222" style="display:table-cell;height:100%;vertical-align:middle;min-width:94px;padding-right:20px;text-align:right" onclick="additem('1059','no','99','1','0','340','8328','30.00' ,'Thé Noire','0','First','','','0','no','no','No','','no','0','1','Bar','','','0.00');event.stopPropagation();">Dh 30.00</section><section id="mbtndiv" class="mbtndiv" style="display:table-cell;height:100%;vertical-align:middle;width:40px;min-width:40px;"><section style=""></section> </section></section><section id="iddet-8328" class="greybardiv-extnd" style="display:none"><section class="greybar-tab-extnd"><section class="dishimg" style="margin-top:5px;margin-left:5px"><img style="float:left; height:88px" src="images/defimgpro.png"></section><section class="dishdesc" style="padding-left:115px;"><section class="dishtitle">PLU#290</section><section class="dishingr"></section></section></section> </section><section class="greybardiv" style="marin-bottom:1%;overflow:hidden"><section class="greybar-tab" style="height:35px;display:table"><section class="ibtndiv" onclick="infoitem('8329'); "><img style="vertical-align:middle" src="images/ibtn-slver.png"></section><section class="dishnamew" style=" width:75%;display:table-cell;height:100%;vertical-align:middle;min-width:100%;padding-left:30px;text-align:left" id="valit-8329" onclick="additem('1059','no','99','1','0','340','8329','30.00' ,'Thé A La Menth','0','First','','','0','no','no','No','','no','0','1','Bar','','','0.00');event.stopPropagation();">Thé A La Menth</section><section class="dishprice222" style="display:table-cell;height:100%;vertical-align:middle;min-width:94px;padding-right:20px;text-align:right" onclick="additem('1059','no','99','1','0','340','8329','30.00' ,'Thé A La Menth','0','First','','','0','no','no','No','','no','0','1','Bar','','','0.00');event.stopPropagation();">Dh 30.00</section><section id="mbtndiv" class="mbtndiv" style="display:table-cell;height:100%;vertical-align:middle;width:40px;min-width:40px;"><section style=""></section> </section></section><section id="iddet-8329" class="greybardiv-extnd" style="display:none"><section class="greybar-tab-extnd"><section class="dishimg" style="margin-top:5px;margin-left:5px"><img style="float:left; height:88px" src="images/defimgpro.png"></section><section class="dishdesc" style="padding-left:115px;"><section class="dishtitle">PLU#291</section><section class="dishingr"></section></section></section> </section><section class="greybardiv" style="marin-bottom:1%;overflow:hidden"><section class="greybar-tab" style="height:35px;display:table"><section class="ibtndiv" onclick="infoitem('8330'); "><img style="vertical-align:middle" src="images/ibtn-slver.png"></section><section class="dishnamew" style=" width:75%;display:table-cell;height:100%;vertical-align:middle;min-width:100%;padding-left:30px;text-align:left" id="valit-8330" onclick="additem('1059','no','99','1','0','340','8330','30.00' ,'Chocolat Chaud','0','First','','','0','no','no','No','','no','0','1','Bar','','','0.00');event.stopPropagation();">Chocolat Chaud</section><section class="dishprice222" style="display:table-cell;height:100%;vertical-align:middle;min-width:94px;padding-right:20px;text-align:right" onclick="additem('1059','no','99','1','0','340','8330','30.00' ,'Chocolat Chaud','0','First','','','0','no','no','No','','no','0','1','Bar','','','0.00');event.stopPropagation();">Dh 30.00</section><section id="mbtndiv" class="mbtndiv" style="display:table-cell;height:100%;vertical-align:middle;width:40px;min-width:40px;"><section style=""></section> </section></section><section id="iddet-8330" class="greybardiv-extnd" style="display:none"><section class="greybar-tab-extnd"><section class="dishimg" style="margin-top:5px;margin-left:5px"><img style="float:left; height:88px" src="http://www.softpointdev.com/images/location_menu_item/9ccaa10ed1744a3c0a81549323a3f141-.png"></section><section class="dishdesc" style="padding-left:115px;"><section class="dishtitle">PLU#292</section><section class="dishingr"></section></section></section> </section><section class="greybardiv" style="marin-bottom:1%;overflow:hidden"><section class="greybar-tab" style="height:35px;display:table"><section class="ibtndiv" onclick="infoitem('8331'); "><img style="vertical-align:middle" src="images/ibtn-slver.png"></section><section class="dishnamew" style=" width:75%;display:table-cell;height:100%;vertical-align:middle;min-width:100%;padding-left:30px;text-align:left" id="valit-8331" onclick="additem('1059','no','99','1','0','340','8331','20.00' ,'Ali Petit','0','First','','','0','no','no','No','','no','0','1','Bar','','','0.00');event.stopPropagation();">Ali Petit</section><section class="dishprice222" style="display:table-cell;height:100%;vertical-align:middle;min-width:94px;padding-right:20px;text-align:right" onclick="additem('1059','no','99','1','0','340','8331','20.00' ,'Ali Petit','0','First','','','0','no','no','No','','no','0','1','Bar','','','0.00');event.stopPropagation();">Dh 20.00</section><section id="mbtndiv" class="mbtndiv" style="display:table-cell;height:100%;vertical-align:middle;width:40px;min-width:40px;"><section style=""></section> </section></section><section id="iddet-8331" class="greybardiv-extnd" style="display:none"><section class="greybar-tab-extnd"><section class="dishimg" style="margin-top:5px;margin-left:5px"><img style="float:left; height:88px" src="images/defimgpro.png"></section><section class="dishdesc" style="padding-left:115px;"><section class="dishtitle">PLU#293</section><section class="dishingr"></section></section></section> </section><section class="greybardiv" style="marin-bottom:1%;overflow:hidden"><section class="greybar-tab" style="height:35px;display:table"><section class="ibtndiv" onclick="infoitem('8332'); "><img style="vertical-align:middle" src="images/ibtn-slver.png"></section><section class="dishnamew" style=" width:75%;display:table-cell;height:100%;vertical-align:middle;min-width:100%;padding-left:30px;text-align:left" id="valit-8332" onclick="additem('1059','no','99','1','0','340','8332','25.00' ,'Ali Grand','0','First','','','0','no','no','No','','no','0','1','Bar','','','0.00');event.stopPropagation();">Ali Grand</section><section class="dishprice222" style="display:table-cell;height:100%;vertical-align:middle;min-width:94px;padding-right:20px;text-align:right" onclick="additem('1059','no','99','1','0','340','8332','25.00' ,'Ali Grand','0','First','','','0','no','no','No','','no','0','1','Bar','','','0.00');event.stopPropagation();">Dh 25.00</section><section id="mbtndiv" class="mbtndiv" style="display:table-cell;height:100%;vertical-align:middle;width:40px;min-width:40px;"><section style=""></section> </section></section><section id="iddet-8332" class="greybardiv-extnd" style="display:none"><section class="greybar-tab-extnd"><section class="dishimg" style="margin-top:5px;margin-left:5px"><img style="float:left; height:88px" src="images/defimgpro.png"></section><section class="dishdesc" style="padding-left:115px;"><section class="dishtitle">PLU#294</section><section class="dishingr"></section></section></section> </section><section class="greybardiv" style="marin-bottom:1%;overflow:hidden"><section class="greybar-tab" style="height:35px;display:table"><section class="ibtndiv" onclick="infoitem('8333'); "><img style="vertical-align:middle" src="images/ibtn-slver.png"></section><section class="dishnamew" style=" width:75%;display:table-cell;height:100%;vertical-align:middle;min-width:100%;padding-left:30px;text-align:left" id="valit-8333" onclick="additem('1059','no','99','1','0','340','8333','20.00' ,'Oulmes Petit','0','First','','','0','no','no','No','','no','0','1','Bar','','','0.00');event.stopPropagation();">Oulmes Petit</section><section class="dishprice222" style="display:table-cell;height:100%;vertical-align:middle;min-width:94px;padding-right:20px;text-align:right" onclick="additem('1059','no','99','1','0','340','8333','20.00' ,'Oulmes Petit','0','First','','','0','no','no','No','','no','0','1','Bar','','','0.00');event.stopPropagation();">Dh 20.00</section><section id="mbtndiv" class="mbtndiv" style="display:table-cell;height:100%;vertical-align:middle;width:40px;min-width:40px;"><section style=""></section> </section></section><section id="iddet-8333" class="greybardiv-extnd" style="display:none"><section class="greybar-tab-extnd"><section class="dishimg" style="margin-top:5px;margin-left:5px"><img style="float:left; height:88px" src="http://www.softpointdev.com/images/location_menu_item/4999ca4c72bea2afcf317e431db4907a-.jpg"></section><section class="dishdesc" style="padding-left:115px;"><section class="dishtitle">PLU#295</section><section class="dishingr"></section></section></section> </section><section class="greybardiv" style="marin-bottom:1%;overflow:hidden"><section class="greybar-tab" style="height:35px;display:table"><section class="ibtndiv" onclick="infoitem('8334'); "><img style="vertical-align:middle" src="images/ibtn-slver.png"></section><section class="dishnamew" style=" width:75%;display:table-cell;height:100%;vertical-align:middle;min-width:100%;padding-left:30px;text-align:left" id="valit-8334" onclick="additem('1059','no','99','1','0','340','8334','25.00' ,'Oulmes Grand','0','First','','','0','no','no','No','','no','0','1','Bar','','','0.00');event.stopPropagation();">Oulmes Grand</section><section class="dishprice222" style="display:table-cell;height:100%;vertical-align:middle;min-width:94px;padding-right:20px;text-align:right" onclick="additem('1059','no','99','1','0','340','8334','25.00' ,'Oulmes Grand','0','First','','','0','no','no','No','','no','0','1','Bar','','','0.00');event.stopPropagation();">Dh 25.00</section><section id="mbtndiv" class="mbtndiv" style="display:table-cell;height:100%;vertical-align:middle;width:40px;min-width:40px;"><section style=""></section> </section></section><section id="iddet-8334" class="greybardiv-extnd" style="display:none"><section class="greybar-tab-extnd"><section class="dishimg" style="margin-top:5px;margin-left:5px"><img style="float:left; height:88px" src="http://www.softpointdev.com/images/location_menu_item/87946be991e64c02883d5ae0a1fcd79e-.jpg"></section><section class="dishdesc" style="padding-left:115px;"><section class="dishtitle">PLU#296</section><section class="dishingr"></section></section></section> </section></section></section></section></section></section></section></section></section></section></section></section></section></section></section></section></section></div><section id="gui-1084" class="widgettitle_lgtblue" onclick="opengroup('1084')"><section id="bluebartext" class="bluebartext" style="height:35px;min-height:35px;max-height:35px;overflow:hidden;"><section class="bluebartextcell" style="padding-top:7px;height:35px;min-height:35px;max-height:35px;overflow:hidden;text-align:center;">Les Jus Frais</section></section></section><div id="adg-1084" class="contgroup" style="display:none;border:"><section class="greybardiv" style="marin-bottom:1%;overflow:hidden"><section class="greybar-tab" style="height:35px;display:table"><section class="ibtndiv" onclick="infoitem('8340'); "><img style="vertical-align:middle" src="images/ibtn-slver.png"></section><section class="dishnamew" style=" width:75%;display:table-cell;height:100%;vertical-align:middle;min-width:100%;padding-left:30px;text-align:left" id="valit-8340" onclick="additem('1084','no','99','1','0','340','8340','30.00' ,'Jus Orange','0','First','','','0','no','no','No','','no','0','1','Bar','','','0.00');event.stopPropagation();">Jus Orange</section><section class="dishprice222" style="display:table-cell;height:100%;vertical-align:middle;min-width:94px;padding-right:20px;text-align:right" onclick="additem('1084','no','99','1','0','340','8340','30.00' ,'Jus Orange','0','First','','','0','no','no','No','','no','0','1','Bar','','','0.00');event.stopPropagation();">Dh 30.00</section><section id="mbtndiv" class="mbtndiv" style="display:table-cell;height:100%;vertical-align:middle;width:40px;min-width:40px;"><section style=""></section> </section></section><section id="iddet-8340" class="greybardiv-extnd" style="display:none"><section class="greybar-tab-extnd"><section class="dishimg" style="margin-top:5px;margin-left:5px"><img style="float:left; height:88px" src="http://www.softpointdev.com/images/location_menu_item/9192a606827ef9998656fd869d2c0802-.png"></section><section class="dishdesc" style="padding-left:115px;"><section class="dishtitle">PLU#300</section><section class="dishingr"></section></section></section> </section><section class="greybardiv" style="marin-bottom:1%;overflow:hidden"><section class="greybar-tab" style="height:35px;display:table"><section class="ibtndiv" onclick="infoitem('8341'); "><img style="vertical-align:middle" src="images/ibtn-slver.png"></section><section class="dishnamew" style=" width:75%;display:table-cell;height:100%;vertical-align:middle;min-width:100%;padding-left:30px;text-align:left" id="valit-8341" onclick="additem('1084','no','99','1','0','340','8341','30.00' ,'Jus de Peche','0','First','','','0','no','no','No','','no','0','1','Bar','','','0.00');event.stopPropagation();">Jus de Peche</section><section class="dishprice222" style="display:table-cell;height:100%;vertical-align:middle;min-width:94px;padding-right:20px;text-align:right" onclick="additem('1084','no','99','1','0','340','8341','30.00' ,'Jus de Peche','0','First','','','0','no','no','No','','no','0','1','Bar','','','0.00');event.stopPropagation();">Dh 30.00</section><section id="mbtndiv" class="mbtndiv" style="display:table-cell;height:100%;vertical-align:middle;width:40px;min-width:40px;"><section style=""></section> </section></section><section id="iddet-8341" class="greybardiv-extnd" style="display:none"><section class="greybar-tab-extnd"><section class="dishimg" style="margin-top:5px;margin-left:5px"><img style="float:left; height:88px" src="images/defimgpro.png"></section><section class="dishdesc" style="padding-left:115px;"><section class="dishtitle">PLU#301</section><section class="dishingr"></section></section></section> </section><section class="greybardiv" style="marin-bottom:1%;overflow:hidden"><section class="greybar-tab" style="height:35px;display:table"><section class="ibtndiv" onclick="infoitem('8342'); "><img style="vertical-align:middle" src="images/ibtn-slver.png"></section><section class="dishnamew" style=" width:75%;display:table-cell;height:100%;vertical-align:middle;min-width:100%;padding-left:30px;text-align:left" id="valit-8342" onclick="additem('1084','no','99','1','0','340','8342','30.00' ,'Jus de Citron','0','First','','','0','no','no','No','','no','0','1','Bar','','','0.00');event.stopPropagation();">Jus de Citron</section><section class="dishprice222" style="display:table-cell;height:100%;vertical-align:middle;min-width:94px;padding-right:20px;text-align:right" onclick="additem('1084','no','99','1','0','340','8342','30.00' ,'Jus de Citron','0','First','','','0','no','no','No','','no','0','1','Bar','','','0.00');event.stopPropagation();">Dh 30.00</section><section id="mbtndiv" class="mbtndiv" style="display:table-cell;height:100%;vertical-align:middle;width:40px;min-width:40px;"><section style=""></section> </section></section><section id="iddet-8342" class="greybardiv-extnd" style="display:none"><section class="greybar-tab-extnd"><section class="dishimg" style="margin-top:5px;margin-left:5px"><img style="float:left; height:88px" src="http://www.softpointdev.com/images/location_menu_item/dd85708d12d34c2df2d1d59ef49e7d7f-.png"></section><section class="dishdesc" style="padding-left:115px;"><section class="dishtitle">PLU#302</section><section class="dishingr"></section></section></section> </section><section class="greybardiv" style="marin-bottom:1%;overflow:hidden"><section class="greybar-tab" style="height:35px;display:table"><section class="ibtndiv" onclick="infoitem('8343'); "><img style="vertical-align:middle" src="images/ibtn-slver.png"></section><section class="dishnamew" style=" width:75%;display:table-cell;height:100%;vertical-align:middle;min-width:100%;padding-left:30px;text-align:left" id="valit-8343" onclick="additem('1084','no','99','1','0','340','8343','30.00' ,'Jus de Pompleousse','0','First','','','0','no','no','No','','no','0','1','Bar','','','0.00');event.stopPropagation();">Jus de Pompleousse</section><section class="dishprice222" style="display:table-cell;height:100%;vertical-align:middle;min-width:94px;padding-right:20px;text-align:right" onclick="additem('1084','no','99','1','0','340','8343','30.00' ,'Jus de Pompleousse','0','First','','','0','no','no','No','','no','0','1','Bar','','','0.00');event.stopPropagation();">Dh 30.00</section><section id="mbtndiv" class="mbtndiv" style="display:table-cell;height:100%;vertical-align:middle;width:40px;min-width:40px;"><section style=""></section> </section></section><section id="iddet-8343" class="greybardiv-extnd" style="display:none"><section class="greybar-tab-extnd"><section class="dishimg" style="margin-top:5px;margin-left:5px"><img style="float:left; height:88px" src="http://www.softpointdev.com/images/location_menu_item/810cc9aaaea90b515a2e6f80cdbcb3b3-.png"></section><section class="dishdesc" style="padding-left:115px;"><section class="dishtitle">PLU#303</section><section class="dishingr"></section></section></section> </section><section class="greybardiv" style="marin-bottom:1%;overflow:hidden"><section class="greybar-tab" style="height:35px;display:table"><section class="ibtndiv" onclick="infoitem('8344'); "><img style="vertical-align:middle" src="images/ibtn-slver.png"></section><section class="dishnamew" style=" width:75%;display:table-cell;height:100%;vertical-align:middle;min-width:100%;padding-left:30px;text-align:left" id="valit-8344" onclick="additem('1084','no','99','1','0','340','8344','50.00' ,'Jus de Fruits','0','First','','','0','no','no','No','','no','0','1','Bar','','','0.00');event.stopPropagation();">Jus de Fruits</section><section class="dishprice222" style="display:table-cell;height:100%;vertical-align:middle;min-width:94px;padding-right:20px;text-align:right" onclick="additem('1084','no','99','1','0','340','8344','50.00' ,'Jus de Fruits','0','First','','','0','no','no','No','','no','0','1','Bar','','','0.00');event.stopPropagation();">Dh 50.00</section><section id="mbtndiv" class="mbtndiv" style="display:table-cell;height:100%;vertical-align:middle;width:40px;min-width:40px;"><section style=""></section> </section></section><section id="iddet-8344" class="greybardiv-extnd" style="display:none"><section class="greybar-tab-extnd"><section class="dishimg" style="margin-top:5px;margin-left:5px"><img style="float:left; height:88px" src="http://www.softpointdev.com/images/location_menu_item/ab844f772d319afe744fb6d6e39545b0-.png"></section><section class="dishdesc" style="padding-left:115px;"><section class="dishtitle">PLU#304</section><section class="dishingr"></section></section></section> </section></section></section></section></section></section></div><section id="gui-1057" class="widgettitle_lgtblue" onclick="opengroup('1057')"><section id="bluebartext" class="bluebartext" style="height:35px;min-height:35px;max-height:35px;overflow:hidden;"><section class="bluebartextcell" style="padding-top:7px;height:35px;min-height:35px;max-height:35px;overflow:hidden;text-align:center;">Biéres</section></section></section><div id="adg-1057" class="contgroup" style="display:none;border:"><section class="greybardiv" style="marin-bottom:1%;overflow:hidden"><section class="greybar-tab" style="height:35px;display:table"><section class="ibtndiv" onclick="infoitem('7646'); "><img style="vertical-align:middle" src="images/ibtn-slver.png"></section><section class="dishnamew" style=" width:75%;display:table-cell;height:100%;vertical-align:middle;min-width:100%;padding-left:30px;text-align:left" id="valit-7646" onclick="additem('1057','no','99','1','0','340','7646','18.00' ,'Flag spéciale','0','First','no','no','0','no','no','No','','no','0','1','Bar','','','0.00');event.stopPropagation();">Flag spéciale</section><section class="dishprice222" style="display:table-cell;height:100%;vertical-align:middle;min-width:94px;padding-right:20px;text-align:right" onclick="additem('1057','no','99','1','0','340','7646','18.00' ,'Flag spéciale','0','First','no','no','0','no','no','No','','no','0','1','Bar','','','0.00');event.stopPropagation();">Dh 55.00</section><section id="mbtndiv" class="mbtndiv" style="display:table-cell;height:100%;vertical-align:middle;width:40px;min-width:40px;"><section style=""></section> </section></section><section id="iddet-7646" class="greybardiv-extnd" style="display:none"><section class="greybar-tab-extnd"><section class="dishimg" style="margin-top:5px;margin-left:5px"><img style="float:left; height:88px" src="http://www.softpointdev.com/images/location_menu_item/a848055dfae88a47b5c7c748c9baf722-.jpg"></section><section class="dishdesc" style="padding-left:115px;"><section class="dishtitle">PLU#153</section><section class="dishingr"></section></section></section> </section><section class="greybardiv" style="marin-bottom:1%;overflow:hidden"><section class="greybar-tab" style="height:35px;display:table"><section class="ibtndiv" onclick="infoitem('7642'); "><img style="vertical-align:middle" src="images/ibtn-slver.png"></section><section class="dishnamew" style=" width:75%;display:table-cell;height:100%;vertical-align:middle;min-width:100%;padding-left:30px;text-align:left" id="valit-7642" onclick="additem('1057','no','99','1','0','340','7642','55.00' ,'Heineken','0','First','no','no','0','no','no','No','','no','0','1','Bar','','','0.00');event.stopPropagation();">Heineken</section><section class="dishprice222" style="display:table-cell;height:100%;vertical-align:middle;min-width:94px;padding-right:20px;text-align:right" onclick="additem('1057','no','99','1','0','340','7642','55.00' ,'Heineken','0','First','no','no','0','no','no','No','','no','0','1','Bar','','','0.00');event.stopPropagation();">Dh 55.00</section><section id="mbtndiv" class="mbtndiv" style="display:table-cell;height:100%;vertical-align:middle;width:40px;min-width:40px;"><section style=""></section> </section></section><section id="iddet-7642" class="greybardiv-extnd" style="display:none"><section class="greybar-tab-extnd"><section class="dishimg" style="margin-top:5px;margin-left:5px"><img style="float:left; height:88px" src="http://www.softpointdev.com/images/location_menu_item/8888a6efa07d3238748c68ad57ac6508-.jpg"></section><section class="dishdesc" style="padding-left:115px;"><section class="dishtitle">PLU#149</section><section class="dishingr"></section></section></section> </section><section class="greybardiv" style="marin-bottom:1%;overflow:hidden"><section class="greybar-tab" style="height:35px;display:table"><section class="ibtndiv" onclick="infoitem('7643'); "><img style="vertical-align:middle" src="images/ibtn-slver.png"></section><section class="dishnamew" style=" width:75%;display:table-cell;height:100%;vertical-align:middle;min-width:100%;padding-left:30px;text-align:left" id="valit-7643" onclick="additem('1057','no','99','1','0','340','7643','50.00' ,'Casablanca','0','First','no','no','0','no','no','No','','no','0','1','Bar','','','0.00');event.stopPropagation();">Casablanca</section><section class="dishprice222" style="display:table-cell;height:100%;vertical-align:middle;min-width:94px;padding-right:20px;text-align:right" onclick="additem('1057','no','99','1','0','340','7643','50.00' ,'Casablanca','0','First','no','no','0','no','no','No','','no','0','1','Bar','','','0.00');event.stopPropagation();">Dh 60.00</section><section id="mbtndiv" class="mbtndiv" style="display:table-cell;height:100%;vertical-align:middle;width:40px;min-width:40px;"><section style=""></section> </section></section><section id="iddet-7643" class="greybardiv-extnd" style="display:none"><section class="greybar-tab-extnd"><section class="dishimg" style="margin-top:5px;margin-left:5px"><img style="float:left; height:88px" src="http://www.softpointdev.com/images/location_menu_item/9389bacefed658adcf4fb3d5a322f67a-.jpg"></section><section class="dishdesc" style="padding-left:115px;"><section class="dishtitle">PLU#150</section><section class="dishingr"></section></section></section> </section><section class="greybardiv" style="marin-bottom:1%;overflow:hidden"><section class="greybar-tab" style="height:35px;display:table"><section class="ibtndiv" onclick="infoitem('8106'); "><img style="vertical-align:middle" src="images/ibtn-slver.png"></section><section class="dishnamew" style=" width:75%;display:table-cell;height:100%;vertical-align:middle;min-width:100%;padding-left:30px;text-align:left" id="valit-8106" onclick="additem('1057','no','99','1','0','340','8106','60.00' ,'Budweiser','0','First','no','no','0','no','no','No','','no','0','1','Bar','','','0.00');event.stopPropagation();">Budweiser</section><section class="dishprice222" style="display:table-cell;height:100%;vertical-align:middle;min-width:94px;padding-right:20px;text-align:right" onclick="additem('1057','no','99','1','0','340','8106','60.00' ,'Budweiser','0','First','no','no','0','no','no','No','','no','0','1','Bar','','','0.00');event.stopPropagation();">Dh 60.00</section><section id="mbtndiv" class="mbtndiv" style="display:table-cell;height:100%;vertical-align:middle;width:40px;min-width:40px;"><section style=""></section> </section></section><section id="iddet-8106" class="greybardiv-extnd" style="display:none"><section class="greybar-tab-extnd"><section class="dishimg" style="margin-top:5px;margin-left:5px"><img style="float:left; height:88px" src="http://www.softpointdev.com/images/location_menu_item/c50e558569355dad546aac3c2f876331-.jpg"></section><section class="dishdesc" style="padding-left:115px;"><section class="dishtitle">PLU#239</section><section class="dishingr"></section></section></section> </section></section></section></section></section></div></div> -->
								            </div>
								            </div>
								            <!--end e-8-->
								            <div id="e-9" class="ui-tabs-panel ui-widget-content ui-corner-bottom ui-tabs-hide" style="height: 693px; overflow-y: hidden;">
								            	<div id="d-9">
								            	 <div style="width:100%; float:left;">
								                        <input type="hidden" id="methodofpayment-input" placeholder="cash" class="input-block-level">
								                        <select id="methodofpayment" name="methodofpayment" onchange="payment(this.value);" class="select1"><option value="Adjustments">Adjustments</option><option value="Cash">Cash</option><option value="Credit Card">Credit Card</option><option value="Debit Card">Debit Card</option><option value="Gratuity">Gratuity</option><option value="Interface">Interface</option><option value="Surcharge">Surcharge</option><option value="Receivables">Receivables</option><option value="Clover">Clover</option></select>

								                        <input id="vlpayment_code" type="hidden" class="hidden_textfield2 input-block-level" style="direction:ltr !important;text-align:right" placeholder="" value="">
								                        <div id="paymenttype" style="width: 100%; float: left; display: block;">
								                        <select name="pymntyp" id="pymntyp" class="select1" title="Type" onchange="paymentcode(this.value)"><option data-processor="No" value="223,Cash,">Cash</option><option data-processor="No" value="237,PayPal,">PayPal</option><option data-processor="No" value="256,Transfer,Transfer"> Transfer</option><option data-processor="No" value="238,Western Union,">Western Union</option></select>
								                        </div>
								                    </div>

								                    <div id="receivable" style="display:none; width:100%; float:left;">
								                        	<table width="100%" border="0" cellpadding="0" cellspacing="0">
								                                <tbody><tr>
								                                    <td>
								                                    <table width="100%" border="0">
								                                            <tbody><tr>
								                                                <td width="95%">
								                                                    <input name="txtcompany" id="txtcompany" type="text" class="input-block-level" value="" placeholder="Name" title="Name" style="text-align:right; min-height:30px;">
								                                                    <input type="hidden" id="company_id" value="">
								                                                </td>
								                                                <td class="addminusarrow" width="5%;"><img name="btnSearchAffiliate" onclick="getCompanydetail();" style="cursor:pointer; padding-left:5px;" src="images/searchiocn.png"></td>
								                                            </tr>
								                                        </tbody></table>
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
								                            </tbody></table>
								                        </div>

								                    <div id="btnauthsale" style="display: none; width: 100%; float: left; margin: 15px 0px;">
								                    	<center>
								                        <button class="auth" id="abc-autho" style=" padding:5px 30px;border:0;" onclick="payment2a('autho')">Authorize</button>
								                        <button class="sale-in" style="width:120px;padding:5px 30px;border:0;" id="abc-salefn" onclick="payment2a('salefn')">Sale</button>
								                        </center>
								                    </div>

								                    <div id="btnauthsaleht" style="display:none; width:95%;float:left; margin:15px 0px;">
								                    	<center>
								                        <button class="btn btn-primary btn-large auth" id="abc-autho-ht" style=" padding:5px 30px;border:0;" onclick="payment('autho-ht')">Authorize</button>
								                        <button class="btn btn-primary btn-large" id="abc-salefn-ht" style="width:120px;padding:5px 30px;border:0;" onclick="payment('salefn-ht')">Sale</button>
								                        </center>
								                    </div>

								                    <div id="conthotelauth" class="lftform" style="border-bottom: 1px solid rgb(150, 150, 150); height: 100px; display: none;"></div>


								                    <div id="visaamxbtns" style="display:none; width:100%; float:left; margin-bottom:10px;">
								                        <div id="content_autho" class="lftform" style="border-bottom:1px solid #969696; height:auto;"></div>
								                    </div>

								                    <div id="txtsmall" style="display:none;width:100%; float:left;">
								                          <div id="fku" class="lftform" style=" width:97.4%;margin-left:2%;margin-right:3%;position:relative;left:-8px">
								                            <div class="clientname-form" style="height:40px; width:100%; float:left;">
								                              <div class="clientemail_lft2 form-text-lft" style=" width:50%;float:left;"> Payment Type </div>
								                              <div id="cctxt_payment_code" style="width:30%;float:right; text-align:right;" class="clientemail_rit-ful form-text-rit"></div>
								                            </div>
								                            <div class="clientname-form" style="height:40px; width:100%; float:left;">
								                              <div class="clientemail_lft2 form-text-lft" style="width:auto; float:left;"> CC Number </div>
								                              <div id="cctxt_cc_number" style="line-height:25px;float:right; text-align:right;" class="clientemail_rit-ful form-text-rit"></div>
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
								                              <div class="clientemail_lft2 form-text-lft" style="float:left;width:100px;">
								                              		<span>CVV</span>
								                                    <span id="cctxt_cc_ccv" style="color:black; font-size:14px; text-align:left; padding-left:10px; font-weight:bold; line-height:32px;"></span>
								                              </div>
								                            </div>
								                            <div class="clientname-form" style="height:40px;width:40%;float:left">
								                              <div class="clientemail_lft2 form-text-lft" style="direction:ltr;text-align:left;width:auto">
								                               		<span>Authorization</span>
								                                    <span id="cctxt_cc_authorization" style="color:black; font-size:14px; text-align:left; padding-left:10px; font-weight:bold; line-height:32px;"></span>
								                             </div>
								                            </div>
								                          </div>
								                        </div>

								                    <div id="fku2">
								                        <div id="ccnum" style="display: none; width: 100%; float: left;">
								                            <section id="maskcard" style="position: relative; background-color: white; top: 25px; font-size: 15px; float: right; right: 38px; display: none;">xxxx-xxxx-xxxx-</section>
								                            <input type="text" name="first_name_cc" id="first_name_cc" class="input-block-level" placeholder="First Name" value="" style="width:49%;min-height:30px; text-align:right"><input type="text" name="last_name_cc" id="last_name_cc" class="input-block-level" value="" style="width:49%;min-height:30px; text-align:right; margin-left:2%;" placeholder="Last Name">
								                            <input tabindex="1" name="pay_cc_number" id="pay_cc_number" type="text" class="input-block-level" value="" placeholder="CC Number" style="min-height:30px; text-align:right;" onblur="checkcctypebynumber();">

								                            <input id="pay_cc_name" class="txtfld_clientname form-text-rit" name="" type="hidden" placeholder="">
								                            <input id="swiped" class="txtfld_clientname form-text-rit" name="" type="hidden" placeholder="" value="">
								                                    <input type="hidden" id="payid" name="payid" value="">
								                                <input type="hidden" name="monthselected" id="monthselected" onfocus="$('#expmnth').click();" value="">
								                            <select name="expmnth" id="expmnth" class="input-block-level" title="Exp. Month" style=" width:49%; float:left;min-height:30px;" onfocus="$('#expmnth').click();" onchange="$('#monthselected').val(this.value)"><option value="">Exp. Month</option><option value="01">01</option><option value="02">02</option><option value="03">03</option><option value="04">04</option><option value="05">05</option><option value="06">06</option><option value="07">07</option><option value="08">08</option><option value="09">09</option><option value="10">10</option><option value="11">11</option><option value="12">12</option></select>
								                                <input style="width:30%" tabindex="99" type="hidden" class="hidden_textfield2" placeholder="" id="yearselected" onfocus="$('#year').click();" readonly="" value="2017">
								                            <select name="year" id="year" class="input-block-level" title="Year" style="width:49%; min-height:30px; float:right;" onchange="$('#yearselected').val(this.value)"><option value="2017">2017</option><option value="2018">2018</option><option value="2019">2019</option><option value="2020">2020</option><option value="2021">2021</option><option value="2022">2022</option><option value="2023">2023</option><option value="2024">2024</option><option value="2025">2025</option><option value="2026">2026</option><option value="2027">2027</option><option value="2028">2028</option><option value="2029">2029</option><option value="2030">2030</option><option value="2031">2031</option><option value="2032">2032</option><option value="2033">2033</option><option value="2034">2034</option><option value="2035">2035</option><option value="2036">2036</option><option value="2037">2037</option><option value="2038">2038</option><option value="2039">2039</option><option value="2040">2040</option><option value="2041">2041</option><option value="2042">2042</option><option value="2043">2043</option><option value="2044">2044</option><option value="2045">2045</option><option value="2046">2046</option></select>
								                             <input tabindex="1" maxlength="4" name="cc_ccv" id="cc_ccv" type="text" class="input-block-level" value="" placeholder="CVV" onkeyup="forcanumero(this.id)" style="min-height:30px; text-align:right;" onfocus="checkcvvsize();">
								                        </div>

								                        <div class="testwidth2" style="height:auto;">
								                            <div id="containerseats" style="width:100%;height:auto;margin-bottom:10px;"> </div>
								                        </div>

								                        <div id="amountdue" style="display: block; width: 100%; float: left;">
								                        <table width="100%" border="0" cellpadding="0" cellspacing="0">
								                            <tbody><tr><td align="center" style="text-align:left;"> Amount Due</td>
								                            <td style="text-align:right;" id="vlamountdue">Dh 0.00</td></tr>
								                        </tbody></table>
								                        </div>

								                        <div id="gratuitya" style="display: none; width: 100%; float: left;">
								                            <input id="gratuity2a" class="input-block-level" name="" type="text" value="" onkeyup="calcpercad2(this.value);calculatepay()" placeholder="Percentage Of Gratuity" style="min-height:30px; text-align:right;" onblur="calctotcar()">
								                        </div>

								                        <div id="gratuity" style="display:block; width:100%; float:left;">
								                            <input name="gratuity2" id="gratuity2" type="text" class="input-block-level" onkeyup="limpareceived();" onblur="calctotcar()" onfocus="this.select();" value="Gratuity" placeholder="Gratuity" style=" min-height:30px;text-align:right;">
								                        </div>

								                        <div id="htt_amount" style="display: none; width: 100%; float: left;">
								                            <input id="ht_amount" class="input-block-level" name="" type="text" value="" placeholder="Amount" style="min-height:30px; text-align:right;">
								                        </div>

								                        <div id="htt_client_listsc" class="txtfld_div_clintname_dropd" style="display:block; width:100%; float:left;display:none"></div>

								                        <div id="htt_client_room" style="display: none; width: 100%; float: left;">
								                            <input id="ht_client_room" class="input-block-level" name="" type="text" placeholder="Client Room" style="min-height:30px; text-align:right;" onkeyup="if (event.keyCode == 8){devapaht=true}" onblur="if(this.value != ''){tpkh=1;showmessageroom=true;findroom()}else{clearho()}" value="">
								                        </div>

								                        <div id="htt_client_name" style="display: none; width: 100%; float: left;">
								                            <input id="ht_client_name" class="input-block-level" name="" type="text" placeholder="Client Name" value="" style="min-height:30px;text-align:right;" onkeyup="if (event.keyCode != 8){tpkh=0;findrooml()}else{devapaht=true}" onblur="if(this.value != ''){tpkh=2;showmessageroom=true;findroom()}else{clearho()}">
								                        </div>

								                        <div id="htt_client_account" style="display: none; width: 100%; float: left;">
								                            <input id="ht_client_account" class="input-block-level" name="" type="text" placeholder="Client Account" style="min-height:30px;text-align:right;" value="" onkeyup="if (event.keyCode == 8){devapaht=true}" onblur="if(this.value != ''){tpkh=3;showmessageroom=true;findroom()}else{clearho()}">
								                        </div>

								                        <div id="gift" class="clientname-form" style="width: 100%; float: left; display: none;">
								                            <input name="giftnumber" id="giftnumber" type="text" class="input-block-level" onblur="getamountgift();" onclick="this.select();" value="" placeholder="Gift Certificate Number" style="min-height:30px;text-align:right;">
								                            <table width="100%" border="0" cellpadding="0" cellspacing="0">
								                                <tbody><tr>
								                                    <td align="center" style="text-align:left;"> Balance</td>
								                                    <td style="text-align:right;" id="blbalance"></td>
								                                </tr>
								                            </tbody></table>
								                        </div>



								                        <div id="payment" class="clientname-form" style="width: 100%; float: left; display: block;">
								                            <input tabindex="1" name="vlpayment" id="vlpayment" type="text" class="input-block-level" value="" placeholder="Payment" style="min-height:30px; text-align:right;" onkeyup="calccashtps()" onclick="this.select();" onblur="ccck(this.id);">
								                        </div>

								                        <div id="btnsrechng" style="display: block; width: 100%; float: left;">
								                        	<center>
								                                <div id="sawtle" class="buttonsmid" style="margin: 10px; display: block;"><button id="female" style="width:100px;padding:7px 8px;" class="btn btn-primary btn-large" onclick="dvlor(this.id);">Dh 0.00</button> <button style="width:100px;padding:7px 8px;" id="male" class="btn btn-primary btn-large" onclick="dvlor(this.id);">Dh 10.00</button> <button id="child" style="width:100px;padding:7px 8px;" class="btn btn-primary btn-large" onclick="dvlor(this.id);">Dh 20.00</button> <button id="child2" style="width:100px;padding:7px 8px;" class="btn btn-primary btn-large " onclick="dvlor(this.id);">Dh 30.00</button></div>
								                            </center>
								                            <input id="received" class="input-block-level" name="" type="text" placeholder="0.00" style="min-height:30px; text-align:right;text-align:right;direction:ltr" onkeyup="calccash()" onfocus="this.select()" onblur="calccashupdchange()">
								                            <table width="100%" border="0" cellpadding="0" cellspacing="0" style="margin-bottom:10px;">
								                                <tbody><tr>
								                                    <td align="center" style="text-align:left;">Change</td>
								                                    <td style="text-align:right;" id="vlchange"> 5.00 </td>
								                                </tr>
								                            </tbody></table>
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



								                        <div id="mannualautho" style="display: none; width: 100%; float: left;">
								                            <input tabindex="1" name="received2" id="received2" type="text" class="input-block-level" value="" placeholder="Manual Authorization" style="min-height:30px; text-align:right;">
								                        </div>

								                        <div id="totalkjhg" style="display:none; width:100%; float:left;">
								                            <input id="totalk" class="input-block-level" readonly="" name="" type="text" placeholder="0.00" value="0.00">
								                        </div>

								                        <div id="prcntadjsmntamnt" style="display:none; width:100%; float:left;">
								                             <input name="poaa" id="poaa" type="text" onkeyup="if(this.value > 100){this.value=''}calcpercad(this.value)" class="input-block-level" value="" placeholder="Percentage of Adjustments Amount" style="min-height:30px;text-align:right;">
								                        </div>

								                        <div id="amount1" style="display:none; width:100%; float:left;">
								                        	<input id="receivedam" class="input-block-level txtfld_clientname form-text-rit" name="" placeholder="Amount" type="text" value="" onblur="ccck(this.id);" onfocus="this.select();" onclick="this.select();" style="min-height:30px; text-align:right;" onkeyup="calcamountxbx(this);calcpereverse(this.value);">
								                        </div>

								                        <div id="totalamount" style="display:none; width:100%; float:left;">
								                            <input name="vtotalamount" id="amt" type="text" class="input-block-level" value="" placeholder="Total Amount" style="min-height:30px; text-align:right;">
								                        </div>

								                        <div id="clintexpntabid" style="display:none; width:100%; float:left;">
								                            <input name="clientemail" id="clientemail" type="text" class="input-block-level" value="" placeholder="Client" onblur="checkemailet(this.value,true);" style="min-height:30px; text-align:right;">
								                            <input name="clientetac" id="clientetac" type="text" class="input-block-level" value="" placeholder="ExpenseTAB Client ID" style="min-height:30px; text-align:right;">
								                        </div>

								                        <div id="descriptiontxtarea" style="display:none; width:100%; float:left;">
								                             <textarea name="desc" id="desc" class="input-block-level" rows="5" style="min-height:30px; text-align:right;" placeholder="Description"></textarea>
								                        </div>



								                        <button id="scanepntabgreenbtn" class="btn btn-primary btn-large" style="width: 100%; float: left; text-align: center; background-color: rgb(0, 153, 0); border-color: rgb(0, 153, 0); margin-bottom: 5px; padding: 2px; display: none;">
								                            <table width="100%" border="0" cellpadding="0" cellspacing="0">
								                                <tbody><tr>
								                                    <td style="text-align:right; vertical-align:middle;width:40%;">
								                                        <img src="images/scan.png" width="25" style="margin:0; padding:0;">
								                                    </td>
								                                    <td style="text-align:left; vertical-align:middle;width:60%;">&nbsp;Scan ExpenseTAB QR Code For Easy Entry </td>
								                                 </tr>
								                            </tbody></table>
								                        </button>
								                    </div>

								                    <div id="kjhkjhdaadsda" style="display:none;position:relative;width:100%;height:1px;min-width:100%"><br></div>

								                    <button id="sbmtpaymntbluebtn" style="width: 100%; text-align: center; margin-top: 10px; padding: 2px; display: block;" class="btn btn-primary btn-large" onclick="submitpayments();">
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

                <br /><br />



                    <?php include_once 'includes/footer.php';?>
                <!--footer-->
							</div>
            </div><!--maincontentinner-->
        </div><!--maincontent-->

    </div><!--rightpanel-->

</div><!--mainwrapper-->

<div id="edit_item_modal" class="modal hide fade" style="width:530px;max-height:500px">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal"
			aria-hidden="true">&times;</button>
		<h3>Edit Menu Item</h3>
	</div>
	<div class="modal-body">
	</div>
   <div class="modal-footer" style="text-align:center;">
        <button aria-hidden="true" data-dismiss="modal" class="btn " onClick="resetBtn()">Cancel</button>
        <button class="btn btn-primary" onClick="jQuery('#menuform').submit();"><span class="icon-ok icon-white"></span> Save Changes</button>
    </div>
</div>

<div id="edit_modifier_modal" class="modal hide fade" style="width:750px;">
	<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal"
			aria-hidden="true">&times;</button>
		<h3>Add/Edit Menu Group</h3>
	</div>
	<div class="modal-body" style="max-height: 450px;">
    <div id="loading-header">
        <img id="loading-image-header" src="images/loaders/loader7.gif" alt="Loading..." />
    </div>
	</div>
   <div class="modal-footer" style="text-align:center;">
        <button aria-hidden="true" data-dismiss="modal" class="btn ">Cancel</button>
        <button class="btn btn-primary" onClick="menuValidate();" id="save_imgdigital"><span class="icon-ok icon-white"></span> Save Changes</button>
    </div>
</div>



<div id="imageModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="width: auto;">
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
<h3 id="myModalLabel">Add/Edit Media</h3>
</div>

<div class="modal-body " id="mymodalhtml">
</div>

<div class="modal-footer" style="text-align:center;">
<button aria-hidden="true" data-dismiss="modal" class="btn ">Cancel</button>
<button class="btn btn-primary" id="save_img"><span class="icon-ok icon-white"></span> Save Changes</button>
</div>

</div>

<div id="videoModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="width: auto;">
    <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
        <h3 id="myModalLabel">Add/Edit Video</h3>
    </div>
    <div class="modal-body " id="mymodalhtml">

    </div>

    <div class="modal-footer" style="text-align:center;">
        <button aria-hidden="true" data-dismiss="modal" class="btn ">Cancel</button>
        <button class="btn btn-primary" id="save_viedodigital"><span class="icon-ok icon-white"></span> Save Changes</button>
    </div>
</div>



</body>
</html>
<script type="text/javascript" src="simpleautocomplete/js/simpleAutoComplete.js"></script>
<link rel="stylesheet" type="text/css" href="simpleautocomplete/css/simpleAutoComplete.css"/>

<script type="text/javascript">
	var menu_id = '<?php echo $menu; ?>';

    var newArticle = true;
    var stop = false;

    jQuery(document).ready(function () {



jQuery("#accordion").accordion({
    collapsible: true,
        active: true,
        heightStyle: "content",
        header: 'h3'
    })
    .sortable({
    items: '.s_panel',
    stop: function( event, ui ) {
        var items=[];
        ui.item.siblings().andSelf().each( function(){
               items.push(jQuery(this).data('id'));
        });
        console.log(items);
        jQuery.ajax({
            url:"ajax-update-menu-groups-priority.php?groups=" + items,
           // dataType:'json',
            success:function (item) {

            }
        })
    }
	});
	jQuery('#accordion').find('div[id*="ac_menugroup_<?php echo $menu_id; ?>"]').closest('h3').trigger('click');
	jQuery('#accordion').find('ac_menugroup_<?php echo $menu_id; ?>').closest('h3').trigger('click');
//jQuery('.accordion-toggle').mouseover(function(){
//    jQuery( this ).click();
//});

jQuery('#accordion').on('accordionactivate', function (event, ui) {
        if (ui.newPanel.length) {
           jQuery('#accordion').sortable('disable');
        } else {
            jQuery('#accordion').sortable('enable');
        }
    });


        jQuery('#ccitem').simpleAutoComplete('ajax_article_query.php?menu_id='+menu_id, {
            autoCompleteClassName:'autocomplete',
            selectedClassName:'sel',
            attrCallBack:'rel',
            extraParamFromInput:'#group_name',
            identifier:'article'
        }, itemCallback);
        jQuery('#item_group').change(function(){
            jQuery.get('ajax-menu-group-priority.php?menu=<?=$menu?>&group=' + jQuery(this).val(),function(data){
                jQuery('#item_priority').val(data);
            });
        });
        jQuery('#ccitem').blur(function(){
            if(newArticle){
                jQuery.get('ajax-menu-article-priority.php',function(data){
                    jQuery('#ccpriority').val(data);
                });
            }
        });
        if(jQuery('#item_group').val() != '' && jQuery('#item_priority').val() == ''){
            jQuery('#item_group').change();
        }
        <?php if($_GET['idads'] != ''){ ?>
            jQuery('.art').prop('disabled',true);
        <?php }?>

    });

    function clearField() {
		jQuery('#item_priority').val('');
        jQuery('#item_price').val('');
        jQuery('#ccitem').prop('readonly',false).val('');
        jQuery('#ccpriority').prop('disabled',false).val('');
        jQuery('#cctaxable').prop('disabled',false).val('');
        jQuery('#ccprice').prop('disabled',false).val('');
        jQuery('#ccmax_quantity').prop('disabled',false).val('');
        jQuery('#cctogo').prop('disabled',false).val('');
        jQuery('#ccdelivery').prop('disabled',false).val('');
        jQuery('#ccrequire_temperature').prop('disabled',false).val('');
        jQuery('#ccdrink').prop('disabled',false).val('');
        jQuery('#ccglass').prop('disabled',false).val('');
        jQuery('#ccglass_price').prop('disabled',false).val('');
        jQuery('#ccglass_price2').prop('disabled',false).val('');
        jQuery('#ccdivide').prop('disabled',false).val('');
        jQuery('#ccmax_divide').prop('disabled',false).val('');
        jQuery('#ccfire_order').prop('disabled',false).val('');
        jQuery('#ccsides').prop('disabled',false).val('');
        jQuery('#ccrefills').prop('disabled',false).val('');
        jQuery('#printer_id').prop('disabled',false).val('');
        jQuery('#ccdescription').prop('disabled',false).val('');
        jQuery('#video').prop('disabled',false).val('');
        jQuery('#image').prop('disabled',false).val('');
		jQuery('#ccplu').prop('disabled',false).val('');
		jQuery("#imagebox").html('');
		jQuery("#video_canvas").html('');
        newArticle = true;
    }
    function itemCallback(par) {
        newArticle = false;
        jQuery("#ccitem").val((par[1]));
        jQuery.ajax({
            url:"getArticleData.php?item=" + encodeURIComponent(par[1]) + "&sid=" + Math.random() + "&loc=<?=$_SESSION['loc'];?>",
            dataType:'json',
            success:function (item) {
                if(jQuery('#item_price').val() == ''){
                    jQuery('#item_price').val(item.price);
                }
		//		jQuery("#item_priority").val(item.priority);//.prop('readonly',true);
                jQuery("#ccitem").prop('readonly',true);
				jQuery('#ccplu').val(item.plu).prop('disabled',true);
                jQuery('#ccpriority').val(item.priority).prop('disabled',true);
                jQuery('#ccprice').val(item.price).prop('disabled',true);
                jQuery('#cctaxable').val(item.taxable).prop('disabled',true);
                jQuery('#ccmax_quantity').val(item.max_quantity).prop('disabled',true);
                jQuery('#cctogo').val(item.togo).prop('disabled',true);
                jQuery('#ccdelivery').val(item.delivery).prop('disabled',true);
                jQuery('#ccrequire_temperature').val(item.require_temperature).prop('disabled',true);
                jQuery('#ccdrink').val(item.drink).prop('disabled',true);
                jQuery('#ccglass').val(item.glass).prop('disabled',true);
                jQuery('#ccglass_price').val(item.glass_price).prop('disabled',true);
                jQuery('#ccglass_price2').val(item.glass_price).prop('disabled',true);
                jQuery('#ccdivide').val(item.divide).prop('disabled',true);
                jQuery('#ccmax_divide').val(item.max_divide).prop('disabled',true);
                jQuery('#ccfire_order').val(item.fire_order).prop('disabled',true);
                jQuery('#ccsides').val(item.sides).prop('disabled',true);
                jQuery('#ccrefills').val(item.Refills).prop('disabled',true);
                jQuery('#printer_id').val(item.printer_id).prop('disabled',true);
                jQuery('#ccdescription').val(item.description).prop('disabled',true);

				if(item.image!=null && item.image!="")
		{

			jQuery('#old_image').val(item.image);
			jQuery("#imagebox").html('<img src="<?php echo APIIMAGE;?>images/'+item.image+'" width="100px;">');
		}
		else
		{
			jQuery("#imagebox").html('');
		}
		if(item.video!=null && item.video!="")
		{
			jQuery('#old_video').val(item.video);
			var video='<object id="player" classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" name="player" >';
                video+=' <param name="movie" value="player.swf"/>';
                video+='   <param name="allowfullscreen" value="true"/>';
            	video+='    <param name="allowscriptaccess" value="always"/>';
            	video+='    <param name="wmode" value="opaque"/>';
            	video+='    <param name="flashvars" value="file=<?php echo APIIMAGE; ?>images/'+item.video+'"/>';
           		video+='     <embed type="application/x-shockwave-flash" id="player2" name="player2" src="player.swf" allowscriptaccess="always" allowfullscreen="true" flashvars="file=<?php echo APIIMAGE; ?>images/'+item.video+'"/>';
           		video+='    </object>';
				jQuery("#video_canvas").html(video);
		}
		else
		{
			jQuery("#video_canvas").html('');
		}


                jQuery.uniform.update();
            },error:function(t,s,p){
                console.log(t);
                console.log(s);
                console.log(p);
            }
        });
    }
</script>
<script>
var codeid=0;
var cObject="";

function DeleteMenuGroup(menugrpid){
	jConfirm("Would you like to remove this group?","Confirm Dialog",function(r){
		if(r){
			jQuery.ajax({
				url:'setup_rest_menu_live_page.php',
				type:'POST',
				data:{action:'delete',menugrp_id:menugrpid, menu_id: menu_id },
				success:function(data){
					if(data=='yes'){
						jAlert("Group Deleted successfully!","Alert Dialog",function(r){
							if(r){
								jQuery("#btn_submit").addClass("btn-active");
								window.location.reload();
								//jQuery("#del_"+menugrpid).fadeOut('slow');
							}
						});
					}else{
						jAlert('Can not delete this Group','Alert Dialog');
					}
				}
			});
		}
	});
}


function resetBtn()
{

        if(codeid==0)
	{
		document.getElementById("menuform").reset();
	}
	else
	{
		/*jConfirm('Do you want to discard your changes?', 'Confirm', function(r) {
			if (r)
			{
				clearForm();
			}
                });*/

	}
}
function loadData(cObject){
	  jQuery("#menu_item").val(cObject.data("id"));
	   jQuery("#item_group").val(cObject.data("menu_id"));
	   jQuery("#item_priority").val(cObject.data("priority"));
	   jQuery("#item_price").val(cObject.data("price"));
		codeid=cObject.data("id");
		var myarray = new Array();
	   myarray[0]=cObject.data("item_id");
	   myarray[1]=cObject.data("id");
	  itemCallback(myarray);
}
function Confirm(){
		jConfirm("This Menu Article is already offered on this Menu Would you like to continue?", 'Confirmation Dialog', function(r) {
							if(r)
							{

							}else{
								clearField();
							}
					});

}

 jQuery(document).ready(function(){
     jQuery('#videoModal').on('hidden', function() {
    jQuery(this).removeData('modal');
});
  jQuery('#imageModal').on('hidden', function() {
    jQuery(this).removeData('modal');
});


    jQuery(".codedata").click(function(){
	jQuery('.line3').removeClass('line3');
	var id = jQuery(this).data('id');
	jQuery('#'+id).addClass('line3');

	jQuery("#ResetButton").hide();

		cObject=jQuery(this);
	    loadData(cObject);




    });

    jQuery(".addmenu").click(function(){

	jQuery("#ResetButton").show();
		 codeid=0;
		 cObject="";
	    jQuery("#menu_item").val("");
		jQuery("#item_group").val(jQuery(this).data("id"));

    })



			 jQuery(".deletemenu1").live("click",function(){

			 		var id=jQuery(this).data("id");
					var ths = jQuery(this);
			 		jConfirm("Are you sure you want to delete this menu item from the menu ?", 'Confirmation Dialog', function(r) {
							if(r)
							{
								//window.location="delete_menu_items.php?menu=<?=$menu?>&id="+id;

								var item_ids = jQuery("#hidItemIds").val();
								var menugroupid = jQuery(ths).attr('data-menugroupid');
								var itemId = jQuery(ths).attr('data-id');
								var string = itemId+'|'+ menugroupid + ",";
								console.log(string);
								if(item_ids!=''){
									item_ids = item_ids.toString().replace(string,'');
									console.log(item_ids);
									jQuery("#hidItemIds").val(item_ids);

								}


								jQuery(ths).closest('tr').remove();
								jAlert("Item Deleted successfully!","Alert Dialog");
								//jQuery("#hidItemIds").val(obj.data("id")+'|'+ menugroupid + "," + jQuery("#hidItemIds").val());


							}
					});
			 });
	 });
  function itemtoInactive(id){
  			jConfirm("This menu item has been used before. Are you sure you want to remove it from the menu?", 'Confirmation Dialog', function(r) {
					if(r)
					{
						//window.location="delete_menu_items.php?menu=<?=$menu?>&id="+id;
						jQuery.ajax({
							url:'delete_menu_items.php?menu=<?=$menu?>&id='+id+'&type=Inactive',
							type:'POST',
							success:function(data){
								if(data){
									jAlert("Item Deleted successfully!","Alert Dialog");
									jQuery("#delete_items_"+id).css('display','none');
								}else{
									jAlert("Item Can not be deleted This time","Alert Dialog");
								}
							}
						});

					}
			});
  }

  function itemtodelete(id){
        jConfirm("This menu item has been used before. Are you sure you want to remove it from the menu?", 'Confirmation Dialog', function(r) {
					if(r)
					{
						//window.location="delete_menu_items.php?menu=<?=$menu?>&id="+id;
						jQuery.ajax({
							url:'delete_menu_items.php?menu=<?=$menu?>&id='+id,
							type:'POST',
							success:function(data){
								if(data){
									jAlert("Item Deleted successfully!","Alert Dialog");
									jQuery("#delete_items_"+id).css('display','none');
								}else{
									jAlert("Item Can not be deleted This time","Alert Dialog");
								}
							}
						});

					}
			});
  }
</script>
