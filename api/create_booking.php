<?php
require_once __DIR__ . '/../db.php';
header('Content-Type: application/json; charset=utf-8');

function json_input(): array {
    $raw = file_get_contents('php://input');
    $data = json_decode($raw, true);
    return is_array($data) ? $data : [];
}
function clean(string $v, int $max = 1000): string {
    $v = trim($v);
    return mb_substr($v, 0, $max);
}

$data = json_input();
$fields = ['name','contact','email','id_number','check_in','check_out','guest_type'];
foreach ($fields as $f) {
    if (empty($data[$f])) {
        http_response_code(422);
        echo json_encode(['success'=>false,'message'=>'Please complete all required fields.']);
        exit;
    }
}

$name = clean($data['name'], 120);
$contact = clean($data['contact'], 30);
$email = clean($data['email'], 160);
$id_number = clean($data['id_number'], 60);
$check_in = clean($data['check_in'], 10);
$check_out = clean($data['check_out'], 10);
$guest_type = clean($data['guest_type'], 20);
$details = clean($data['details'] ?? '', 1000);

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    http_response_code(422);
    echo json_encode(['success'=>false,'message'=>'Please enter a valid email address.']);
    exit;
}
if (!in_array($guest_type, ['Local','Foreign'], true)) {
    http_response_code(422);
    echo json_encode(['success'=>false,'message'=>'Please select Local or Foreign.']);
    exit;
}

$in = DateTime::createFromFormat('Y-m-d', $check_in);
$out = DateTime::createFromFormat('Y-m-d', $check_out);
$now = new DateTime('today');
if (!$in || !$out || $in->format('Y-m-d') !== $check_in || $out->format('Y-m-d') !== $check_out || $out <= $in || $in < $now) {
    http_response_code(422);
    echo json_encode(['success'=>false,'message'=>'Please choose valid future check-in and check-out dates.']);
    exit;
}

try {
    $pdo = db();
    $stmt = $pdo->prepare('INSERT INTO bookings (name, contact_number, email, id_number, guest_type, check_in, check_out, more_details) VALUES (?, ?, ?, ?, ?, ?, ?, ?)');
    $stmt->execute([$name, $contact, $email, $id_number, $guest_type, $check_in, $check_out, $details]);

    $id = (int)$pdo->lastInsertId();
    $text = "Hello Lion Rock Haven Cabana, I would like to make a booking request.\n\n".
        "Booking Reference: #{$id}\n".
        "Name: {$name}\n".
        "Contact: {$contact}\n".
        "Email: {$email}\n".
        "NIC/Passport: {$id_number}\n".
        "Guest Type: {$guest_type}\n".
        "Check-in: {$check_in}\n".
        "Check-out: {$check_out}".
        ($details !== '' ? "\nMore details: {$details}" : '');

    echo json_encode([
        'success'=>true,
        'booking_id'=>$id,
        'whatsapp_url'=>'https://wa.me/' . WHATSAPP_NUMBER . '?text=' . rawurlencode($text)
    ]);
} catch (Throwable $e) {
    http_response_code(500);
    echo json_encode(['success'=>false,'message'=>'Database error. Please make sure the database is configured correctly.']);
}
