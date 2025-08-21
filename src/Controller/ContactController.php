<?php
// src/Controller/ContactController.php
namespace App\Controller;

class ContactController
{
    public function index(): array
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        $messageType = '';
        $messageTitle = '';
        $messageContent = [];

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            $name = htmlspecialchars($_POST['name'] ?? '');
            $email = htmlspecialchars($_POST['email'] ?? '');
            $subject = htmlspecialchars($_POST['subject'] ?? '');
            $message = htmlspecialchars($_POST['message'] ?? '');

            if (empty($name) || empty($email) || empty($subject) || empty($message)) {
                $messageType = 'error';
                $messageTitle = 'Erreur d\'envoi :';
                $messageContent[] = "Tous les champs du formulaire de contact sont requis.";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $messageType = 'error';
                $messageTitle = 'Erreur d\'envoi :';
                $messageContent[] = "L'adresse email n'est pas valide.";
            } else {
                $messageType = 'success';
                $messageTitle = 'Message envoyé !';
                $messageContent[] = "Merci <strong>$name</strong>, votre message a été envoyé avec succès.";
                $messageContent[] = "Nous vous répondrons à l'adresse <strong>$email</strong> dans les plus brefs délais.";
            }

            $_SESSION['contact_form_message_type'] = $messageType;
            $_SESSION['contact_form_message_title'] = $messageTitle;
            $_SESSION['contact_form_message_content'] = $messageContent;

            header('Location: /contact');
            exit();
        }

        if (!empty($_SESSION['contact_form_message_type'])) {
            $messageType = $_SESSION['contact_form_message_type'];
            $messageTitle = $_SESSION['contact_form_message_title'];
            $messageContent = $_SESSION['contact_form_message_content'];

            unset($_SESSION['contact_form_message_type']);
            unset($_SESSION['contact_form_message_title']);
            unset($_SESSION['contact_form_message_content']);
        }

        return [
            'messageType' => $messageType,
            'messageTitle' => $messageTitle,
            'messageContent' => $messageContent
        ];
    }
}
