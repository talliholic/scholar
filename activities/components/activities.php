<?php
$study_guides = $activity->get_study_guides();
$root = $_SERVER["DOCUMENT_ROOT"];
require "$root/scholar/models/Flow_chart_activity.php";
?>
<?php
$subject = isset($_GET["subject"]) ? $_GET["subject"] : null;
$grade_level = isset($_GET["grade_level"]) ? $_GET["grade_level"] : null;
$subject_list = $activity->get_subjects_by_grade_level($grade_level);

// $flow_chart_activity = new Flow_chart_Activity();
// if ($grade_level && $subject) {
//     echo "<pre>";
//     print_r($flow_chart_activity->get_by_grade_and_subject($grade_level, $subject));
//     echo "</pre>";
// }

//SHOULD HAVE GONE IN MODEL BUT WANTED TO TRY SOMETHING NEW
if ($subject && !$grade_level) $study_guides = array_filter($study_guides, function ($study_guide) use ($subject) {
    return $study_guide["data"]["subject"] === $subject;
});
if (!$subject && $grade_level) $study_guides = array_filter($study_guides, function ($study_guide) use ($grade_level) {
    return $study_guide["data"]["grade_level"] === $grade_level;
});
if ($subject && $grade_level) {
    $study_guides = array_filter($study_guides, function ($study_guide) use ($subject) {
        return $study_guide["data"]["subject"] === $subject;
    });
    $study_guides = array_filter($study_guides, function ($study_guide) use ($grade_level) {
        return $study_guide["data"]["grade_level"] === $grade_level;
    });
}
?>
<!-- ABOVE MUST GO IN MODEL -->

<?php if ($subject_list && !$subject) : ?>
    <h2 style="width:100%; background-color:lightyellow; text-align:center;margin:0 0 15px 0;"><i><?= $grade_level ?> Subjects</i></h2>
    <div style="display:flex;flex-flow:row wrap;justify-content:center">
        <?php foreach ($subject_list as $subject_) : ?>
            <a style="text-decoration:none;color:black" href="?grade_level=<?= $grade_level ?>&subject=<?= $subject_["subject"] ?>">
                <fieldset style="background-color:white">
                    <legend><?= $subject_["subject"] ?></legend>
                    <div>
                        <img style="display:block;margin:auto" height="150" src="<?= $subject_["img_path"] ?>" alt="<?= $subject_["subject"] ?>">
                    </div>
                </fieldset>
            </a>
        <?php endforeach ?>
    </div>
<?php endif ?>

<?php if (!$grade_level) : ?>
    <h2 style="width:100%; background-color:lightyellow; text-align:center;margin:0 0 15px 0;"><i>Pick your Grade Level</i></h2>
    <div style="display:flex;flex-flow:row wrap;justify-content:center">
        <img height="150" src="/scholar/media/content/flow_arrow.jpg" alt="Flow Arrow">
        <a href="?grade_level=Transition"> <img height="150" src="/scholar/media/content/transition.jpg" alt="Transition"></a>
        <a href="?grade_level=First Grade"> <img height="150" src="/scholar/media/content/first_grade.jpg" alt="First Grade"></a>
        <a href="?grade_level=Second Grade"> <img height="150" src="/scholar/media/content/second_grade.png" alt="Second Grade"></a>
        <a href="?grade_level=Third Grade"> <img height="150" src="/scholar/media/content/third_grade.jpg" alt="Third Grade"></a>
        <a href="?grade_level=Fourth Grade"> <img height="150" src="/scholar/media/content/fourth_grade.png" alt="Fourth Grade"></a>
        <a href="?grade_level=Fifth Grade"> <img height="150" src="/scholar/media/content/fifth_grade.jpg" alt="Fifth Grade"></a>
    </div>
<?php endif ?>

<?php if ($subject) : ?>
    <h2 style="width:100%; background-color:lightyellow; text-align:center;margin:0 0 15px 0;"><i><?= $grade_level ?> <?= $subject ?> Activities</i></h2>
    <?php foreach ($study_guides as $study_guide) : ?>
        <?php $performance = $activity->get_study_guide_personal_performance($study_guide["data"]["id"], $user_id); ?>
        <?php if ($study_guide["complete"]) : ?>
            <a style="text-decoration:none;color:black" href="?page=Review&id=<?= $study_guide["data"]["id"] ?>">
                <fieldset class="small-card">
                    <legend><?= $study_guide["data"]["title"] ?></legend>
                    <img src="<?= $study_guide["img"] ?>" class="medium-img" alt="activity icon">
                    <?php if (isset($performance[0])) : ?>
                        <?php $num_completed = isset($performance[0]["completed"]) ? $performance[0]["completed"] : 0 ?>
                        <table style="font-size:12px">
                            <tr>
                                <th>Activity</th>
                                <th>Best</th>
                            </tr>
                            <?php foreach ($performance as $result) : ?>
                                <tr">
                                    <td style="padding-bottom:5px"><a href="<?= $result["best_results"]["link"] ?>"><?= $result["best_results"]["activity"] ?></a></td>
                                    <td style="text-align:center"><?= $result["best_results"]["score"] ?></td>
                                    </tr>
                                <?php endforeach ?>
                        </table>
                    <?php endif ?>
                </fieldset>
            </a>
        <?php endif ?>
    <?php endforeach ?>
<?php endif ?>