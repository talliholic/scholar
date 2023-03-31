<?php $writings = $writing->read_by_guide_id() ?>
<?php foreach ($writings as $writing_) : ?>
    <?php $num_likes = $writing->get_likes($writing_["id"]) ?>
    <fieldset style="background-color:white;max-width:500px">
        <legend>Written by: <?= $writing_["author"] ?></legend>
        <div>
            <img style="display:block;margin:auto" src="<?= $writing_["img_path"] ?>" height="150" alt="Study Guide Image">
        </div>
        <p style="text-align:justify;border:1px solid black;background-color:lightgoldenrodyellow;padding:7px"><i><?= $writing_["body"] ?></i></p>
        <?php if ($writing_["is_owner"]) : ?>
            <a href="?page=update&id=<?= $id ?>&writing_id=<?= $writing_["id"] ?>">Modify</a>
        <?php endif ?>
        <div style="float:right;">
            <img id=" <?= $writing_["id"] ?>" class="like" src="/scholar/media/content/like.png" height="30" style="cursor:pointer" alt="Like Button">
            <?php if ($num_likes > 0) : ?>
                <b style="vertical-align:top;padding:3px;background-color:orange;color:white;border-radius:15%">
                    <?= $num_likes ?> <?= $num_likes === 1 ? "like" : "likes" ?>
                </b>
            <?php endif ?>
        </div>
    </fieldset>
<?php endforeach ?>
<script>
    function like(e) {
        const body = new FormData()
        body.append("request", "like")
        body.append("object_id", e.target.id)
        body.append("id", "<?= $id ?>")
        fetch("/scholar/api/writing.php", {
                method: "POST",
                body
            })
            .then((res) => res.json())
            .then((res) => {
                if (res.success) {
                    alert("You like this writing!")
                    location.reload()
                } else {
                    alert("You already like this writing!")
                }
            })
    }
    document.querySelectorAll(".like").forEach((like_img) => like_img.addEventListener("click", like))
</script>