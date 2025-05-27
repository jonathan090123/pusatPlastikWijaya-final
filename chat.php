<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();
require_once 'dbConnection.php';

function generateUUID() {
    return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
        mt_rand(0, 0xffff), mt_rand(0, 0xffff),
        mt_rand(0, 0xffff),
        mt_rand(0, 0x0fff) | 0x4000,
        mt_rand(0, 0x3fff) | 0x8000,
        mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
    );
}

class Chat {
    private $pdo;

    public function __construct($pdo) {
        $this->pdo = $pdo;
    }

    public function checkSession() {
        if (isset($_SESSION['chat_session'])) {
            return ['status' => true, 'session' => $_SESSION['chat_session']];
        } else {
            return ['status' => false];
        }
    }

    public function createSession() {
        $id_customer = $_SESSION['id'];
        $id_chat = generateUUID();
        try {
            $stmtAdmin = $this->pdo->query("SELECT id_admin FROM admin LIMIT 1");
            $admin = $stmtAdmin->fetch(PDO::FETCH_ASSOC);
            $id_admin = $admin['id_admin'];
    
            $stmt = $this->pdo->prepare("INSERT INTO chat (id_chat, id_customer, id_admin, status) VALUES (:id_chat, :id_customer, :id_admin, :status)");
            $stmt->execute([
                'id_chat' => $id_chat,
                'id_customer' => $id_customer,
                'id_admin' => $id_admin,
                'status' => 'open'
            ]);
            $_SESSION['chat_session'] = $id_chat;
            return $id_chat;
        } catch (PDOException $e) {
            return json_encode(['error' => $e->getMessage()]);
        }
    }
    

    public function saveMessage($session, $message, $senderType) {
        try {
            $stmt = $this->pdo->prepare("INSERT INTO detailchat (id_chat, message, sender_type) VALUES (:id_chat, :message, :sender_type)");
            $stmt->execute(['id_chat' => $session, 'message' => $message, 'sender_type' => $senderType]);
            return true;
        } catch (PDOException $e) {
            return json_encode(['error' => $e->getMessage()]);
        }
    }

    public function fetchMessages($session) {
        try {
            $stmt = $this->pdo->prepare("SELECT message, timestamp, sender_type FROM detailchat WHERE id_chat = :id_chat ORDER BY timestamp ASC");
            $stmt->execute(['id_chat' => $session]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return json_encode(['error' => $e->getMessage()]);
        }
    }

    public function fetchChatSessions() {
        try {
            $stmt = $this->pdo->query("SELECT id_chat, id_customer FROM chat WHERE status = 'open'");
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            return json_encode(['error' => $e->getMessage()]);
        }
    }
    
}


$chat = new Chat($pdo);
$action = $_POST['action'];

switch ($action) {
    case 'checkSession':
        echo json_encode($chat->checkSession());
        break;
    case 'createSession':
        $id_customer = $_SESSION['id']; 
        echo json_encode(['session' => $chat->createSession($id_customer)]);
        break;
    case 'saveMessage':
        $session = $_POST['session'];
        $message = $_POST['message'];
        $senderType = $_POST['senderType'];
        echo json_encode(['status' => $chat->saveMessage($session, $message, $senderType)]);
        break;
    case 'fetchMessages':
        $session = $_POST['session'];
        echo json_encode(['messages' => $chat->fetchMessages($session)]);
        break;
    case 'fetchChatSessions':
        echo json_encode(['sessions' => $chat->fetchChatSessions()]);
        break;
    default:
        echo json_encode(['error' => 'Invalid action']);
        break;
}
?>
