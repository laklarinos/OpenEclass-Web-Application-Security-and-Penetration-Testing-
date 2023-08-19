<?php
// Initialize PDO object
$db = new PDO("mysql:host=db;dbname=TMA101", "root", "1234");

// Function to sanitize user input
function sanitize($input) {
  global $db;
  $output = htmlspecialchars($input, ENT_QUOTES | ENT_HTML5, 'UTF-8');
  $output = $db->quote($output);
  return $output;
}

// Protect module topics from XSS and SQL injection attacks
$topic_id = filter_input(INPUT_POST, 'topic_id', FILTER_SANITIZE_NUMBER_INT);
$topic_title = filter_input(INPUT_POST, 'topic_title', FILTER_SANITIZE_STRING);
$topic_time = filter_input(INPUT_POST, 'topic_time', FILTER_SANITIZE_NUMBER_INT);
$topic_views = filter_input(INPUT_POST, 'topic_views', FILTER_SANITIZE_NUMBER_INT);
$topic_replies = filter_input(INPUT_POST, 'topic_replies', FILTER_SANITIZE_NUMBER_INT);
$topic_last_post_id = filter_input(INPUT_POST, 'topic_last_post_id', FILTER_SANITIZE_NUMBER_INT);
$forum_id = filter_input(INPUT_POST, 'forum_id', FILTER_SANITIZE_NUMBER_INT);
$prenom = filter_input(INPUT_POST, 'prenom', FILTER_SANITIZE_STRING);

// Insert the topic data into the database
$query = "INSERT INTO topics (topic_id, topic_title, topic_time, topic_views, topic_replies, topic_last_post_id, forum_id, prenom) 
          VALUES (:topic_id, :topic_title, :topic_time, :topic_views, :topic_replies, :topic_last_post_id, :forum_id, :prenom)";
$stmt = $db->prepare($query);
$stmt->bindValue(":topic_id", $topic_id, PDO::PARAM_INT);
$stmt->bindValue(":topic_title", $topic_title, PDO::PARAM_STR);
$stmt->bindValue(":topic_time", $topic_time, PDO::PARAM_INT);
$stmt->bindValue(":topic_views", $topic_views, PDO::PARAM_INT);
$stmt->bindValue(":topic_replies", $topic_replies, PDO::PARAM_INT);
$stmt->bindValue(":topic_last_post_id", $topic_last_post_id, PDO::PARAM_INT);
$stmt->bindValue(":forum_id", $forum_id, PDO::PARAM_INT);
$stmt->bindValue(":prenom", $prenom, PDO::PARAM_STR);
$stmt->execute();

// Protect module posts from XSS and SQL injection attacks
$post_id = filter_input(INPUT_POST, 'post_id', FILTER_SANITIZE_NUMBER_INT);
$topic_id = filter_input(INPUT_POST, 'topic_id', FILTER_SANITIZE_NUMBER_INT);
$forum_id = filter_input(INPUT_POST, 'forum_id', FILTER_SANITIZE_NUMBER_INT);
$poster_id = filter_input(INPUT_POST, 'poster_id', FILTER_SANITIZE_NUMBER_INT);
$post_time = filter_input(INPUT_POST, 'post_time', FILTER_SANITIZE_NUMBER_INT);
$poster_ip = filter_input(INPUT_POST, 'poster_ip', FILTER_SANITIZE_STRING);
$prenom = filter_input(INPUT_POST, 'prenom', FILTER_SANITIZE_STRING);

// Insert the post data into the database
$query = "INSERT INTO posts (post_id, topic_id, forum_id, poster_id, post_time, poster_ip, prenom)
          VALUES (:post_id, :topic_id, :forum_id, :poster_id, :post_time, :poster_ip, :prenom)";
$stmt = $db->prepare($query);
$stmt->bindValue(":post_id", $post_id, PDO::PARAM_INT);
$stmt->bindValue(":topic_id", $topic_id, PDO::PARAM_INT);
$stmt->bindValue(":forum_id", $forum_id, PDO::PARAM_INT);
$stmt->bindValue(":poster_id", $poster_id, PDO::PARAM_INT);
$stmt->bindValue(":post_time", $post_time);
$stmt->bindValue(":poster_ip", $poster_ip);
$stmt->bindValue(":prenom", $prenom);
$stmt->execute();

// Close database connection
$pdo = null;
?>
