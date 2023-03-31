<a href="?page=flow_charts">View Flow Charts</a>
<a href="?page=create">Make a Flow Chart</a>
<?php
function add_sequence($id)
{
    echo '<a href="?page=update_flow_chart&id=' . $id . '" class="lightlink">Add a sequence to this Flow Chart</a>';
};
match ($page) {
    "sequences" => add_sequence($id),
    default => ""
};
?>
<?php if (isset($id) && $page === "sequences") : ?>
    <a style="background-color:salmon" href="?page=edit_flow_chart&id=<?= $id ?>">Delete or edit this Flow Chart</a>
<?php endif ?>