<?php if ($id) : ?>
    <a href="?page=Review&id=<?= $id ?>">Review</a>
    <div>
        <a href="?page=Unscramble the Word&id=<?= $id ?>">Unscramble the Word</a>
        <?php if ($activity->is_activity_done($user_id, "Unscramble the Word", $id)) : ?>
            <span>&#10004;</span>
        <?php endif ?>
    </div>
    <div>
        <a href="?page=Unscramble the Definition&id=<?= $id ?>">Unscramble the Definition</a>
        <?php if ($activity->is_activity_done($user_id, "Unscramble the Definition", $id)) : ?>
            <span>&#10004;</span>
        <?php endif ?>
    </div>
    <div>
        <a href="?page=Type the Word&id=<?= $id ?>">Type the Word</a>
        <?php if ($activity->is_activity_done($user_id, "Type the Word", $id)) : ?>
            <span>&#10004;</span>
        <?php endif ?>
    </div>
    <div>
        <a href="?page=Type the Definition&id=<?= $id ?>">Type the Definition</a>
        <?php if ($activity->is_activity_done($user_id, "Type the Definition", $id)) : ?>
            <span>&#10004;</span>
        <?php endif ?>
    </div>
    <div>
        <a href="?page=Choose Image from Word&id=<?= $id ?>">Choose image from word</a>
        <?php if ($activity->is_activity_done($user_id, "Choose image from word", $id)) : ?>
            <span>&#10004;</span>
        <?php endif ?>
    </div>
    <div>
        <a href="?page=Choose Image from Definition&id=<?= $id ?>">Choose image from definition</a>
        <?php if ($activity->is_activity_done($user_id, "Choose image from definition", $id)) : ?>
            <span>&#10004;</span>
        <?php endif ?>
    </div>
    <div>
        <a href="?page=Choose Word from Image&id=<?= $id ?>">Choose word from image</a>
        <?php if ($activity->is_activity_done($user_id, "Choose word from image", $id)) : ?>
            <span>&#10004;</span>
        <?php endif ?>
    </div>
    <div>
        <a href="?page=Choose Word from Definition&id=<?= $id ?>">Choose word from definition</a>
        <?php if ($activity->is_activity_done($user_id, "Choose word from definition", $id)) : ?>
            <span>&#10004;</span>
        <?php endif ?>
    </div>
    <div>
        <a href="?page=Choose Definition from Image&id=<?= $id ?>">Choose definition from image</a>
        <?php if ($activity->is_activity_done($user_id, "Choose definition from image", $id)) : ?>
            <span>&#10004;</span>
        <?php endif ?>
    </div>
    <div>
        <a href="?page=Choose Definition from Word&id=<?= $id ?>">Choose definition from word</a>
        <?php if ($activity->is_activity_done($user_id, "Choose definition from word", $id)) : ?>
            <span>&#10004;</span>
        <?php endif ?>
    </div>
    <div>
        <a href="/scholar/writings/?page=create&id=<?= $id ?>">Write a paragraph</a>
        <?php if ($activity->is_paragraph_done($user_id, $id)) : ?>
            <span>&#10004;</span>
        <?php endif ?>
    </div>
<?php endif ?>