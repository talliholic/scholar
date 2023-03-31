<?php
$definitions = $writing->get_definitions();
echo "<script>const definitions = " . json_encode($definitions) . "</script>";
?>
<fieldset style="background-color:white;max-width:500px;margin-bottom:300px">
    <legend>Write a paragraph</legend>
    <table style="border:1px solid;display:block;margin:0 auto">
        <?php foreach ($definitions as $definition) : ?>
            <tr>
                <th><?= $definition["word"] ?>: </th>
                <td><?= $definition["definition"] ?></td>
                <td><img height=" 50" src="<?= $definition["img_path"] ?>" alt="<?= $definition["word"] ?>" />
                </td>
            </tr>
        <?php endforeach ?>
    </table>
    <p style="text-align:justify">Use all the words above to write a paragraph. You can give <b style="color:blue">examples</b> or <b style="color:green">opinions</b>. You can also write about <b style="color:red">favorites</b> or <b style="color:orange">experiences</b>. You can also write <b style="color:purple">other definitions</b>. You don't have to write the words in order.</p>
    <form>
        <input type="hidden" name="id" value="<?= $id ?>">
        <textarea style="font-size:16px" name="body" id="paragraph" cols="60" rows="10"></textarea>
        <input style="float:right;margin-top:7px" type="submit" value="Save">
    </form>
</fieldset>
<script type="module">
    import Writing from "/scholar/scripts/classes/Writing.js"
    const writing = new Writing(definitions)
    writing.save_handler()
</script>