<?php
// Activer l'affichage des erreurs pour le debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Log pour vérifier si le script est appelé
error_log("Contact form submitted");

// Vérifier si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Log des données reçues
    error_log("POST data received: " . print_r($_POST, true));
    
    // Récupérer les données du formulaire
    $name = isset($_POST['name']) ? $_POST['name'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $message = isset($_POST['message']) ? $_POST['message'] : '';
    
    // Log des variables
    error_log("Name: $name");
    error_log("Email: $email");
    error_log("Message: $message");
    
    // Validation basique
    $errors = [];
    if (empty($name)) $errors[] = "Le nom est requis";
    if (empty($email)) $errors[] = "L'email est requis";
    if (empty($message)) $errors[] = "Le message est requis";
    
    if (empty($errors)) {
        // Destinataire
        $to = "juniormichel@proton.me"; // Remplacez par votre email
        
        // Sujet
        $subject = "Nouveau message de contact de $name";
        
        // Corps du message
        $email_content = "Nom: $name\n";
        $email_content .= "Email: $email\n\n";
        $email_content .= "Message:\n$message";
        
        // En-têtes
        $headers = "From: $email\r\n";
        $headers .= "Reply-To: $email\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";
        
        // Tentative d'envoi
        $mail_sent = mail($to, $subject, $email_content, $headers);
        
        // Log du résultat de l'envoi
        error_log("Mail sending attempt result: " . ($mail_sent ? "Success" : "Failed"));
        
        if ($mail_sent) {
            $response = [
                'success' => true,
                'message' => 'Message envoyé avec succès!'
            ];
        } else {
            $response = [
                'success' => false,
                'message' => 'Erreur lors de l\'envoi de l\'email. Veuillez réessayer.'
            ];
        }
    } else {
        $response = [
            'success' => false,
            'message' => 'Erreurs de validation: ' . implode(', ', $errors)
        ];
    }
    
    // Envoyer la réponse
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}
?>