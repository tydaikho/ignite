<?php  defined('TUIYO_EXECUTE' ) || die; ?>

<div class="tuiyoTable">
	<form class="TuiyoForm" action="index.php" method="post">
        <div class="tuiyoTableHeaderRow" style="padding: 0 4px; height: 41px;">
            <div class="tuiyoTableCell" style="width: 5%" align="center"><input type="checkbox" name="masterCheckBox" id="masterCheckBox" style="margin-top: 15px" /></div>
            <div class="tuiyoTableCell" style="width: 7%;padding-top: 10px">ID</div>
            <div class="tuiyoTableCell" style="width: 30%;padding-top: 10px">Notes</div>
            <div class="tuiyoTableCell" style="width: 15%;padding-top: 10px">Reason</div>
            <div class="tuiyoTableCell" style="width: 10%;padding-top: 10px">Type</div>
            <div class="tuiyoTableCell" style="width: 15%;padding-top: 10px">Filed..</div>
            <div class="tuiyoTableCell" style="width: 15%">
                <select name="masterAction" class="TuiyoFormDropDown" >
                	<option value="">Mass action</option>
                	<option value="unistall">Is Solved</option>
                    <option value="unistall">Not Solved</option>
                    <option value="unistall">Delete</option>

                </select>
            </div>
            <div class="tuiyoClearFloat"></div>
        </div>
        <?php foreach($reports as $report) :?>
        
        <div id="<?php echo $user['id'] ?>" class="tuiyoTableRow tuiyoListRow">
            <div class="tuiyoTableCell" style="width: 6%" align="center">
            	<input type="checkbox" name="reportID[]" value="<?php echo $report['reportID'] ?>" style="margin-top: 3px" class="childSelector"/></div>
            <div class="tuiyoTableCell reportView" style="width: 6%;"><?php echo $report['reportID'] ?></div>
            <div class="tuiyoTableCell reportView" style="width: 30%;"><?php echo $report['notes'] ?></div>
            <div class="tuiyoTableCell reportView" style="width: 15%;"><a href="#"><?php echo $report['reason'] ?></a></div>
            <div class="tuiyoTableCell reportView" style="width: 10%;"><?php echo $report['resourceType'] ?></div>
            <div class="tuiyoTableCell reportView" style="width: 15%;"><?php echo TuiyoTimer::diff( strtotime(  $report['reportDate'] )  ) ?></div>
            <div class="tuiyoTableCell reportView" style="width: 18%;">
            	<a href="#" class="solved">solved</a>
                <a href="#" class="notes">User notes</a> 
                <a href="#" class="delete">Delete</a>
                <a href="#" class="view">Suspend</a>  
            </div>
            <div class="tuiyoClearFloat"></div>
        </div>
        
        <?php endforeach; ?>
        <input type="hidden" name="option" value="com_tuiyo" />
    	<?php echo JHTML::_('form.token'); ?>
    </form>
</div>