<div id="menu">
    <div><a class="menuitem <?php if($PAGE == "dashboard") echo "activemenuitem"; ?>" href="<?= $URLPREFIX ?>">Dashboard</a></div> 
    <div><a class="menuitem <?php if($PAGE == "lesson") echo "activemenuitem"; ?>" href="<?= $URLPREFIX."lesson" ?>">Insert Lesson</a></div>
    <div><a class="menuitem <?php if($PAGE == "list") echo "activemenuitem"; ?>" href="<?= $URLPREFIX."list" ?>">List of Lessons</a></div>
</div>