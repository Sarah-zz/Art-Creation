<form action="<?php echo $formActionPath; ?>" method="POST">
    <div class="mb-3">
        <label for="name" class="form-label">Votre Nom</label>
        <input type="text" class="form-control" id="name" name="name" required>
    </div>
    <div class="mb-3">
        <label for="email" class="form-label">Votre Adresse Email</label>
        <input type="email" class="form-control" id="email" name="email" required>
    </div>
    <div class="mb-3">
        <label for="subject" class="form-label">Sujet</label>
        <input type="text" class="form-control" id="subject" name="subject" required>
    </div>
    <div class="mb-3">
        <label for="message" class="form-label">Votre Message</label>
        <textarea class="form-control" id="message" name="message" rows="5" required></textarea>
    </div>
    <div class="d-grid gap-2 mt-4">
        <button type="submit" class="btn btn-outline-success">Envoyer le message</button>
    </div>
</form>