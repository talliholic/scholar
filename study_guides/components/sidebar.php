<a href="?page=study_guides">View study guides</a>
<a href="?page=make">Make a study guide</a>
<?php
function add_definition($id)
{
    echo '<a href="?page=edit&id=' . $id . '" class="lightlink">Add a definition to this guide</a>';
};
match ($page) {
    "definitions" => add_definition($id),
    default => ""
};
?>