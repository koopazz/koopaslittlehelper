<!DOCTYPE html>
<html>
  <head>
    <title>Chat with GPT-3</title>
  </head>
  <body>
    <h1>Chat with GPT-3</h1>
    <div id="messages"></div>
    <form action="" method="post">
      <input type="text" name="message" id="input-message">
      <button type="submit" name="submit">Send</button>
    </form>
    
    <?php
      if (isset($_POST['submit'])) {
        sendMessage();
      }

      function sendMessage() {
        $message = trim($_POST['message']);

        // If input message is empty, don't send anything
        if (!$message) {
          return;
        }

        // Clear input box
        $_POST['message'] = '';

        // Add input message to messages history
        $messagesDiv = '<div id="messages"><p>You: ' . $message . '</p>';

        // Send input message to GPT-3 model through OpenAI API
        $response = sendRequest($message);

        // Display response from GPT-3 model
        $messagesDiv .= '<p>GPT-3: ' . $response . '</p></div>';
        echo $messagesDiv;
      }

      function sendRequest($message) {
        $apiKey = '<your_openai_api_key>';
        $model = 'text-davinci-002';
        $prompt = $message . "\nGPT-3:";

        $data = '{
          "model": "' . $model . '",
          "prompt": "' . $prompt . '",
          "temperature": 0.8,
          "max_tokens": 150,
          "n": 1,
          "stop": "GPT-3:"
        }';

        $url = 'https://api.openai.com/v1/engines/' . $model . '/completions';
        $headers = array(
          'Content-Type: application/json',
          'Authorization: Bearer ' . $apiKey
        );

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($curl);

        if ($response === false) {
          $error = curl_error($curl);
          curl_close($curl);
          return $error;
        } else {
          curl_close($curl);
          $response = json_decode($response, true);
          $text = $response['choices'][0]['text'];
          return $text;
        }
      }
    ?>
  </body>
</html>