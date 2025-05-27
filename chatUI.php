<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Chat Application</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background-color: #f0f2f5;
        }
        #navbar {
            flex: 0 0 auto;
        }
        #content {
            flex: 1;
            display: flex;
            overflow: hidden;
        }
        #sidebar {
            width: 250px;
            border-right: 1px solid #ccc;
            padding: 10px;
            background-color: #fff;
            overflow-y: auto;
        }
        .sessionItem {
            padding: 10px;
            border-bottom: 1px solid #ccc;
            cursor: pointer;
        }
        .sessionItem:hover {
            background-color: #e9ecef;
        }
        #chatContainer {
            flex-grow: 1;
            display: flex;
            flex-direction: column;
            background-color: #fff;
        }
        #chatWindow {
            flex-grow: 1;
            padding: 10px;
            border-bottom: 1px solid #ccc;
            overflow-y: auto;
            background-color: #f8f9fa;
            max-height: calc(100vh - 130px);
        }
        #messageContainer {
            display: flex;
            padding: 10px;
            background-color: #ccc;
            flex-shrink: 0;
        }
        #message {
            flex-grow: 1;
            padding: 10px;
            margin-right: 10px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        #sendBtn {
            flex-shrink: 0;
        }
        .message {
            margin-bottom: 10px;
            padding: 10px;
            border-radius: 5px;
        }
        .message.admin {
            background-color: #f8d7da;
            align-self: flex-end;
        }
        .message.customer {
            background-color: #d1e7dd;
            align-self: flex-start;
        }
    </style>
    <script>
        $(document).ready(function() {
            function fetchChatSessions() {
                $.post('chat.php', {action: 'fetchChatSessions'}, function(data) {
                    try {
                        const response = JSON.parse(data);
                        if (response.error) {
                            console.error('Server error:', response.error);
                        } else {
                            let sessionList = '';
                            response.sessions.forEach(session => {
                                sessionList += `<div data-session="${session.id_chat}" class="sessionItem">Customer ${session.id_customer}</div>`;
                            });
                            $('#sessionList').html(sessionList);

                            $('.sessionItem').click(function() {
                                const sessionId = $(this).data('session');
                                $('#session').val(sessionId);
                                fetchMessages();
                            });
                        }
                    } catch (e) {
                        console.error('Error parsing JSON:', e, data);
                    }
                });
            }

            function fetchMessages() {
                const session = $('#session').val();
                $.post('chat.php', {action: 'fetchMessages', session: session}, function(data) {
                    try {
                        const response = JSON.parse(data);
                        if (response.error) {
                            console.error('Server error:', response.error);
                        } else {
                            let chatContent = '';
                            response.messages.forEach(msg => {
                                const sender = msg.sender_type === 'admin' ? 'Admin' : 'Customer';
                                const msgClass = msg.sender_type === 'customer' ? 'customer' : 'admin';
                                chatContent += `<div class="message ${msgClass}">${sender}: ${msg.message} <br><small>${new Date(msg.timestamp).toLocaleString()}</small></div>`;
                            });
                            $('#chatWindow').html(chatContent);
                        }
                    } catch (e) {
                        console.error('Error parsing JSON:', e, data);
                    }
                });
            }


            function sendMessage() {
                const session = $('#session').val();
                const message = $('#message').val();
                const senderType = 'customer';
                if (message.trim() === '') {
                    alert('Please enter a message.');
                    return;
                }
                $.post('chat.php', {action: 'saveMessage', session: session, message: message, senderType: senderType}, function(data) {
                    try {
                        const response = JSON.parse(data);
                        if (response.error) {
                            console.error('Server error:', response.error);
                        } else if (response.status) {
                            $('#message').val('');
                            fetchMessages();
                        }
                    } catch (e) {
                        console.error('Error parsing JSON:', e, data);
                    }
                });
            }
            function createNewSession() {
                $.post('chat.php', {action: 'createSession'}, function(data) {
                    try {
                        const response = JSON.parse(data);
                        if (response.error) {
                            console.error('Server error:', response.error);
                        } else if (response.session) {
                            // If session created successfully, fetch chat sessions again
                            fetchChatSessions();
                        }
                    } catch (e) {
                        console.error('Error parsing JSON:', e, data);
                    }
                });
            }
            
            $('#sendBtn').click(function() {
                sendMessage();
            });
            $('#createSessionBtn').click(function() {
                createNewSession();
            });
    
            fetchChatSessions();
            setInterval(fetchMessages, 1000); // Refresh chat every second
        });
    </script>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg bg-dark" data-bs-theme="dark">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">Pusat Plastik Wijaya</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav">
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page" href="index.php">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="KeranjangUI.php">Keranjang</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="PurchaseOrder.php">Purchase Order</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="chatUI.php">Chat</a>
                    </li>
                </ul>
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Logout</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Content -->
    <div id="content">
        <div id="sidebar">
            <div id="sessionList"></div>
        </div>
        <div id="chatContainer">
            <div id="chatWindow"></div>
            <div id="messageContainer">
                <input type="hidden" id="session">
                <input type="text" id="message" class="form-control" placeholder="Type your message..." required>
                <button id="sendBtn" class="btn btn-primary">Send</button>
            </div>
        </div>
    </div>
    <button id="createSessionBtn" class="btn btn-primary">+</button>

</body>
</html>
