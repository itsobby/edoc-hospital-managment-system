<?php
session_start();

// Verify user session
if (isset($_SESSION["user"])) {
    if ($_SESSION["user"] == "" || $_SESSION['usertype'] != 'p') {
        header("location: ../login.php");
    } else {
        $useremail = $_SESSION["user"];
    }
} else {
    header("location: ../login.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Symptom Checker</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 60%;
            margin: 50px auto;
            background-color: white;
            padding: 30px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        h1 {
            text-align: center;
            font-size: 2.5em;
            margin-bottom: 20px;
            color: #333;
        }

        label {
            font-size: 1.1em;
            margin-bottom: 10px;
            display: block;
            color: #555;
        }

        input[type="text"] {
            padding: 12px;
            font-size: 1em;
            width: 80%;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        button {
            padding: 12px;
            font-size: 1.1em;
            width: 80%;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            box-sizing: border-box;
        }

        button:hover {
            background-color: #0056b3;
        }

        .result {
            margin-top: 30px;
            font-size: 1.2em;
            color: #333;
            padding: 20px;
            background-color: #e9ecef;
            border-radius: 8px;
            border: 1px solid #ccc;
        }

        .result ul {
            padding: 0;
            list-style: none;
        }

        .result ul li {
            background: #f8f9fa;
            padding: 10px;
            border-radius: 4px;
            margin-bottom: 10px;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }
    </style>
    <script>
        async function checkSymptoms() {
            const symptomInput = document.getElementById('symptoms').value;
            const symptomList = symptomInput.split(',').map(s => s.trim());

            const response = await fetch('http://localhost/edoc-hospital-managment-system/symptom-tracker-api.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ symptoms: symptomList }),
            });

            const data = await response.json();
            const resultDiv = document.getElementById('result');

            if (data.success && data.diagnoses.length > 0) {
                const diagnoses = data.diagnoses.map(d => `<li>${d.symptom}: ${d.diagnosis}</li>`).join('');
                resultDiv.innerHTML = `<h3>Possible Diagnoses:</h3><ul>${diagnoses}</ul>`;
            } else {
                resultDiv.innerHTML = `<h3>${data.message || 'No matching diagnoses found.'}</h3>`;
            }
        }
    </script>
</head>
<body>
    <div class="container">
        <h1>Symptom Checker</h1>
        <label for="symptoms">Enter symptoms separated by commas:</label>
        <input type="text" id="symptoms" placeholder="e.g., headache, fever, cough">
        <button onclick="checkSymptoms()">Check Diagnosis</button>
        <div id="result" class="result"></div>
    </div>
</body>
</html>
