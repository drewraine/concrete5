<? 
defined('C5_EXECUTE') or die(_("Access Denied."));
$btl = $a->getAddBlockTypes($c, $ap );
$blockTypes = $btl->getBlockTypeList();
$ci = Loader::helper('concrete/urls');
$ch = Loader::helper('concrete/interface');
$form = Loader::helper('form');

?>

<script type="text/javascript" src="<?=ASSETS_URL_JAVASCRIPT?>/quicksilver.js"></script>
<script type="text/javascript" src="<?=ASSETS_URL_JAVASCRIPT?>/jquery.liveupdate.js"></script>


<script type="text/javascript">
<? if (ENABLE_MARKETPLACE_SUPPORT) { ?>

ccm_isRemotelyLoggedIn = '<?=UserInfo::isRemotelyLoggedIn()?>';
ccm_remoteUID = <?=UserInfo::getRemoteAuthUserId() ?>;
ccm_remoteUName = '<?=UserInfo::getRemoteAuthUserName()?>';
ccm_loginInstallSuccessFn = function() { jQuery.fn.dialog.closeTop(); };

function ccm_loginSuccess(jsObj) {
	ccm_isRemotelyLoggedIn = true;
	ccm_remoteUID = jsObj.uID;
	ccm_remoteUName = jsObj.uName;
	jQuery.fn.dialog.closeTop();
	ccm_updateMarketplaceTab();
	ccmAlert.notice('Marketplace Login', ccmi18n.marketplaceLoginSuccessMsg);
}
function ccm_logoutSuccess() {
	ccm_isRemotelyLoggedIn = false;
	ccm_updateMarketplaceTab();
	ccmAlert.notice('Marketplace Logout', ccmi18n.marketplaceLogoutSuccessMsg);
}
function ccm_updateLoginArea() {
	if (ccm_isRemotelyLoggedIn) {
		$("#ccm-marketplace-logged-in").show();
		$("#ccm-marketplace-logged-out").hide();
	} else {
		$("#ccm-marketplace-logged-in").hide();
		$("#ccm-marketplace-logged-out").show();
	}
}
function ccm_updateMarketplaceTab() {
	$("#ccm-add-marketplace-tab div.ccm-block-type-list").html(ccmi18n.marketplaceLoadingMsg);
	$.ajax({
        url: CCM_TOOLS_PATH+'/marketplace/refresh_block',
        type: 'POST',
        success: function(html){
			$("#ccm-add-marketplace-tab div.ccm-block-type-list").html(html);
			ccm_updateLoginArea();
			ccmLoginHelper.bindInstallLinks();
        },
	});
}

ccm_showBlockTypeDescription = function(btID) {
	$("#ccm-bt-help" + btID).show();
}

var ccm_areaActiveTab = "ccm-add";

$("#ccm-area-tabs a").click(function() {
	$("li.ccm-nav-active").removeClass('ccm-nav-active');
	$("#" + ccm_areaActiveTab + "-tab").hide();
	ccm_areaActiveTab = $(this).attr('id');
	$(this).parent().addClass("ccm-nav-active");
	$("#" + ccm_areaActiveTab + "-tab").show();
});

$(document).ready(function(){
	ccm_updateMarketplaceTab();
});

<? } ?>

$('input[name=ccmBlockTypeSearch]').focus(function() {
	if ($(this).val() == '<?=t("Search")?>') {
		$(this).val('');
	}
	$(this).css('color', '#000');

	if (!ccmLiveSearchActive) {
		$('#ccmBlockTypeSearch').liveUpdate('ccm-block-type-list');
		ccmLiveSearchActive = true;
		$("#ccm-block-type-clear-search").show();
	}
});

ccmBlockTypeSearchFormCheckResults = function() {
	var num = 0;
	var vobj = false;
	$("#ccm-block-type-list li").each(function() {
		if ($(this).css('display') != 'none' && vobj == false) {
			vobj = $(this);
		}
	});

	if (vobj != false) {
		vobj.find('a').click();
	}
}

ccmBlockTypeSearchClear = function() {
	$("input[name=ccmBlockTypeSearch]").val('');
	$("#ccm-block-type-list li").show();
}

var ccmLiveSearchActive = false;

$(function() {
});

</script>


<? if (ENABLE_MARKETPLACE_SUPPORT && $_REQUEST['addOnly'] != 1) { ?>
<ul class="ccm-dialog-tabs" id="ccm-area-tabs">
	<li class="ccm-nav-active"><a href="javascript:void(0)" id="ccm-add"><?=t('Add New')?></a></li>
	<li><a href="javascript:void(0)" id="ccm-add-marketplace"><?=t('Add From Marketplace')?></a></li>
</ul>
<? } ?>

<div id="ccm-add-tab">
	<div class="ccm-block-type-search-wrapper">
		<form onsubmit="return ccmBlockTypeSearchFormCheckResults()">
		<div class="ccm-block-type-search">
		<?=$form->text('ccmBlockTypeSearch', t('Search'), array('autocomplete' => 'off', 'style' => 'width: 168px'))?>
		<a href="javascript:void(0)" id="ccm-block-type-clear-search" onclick="ccmBlockTypeSearchClear()"><img width="16" height="16" src="<?=ASSETS_URL_IMAGES?>/icons/remove.png" border="0" style="vertical-align: middle" /></a>
		</div>
		<div class="ccm-block-type-filter">
		
		</div>
		</form>
		
	</div>
	
	<ul id="ccm-block-type-list">
	<? if (count($blockTypes) > 0) { 
		foreach($blockTypes as $bt) { 
			$btIcon = $ci->getBlockTypeIconURL($bt);
			?>	
			<li class="ccm-block-type">
				<a class="ccm-block-type-help" href="javascript:ccm_showBlockTypeDescription(<?=$bt->getBlockTypeID()?>)" title="<?=t('Learn more about this block type.')?>" id="ccm-bt-help-trigger<?=$bt->getBlockTypeID()?>"><img src="<?=ASSETS_URL_IMAGES?>/icons/help.png" width="14" height="14" /></a>
				<a class="dialog-launch ccm-block-type-inner" dialog-modal="false" dialog-width="<?=$bt->getBlockTypeInterfaceWidth()?>" dialog-height="<?=$bt->getBlockTypeInterfaceHeight()?>" style="background-image: url(<?=$btIcon?>)" dialog-title="<?=t('Add')?> <?=$bt->getBlockTypeName()?>" href="<?=REL_DIR_FILES_TOOLS_REQUIRED?>/add_block_popup.php?cID=<?=$c->getCollectionID()?>&btID=<?=$bt->getBlockTypeID()?>&arHandle=<?=$a->getAreaHandle()?>"><?=$bt->getBlockTypeName()?></a>
				<div class="ccm-block-type-description"  id="ccm-bt-help<?=$bt->getBlockTypeID()?>"><?=$bt->getBlockTypeDescription()?></div>
			</li>
			<?
			
			/* ?>	
			<div class="ccm-block-type-grid-entry">
				<a class="dialog-launch ccm-block-type-inner" dialog-modal="false" dialog-width="<?=$bt->getBlockTypeInterfaceWidth()?>" dialog-height="<?=$bt->getBlockTypeInterfaceHeight()?>" style="background-image: url(<?=$btIcon?>)" dialog-title="<?=t('Add')?> <?=$bt->getBlockTypeName()?>" href="<?=REL_DIR_FILES_TOOLS_REQUIRED?>/add_block_popup.php?cID=<?=$c->getCollectionID()?>&btID=<?=$bt->getBlockTypeID()?>&arHandle=<?=$a->getAreaHandle()?>"><?=$bt->getBlockTypeName()?></a>
			</div> <? */ ?>
			
		<? }
	} else { ?>
		<p><?=t('No block types can be added to this area.')?></p>
	<? } ?>
	</ul>
</div>

<? if(ENABLE_MARKETPLACE_SUPPORT){ ?>
<div id="ccm-add-marketplace-tab" style="display: none">
	<h1><?=t('Add From Marketplace')?></h1>
	<div class="ccm-block-type-list">
		<p><?=t('Unable to connect to the Concrete5 Marketplace.')?></p>
	</div>
</div>
<? } ?>