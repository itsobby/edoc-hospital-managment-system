<?php
// Include the connection file
include('connection.php'); // Directly include connection.php since it's in the same directory

header("Content-Type: application/json"); // Ensure the response is JSON

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Decode the JSON request body
    $data = json_decode(file_get_contents("php://input"), true);

    // Get the symptoms array from the request
    $symptoms = $data['symptoms'] ?? [];

    if (!empty($symptoms)) {
        // Build a query to find matches for multiple symptoms
        $placeholders = implode(',', array_fill(0, count($symptoms), '?'));
        $query = "SELECT symptom_name, associated_diagnosis FROM symptoms WHERE symptom_name IN ($placeholders)";
        
        $stmt = $database->prepare($query);

        // Dynamically bind the parameters
        $stmt->bind_param(str_repeat('s', count($symptoms)), ...$symptoms);
        $stmt->execute();
        $result = $stmt->get_result();

        $diagnoses = [];
        while ($row = $result->fetch_assoc()) {
            $diagnoses[] = [
                'symptom' => $row['symptom_name'],
                'diagnosis' => $row['associated_diagnosis']
            ];
        }

        // Return the results as JSON
        echo json_encode([
            'success' => true,
            'diagnoses' => $diagnoses
        ]);
    } else {
        // No symptoms provided
        echo json_encode([
            'success' => false,
            'message' => 'No symptoms provided.'
        ]);
    }
} else {
    // Invalid request method
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method. Use POST.'
    ]);
}
?>
