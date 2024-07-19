<?php
header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];
$input = json_decode(file_get_contents('php://input'), true);

// Database connection
$mysqli = new mysqli('localhost', 'username', 'password', 'database');

if ($mysqli->connect_error) {
    die('Connect Error (' . $mysqli->connect_errno . ') ' . $mysqli->connect_error);
}

switch ($method) {
    case 'GET':
        $result = $mysqli->query("SELECT * FROM events");
        $events = $result->fetch_all(MYSQLI_ASSOC);
        echo json_encode(['events' => $events]);
        break;
    
    case 'POST':
        $name = $mysqli->real_escape_string($input['event']['name']);
        $date = $mysqli->real_escape_string($input['event']['date']);
        $location = $mysqli->real_escape_string($input['event']['location']);
        $description = $mysqli->real_escape_string($input['event']['description']);
        $mysqli->query("INSERT INTO events (name, date, location, description) VALUES ('$name', '$date', '$location', '$description')");
        echo json_encode(['event' => $input['event']]);
        break;

    case 'PUT':
        $id = intval(basename($_SERVER['REQUEST_URI']));
        $name = $mysqli->real_escape_string($input['event']['name']);
        $date = $mysqli->real_escape_string($input['event']['date']);
        $location = $mysqli->real_escape_string($input['event']['location']);
        $description = $mysqli->real_escape_string($input['event']['description']);
        $mysqli->query("UPDATE events SET name='$name', date='$date', location='$location', description='$description' WHERE id=$id");
        echo json_encode(['event' => $input['event']]);
        break;

    case 'DELETE':
        $id = intval(basename($_SERVER['REQUEST_URI']));
        $mysqli->query("DELETE FROM events WHERE id=$id");
        echo json_encode(['deleted' => true]);
        break;

    default:
        echo json_encode(['error' => 'Invalid request method']);
        break;
}

$mysqli->close();
?>
