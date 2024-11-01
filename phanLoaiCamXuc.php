<?php
session_start();

// Kết nối đến MySQL database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "chatapp";
global $conn; // Đảm bảo biến conn là toàn cục

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Lấy tất cả tin nhắn từ database
function getAllMessages()
{
    global $conn;
    $sql = "SELECT msg, incoming_msg_id, outgoing_msg_id FROM messages";
    $result = $conn->query($sql);
    $messages = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $messages[] = $row;
        }
    }
    return $messages;
}

// Lớp NaiveBayes
class NaiveBayes
{
    private $emotionWords = [];
    private $totalWords = 0;

    public function train($messages, $labels)
    {
        foreach ($messages as $index => $message) {
            $words = $this->tokenize($message);
            $label = $labels[$index];

            // Kiểm tra nhãn cảm xúc hợp lệ
            if (!in_array($label, ['Hạnh phúc', 'Buồn', 'Tức giận', 'Ngạc nhiên', 'Ghê tởm'])) {
                continue; // Bỏ qua nếu nhãn không hợp lệ
            }

            foreach ($words as $word) {
                $this->emotionWords[$label][$word] = ($this->emotionWords[$label][$word] ?? 0) + 1;
                $this->totalWords++;
            }
        }

        // In ra số lượng từ trong mỗi nhãn để kiểm tra
        // echo "<pre>";
        // print_r($this->emotionWords);
        // echo "</pre>";
    }

    public function predict($message)
    {
        $words = $this->tokenize($message);
        $emotionProb = array_fill_keys(['Hạnh phúc', 'Buồn', 'Tức giận', 'Ngạc nhiên', 'Ghê tởm'], 0);

        foreach ($words as $word) {
            foreach ($emotionProb as $emotion => $prob) {
                $count = $this->emotionWords[$emotion][$word] ?? 0;
                $emotionProb[$emotion] += log(($count + 1) / ($this->totalWords + 5)); // Smoothing
            }
        }

        return array_keys($emotionProb, max($emotionProb))[0]; // Trả về cảm xúc có xác suất cao nhất
    }

    private function tokenize($message)
    {
        return preg_split('/\s+/', strtolower(trim($message))); // Tách tin nhắn thành các từ
    }
}

// Hàm phân tích cảm xúc
function sentimentAnalysis($messages)
{
    $labels = ['Hạnh phúc', 'Buồn', 'Tức giận', 'Ngạc nhiên', 'Ghê tởm']; // Nhãn cảm xúc
    $naiveBayes = new NaiveBayes();

    // Huấn luyện mô hình với một số dữ liệu mẫu (có thể tùy chỉnh)
    $sampleMessages = [
        // Hạnh phúc
        "Hôm nay tôi rất hạnh phúc!",
        "Tôi cảm thấy vui vẻ khi ở bên bạn.",
        "Cuối cùng tôi cũng đã hoàn thành công việc!",
        "Điều này thật tuyệt vời!",
        "Hạnh phúc là khi tôi có thể làm điều mình thích.",
        "Tôi đã có một ngày tuyệt vời.",
        "Hôm nay trời đẹp, thật sự vui quá!",
        "Thật là một buổi tối ấm cúng!",
        "Tôi đã nhận được một món quà bất ngờ!",
        "Cuộc sống thật tuyệt vời khi có bạn bè bên cạnh.",
        "Tôi cảm thấy thật may mắn!",
        "Những kỷ niệm đẹp luôn làm tôi vui.",
        "Mỗi sáng thức dậy đều là một cơ hội mới.",
        "Những điều tốt đẹp luôn đến với tôi.",
        "Hôm nay là một ngày đẹp trời!",
        "Tôi yêu cuộc sống này!",
        "Hạnh phúc đến từ những điều nhỏ bé.",
        "Tôi thích cảm giác được yêu thương.",
        "Một nụ cười có thể thay đổi cả ngày của tôi.",
        "Hãy tận hưởng từng khoảnh khắc!",

        // Buồn
        "Hôm nay tôi cảm thấy buồn.",
        "Tôi không biết phải làm gì với cảm xúc này.",
        "Điều này khiến tôi cảm thấy cô đơn.",
        "Tôi không muốn gặp ai hôm nay.",
        "Cuộc sống thật khó khăn.",
        "Tôi đã mất đi một người bạn thân.",
        "Điều này khiến tôi rất thất vọng.",
        "Tôi cảm thấy trống rỗng.",
        "Mọi thứ đều không diễn ra như tôi mong muốn.",
        "Tôi cảm thấy bất lực trong tình huống này.",
        "Những ký ức đau buồn luôn quay về.",
        "Tôi muốn khóc nhưng không thể.",
        "Đôi khi tôi cảm thấy như không ai hiểu tôi.",
        "Cuộc sống thật chán nản.",
        "Mọi thứ đều thật tồi tệ.",
        "Tôi không thể tìm thấy niềm vui.",
        "Mỗi ngày trôi qua đều giống nhau.",
        "Tôi cảm thấy lạc lõng giữa đám đông.",
        "Đã có quá nhiều điều không như ý muốn.",
        "Tôi không biết mình có thể vượt qua nổi không.",

        // Tức giận
        "Tôi rất tức giận về điều này!",
        "Điều này thật không công bằng!",
        "Tại sao mọi người không thể hiểu tôi?",
        "Tôi không thể chịu đựng được nữa!",
        "Đừng nói với tôi điều đó!",
        "Tôi rất bực bội khi không có ai giúp đỡ.",
        "Điều này khiến tôi cảm thấy thất vọng.",
        "Tôi không thể chấp nhận điều này.",
        "Mọi người thật vô lý!",
        "Tôi đã kiên nhẫn đủ rồi.",
        "Tại sao không ai lắng nghe tôi?",
        "Tôi không thể tin được điều này có thể xảy ra.",
        "Tôi cảm thấy như bị xúc phạm.",
        "Điều này khiến tôi cảm thấy bực bội.",
        "Mọi thứ diễn ra thật khó chịu.",
        "Tôi không thể giữ im lặng nữa.",
        "Điều này thật sự khiến tôi khó chịu.",
        "Tôi không thể kiềm chế được cơn giận của mình.",
        "Tại sao mọi thứ lại không theo ý tôi?",
        "Tôi muốn nói lên sự tức giận của mình.",


        // Ngạc nhiên
        "Tôi thật sự bất ngờ trước điều này!",
        "Điều này không thể xảy ra!",
        "Tôi không thể tin vào mắt mình.",
        "Thật là một bất ngờ thú vị!",
        "Điều này vượt quá mong đợi của tôi.",
        "Tôi không biết phải nói gì.",
        "Wow, điều này thật ấn tượng!",
        "Tôi cảm thấy choáng váng!",
        "Tôi không thể ngờ rằng điều này lại xảy ra.",
        "Thật khó tin!",
        "Điều này thật sự làm tôi ngạc nhiên.",
        "Mọi thứ diễn ra thật bất ngờ.",
        "Tôi đã không nghĩ rằng điều này có thể xảy ra.",
        "Hãy xem điều gì vừa xảy ra!",
        "Tôi cảm thấy như đang sống trong giấc mơ.",
        "Một điều gì đó thú vị vừa đến với tôi.",
        "Tôi đã không chuẩn bị cho điều này.",
        "Tôi không thể ngờ được điều này lại có thể xảy ra.",
        "Mọi thứ đang diễn ra thật kỳ diệu.",
        "Đây thật sự là một khoảnh khắc đáng nhớ.",


        // Ghê tởm
        "Tôi thấy ghê tởm với điều này.",
        "Điều này thật sự không thể chấp nhận được!",
        "Tôi không thể nhìn thấy điều đó.",
        "Mọi thứ thật đáng ghê tởm.",
        "Tôi cảm thấy bị xúc phạm.",
        "Điều này khiến tôi cảm thấy buồn nôn.",
        "Tôi không muốn nghĩ đến điều đó.",
        "Thật là một trải nghiệm kinh khủng!",
        "Điều này thật sự gây sốc cho tôi.",
        "Tôi không thể tin được điều này có thể xảy ra.",
        "Tôi cảm thấy rất khó chịu.",
        "Thật là một cảm giác tồi tệ.",
        "Tôi không thể hiểu nổi!",
        "Điều này thật đáng sợ.",
        "Tôi không thể tin vào những gì mình đã thấy.",
        "Mọi thứ đều trở nên quá tệ.",
        "Tôi không muốn nghe về điều đó.",
        "Điều này thật sự khiến tôi cảm thấy đau đớn.",
        "Tôi không muốn trải nghiệm điều này nữa.",

    ];

    $sampleLabels = array_merge(
        array_fill(0, 20, 'Hạnh phúc'),
        array_fill(0, 20, 'Buồn'),
        array_fill(0, 20, 'Tức giận'),
        array_fill(0, 20, 'Ngạc nhiên'),
        array_fill(0, 20, 'Ghê tởm')
    );

    $naiveBayes->train($sampleMessages, $sampleLabels);

    $results = [];
    $summary = [
        'Hạnh phúc' => 0,
        'Buồn' => 0,
        'Tức giận' => 0,
        'Ngạc nhiên' => 0,
        'Ghê tởm' => 0,
        'Tổng' => count($messages),
    ];

    foreach ($messages as $msg) {
        $sentiment = $naiveBayes->predict($msg);
        $results[] = [
            'message' => $msg,
            'sentiment' => $sentiment
        ];
        // Cập nhật tổng hợp kết quả
        $summary[$sentiment]++;
    }

    return ['results' => $results, 'summary' => $summary];
}



// Phân tích cảm xúc cho từng id_user và lưu vào database
// Phân tích cảm xúc cho từng id_user và lưu vào database
function analyzeMessagesByUserId($messages)
{
    // Tạo một mảng để lưu trữ các tin nhắn theo id_user
    $userMessages = [];

    // Tách riêng tin nhắn theo id_user
    foreach ($messages as $message) {
        $incomingId = $message['incoming_msg_id'];
        $outgoingId = $message['outgoing_msg_id'];
        $msgContent = $message['msg'];

        // Thêm tin nhắn vào danh sách tin nhắn theo incoming_id
        if (!isset($userMessages[$incomingId])) {
            $userMessages[$incomingId] = [];
        }
        $userMessages[$incomingId][] = $msgContent;

        // Thêm tin nhắn vào danh sách tin nhắn theo outgoing_id (nếu cần)
        if (!isset($userMessages[$outgoingId])) {
            $userMessages[$outgoingId] = [];
        }
        // Nếu bạn chỉ muốn phân tích cảm xúc cho incoming messages
        // thì không cần thêm tin nhắn vào outgoing_id nữa.
    }

    // Phân tích cảm xúc cho từng user và lưu kết quả
    $analysisResults = [];
    foreach ($userMessages as $userId => $msgs) {
        $analysis = sentimentAnalysis($msgs);
        $dominantEmotion = getDominantEmotion($analysis['summary']); // Lấy cảm xúc có số lượng lớn nhất

        // Cập nhật trạng thái cảm xúc của người dùng vào database
        updateUserEmotionState($userId, $dominantEmotion);

        $analysisResults[$userId] = $analysis; // Lưu kết quả phân tích cho từng user
    }

    return $analysisResults; // Trả về kết quả phân tích cho tất cả người dùng
}

// Cập nhật trạng thái cảm xúc của người dùng trong database
// Cập nhật trạng thái cảm xúc của người dùng trong database
function updateUserEmotionState($userId, $dominantEmotion)
{
    global $conn;
    echo "Cảm xúc: $dominantEmotion, ID người dùng: $userId<br>"; // Debug

    // Chuẩn bị câu lệnh SQL
    $stmt = $conn->prepare("UPDATE users SET emotion_status = ? WHERE unique_id = ?");

    // Kiểm tra xem câu lệnh chuẩn bị có thành công không
    if ($stmt === false) {
        die("Câu lệnh chuẩn bị không thành công: " . $conn->error);
    }

    // Liên kết tham số
    $stmt->bind_param("si", $dominantEmotion, $userId);

    // Thực thi câu lệnh
    if ($stmt->execute()) {
        echo "Cập nhật trạng thái cảm xúc thành công cho người dùng ID: $userId<br>";
    } else {
        // Ghi lại lỗi vào log
        error_log("Lỗi khi cập nhật trạng thái cảm xúc cho người dùng ID: $userId: " . $stmt->error);
        echo "Lỗi khi cập nhật trạng thái cảm xúc cho người dùng ID: $userId: " . $stmt->error . "<br>";
    }

    // Đóng statement
    $stmt->close();
}

// Hàm lấy cảm xúc có số lượng lớn nhất
function getDominantEmotion($summary)
{
    $dominantEmotion = null;
    $maxCount = -1;

    foreach ($summary as $emotion => $count) {
        if ($emotion !== 'Tổng' && $count > $maxCount) {
            $dominantEmotion = $emotion;
            $maxCount = $count;
        }
    }

    return $dominantEmotion;
}

// Lấy tất cả tin nhắn và phân tích
$allMessages = getAllMessages();
$analysisResults = analyzeMessagesByUserId($allMessages);
?>


<!DOCTYPE html>
<html lang="vi">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Phân Tích Cảm Xúc</title>
    <link rel="stylesheet" href="style.css"> <!-- Đường dẫn đến file CSS của bạn -->
</head>

<body>
    <div class="container">
        <h1>Kết Quả Phân Tích Cảm Xúc</h1>
        <?php foreach ($analysisResults as $userId => $results): ?>
            <h2>Người dùng ID: <?php echo htmlspecialchars($userId); ?></h2>
            <table>
                <thead>
                    <tr>
                        <th>Tin Nhắn</th>
                        <th>Cảm Xúc</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($results['results'] as $result): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($result['message']); ?></td>
                            <td><?php echo htmlspecialchars($result['sentiment']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <h3>Tổng Kết:</h3>
            <ul>
                <?php foreach ($results['summary'] as $sentiment => $count): ?>
                    <li><?php echo htmlspecialchars($sentiment) . ": " . htmlspecialchars($count); ?></li>
                <?php endforeach; ?>
                <li>Tổng số tin nhắn: <?php echo htmlspecialchars($results['summary']['Tổng']); ?></li>
            </ul>
        <?php endforeach; ?>
    </div>
</body>

</html>