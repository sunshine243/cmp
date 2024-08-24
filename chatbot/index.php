<!DOCTYPE html>
<html lang="en">
<head>
    <title>Chat Bot</title>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            align-items: center;
            height: 100vh;
            background-color: #f0f0f0;
        }
        #header {
            padding: 20px;
            background-color: #007bff;
            color: white;
            width: 100%;
            text-align: center;
            font-size: 24px;
            font-weight: bold;
            position: relative; /* Allow positioning of the button */
        }
        #header .navigate-button {
            position: absolute;
            top: 50%;
            right: 5%; /* Adjusted position */
            transform: translateY(-50%);
            background-color: #fff;
            color: #007bff;
            border: 1px solid #007bff;
            border-radius: 4px;
            padding: 10px 20px;
            cursor: pointer;
            font-size: 16px;
            text-decoration: none;
            text-align: center;
        }
        #header .navigate-button:hover {
            background-color: #007bff;
            color: #fff;
        }
        #chatbox {
            width: 500px;
            height: 600px;
            border: 1px solid #ccc;
            overflow-y: auto;
            padding: 20px;
            background-color: #fff;
            margin: 20px 0;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            border-radius: 8px;
            position: relative; /* Ensure the loading message is positioned correctly */
        }
        .message {
            margin-bottom: 15px;
            padding: 10px;
            border-radius: 8px;
            max-width: 80%;
            clear: both;
        }
        .user-message {
            background-color: #e1f5fe;
            color: #007bff;
            align-self: flex-end;
        }
        .bot-message {
            background-color: #f1f8e9;
            color: #4caf50;
            align-self: flex-start;
        }
        #input-container {
            display: flex;
            align-items: center;
            width: 500px;
            margin-bottom: 20px;
        }
        input[type="text"] {
            width: 400px;
            padding: 10px;
            margin-right: 10px;
            border-radius: 4px;
            border: 1px solid #ccc;
        }
        button {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            background-color: #007bff;
            color: white;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: #0056b3;
        }
        .loading-message {
            position: absolute;
            bottom: 20px;
            left: 20px;
            color: #888;
            font-style: italic;
        }
    </style>
</head>
<body>
<div id="header">
    <div>Chat Bot</div>
    <a href="../admin/admin_index.php" class="navigate-button">Home</a>
    </div>

<div id="chatbox">
    <div id="loading" class="loading-message" style="display: none;">Bot is typing...</div>
</div>

<div id="input-container">
    <input type="text" id="text" onkeypress="checkEnter(event);">
    <button onclick="generateResponse();">Send</button>
</div>

<script src="script.js"></script>
</body>
</html>
