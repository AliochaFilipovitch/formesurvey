<?php
  require_once('../config/db.php');
  require_once('../lib/pdo_db.php');
  require_once('../models/Questions.php');

  // Instantiate survey and question
  $questionCM = new Question();

  // Get survey and question
  $questionCM = $questionCM -> getQuestionCM($GET['id']);

?>
<br>
<div class="text-center" style="margin: 5%;">
  <p>Une seule réponse possible :</p>

	<?php foreach($questionCM as $qCM): ?>
    <?php if (!empty($qCM -> textChoose)) { ?>

	  <div class="form-check">
	    <input class="form-check-input" type="radio" name="postAnswer4" id="RadioQCM<?php echo $qCM -> numChoose; ?>" value="<?php echo $qCM -> numChoose; ?>">
	    <label class="form-check-label" for="RadioQCM<?php echo $qCM -> numChoose; ?>"><p class="h5"><?php echo $qCM -> textChoose; ?></p></label>
	  </div>

    <?php } ?>
	<?php endforeach; ?>

</div>
