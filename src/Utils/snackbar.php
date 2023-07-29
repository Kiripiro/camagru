<div id="snackbar" class="notification" hidden>
    <button id="snackbar-delete" class="delete"></button>
    <span id="snackbar-message"></span>
</div>
<?php
if (isset($success_message)) {
    $js_content = "showSnackbar('" . $success_message . "', '" . ('success') . "')";
    $session->remove('success_message');
} else if (isset($error_message)) {
    $js_content = "showSnackbar('" . $error_message . "', '" . ('danger') . "')";
    $session->remove('error_message');
} else
    $js_content = null;
?>
<script>
    <?php if ($js_content)
        echo $js_content; ?>
</script>