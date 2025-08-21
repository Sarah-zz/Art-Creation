<div class="container">
    <section class="mx-auto" style="max-width: 600px;">
        <h1 class="text-center mb-4">Contactez-moi</h1>
        <p class="lead text-center mb-5">
            N'hésitez pas à m'écrire !
        </p>

        <?php if (!empty($messageType)): ?>
            <div class="alert alert-<?php echo htmlspecialchars($messageType); ?> text-center" role="alert">
                <?php if (!empty($messageTitle)): ?>
                    <h4 class="alert-heading"><?php echo htmlspecialchars($messageTitle); ?></h4>
                <?php endif; ?>
                <?php if ($messageType === 'danger'): ?>
                    <ul>
                        <?php foreach ($messageContent as $line): ?>
                            <li><?php echo $line; ?></li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <?php foreach ($messageContent as $line): ?>
                        <p><?php echo $line; ?></p>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php include __DIR__ . '/../Form/ContactForm.php'; ?>

        <p class="text-center mt-4 text-muted">
            J'essaye de vous répondre au plus vite !
        </p>
    </section>
</div>