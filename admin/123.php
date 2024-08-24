<?php

// connect with database
require('database.php');

// 获取所有的FAQs
$sql = "SELECT * FROM faqs";
$result = $con->query($sql);
$faqs = [];

if ($result->num_rows > 0) {
	while ($row = $result->fetch_assoc()) {
		$faqs[] = $row;
	}
} else {
	echo "没有FAQ记录";
}
?>

<!-- show all FAQs in a panel -->
<div class="container" style="margin-top: 50px; margin-bottom: 50px;">
    <div class="accordion" id="accordionExample">
        <?php foreach ($faqs as $faq): ?>
        <div class="accordion-item">
            <h2 class="accordion-header" id="heading-<?php echo $faq['id']; ?>">
                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapse-<?php echo $faq['id']; ?>" aria-expanded="false" aria-controls="collapse-<?php echo $faq['id']; ?>">
                    <?php echo $faq['question']; ?>
                </button>
            </h2>
            <div id="collapse-<?php echo $faq['id']; ?>" class="accordion-collapse collapse" aria-labelledby="heading-<?php echo $faq['id']; ?>" data-bs-parent="#accordionExample">
                <div class="accordion-body">
                    <?php echo $faq['answer']; ?>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>