<?php  defined('TUIYO_EXECUTE' ) || die; ?>

<div class="tuiyoTable">
	<form class="TuiyoForm" action="index.php" method="post">
        <div class="tuiyoTableHeaderRow" style="padding: 0 4px; height: 41px;">
            <div class="tuiyoTableCell" style="width: 5%" align="center">
            <input type="checkbox" name="masterCheckBox" id="masterCheckBox" style="margin-top: 15px" /></div>
            <div class="tuiyoTableCell" style="width: 7%;padding-top: 10px">&nbsp;</div>
            <div class="tuiyoTableCell" style="width: 30%;padding-top: 10px">Full Name</div>
            <div class="tuiyoTableCell" style="width: 15%;padding-top: 10px">Nick name</div>
            <div class="tuiyoTableCell" style="width: 10%;padding-top: 10px">Profile</div>
            <div class="tuiyoTableCell" style="width: 15%;padding-top: 10px">Last seen</div>
            <div class="tuiyoTableCell" style="width: 15%">
                <select name="masterAction" class="TuiyoFormDropDown" >
                	<option value="">Mass action</option>
                	<option value="unistall">Suspend User</option>
                    <option value="unistall">Delete Profile</option>
                    <option value="unistall">Delete User</option>
                </select>
            </div>
            <div class="tuiyoClearFloat"></div>
        </div>
        <?php foreach($users as $user) :?>
        
        <div id="<?php echo $user['id'] ?>" class="tuiyoTableRow tuiyoListRow">
            <div class="tuiyoTableCell" style="width: 6%" align="center">
            	<input type="checkbox" name="userID[]" value="<?php echo $user['id'] ?>" style="margin-top: 3px" class="childSelector"/></div>
            <div class="tuiyoTableCell profileView" style="width: 6%;"><?php echo $user["id"] ?></div>
            <div class="tuiyoTableCell profileView" style="width: 30%;"><?php echo $user["name"] ?></div>
            <div class="tuiyoTableCell profileView" style="width: 15%;"><a href="#"><?php echo $user["username"] ?></a></div>
            <div class="tuiyoTableCell profileView" style="width: 10%;"><a href="#">profile</a></div>
            <div class="tuiyoTableCell profileView" style="width: 15%;"><?php echo TuiyoTimer::diff( strtotime( $user["lastVisitDate"] )  ) ?></div>
            <div class="tuiyoTableCell profileView" style="width: 18%;">
            	<a href="#" class="refresh">Refresh</a>
                <a href="#" class="notes">User notes</a> 
                <a href="#" class="suspend">Suspend</a> 
                <a href="#" class="delete">Delete</a> 
            </div>
            <div class="tuiyoClearFloat"></div>
        </div>
        
        <?php endforeach; ?>
        <input type="hidden" name="option" value="com_tuiyo" />
    	<?php echo JHTML::_('form.token'); ?>
    </form>
</div>