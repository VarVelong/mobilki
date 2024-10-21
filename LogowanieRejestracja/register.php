<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "wsbula";

$conn = new mysqli($servername, $username, $password, $dbname);

// Sprawdzenie połączenia
if ($conn->connect_error) {
    die("Połączenie nieudane: " . $conn->connect_error);
}

// Pobranie danych z formularza rejestracyjnego
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $imie = $_POST['imie'];
    $nazwisko = $_POST['nazwisko'];
    $email = $_POST['email'];
    $numer_telefonu = $_POST['numer_telefonu'];
    $haslo = $_POST['haslo'];

    // Sprawdzenie, czy użytkownik już istnieje (po adresie e-mail)
    $sql_check = "SELECT * FROM uzytkownicy WHERE email = '$email'";
    $result_check = $conn->query($sql_check);

    if ($result_check->num_rows > 0) {
        echo "Użytkownik z tym adresem e-mail już istnieje.<br>";
    } else {
        // Hashowanie hasła
        $hashed_password = password_hash($haslo, PASSWORD_DEFAULT);

        // Dodanie nowego użytkownika do bazy danych
        $sql = "INSERT INTO uzytkownicy (imie, nazwisko, email, numer_telefonu, haslo) 
                VALUES ('$imie', '$nazwisko', '$email', '$numer_telefonu', '$hashed_password')";

        if ($conn->query($sql) === TRUE) {
            echo "Nowy użytkownik zarejestrowany pomyślnie.<br>";
        } else {
            echo "Błąd: " . $sql . "<br>" . $conn->error;
        }
    }
} else {
    echo "Błąd: metoda nieprawidłowa.";
}

echo '<br><a href="index.html"><button>Powrót do strony głównej</button></a>';

$conn->close();
?>
