<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = isset($_POST['action']) ? $_POST['action'] : '';
    
    switch ($action) {
        case 'booking':
            handleBooking();
            break;
        case 'custom_trip':
            handleCustomTrip();
            break;
        case 'review':
            handleReview();
            break;
        case 'contact':
            handleContact();
            break;
        default:
            echo json_encode(['success' => false, 'message' => 'Invalid action']);
            break;
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}

function handleBooking() {
    $required = ['name', 'email', 'phone', 'trek', 'participants', 'start_date'];
    foreach ($required as $field) {
        if (empty($_POST[$field])) {
            echo json_encode(['success' => false, 'message' => "Please fill in all required fields: $field"]);
            return;
        }
    }
    
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $phone = filter_var($_POST['phone'], FILTER_SANITIZE_STRING);
    $trek = filter_var($_POST['trek'], FILTER_SANITIZE_STRING);
    $participants = filter_var($_POST['participants'], FILTER_SANITIZE_NUMBER_INT);
    $start_date = filter_var($_POST['start_date'], FILTER_SANITIZE_STRING);
    $message = isset($_POST['message']) ? filter_var($_POST['message'], FILTER_SANITIZE_STRING) : '';
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Please provide a valid email address']);
        return;
    }
    
    $booking_ref = 'NEP' . date('Ymd') . strtoupper(substr(uniqid(), -6));
    
    $booking_data = [
        'booking_ref' => $booking_ref,
        'name' => $name,
        'email' => $email,
        'phone' => $phone,
        'trek' => $trek,
        'participants' => $participants,
        'start_date' => $start_date,
        'message' => $message,
        'timestamp' => date('Y-m-d H:i:s')
    ];
    
    $bookings_file = 'data/bookings.json';
    $bookings = [];
    
    if (file_exists($bookings_file)) {
        $bookings = json_decode(file_get_contents($bookings_file), true) ?: [];
    }
    
    $bookings[] = $booking_data;
    
    if (!is_dir('data')) {
        mkdir('data', 0755, true);
    }
    
    if (file_put_contents($bookings_file, json_encode($bookings, JSON_PRETTY_PRINT))) {
        $email_sent = sendBookingEmail($booking_data);
        
        echo json_encode([
            'success' => true, 
            'message' => 'Booking submitted successfully!', 
            'booking_ref' => $booking_ref,
            'email_sent' => $email_sent
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to save booking. Please try again.']);
    }
}

function handleCustomTrip() {
    $required = ['name', 'email', 'destination', 'duration', 'participants'];
    foreach ($required as $field) {
        if (empty($_POST[$field])) {
            echo json_encode(['success' => false, 'message' => "Please fill in all required fields: $field"]);
            return;
        }
    }
    
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $phone = isset($_POST['phone']) ? filter_var($_POST['phone'], FILTER_SANITIZE_STRING) : '';
    $destination = filter_var($_POST['destination'], FILTER_SANITIZE_STRING);
    $duration = filter_var($_POST['duration'], FILTER_SANITIZE_STRING);
    $participants = filter_var($_POST['participants'], FILTER_SANITIZE_NUMBER_INT);
    $budget = isset($_POST['budget']) ? filter_var($_POST['budget'], FILTER_SANITIZE_STRING) : '';
    $interests = isset($_POST['interests']) ? $_POST['interests'] : [];
    $message = isset($_POST['message']) ? filter_var($_POST['message'], FILTER_SANITIZE_STRING) : '';
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Please provide a valid email address']);
        return;
    }
    
    if (is_array($interests)) {
        $interests = array_map(function($interest) {
            return filter_var($interest, FILTER_SANITIZE_STRING);
        }, $interests);
        $interests = implode(', ', $interests);
    } else {
        $interests = '';
    }
    
    $trip_data = [
        'name' => $name,
        'email' => $email,
        'phone' => $phone,
        'destination' => $destination,
        'duration' => $duration,
        'participants' => $participants,
        'budget' => $budget,
        'interests' => $interests,
        'message' => $message,
        'timestamp' => date('Y-m-d H:i:s')
    ];
    
    $trips_file = 'data/custom_trips.json';
    $trips = [];
    
    if (file_exists($trips_file)) {
        $trips = json_decode(file_get_contents($trips_file), true) ?: [];
    }
    
    $trips[] = $trip_data;
    
    if (!is_dir('data')) {
        mkdir('data', 0755, true);
    }
    
    if (file_put_contents($trips_file, json_encode($trips, JSON_PRETTY_PRINT))) {
        $email_sent = sendCustomTripEmail($trip_data);
        
        echo json_encode([
            'success' => true, 
            'message' => 'Custom trip request submitted successfully! We will contact you within 24 hours.',
            'email_sent' => $email_sent
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to submit request. Please try again.']);
    }
}

function handleReview() {
    $required = ['name', 'email', 'rating', 'trek', 'review'];
    foreach ($required as $field) {
        if (empty($_POST[$field])) {
            echo json_encode(['success' => false, 'message' => "Please fill in all required fields: $field"]);
            return;
        }
    }
    
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $rating = filter_var($_POST['rating'], FILTER_SANITIZE_NUMBER_INT);
    $trek = filter_var($_POST['trek'], FILTER_SANITIZE_STRING);
    $review = filter_var($_POST['review'], FILTER_SANITIZE_STRING);
    $location = isset($_POST['location']) ? filter_var($_POST['location'], FILTER_SANITIZE_STRING) : '';
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Please provide a valid email address']);
        return;
    }
    
    if ($rating < 1 || $rating > 5) {
        echo json_encode(['success' => false, 'message' => 'Please provide a valid rating between 1 and 5']);
        return;
    }
    
    $review_data = [
        'name' => $name,
        'email' => $email,
        'rating' => $rating,
        'trek' => $trek,
        'review' => $review,
        'location' => $location,
        'timestamp' => date('Y-m-d H:i:s'),
        'approved' => false 
    ];
    
    $reviews_file = 'data/reviews.json';
    $reviews = [];
    
    if (file_exists($reviews_file)) {
        $reviews = json_decode(file_get_contents($reviews_file), true) ?: [];
    }
    
    $reviews[] = $review_data;
    
    if (!is_dir('data')) {
        mkdir('data', 0755, true);
    }
    
    if (file_put_contents($reviews_file, json_encode($reviews, JSON_PRETTY_PRINT))) {
        echo json_encode([
            'success' => true, 
            'message' => 'Thank you for your review! It will be published after approval.'
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to submit review. Please try again.']);
    }
}

function handleContact() {
    $required = ['name', 'email', 'subject', 'message'];
    foreach ($required as $field) {
        if (empty($_POST[$field])) {
            echo json_encode(['success' => false, 'message' => "Please fill in all required fields: $field"]);
            return;
        }
    }
    
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $subject = filter_var($_POST['subject'], FILTER_SANITIZE_STRING);
    $message = filter_var($_POST['message'], FILTER_SANITIZE_STRING);
    $phone = isset($_POST['phone']) ? filter_var($_POST['phone'], FILTER_SANITIZE_STRING) : '';
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['success' => false, 'message' => 'Please provide a valid email address']);
        return;
    }
    
    $contact_data = [
        'name' => $name,
        'email' => $email,
        'phone' => $phone,
        'subject' => $subject,
        'message' => $message,
        'timestamp' => date('Y-m-d H:i:s')
    ];
    
    $contacts_file = 'data/contacts.json';
    $contacts = [];
    
    if (file_exists($contacts_file)) {
        $contacts = json_decode(file_get_contents($contacts_file), true) ?: [];
    }
    
    $contacts[] = $contact_data;
    
    if (!is_dir('data')) {
        mkdir('data', 0755, true);
    }
    
    if (file_put_contents($contacts_file, json_encode($contacts, JSON_PRETTY_PRINT))) {
        $email_sent = sendContactEmail($contact_data);
        
        echo json_encode([
            'success' => true, 
            'message' => 'Message sent successfully! We will respond within 24 hours.',
            'email_sent' => $email_sent
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Failed to send message. Please try again.']);
    }
}

function sendBookingEmail($booking_data) {
    $to = $booking_data['email'];
    $subject = "Nepal Odyssey - Booking Confirmation #" . $booking_data['booking_ref'];
    
    $message = "
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
            .header { background: linear-gradient(135deg, #3a86ff, #8338ec); color: white; padding: 20px; text-align: center; }
            .content { padding: 20px; }
            .footer { background: #f8f9fa; padding: 15px; text-align: center; font-size: 14px; color: #6c757d; }
            .booking-details { background: #f8f9fa; padding: 15px; border-radius: 5px; margin: 15px 0; }
        </style>
    </head>
    <body>
        <div class='header'>
            <h1>Nepal Odyssey</h1>
            <h2>Booking Confirmation</h2>
        </div>
        <div class='content'>
            <p>Dear " . $booking_data['name'] . ",</p>
            <p>Thank you for booking your trek with Nepal Odyssey! We're excited to help you experience the beauty of the Himalayas.</p>
            
            <div class='booking-details'>
                <h3>Booking Details</h3>
                <p><strong>Booking Reference:</strong> " . $booking_data['booking_ref'] . "</p>
                <p><strong>Trek:</strong> " . $booking_data['trek'] . "</p>
                <p><strong>Start Date:</strong> " . $booking_data['start_date'] . "</p>
                <p><strong>Participants:</strong> " . $booking_data['participants'] . "</p>
            </div>
            
            <p>Our team will contact you within 24 hours to confirm your booking details and provide further information about your trek.</p>
            
            <p>If you have any questions, please don't hesitate to contact us at info@nepalodyssey.com or call +977 1 1234567.</p>
            
            <p>We look forward to welcoming you to Nepal!</p>
            
            <p>Best regards,<br>The Nepal Odyssey Team</p>
        </div>
        <div class='footer'>
            <p>Nepal Odyssey &copy; " . date('Y') . " | Thamel, Kathmandu, Nepal</p>
        </div>
    </body>
    </html>
    ";
    
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "From: Nepal Odyssey <info@nepalodyssey.com>" . "\r\n";
    
    return mail($to, $subject, $message, $headers);
}

function sendCustomTripEmail($trip_data) {
    $to = "info@nepalodyssey.com"; 
    $subject = "New Custom Trip Request - " . $trip_data['name'];
    
    $message = "
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
            .header { background: linear-gradient(135deg, #3a86ff, #8338ec); color: white; padding: 20px; text-align: center; }
            .content { padding: 20px; }
            .trip-details { background: #f8f9fa; padding: 15px; border-radius: 5px; margin: 15px 0; }
        </style>
    </head>
    <body>
        <div class='header'>
            <h1>Nepal Odyssey</h1>
            <h2>New Custom Trip Request</h2>
        </div>
        <div class='content'>
            <p>A new custom trip request has been submitted through the website.</p>
            
            <div class='trip-details'>
                <h3>Trip Details</h3>
                <p><strong>Name:</strong> " . $trip_data['name'] . "</p>
                <p><strong>Email:</strong> " . $trip_data['email'] . "</p>
                <p><strong>Phone:</strong> " . ($trip_data['phone'] ?: 'Not provided') . "</p>
                <p><strong>Destination:</strong> " . $trip_data['destination'] . "</p>
                <p><strong>Duration:</strong> " . $trip_data['duration'] . "</p>
                <p><strong>Participants:</strong> " . $trip_data['participants'] . "</p>
                <p><strong>Budget:</strong> " . ($trip_data['budget'] ?: 'Not specified') . "</p>
                <p><strong>Interests:</strong> " . ($trip_data['interests'] ?: 'Not specified') . "</p>
                <p><strong>Message:</strong> " . ($trip_data['message'] ?: 'No additional message') . "</p>
            </div>
            
            <p>Submitted on: " . $trip_data['timestamp'] . "</p>
        </div>
    </body>
    </html>
    ";
    
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "From: Nepal Odyssey Website <noreply@nepalodyssey.com>" . "\r\n";
    
    return mail($to, $subject, $message, $headers);
}

function sendContactEmail($contact_data) {
    $to = "info@nepalodyssey.com"; 
    $subject = "Website Contact: " . $contact_data['subject'];
    
    $message = "
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
            .header { background: linear-gradient(135deg, #3a86ff, #8338ec); color: white; padding: 20px; text-align: center; }
            .content { padding: 20px; }
            .contact-details { background: #f8f9fa; padding: 15px; border-radius: 5px; margin: 15px 0; }
        </style>
    </head>
    <body>
        <div class='header'>
            <h1>Nepal Odyssey</h1>
            <h2>New Contact Message</h2>
        </div>
        <div class='content'>
            <p>A new message has been submitted through the website contact form.</p>
            
            <div class='contact-details'>
                <h3>Contact Details</h3>
                <p><strong>Name:</strong> " . $contact_data['name'] . "</p>
                <p><strong>Email:</strong> " . $contact_data['email'] . "</p>
                <p><strong>Phone:</strong> " . ($contact_data['phone'] ?: 'Not provided') . "</p>
                <p><strong>Subject:</strong> " . $contact_data['subject'] . "</p>
                <p><strong>Message:</strong> " . $contact_data['message'] . "</p>
            </div>
            
            <p>Submitted on: " . $contact_data['timestamp'] . "</p>
        </div>
    </body>
    </html>
    ";
    
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= "From: Nepal Odyssey Website <noreply@nepalodyssey.com>" . "\r\n";
    $headers .= "Reply-To: " . $contact_data['email'] . "\r\n";
    
    return mail($to, $subject, $message, $headers);
}
?>