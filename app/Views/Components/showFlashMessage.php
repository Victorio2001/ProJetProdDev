
<?php
$flash = $flash ?? null;
if ($flash) : ?>

    <div id="flash"
         class="fixed top-0 right-0 mt-4 mr-4 bg-<?php echo $flash['type'] === "error" ? "red" : "green"; ?>-500 text-white px-4 py-2 rounded-full">
        <?php echo $flash['message']; ?>
    </div>
    <script>
        setTimeout(() => {
            document.getElementById('flash').remove();
        }, 5000);
    </script>
<?php endif; ?>