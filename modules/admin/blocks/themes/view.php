<?php
/**
 * Parsimony
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to contact@parsimony.mobi so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade Parsimony to newer
 * versions in the future. If you wish to customize Parsimony for your
 * needs please refer to http://www.parsimony.mobi for more information.
 *
 * @authors Julien Gras et Benoît Lorillot
 * @copyright  Julien Gras et Benoit Lorillot
 * @version  Release: 1.0
 * @category  Parsimony
 * @package admin
 * @license    http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */
?>
<style>
    .adminzone{width:100%;padding-left:0;filter: progid:DXImageTransform.Microsoft.gradient(startColorstr='white', endColorstr='#ECECEC');
background-image: -webkit-gradient(linear, left top, left bottom, color-stop(0%, white), color-stop(100%, #ECECEC));
background-image: -webkit-linear-gradient(top, white, #ECECEC);
background-image: -moz-linear-gradient(top, white, #ECECEC);
background-image: -ms-linear-gradient(top, white, #ECECEC);
background-image: -o-linear-gradient(top, white, #ECECEC);
background-image: linear-gradient(top, white, #ECECEC);
text-shadow: white 0 1px 0;display:block;border-top: 2px solid #999;}
    .adminzone .adminzonemenu{display: block;min-height: 0;width: auto;position: relative}
    .adminzone .adminzonecontent{display: block;width:100%;box-shadow: -2px 0px 2px #999;overflow-x: hidden;overflow-y: auto;background: #EEE url(<?php echo BASE_PATH; ?>admin/img/concrete_wall_3.png);}
    .adminzone .adminzonemenu .adminzonetab{border-left: 1px solid white;border-right: 1px solid #D3D5DB;float: left;border-top: none;border-bottom: none;}
    .adminzone .adminzonemenu .adminzonetab a{padding: 0 10px;color: #777; background: none;border-top: none;border-bottom:none;color: #464646;text-decoration: none;line-height:25px;}
    .adminzone .adminzonemenu .adminzonetab a.active, .adminzonemenu .adminzonetab a:hover{border-top: none;background: #E4E4E4;}
    .placeholder {position: relative;clear: both;width: 200px;}
    #themeFormAdd input{width: 90px;}
    #themelist{overflow-x: scroll;overflow-y: hidden;height: 145px;width: 99.5%;}
    #themelist ul{width: 4000px;}
    #themelist h4{line-height: 20px;text-align: center}
    .themeItem.active{background: #C9C9C9;border-radius: 5px;}
    .themeItem:hover{background: #C9C9C9;border-radius: 5px;}
    #themeFormAdd{float:left;text-align: center;width:255px;border-right: 1px solid #CCC;}
    #themeFormAdd h4{margin: 0px 5px;border: 1px solid #D3D5DB;line-height: 20px;}
    #themeFormPattern{float:left;border-left: 1px solid whitesmoke;padding-left:10px}
    #duplicatepattern{display:none}
    #themes span.ui-icon { background-image: url(admin/img/icons.png);}
    .adminzone .adminzonecontent li:last-child {border-right: 0;}
    .adminzone .adminzonecontent li:first-child{border-left: 0;}
    #themes_close{margin-right: 15px;border: #CCC solid 1px;border-radius: 5px;cursor: pointer;margin-top: 2px;}
    .contimg{position:relative;width:97px;height:97px;margin: 0 auto;}
    .contimg:hover .preview{display:block}
    .preview{position:absolute;width:100%;height:100%;background:rgba(0,0,0,.75);display:none;text-align: center;font-size:25px;padding-top:40%;color:#fff;cursor:pointer;font-family:sans-serif}
    .themeOptions{display:none;text-align: center;position: absolute;top: 0;width: 120px;height: 125px;left: 137px;z-index: 999;background-color: inherit;border-radius: 0 5px 5px 0;padding-top: 17px;}
    .themeItem:hover .themeOptions{display:block}
     #patternName{float: left;line-height: 27px;}
    .themeItem{position: relative;width: 140px;height: 125px;padding-right: 10px;padding-left:10px;border-right: 1px solid #CCC;border-left: 1px solid whitesmoke;float: left}
</style>
<script type="text/javascript">
    $(document).on('click',".adminzonetab a", function(event){
	event.preventDefault();
	$(".adminzonecontent .admintabs").hide();
	$(".adminzonetab a").removeClass("active");
	$(this).addClass("active");
	$($(this).attr("href")).show();
    });           
    $(document).on("click",".duplicate",function(){
	$('#duplicatepattern').show();
	$('#admin_themes .secondpanel a').trigger('click');
	$('#patternName').text($(this).data("themename").split(";")[1]);
	$('#themeFormPattern input[name="template"]').val($(this).data("themename"));
	$('#patternIMG').attr("src",($(this).data("imgurl")));
	$('input[value="template"]').attr('checked', true)
    });
    $(window).load(function() {
	$(".firstpanel a").trigger("click");
    });
</script>
<div style="background: #E9E9E9;" id="admin_themes" class="adminzone">
    <div class="adminzonemenu">
        <div class="adminzonetab firstpanel"><a href="#themelist" class="ellipsis"><?php echo t('Existing Themes', FALSE); ?></a></div>
        <div class="adminzonetab secondpanel"><a href="#tabs-2" class="ellipsis"><?php echo t('New theme', FALSE); ?></a></div>
        <span id="themes_close" onclick="$('#themes').hide();" class="floatright ui-icon ui-icon-closethick"></span>
    </div>
    <div class="adminzonecontent">
        <div id="themelist" class="admintabs fs">
            <ul>
		<?php
                $modules = \app::$activeModules;
                unset($modules['admin']);
                foreach ($modules as $moduleName => $mode) {
		    $module = \app::getModule($moduleName);
		    foreach ($module->getThemes() as $themeName) {
			$imgURL = stream_resolve_include_path($moduleName . '/themes/' . s($themeName) . '/thumb.png');
			if($imgURL)  $imgURL = BASE_PATH.  strstr(str_replace('\\','/',$imgURL),'modules/');
			else $imgURL = BASE_PATH.'admin/img/defaulttheme.png';
			?>
			<li id="theme_<?php echo s($themeName); ?>" class="themeItem<?php if($themeName == THEME) echo ' active'; ?>">
			    <h4 class="ellipsis"><?php echo ucfirst(s($themeName)); ?></h4>
			    <div class="contimg" style="background:url(<?php echo $imgURL; ?>) center" class="floatleft">
				<div class="preview ellipsis" onclick="$('#themelist li.active').removeClass('active');$(this).closest('li').addClass('active');top.ParsimonyAdmin.setCookie('THEMEMODULE','<?php echo $moduleName; ?>',999);top.ParsimonyAdmin.setCookie('THEME','<?php echo s($themeName); ?>',999);document.getElementById('parsiframe').contentWindow.location.reload();" /><?php echo t('Preview', FALSE) ?></div>
			    </div>
			    <div class="themeOptions">
				<input class="button duplicate" data-themename="<?php echo s($moduleName.';'.$themeName); ?>" data-imgurl="<?php echo $imgURL; ?>" type="button" value="<?php echo t('Duplicate', FALSE) ?>" />
				<?php if($themeName != app::$config['THEME']): ?>
                                <form method="POST" style="" action="<?php echo BASE_PATH; ?>admin/changeTheme" target="ajaxhack">
				    <input type="hidden" name="THEMEMODULE" value="<?php echo $moduleName; ?>" />
				    <input type="hidden" name="TOKEN" value="<?php echo TOKEN; ?>" />
				    <input type="hidden" name="name" value="<?php echo s($themeName); ?>" />
				    <input class="input" type="submit" value="<?php echo t('Choose', FALSE) ?>" />
				</form>
				<form method="POST" style="" action="admin/deleteTheme" target="ajaxhack">
				    <input type="hidden" name="THEMEMODULE" value="<?php echo $moduleName; ?>" />
				    <input type="hidden" name="TOKEN" value="<?php echo TOKEN; ?>" />
				    <input type="hidden" name="name" value="<?php echo s($themeName); ?>" />
				    <input class="input" type="submit" value="<?php echo t('Delete', FALSE) ?>" />
				</form>
                                <?php endif; ?>
			    </div>
			</li>
			<?php
		    }
		}
		?>
            </ul>	
        </div> 
        <div id="tabs-2" class="admintabs">
            <form method="POST" target="ajaxhack" action="<?php echo BASE_PATH; ?>admin/addTheme">
                <div id="themeFormAdd">
                    <input type="hidden" name="TOKEN" value="<?php echo TOKEN; ?>"/>
                    <div class="placeholder" style="margin: 0px 10px;">
                        <label for="name"><?php echo t('Theme Name', FALSE); ?></label>
                        <input type="text" style="width: 220px;" name="name" required="required"/>                       
                    </div>
                    <div class="placeholder" style="margin: 0px 10px;width: 220px;">
			<label><?php echo t('Module', FALSE); ?>: </label>
			<select name="thememodule">
                            <?php
                            $modules = \app::$activeModules;
                            unset($modules['admin']);
                            foreach ($modules as $moduleName => $mode) {
                                echo '<option value="' . $moduleName . '">' . $moduleName . '</option>';
                            }
                            ?>
			</select>
		    </div>
                    <input type="submit" style="width: 220px;margin: 4px 0 0 -15px;" value="<?php echo t('Create Theme', FALSE); ?>"/>
                </div>
                <div id="themeFormPattern">
		    <h4><?php echo t('Pattern', FALSE) . ' : ' ?></h4>
		    <div><input type="radio" name="patterntype" value="blank" checked="checked" /> <?php echo t('Blank', FALSE) ?></div>
		    <?php /*<div><input type="radio" name="patterntype" value="url" />  <?php echo t('An URL', FALSE) ?> : <input type="text" name="url" style="width:150px;" ></div>*/ ?>
		    <div id="duplicatepattern">
			<div><input type="radio" name="patterntype" value="template" style="float:left" /><h4 id="patternName"></h4></div>
			<img id="patternIMG" src="" />
			<input type="hidden" name="template" value=""  />
		    </div>

                </div>
            </form>
        </div>
    </div>
</div>
