<div id="menu">
    <div><a class="menuitem <?php if($PAGE == "dashboard") echo "activemenuitem"; ?>" href="<?= $URLPREFIX ?>">Dashboard</a></div> 
    <div><a class="menuitem <?php if($PAGE == "course") echo "activemenuitem"; ?>" href="<?= $URLPREFIX."course?action=insert" ?>">Insert Course</a></div>
    <div><a class="menuitem <?php if($PAGE == "list") echo "activemenuitem"; ?>" href="<?= $URLPREFIX."list" ?>">Course List</a></div>

    <div><a class="menuitem <?php if($PAGE == "load") echo "activemenuitem"; ?>" href="<?= $URLPREFIX."load" ?>">Load data (JSON/Task 1)</a></div>
</div>