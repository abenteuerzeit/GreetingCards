<?php

require_once '_header.php';
require_once '_utilities.php';


function makeGreetingCard($sender, $recipient, $template, $personal_message)
{
    $file_name = sanitizeFileName("$sender-$recipient.txt");
    $message = "Dear $recipient," . "\n\n";
    $message .= file_get_contents($template) . "\n\n";

    if (!empty($personal_message)) {
        $message .= "$personal_message" . "\n\n";
    }

    $message .= "Sincerely," . "\n" . "$sender";

    file_put_contents("cards/" . $file_name, $message);
    return $file_name;
}


$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $sender = $_POST['sender'] ?? '';
    $recipient = $_POST['recipient'] ?? '';
    $template = $_POST['template'] ?? '';
    $personal_message = $_POST['personal_message'] ?? '';

    if (empty($sender)) {
        $errors[] = 'Sender name is required.';
    }

    if (empty($recipient)) {
        $errors[] = 'Recipient name is required.';
    }

    if (!file_exists($template)) {
        $errors[] = 'Selected template does not exist.';
    }

    if (empty($errors)) {
        try {
            $file_name = makeGreetingCard($sender, $recipient, $template, $personal_message);
            header('Location:' . 'card.php?name=' . $file_name);
            exit;
        } catch (Exception $e) {
            $errors[] = 'Failed to create card. Please try again later.';
        }
    }
}

?>

<h1 class="my-4">Create a Greeting Card</h1>
<?php if (!empty($errors)) : ?>
    <div class="alert alert-danger">
        <?php foreach ($errors as $error) : ?>
            <p><?= $error ?></p>
        <?php endforeach; ?>
    </div>
<?php endif; ?>


<form method="post">
    <div class="my-3">
        <label for="sender" class="form-label">Sender Name</label>
        <input type="text" name="sender" class="form-control" required>
    </div>

    <div class="my-3">
        <label for="sender" class="form-label">Recipient Name</label>
        <input type="text" name="recipient" class="form-control" required>
    </div>

    <div class="my-3">
        <label for="personal_message" class="form-label">Personal Message: </label>
        <input type="textarea" name="personal_message" class="form-control">
    </div>

    <div class="my-3">
        <label for="template" class="form-label">Template</label>
        <select name="template" class="form-select" required>
            <option value="">Choose a Template</option>
            <option value="birthday.txt">Birthday</option>
            <option value="thank_you.txt">Thank You</option>
            <option value="congratulations.txt">Congratulations</option>
            <option value="get_well_soon.txt">Get Well Soon</option>

        </select>
    </div>

    <button type="submit" class="btn btn-primary mt-1">Create Card</button>
</form>

<?php

require_once '_footer.php';
