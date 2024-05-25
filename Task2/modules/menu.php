<div id="menu">
    <div><a class="menuitem <?php if($PAGE == "dashboard") echo "activemenuitem"; ?>" href="<?= $URLPREFIX ?>">Dashboard</a></div> 
    <div><a class="menuitem <?php if($PAGE == "course") echo "activemenuitem"; ?>" href="<?= $URLPREFIX."course?action=insert" ?>">Course Actions</a></div>
    <div><a class="menuitem <?php if($PAGE == "list") echo "activemenuitem"; ?>" href="<?= $URLPREFIX."list" ?>">Course List/Report</a></div>
    <div class="mt-30"><a class="menuitem <?php if($PAGE == "load") echo "activemenuitem"; ?>" href="<?= $URLPREFIX."load" ?>" onclick="return confirm('Αυτή η ενέργεια θα φορτώσει δεδομένα απο το Task 1 (JSON).\n\nΕίστε σίγουροι πως θέλετε να προχωρήσετε?')">Load data (JSON/Task 1)</a></div>
</div>