<?php

require_once '_header.php';
require_once '_utilities.php';

$file_name = sanitizeFileName($_GET['name']);
$path = "cards/" . $file_name;
if (file_exists($path)) {
    $card_content = file_get_contents($path);
}


?>

<h1 class="my-4">Card Preview</h1>
<pre class="bg-light p-3">

<?= !isset($card_content) ? "Oops! Something went wrong. " : htmlspecialchars($card_content); ?>

</pre>

<?php

require_once '_footer.php';
