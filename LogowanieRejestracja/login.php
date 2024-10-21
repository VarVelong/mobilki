<?php
session_start();

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "wsbula";

$conn = new mysqli($servername, $username, $password, $dbname);

// Sprawdzenie połączenia
if ($conn->connect_error) {
    die("Połączenie nieudane: " . $conn->connect_error);
}

$login = $_POST['login'];
$haslo = $_POST['haslo'];

// Sprawdzenie, czy użytkownik podaje e-mail lub numer telefonu
if (filter_var($login, FILTER_VALIDATE_EMAIL)) {
    $sql = "SELECT id, haslo FROM uzytkownicy WHERE email = ?";
} else {
    $sql = "SELECT id, haslo FROM uzytkownicy WHERE numer_telefonu = ?";
}

// Przygotowanie zapytania
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $login);
$stmt->execute();
$result = $stmt->get_result();

// Sprawdzenie, czy znaleziono użytkownika
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    
    // Weryfikacja hasła
    if (password_verify($haslo, $row['haslo'])) {
        // Logowanie udane, zapisz ID użytkownika w sesji
        $_SESSION['id_uzytkownika'] = $row['id'];
        
        // Komunikat po poprawnym zalogowaniu
        echo "Zalogowano pomyślnie! Możesz przejść do <a href='index.html'>strony głównej</a>.";
    } else {
        echo "Nieprawidłowe hasło! Możesz przejść do <a href='index.html'>strony głównej</a>.";
            }
    } else {
    echo "Nie znaleziono użytkownika! Możesz przejść do <a href='index.html'>strony głównej</a>.";
            }

$stmt->close();
$conn->close();
?>
