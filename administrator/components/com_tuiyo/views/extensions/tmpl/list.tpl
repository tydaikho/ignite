<?php  defined('TUIYO_EXECUTE' ) || die; ?>

<div class="tuiyoTable">
	<form class="TuiyoForm" action="index.php" method="post">
        <div class="tuiyoTableHeaderRow" style="padding: 0 4px; height: 41px;">
            <div class="tuiyoTableCell" style="width: 5%" align="center"><input type="checkbox" name="masterCheckBox" style="margin-top: 15px" /></div>
            <div class="tuiyoTableCell" style="width: 10%;padding-top: 10px">&nbsp;</div>
            <div class="tuiyoTableCell" style="width: 35%;padding-top: 10px">Application Name</div>
            <div class="tuiyoTableCell" style="width: 15%;padding-top: 10px">Access</div>
            <div class="tuiyoTableCell" style="width: 10%;padding-top: 10px">Users</div>
            <div class="tuiyoTableCell" style="width: 10%;padding-top: 10px">Active</div>
            <div class="tuiyoTableCell" style="width: 15%">
                <select name="masterAction" class="TuiyoFormDropDown" >
                	<option value="unistall">Unistall</option>
                    <option value="unistall">Activate</option>
                    <option value="unistall">Deactivate</option>
                </select>
            </div>
            <div class="tuiyoClearFloat"></div>
        </div>
        <?php foreach($apps as $app) :?>
        <div class="tuiyoTableRow tuiyoListRow">
            <div class="tuiyoTableCell" style="width: 6%" align="center"><input type="checkbox" name="masterCheckBox" style="margin-top: 3px"/></div>
            <div class="tuiyoTableCell" style="width: 9%;">&nbsp;</div>
            <div class="tuiyoTableCell" style="width: 35%;"><?php echo $app["name"] ?></div>
            <div class="tuiyoTableCell" style="width: 15%;">Access</div>
            <div class="tuiyoTableCell" style="width: 10%;">Users</div>
            <div class="tuiyoTableCell" style="width: 10%;"><a href="#" class="<?php echo $app['name'] ?>">active</a></div>
            <div class="tuiyoTableCell" style="width: 15%"><a href="#">uninstall</a></div>       
            <div class="tuiyoClearFloat"></div>
        </div>
        <?php endforeach; ?>
        <input type="hidden" name="option" value="com_tuiyo" />
    	<?php echo JHTML::_('form.token'); ?>
    </form>
</div>